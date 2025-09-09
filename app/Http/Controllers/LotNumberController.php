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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

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

    public function checkLotNoDuplicate($lot_no)
{
    $exists = \App\Models\LotNumber::where('lot_no', $lot_no)->exists();
    return response()->json(['exists' => $exists]);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // ผู้ใช้ยังส่งมาได้เหมือนเดิม แต่ lot_no ไม่ใช้ค่าจากผู้ใช้อีกต่อไป (จะคำนวณใหม่)
    $validated = $request->validate([
        'category_id' => ['required','exists:product_categories,id'],
        'product_id'  => ['required','exists:products,id'],

        // จะไม่ใช้ lot_no ที่ผู้ใช้กรอก แต่คง validation ไว้เป็น required เฉย ๆ ก็ได้ หรือถอดทิ้ง
        // ถ้าจะให้ฟอร์มส่งมาเป็น read-only/empty ก็ลบ required ได้
        // 'lot_no' => ['nullable', Rule::unique('lot_numbers', 'lot_no')],
        'lot_no'    => ['required'],
        'mfg_date'    => ['required','date'],
        'mfg_time'    => ['nullable','date_format:H:i'],
        'qty'         => ['required','integer','min:1'],

        'product_no_old' => ['nullable','integer','min:0','max:9999'],
        'product_no_new' => ['nullable','integer','min:0','max:9999'],

        'received_date'  => ['nullable','date'],
        'supplier'       => ['nullable','max:150'],
        'stock_no'       => ['nullable','max:100'],
        'remark'         => ['nullable','max:2000'],

        'galvanize_cert_file' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        'steel_cert_file'     => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        'official_cert_file'  => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
    ]);

    /** @var \App\Models\Product $product */
    $product  = Product::with('category')->findOrFail($validated['product_id']);
    $category = $product->category ?? ProductCategory::findOrFail($validated['category_id']);
    $mfgDate  = Carbon::parse($validated['mfg_date']);

    // ===== คำนวณเลขล็อตด้วยทรานแซคชัน + lockForUpdate() เพื่อกันชนกัน =====
    // $lotNoFinal = DB::transaction(function () use ($product) {
    //     $today  = Carbon::today();                // ใช้วันปัจจุบันตาม requirement
    //     $suffix = $today->format('ym');           // YYMM เช่น 2508
    //     $format = trim($product->lot_format ?: $product->sku);
    //     $prefix = "{$format}-{$suffix}-";         // เช่น "GR 3.2-2509-001"



    //     // ดึงตัวท้ายสุดภายใต้ prefix เดียวกัน (และสินค้าตัวเดียวกัน) พร้อม lock แถว
    //     $last = LotNumber::where('product_id', $product->id)
    //             ->where('lot_no', 'like', $prefix.'%')
    //             ->lockForUpdate()
    //             ->orderBy('lot_no', 'desc')
    //             ->first();

    //     $lastSeq = 0;
    //     if ($last && preg_match('/(\d{3})$/', $last->lot_no, $m)) {
    //         $lastSeq = (int) $m[1];
    //     }
    //     $nextSeq = $lastSeq + 1;
    //     $seq3    = str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

    //     $final   = $prefix.$seq3;

    //     // กันซ้ำอีกชั้น (ในกรณีสุดวิสัย)
    //     if (LotNumber::where('product_id', $product->id)->where('lot_no', $final)->exists()) {
    //         // เลขชน ให้เด้งเป็นข้อยกเว้นเพื่อให้ทรานแซคชัน rollback
    //         throw new \RuntimeException('เลขล็อตซ้ำ กรุณาลองใหม่');
    //     }

    //     return $final;
    // });
    $lotNoFinal = $validated['lot_no'];
    //GR 3.2-2509-001
   // dd($lotNoFinal);

    // ===== อัปโหลดไฟล์แนบ =====
    $paths = [];
    if ($request->hasFile('galvanize_cert_file')) {
        $file = $request->file('galvanize_cert_file');
        $fileName = 'ATC-Traffic/galvanize_' . time() . '.' . $file->getClientOriginalExtension();
        $paths['galvanize_cert_path'] = $file->storePubliclyAs('', $fileName, 'spaces');
    }

    if ($request->hasFile('steel_cert_file')) {
        $file = $request->file('steel_cert_file');
        $fileName = 'ATC-Traffic/steel_' . time() . '.' . $file->getClientOriginalExtension();
        $paths['steel_cert_path'] = $file->storePubliclyAs('', $fileName, 'spaces');
    }

    if ($request->hasFile('official_cert_file')) {
        $file = $request->file('official_cert_file');
        $fileName = 'ATC-Traffic/official_cert_' . time() . '.' . $file->getClientOriginalExtension();
        // แก้คีย์ให้ถูก: เก็บเป็น official_cert_file (คอลัมน์ที่คุณใช้)
        $paths['official_cert_file'] = $file->storePubliclyAs('', $fileName, 'spaces');
    }

    // ทำช่วงหมายเลขผลิต (run_range)
    $runRange = $this->makeRunRange(
        $lotNoFinal,
        $request->input('product_no_old'),
        $request->input('product_no_new')
    );

    // เก็บ product_no_old/new แบบ zero-pad
    $productNoOld = $request->filled('product_no_old') ? $this->padNo($request->product_no_old) : null;
    $productNoNew = $request->filled('product_no_new') ? $this->padNo($request->product_no_new) : null;

    // ===== บันทึก =====
    LotNumber::create([
        'category_id' => (int) $validated['category_id'],
        'product_id'  => (int) $product->id,

        'lot_no'      => $lotNoFinal,                  // ใช้ที่คำนวณ ไม่ใช่ที่ผู้ใช้กรอก

        'mfg_date'    => $mfgDate->toDateString(),
        'mfg_time'    => $validated['mfg_time'] ?? null,
        'qty'         => (int) $validated['qty'],

        'product_no_old' => $productNoOld,
        'product_no_new' => $productNoNew,
        'run_range'      => $runRange,

        'received_date'   => $request->input('received_date'),
        'supplier'       => $request->input('supplier'),
        'stock_no'       => $request->input('stock_no'),
        'remark'         => $request->input('remark'),

        'class1'         => $request->input('class1'),
        'type1'          => $request->input('type1'),

        'galvanize_cert_path' => $paths['galvanize_cert_path'] ?? null,
        'steel_cert_path'     => $paths['steel_cert_path'] ?? null,
        'official_cert_file'  => $paths['official_cert_file'] ?? null, // <-- แก้ให้ตรงคอลัมน์

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

             //   dd($products);

    return view('admin.lots.edit', compact('lot','categories','products'));
}

    public function productsByCategory($categoryId)
    {
        return Product::where('category_id', $categoryId)
    ->orderBy('sku')
    ->get(['id','sku','name','lot_format']);
    }


    public function productsByCategory2(Request $request)
{
    $categoryId = $request->get('category_id');
    $items = Product::select('id','sku','name', 'lot_format')
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
        'received_date'  => ['nullable'],
        'supplier'       => ['nullable','max:150'],
        'stock_no'       => ['nullable','max:100'],
        'remark'         => ['nullable','max:2000'],
        'galvanize_cert_file' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        'steel_cert_file'     => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        'official_cert_file' => ['nullable','file','mimes:jpg,jpeg,png,pdf','max:5120'],
        'active'   => ['required','boolean'],
    ]);

    // ตรวจสอบซ้ำ
    $dup = LotNumber::where('product_id', $request->product_id)
        ->where('lot_no', $request->lot_no)
        ->where('id', '!=', $lot->id)
        ->exists();

    if ($dup) {
        return back()->withErrors(['lot_no' => 'มีล็อตนัมเบอร์นี้ในสินค้านี้แล้ว'])->withInput();
    }

    // ✅ เพิ่มส่วนนี้: คำนวณ run_range ใหม่
    $lotNoFinal = $request->lot_no;
    $runRange = null;
    if ($request->filled('product_no_old') && $request->filled('product_no_new')) {
        $fromPad = $this->padNo($request->product_no_old);
        $toPad = $this->padNo($request->product_no_new);
        $runRange = "{$lotNoFinal}{$fromPad} - {$lotNoFinal}{$toPad}";
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

    if ($request->hasFile('official_cert_file')) {
        if ($lot->official_cert_file) {
            Storage::disk('spaces')->delete($lot->official_cert_file);
        }

        $filename = 'ATC-Traffic/official_cert_'.time().'.'.$request->file('official_cert_file')->getClientOriginalExtension();

        $request->file('official_cert_file')->storePubliclyAs('', $filename, 'spaces');

        $lot->official_cert_file = $filename;
    }

    // อัปเดตฟิลด์
    $lot->fill([
        'category_id'     => $request->category_id,
        'product_id'      => $request->product_id,
        'lot_no'          => $request->lot_no,
        'mfg_date'        => $request->mfg_date,
        'mfg_time'        => $request->mfg_time,
        'run_range'       => $runRange, // ✅ เพิ่มตรงนี้
        'qty'             => $request->qty,
        'product_no_old'  => $request->product_no_old,
        'product_no_new'  => $request->product_no_new,
        'received_date'   => $request->received_date,
        'supplier'        => $request->supplier,
        'stock_no'        => $request->stock_no,
        'remark'          => $request->remark,
        'class1'          => $request->class1,
        'type1'           => $request->type1,
        'active'           => $request->active,
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
