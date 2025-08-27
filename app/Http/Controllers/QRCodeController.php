<?php

namespace App\Http\Controllers;

use App\Models\QRCode;
use App\Models\LotNumber;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeFacade;
use Illuminate\Support\Facades\Storage;

class QRCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $q          = trim((string) $request->input('q'));
    $categoryId = $request->integer('category_id') ?: null;

    // SET: ใช้ทั้ง has() และ filled() เพื่อรับค่าเฉพาะเมื่อผู้ใช้ส่งมา และไม่ใช่ค่าว่าง
    $mfgDate = ($request->has('mfg_date1') && $request->filled('mfg_date1'))
        ? $request->input('mfg_date1')
        : null;

    $items = QRCode::with(['lot.product.category'])
        ->when($q, function ($qb) use ($q) {
            $qb->where('qr_code', 'like', "%{$q}%")
               ->orWhere('link_url', 'like', "%{$q}%")
               ->orWhereHas('lot', fn($w)=>$w->where('lot_no','like',"%{$q}%")
                                              ->orWhere('supplier','like',"%{$q}%"))
               ->orWhereHas('lot.product', fn($w)=>$w->where('sku','like',"%{$q}%")
                                                     ->orWhere('name','like',"%{$q}%"));
        })
        ->when($categoryId, fn($qb)=>$qb->whereHas('lot.product.category', fn($c)=>$c->where('id',$categoryId)))
        ->when($mfgDate,   fn($qb)=>$qb->whereHas('lot', fn($l)=>$l->whereDate('mfg_date', $mfgDate)))
        ->latest()
        ->paginate((int) $request->input('per_page', 12))
        ->withQueryString();

    $todayCount = QRCode::whereDate('created_at', now())->count();
    $monthCount = QRCode::whereYear('created_at', now()->year)->whereMonth('created_at', now()->month)->count();
    $yearCount  = QRCode::whereYear('created_at', now()->year)->count();
    $categories = ProductCategory::orderBy('name')->get();

    return view('admin.qrcode.index', compact(
        'items','q','categoryId','mfgDate','categories','todayCount','monthCount','yearCount'
    ));
}


public function download(\App\Models\QRCode $qr)
{
    $qr->load('lot');

    if (empty($qr->link_url)) {
        abort(422, 'QR code link URL is empty.');
    }

    $result = Builder::create()
        ->data($qr->link_url)
        ->size(300)
        ->margin(1)
        ->build();

    $fileName = 'qr_' . Str::uuid() . '.png';
    $qrPublicPath = public_path('temp/' . $fileName);

    file_put_contents($qrPublicPath, $result->getString());

    $data = [
    'company' => 'ATC TRAFFIC Co., Ltd.',
    'mfg_th' => optional($qr->lot)->mfg_date
        ? \Carbon\Carbon::parse($qr->lot->mfg_date)->format('d/m/') .
          (\Carbon\Carbon::parse($qr->lot->mfg_date)->year + 543)
        : '-',
    'lot_no' => optional($qr->lot)->lot_no ?? '-',
    'tc_mark' => 'มอก. 248-2567',
    'qr_path' => $qrPublicPath,
    'logo' => public_path('img/logo-stick.jpg'),
    'logo_ban' => public_path('img/logo-ban.jpg'),
];

// เงื่อนไขใส่เฉพาะถ้ามีค่า
if (!empty($qr->lot->class1)) {
    $data['class1'] = 'Class ' . $qr->lot->class1;
}
if (!empty($qr->lot->type1)) {
    $data['type1'] = 'Type ' . $qr->lot->type1;
}

    $customPaper = [0, 0, 500, 278]; // หน่วยเป็น point (1 point = 1/72 inch)

    $pdf = Pdf::loadView('admin.qrcode.pdf-label', $data)
            ->setPaper($customPaper, 'portrait');

    return $pdf->download('QR-' . $qr->qr_code . '.pdf');
// return $pdf->stream();
}

    // คืนรายการ “ล็อต” เฉพาะของประเภทที่เลือก
    public function lotsByCategory(ProductCategory $category)
{
    return LotNumber::where('category_id', $category->id)
        ->whereDoesntHave('qrCode') // <-- สำคัญ
        ->orderByDesc('created_at')
        ->get(['id','lot_no','mfg_date','qty','product_id']);
}


public function lotsByCategory2(ProductCategory $category)
{
    $currentQrLotId = request('include_id'); // ID ที่ต้องดึงกลับมาด้วย

    $query = LotNumber::where('category_id', $category->id)
        ->where(function ($q) use ($currentQrLotId) {
            $q->whereDoesntHave('qrCode'); // ยังไม่มี QR Code

            // หรือ เป็นล็อตที่เลือกอยู่
            if ($currentQrLotId) {
                $q->orWhere('id', $currentQrLotId);
            }
        })
        ->orderByDesc('created_at');

    return $query->get(['id','lot_no','mfg_date','qty','product_id']);
}

    // คืนรายละเอียดของล็อตที่เลือก (ไว้เติมแผง “ข้อมูลสินค้า”)
    public function lotDetail(LotNumber $lot)
    {
        $lot->load('product:id,sku,name');

        // แปลงวันที่เป็นไทย ถ้าอยากได้แบบ 1-ก.ค.-2568 (ถ้าระบบตั้งค่า locale th แล้ว)
        $mfgTh = $lot->mfg_date
        ? Carbon::parse($lot->mfg_date)
            ->locale('th')                 // ตั้ง locale เป็นไทย
            ->translatedFormat('j F Y')    // j=วันไม่เติม 0, F=ชื่อเดือนเต็ม, Y=ค.ศ.
        : null;

        return [
            'id'            => $lot->id,
            'lot_no'        => $lot->lot_no,
            'class1'        => $lot->class1,
            'type1'         => $lot->type1,
            'sku'           => $lot->product?->sku,
            'name'          => $lot->product?->name,
            'mfg_date'      => $lot->mfg_date,     // YYYY-MM-DD
            'mfg_date_th'   => $mfgTh,             // ไทย (ถ้าตั้ง locale แล้ว)
            'qty'           => (int) $lot->qty,
            // เลขรันสินค้า ใช้จาก product_no_old/product_no_new ที่คุณบันทึกไว้
            'run_range'     => ($lot->run_range),
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{

    $qrCode = $this->generateUniqueQR();

    $categories = \App\Models\ProductCategory::orderBy('name')->get();
    $lots = \App\Models\LotNumber::orderByDesc('created_at')->get(['id','lot_no']);

    $qrBase = rtrim(config('app.url'), '/');

    return view('admin.qrcode.create', compact('categories','lots','qrBase', 'qrCode'));
}


private function generateUniqueQR()
{
    do {
        $code = Str::upper(Str::random(5)); // สุ่มรหัส A-Z+0-9 ความยาว 5 ตัว
    } while (QRCode::where('qr_code', $code)->exists());

    return $code;
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'qr_code' => 'required|unique:qrcodes,qr_code',
            'link_url' => ['nullable','url','max:255'],
            'is_active'=> ['nullable','boolean'],
            'lot_id'   => ['nullable','exists:lot_numbers,id'],
            'category_id' => ['nullable','exists:product_categories,id'],
        ]);

        $base  = rtrim(config('app.url'), '/');
        $code  = $validated['qr_code'];
        $link  = $validated['link_url'] ?? ($base.'/qr/'.$code);

        \App\Models\QRCode::create([
            'qr_code'   => $code,
            'link_url'  => $link,
            'is_active' => $request->boolean('is_active'),
            'lot_id'    => $validated['lot_id'] ?? null,
            'created_by'=> \Auth::id(),
        ]);

        return redirect()->route('qrcode.index')->with('success','สร้างรายการ QR Code เรียบร้อย');
    }

    /**
     * Display the specified resource.
     */
public function show(\App\Models\QRCode $qrcode)
{
    $qrcode->load([
        'lot.product.category',
        'lot.creator',
    ]);

    $lot     = $qrcode->lot;
    $product = optional($lot)->product;
    $category= optional($product)->category;

    // แปลง path เป็น URL บน DigitalOcean Spaces
    $docs = collect([
        $lot->galvanize_cert_path ?? null,
        $lot->steel_cert_path ?? null,
    ])
    ->filter()
    ->map(function ($path) {
        return Storage::disk('spaces')->url($path);
    })
    ->values()
    ->all();

    return view('admin.qrcode.show', compact('qrcode','lot','product','category','docs'));
}



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $qr = QRCode::with(['lot.product.category'])->findOrFail($id);

        $categories = ProductCategory::orderBy('name')->get();
        $lots       = LotNumber::orderByDesc('created_at')->get(['id','lot_no']); // ถ้าต้องการมีรายการไว้ก่อน

        // base สำหรับแสดงตัวอย่างลิงก์ และใช้ฝั่ง JS
        $qrBase = rtrim(env('QR_BASE_URL', config('app.url')), '/');

        // ค่าเริ่มต้นของ select
        $selectedCategoryId = optional(optional($qr->lot)->product)->category_id;
        $selectedLotId      = $qr->lot_id;

        return view('admin.qrcode.edit', compact(
            'qr','categories','lots','qrBase','selectedCategoryId','selectedLotId'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $qr = QRCode::findOrFail($id);

        $validated = $request->validate([
            'qr_code'     => ['required','string','max:100', Rule::unique('qrcodes','qr_code')->ignore($qr->id)],
            'is_active'   => ['nullable','boolean'],
            'lot_id'      => ['nullable','exists:lot_numbers,id'],
            'category_id' => ['nullable','exists:product_categories,id'], // ใช้เฉพาะช่วยกรอง dropdown
        ]);

        // สร้างลิงก์แบบ .../qr/{code} อัตโนมัติ
        $base    = rtrim(env('QR_BASE_URL', config('app.url')), '/');
        $code    = trim($validated['qr_code']);
        $linkUrl = $base.'/qr/'.rawurlencode($code);

        $qr->update([
            'qr_code'   => $code,
            'link_url'  => $linkUrl,
            'is_active' => $request->boolean('is_active'),
            'lot_id'    => $validated['lot_id'] ?? null,
        ]);

        return redirect()->route('qrcode.index')->with('success','บันทึกการแก้ไขเรียบร้อย');
    }


    public function ajaxLotsByCategory(int $categoryId)
    {
        $rows = LotNumber::select('id','lot_no')
            ->whereHas('product', fn($q) => $q->where('category_id',$categoryId))
            ->latest()->get();

        return response()->json($rows);
    }

    // /admin/qrcode/ajax/lot-detail/{lot}
    public function ajaxLotDetail(LotNumber $lot)
    {
        $lot->load('product');

        return response()->json([
            'sku'       => $lot->product->sku ?? null,
            'name'      => $lot->product->name ?? null,
            'mfg_date'  => optional($lot->mfg_date)->format('Y-m-d'),
            'mfg_time'  => $lot->mfg_time,
            'qty'       => $lot->qty,
            'run_range' => $lot->run_range,
            // ถ้าไม่มี helper แปลงไทย ให้ใช้ mfg_date ธรรมดาแทน
            'mfg_date_th' => null,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $qr = QRCode::findOrFail($id);

        // ถ้าอยากลบแบบถาวรใช้ ->forceDelete();
        // แนะนำเปิด soft deletes (ดูข้อ 4) แล้วใช้ ->delete()
        $qr->delete();

        if ($request->wantsJson()) {
            return response()->json(['ok' => true]);
        }

        return redirect()
            ->route('qrcode.index')
            ->with('success', "ลบ QR Code: {$qr->qr_code} แล้ว");
    }
}
