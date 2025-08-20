<?php

namespace App\Http\Controllers;

use App\Models\QRCode;
use App\Models\LotNumber;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

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

    // คืนรายการ “ล็อต” เฉพาะของประเภทที่เลือก
    public function lotsByCategory(ProductCategory $category)
    {
        return LotNumber::where('category_id', $category->id)
            ->orderByDesc('created_at')
            ->get(['id','lot_no','mfg_date','qty','product_id']);
    }

    // คืนรายละเอียดของล็อตที่เลือก (ไว้เติมแผง “ข้อมูลสินค้า”)
    public function lotDetail(LotNumber $lot)
    {
        $lot->load('product:id,sku,name');

        // แปลงวันที่เป็นไทย ถ้าอยากได้แบบ 1-ก.ค.-2568 (ถ้าระบบตั้งค่า locale th แล้ว)
        $mfgTh = optional($lot->mfg_date)
            ? Carbon::parse($lot->mfg_date)->locale('th')->isoFormat('D-MMM-YYYY')
            : null;

        return [
            'id'            => $lot->id,
            'lot_no'        => $lot->lot_no,
            'sku'           => $lot->product?->sku,
            'name'          => $lot->product?->name,
            'mfg_date'      => $lot->mfg_date,     // YYYY-MM-DD
            'mfg_date_th'   => $mfgTh,             // ไทย (ถ้าตั้ง locale แล้ว)
            'qty'           => (int) $lot->qty,
            // เลขรันสินค้า ใช้จาก product_no_old/product_no_new ที่คุณบันทึกไว้
            'run_range'     => ($lot->product_no_old && $lot->product_no_new)
                                ? "{$lot->product_no_old} - {$lot->product_no_new}"
                                : null,
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $categories = \App\Models\ProductCategory::orderBy('name')->get();
    $lots = \App\Models\LotNumber::orderByDesc('created_at')->get(['id','lot_no']);

    $qrBase = rtrim(config('app.url'), '/');

    return view('admin.qrcode.create', compact('categories','lots','qrBase'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'qr_code'  => ['required','string','max:100','unique:qrcodes,qr_code'],
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
        'lot.creator',       // ถ้ามีผู้บันทึก
    ]);

    $lot     = $qrcode->lot;
    $product = optional($lot)->product;
    $category= optional($product)->category;

    // ถ้ามีไฟล์/ลิงก์เอกสารในตาราง lot_numbers (แก้ชื่อ field ให้ตรง schema ของคุณ)
    $docs = array_values(array_filter([
        optional($lot)->galvanize_cert_path ?? null,
        optional($lot)->steel_cert_path ?? null,
    ]));

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
    public function destroy(string $id)
    {
        //
    }
}
