<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LotNumber;
use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class LotNumberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    // ค่าจากฟอร์ม (GET)
    $q          = trim($request->get('q',''));
    $categoryId = $request->get('category_id');
    $mfgDate    = $request->get('mfg_date');     // รูปแบบ Y-m-d
    $perPage    = $request->integer('per_page', 12);

    // ดึงรายการพร้อมความสัมพันธ์ + ฟิลเตอร์ (เพิ่มเข้าไปบนของเดิม)
    $query = LotNumber::with(['product.category','creator'])->latest()
        ->when($categoryId, fn($qq) => $qq->where('category_id', $categoryId))
        ->when($mfgDate,   fn($qq) => $qq->whereDate('mfg_date', $mfgDate))
        ->when($q, function ($qq) use ($q) {
            $kw = '%'.$q.'%';
            $qq->where(function ($w) use ($kw) {
                $w->where('lot_no', 'like', $kw)
                  ->orWhere('supplier', 'like', $kw)
                  ->orWhere('stock_no', 'like', $kw)
                  ->orWhereHas('product', fn($p) =>
                        $p->where('sku','like',$kw)->orWhere('name','like',$kw)
                  );
            });
        });

    $lots = $query->paginate($perPage)->withQueryString(); // คง query string ตามของเดิม

    // ตัวเลขสรุป (ยังไม่ผูกกับฟิลเตอร์ตามที่ของเดิมทำ)
    $stats = [
        'today' => LotNumber::whereDate('created_at', now())->count(),
        'month' => LotNumber::whereYear('created_at', now()->year)
                            ->whereMonth('created_at', now()->month)
                            ->count(),
        'year'  => LotNumber::whereYear('created_at', now()->year)->count(),
    ];

    // ส่งหมวด/สินค้าไปเหมือนเดิม
    $categories = ProductCategory::orderBy('name')->get();
    $products   = Product::orderBy('sku')->get();

    return view('admin.lots.index', compact(
        'lots','stats','categories','products','q','categoryId','mfgDate','perPage'
    ));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get();

        // ถ้ามีค่าเก่าจาก validation ให้เติมสินค้าที่ตรงหมวดไว้เลย
        $products = collect();
        if (old('category_id')) {
            $products = Product::where('category_id', old('category_id'))
                        ->orderBy('sku')->get();
        }

        return view('admin.lots.create', compact('categories','products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
        {
            // ผู้ใช้กรอก lot_no เป็น "เลข 1–3 หลัก" (เช่น 1, 7, 12, 001, 050)
            $validated = $request->validate([
                'category_id' => ['required','exists:product_categories,id'],
                'product_id'  => ['required','exists:products,id'],

                'lot_no'      => ['required','regex:/^\d{1,3}$/'], // ระบบจะประกอบเลขจริงให้อัตโนมัติ

                'mfg_date'    => ['required','date'],
                'mfg_time'    => ['nullable','date_format:H:i'],
                'qty'         => ['required','integer','min:0'],

                // Product No. รับเลข 0–9999 แล้วไป pad ให้เอง
                'product_no_old' => ['nullable','integer','min:0','max:9999'],
                'product_no_new' => ['nullable','integer','min:0','max:9999'],

                'received_date'  => ['nullable','date'],  // ฟอร์มส่งชื่อ received_date
                'supplier'       => ['nullable','max:150'],
                'stock_no'       => ['nullable','max:100'],
                'remark'         => ['nullable','max:2000'],

                'galvanize_cert_file' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
                'steel_cert_file'     => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
            ],[
                'lot_no.regex' => 'กรุณากรอกเลขล็อตเป็นตัวเลข 1-3 หลัก (เช่น 1, 07, 123)',
            ]);

            // โหลดสินค้า + หมวด
            /** @var \App\Models\Product $product */
            $product  = Product::with('category')->findOrFail($validated['product_id']);
            $category = $product->category ?? ProductCategory::findOrFail($validated['category_id']);
            $mfgDate  = Carbon::parse($validated['mfg_date']);
            $seq3     = $validated['lot_no'];

            // ประกอบเลขล็อตตามกติกา
            $lotNoFinal = $this->buildLotNo($product, $category, $mfgDate, $seq3);

            // กันซ้ำในสินค้าเดียวกัน
            $exists = LotNumber::where('product_id', $product->id)
                        ->where('lot_no', $lotNoFinal)
                        ->exists();
            if ($exists) {
                return back()
                    ->withErrors(['lot_no' => 'มีล็อตนัมเบอร์นี้ในสินค้านี้แล้ว'])
                    ->withInput();
            }

            // อัปโหลดไฟล์แนบ
            $paths = [];
            if ($request->hasFile('galvanize_cert_file')) {
                $file = $request->file('galvanize_cert_file');
                $fileName = 'ATC-Traffic/galvanize_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storePubliclyAs('', $fileName, 'spaces');
                $paths['galvanize_cert_path'] = $path;
            }

            if ($request->hasFile('steel_cert_file')) {
                $file = $request->file('steel_cert_file');
                $fileName = 'ATC-Traffic/steel_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storePubliclyAs('', $fileName, 'spaces');
                $paths['steel_cert_path'] = $path;
            }

            // ทำช่วงหมายเลขผลิต (run_range) — ถ้ากรอกทั้ง old/new
            $runRange = $this->makeRunRange(
                $lotNoFinal,
                $request->input('product_no_old'),
                $request->input('product_no_new')
            );

            // เก็บ product_no_old/new แบบ zero-pad (สวยงามต่อเนื่องกับ run_range)
            $productNoOld = $request->filled('product_no_old') ? $this->padNo($request->product_no_old) : null;
            $productNoNew = $request->filled('product_no_new') ? $this->padNo($request->product_no_new) : null;

            // บันทึก
            LotNumber::create([
                'category_id' => (int) $validated['category_id'],
                'product_id'  => (int) $product->id,

                'lot_no'      => $lotNoFinal,

                'mfg_date'    => $mfgDate->toDateString(),
                'mfg_time'    => $validated['mfg_time'] ?? null,
                'qty'         => (int) $validated['qty'],

                'product_no_old' => $productNoOld,
                'product_no_new' => $productNoNew,
                'run_range'      => $runRange,                       // ex: "LP4 6807-0010001 - LP4 6807-0010050"

                // ฟอร์มส่งชื่อ received_date -> map ไปคอลัมน์ receive_date
                'receive_date'   => $request->input('received_date'),
                'supplier'       => $request->input('supplier'),
                'stock_no'       => $request->input('stock_no'),
                'remark'         => $request->input('remark'),

                'class1'         => $request->input('class1'),
                'type1'         => $request->input('type1'),

                'galvanize_cert_path' => $paths['galvanize_cert_path'] ?? null,
                'steel_cert_path'     => $paths['steel_cert_path'] ?? null,

                'created_by' => Auth::id(),
            ]);

            return redirect()->route('lots.index')->with('success','สร้างล็อตนัมเบอร์เรียบร้อย');
        }


    private function makeLotNoFromRule(Product $product, Carbon $mfgDate, string $seq): string
{
    $prefix = trim($product->lot_format ?? '');
    $cat    = trim(mb_strtolower(optional($product->category)->name ?? ''));

    $mm = $mfgDate->format('m');

    if ($cat === 'สีเทอร์โมพลาสติก') {
        // ปี ค.ศ. 2 หลัก + วัน
        $yy = $mfgDate->format('y');
        $dd = $mfgDate->format('d');
        return "{$prefix}-{$yy}{$mm}{$dd}{$seq}";
    }

    // เสาไฟ / ราวกั้นอันตราย → ปี พ.ศ. 2 หลัก (AD+543)
    $yyBe = $mfgDate->copy()->addYears(543)->format('y');
    return "{$prefix} {$yyBe}{$mm}-{$seq}";
}


/** หมวด "สีเทอร์โมพลาสติก" ? (ปรับเงื่อนไขให้ตรงชื่อจริงใน DB ได้) */
    private function isPaint(ProductCategory $category): bool
    {
        return Str::contains(mb_strtolower($category->name), 'สี');
    }

    /** zero-pad (ขั้นต่ำ 4 หลัก แต่ถ้ากรอกยาวกว่านั้นจะตามความยาวที่มากกว่า) */
    private function padNo($n, int $min = 4): string
    {
        $n = (string) (int) $n;
        $width = max($min, strlen($n));
        return str_pad($n, $width, '0', STR_PAD_LEFT);
    }

    /**
     * ประกอบเลขล็อตจริงตามกติกา
     * - สี:   {lot_format}-{yy}{mm}{dd}{seq3}  (ปี ค.ศ.)
     * - อื่น:  {lot_format} {yy}{mm}-{seq3}    (ปี พ.ศ.)
     */
    private function buildLotNo(Product $product, ProductCategory $category, Carbon $mfgDate, string $seq3): string
    {
        $seq3 = str_pad(preg_replace('/\D/', '', $seq3) ?: '0', 3, '0', STR_PAD_LEFT);
        $fmt  = trim($product->lot_format ?? '');

        if ($this->isPaint($category)) {
            // ปี ค.ศ. 2 หลัก
            $yy = $mfgDate->format('y');
            $mm = $mfgDate->format('m');
            $dd = $mfgDate->format('d');

            // ถ้าต้องการย่อ "TH (W 0%)" -> "TH(W)" ให้ใช้ 2 บรรทัดนี้แทน:
            // $fmt = preg_replace('/\((W|Y)[^)]+\)/', '($1)', $fmt);
            // $fmt = str_replace('%', '', preg_replace('/\s+/', '', $fmt));

            return "{$fmt}-{$yy}{$mm}{$dd}{$seq3}";
        }

        // ปี พ.ศ. 2 หลัก + เดือน
        $yy = (($mfgDate->year + 543) % 100);
        $mm = $mfgDate->format('m');

        return "{$fmt} {$yy}{$mm}-{$seq3}";
    }

    /**
     * สร้างช่วงหมายเลขสินค้า (run_range)
     * ตัวอย่าง: "LP4 6807-0010001 - LP4 6807-0010050"
     */
    private function makeRunRange(string $lotNo, $from, $to): ?string
    {
        if ($from === null || $to === null) return null;

        $fromPad = $this->padNo($from); // อย่างน้อย 4 หลัก
        $toPad   = $this->padNo($to);

        return "{$lotNo}{$fromPad} - {$lotNo}{$toPad}";
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
{
    $lot = LotNumber::with(['product', 'category', 'creator'])->findOrFail($id);
    return view('admin.lots.show', compact('lot'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $lot = LotNumber::with(['product','category','creator'])->findOrFail($id);

    // ทุกประเภทสำหรับดรอปดาวน์ซ้าย
    $categories = ProductCategory::orderBy('name')->get();

    // สินค้า “เฉพาะประเภทที่เลือกไว้ของรายการนี้”
    $products = Product::where('category_id', $lot->category_id)
                ->orderBy('sku')->get();

    return view('admin.lots.edit', compact('lot','categories','products'));
}

    public function productsByCategory($categoryId)
    {
        return Product::where('category_id', $categoryId)
            ->orderBy('sku')
            ->get(['id','sku','name']);
    }


    public function productsByCategory2(Request $request)
{
    $categoryId = $request->get('category_id');
    $items = Product::select('id','sku','name')
        ->when($categoryId, fn($q)=>$q->where('category_id',$categoryId))
        ->orderBy('sku')
        ->get();

    return response()->json($items);
}



    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, LotNumber $lot)
{
    $request->validate([
        'category_id' => ['required','exists:product_categories,id'],
        'product_id'  => ['required','exists:products,id'],
        'lot_no'      => ['required','max:50'],
        'mfg_date'    => ['nullable','date'],
        'mfg_time'    => ['nullable','date_format:H:i'],
        'qty'         => ['required','integer','min:0'],
        'product_no_old' => ['nullable','max:100'],
        'product_no_new' => ['nullable','max:100'],
        'received_date'  => ['nullable','date'],
        'supplier'       => ['nullable','max:150'],
        'stock_no'       => ['nullable','max:100'],
        'remark'         => ['nullable','max:2000'],
        'galvanize_cert_file' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        'steel_cert_file'     => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
    ]);

    // ตรวจสอบซ้ำ
    $dup = LotNumber::where('product_id', $request->product_id)
        ->where('lot_no', $request->lot_no)
        ->where('id', '!=', $lot->id)
        ->exists();

    if ($dup) {
        return back()->withErrors(['lot_no' => 'มีล็อตนัมเบอร์นี้ในสินค้านี้แล้ว'])->withInput();
    }

    // อัปโหลดไฟล์ใหม่เข้า DigitalOcean Spaces
    if ($request->hasFile('galvanize_cert_file')) {
        // ลบของเก่าถ้ามี
        if ($lot->galvanize_cert_path) {
            Storage::disk('spaces')->delete($lot->galvanize_cert_path);
        }

        // ตั้งชื่อไฟล์ใหม่
        $filename = 'ATC-Traffic/galvanize_'.time().'.'.$request->file('galvanize_cert_file')->getClientOriginalExtension();

        // อัปโหลดไฟล์
        $request->file('galvanize_cert_file')->storePubliclyAs('', $filename, 'spaces');

        $lot->galvanize_cert_path = $filename;
    }

    if ($request->hasFile('steel_cert_file')) {
        if ($lot->steel_cert_path) {
            Storage::disk('spaces')->delete($lot->steel_cert_path);
        }

        $filename = 'ATC-Traffic/steel_'.time().'.'.$request->file('steel_cert_file')->getClientOriginalExtension();

        $request->file('steel_cert_file')->storePubliclyAs('', $filename, 'spaces');

        $lot->steel_cert_path = $filename;
    }

    // อัปเดตฟิลด์
    $lot->fill([
        'category_id'     => $request->category_id,
        'product_id'      => $request->product_id,
        'lot_no'          => $request->lot_no,
        'mfg_date'        => $request->mfg_date,
        'mfg_time'        => $request->mfg_time,
        'qty'             => $request->qty,
        'product_no_old'  => $request->product_no_old,
        'product_no_new'  => $request->product_no_new,
        'received_date'   => $request->received_date,
        'supplier'        => $request->supplier,
        'stock_no'        => $request->stock_no,
        'remark'          => $request->remark,
        'class1'          => $request->class1,
        'type1'           => $request->type1,
    ]);

    $lot->save();

    return redirect()->route('lots.index')->with('success','บันทึกการแก้ไขเรียบร้อย');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $lot = LotNumber::findOrFail($id);

        // ลบไฟล์แนบถ้ามี
        if ($lot->galvanize_cert_path) {
            Storage::disk('public')->delete($lot->galvanize_cert_path);
        }
        if ($lot->steel_cert_path) {
            Storage::disk('public')->delete($lot->steel_cert_path);
        }

        $lot->delete();

        return back()->with('success', 'ลบรายการสำเร็จ');
    }
}
