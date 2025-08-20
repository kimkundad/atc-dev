<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LotNumber;
use App\Models\ProductCategory;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

        // กันซ้ำ
        if (LotNumber::where('product_id',$request->product_id)
                     ->where('lot_no',$request->lot_no)->exists()) {
            return back()->withErrors(['lot_no' => 'มีล็อตนัมเบอร์นี้ในสินค้านี้แล้ว'])
                         ->withInput();
        }

        // อัปโหลดไฟล์
        $paths = [];
        if ($request->hasFile('galvanize_cert_file')) {
            $paths['galvanize_cert_path'] =
                $request->file('galvanize_cert_file')->store('lots', 'public');
        }
        if ($request->hasFile('steel_cert_file')) {
            $paths['steel_cert_path'] =
                $request->file('steel_cert_file')->store('lots', 'public');
        }

        LotNumber::create([
            'category_id' => $request->category_id,
            'product_id'  => $request->product_id,
            'lot_no'      => $request->lot_no,
            'mfg_date'    => $request->mfg_date,
            'mfg_time'    => $request->mfg_time,
            'qty'         => $request->qty,
            'product_no_old' => $request->product_no_old,
            'product_no_new' => $request->product_no_new,
            'received_date'  => $request->received_date,
            'supplier'       => $request->supplier,
            'stock_no'       => $request->stock_no,
            'remark'         => $request->remark,
            'galvanize_cert_path' => $paths['galvanize_cert_path'] ?? null,
            'steel_cert_path'     => $paths['steel_cert_path'] ?? null,
            'created_by'    => Auth::id(),
        ]);

        return redirect()->route('lots.index')
                         ->with('success','สร้างล็อตนัมเบอร์เรียบร้อย');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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

        // กันซ้ำ (ถ้าจะกันการชนกันของ lot_no ต่อสินค้า)
        $dup = LotNumber::where('product_id', $request->product_id)
                ->where('lot_no', $request->lot_no)
                ->where('id', '!=', $lot->id)
                ->exists();
        if ($dup) {
            return back()->withErrors(['lot_no' => 'มีล็อตนัมเบอร์นี้ในสินค้านี้แล้ว'])->withInput();
        }

        // จัดการไฟล์อัปโหลด (ลบของเก่าถ้าอัปใหม่)
        if ($request->hasFile('galvanize_cert_file')) {
            if ($lot->galvanize_cert_path) {
                Storage::disk('public')->delete($lot->galvanize_cert_path);
            }
            $lot->galvanize_cert_path = $request->file('galvanize_cert_file')
                ->store('lots', 'public');
        }
        if ($request->hasFile('steel_cert_file')) {
            if ($lot->steel_cert_path) {
                Storage::disk('public')->delete($lot->steel_cert_path);
            }
            $lot->steel_cert_path = $request->file('steel_cert_file')
                ->store('lots', 'public');
        }

        // อัปเดตฟิลด์หลัก
        $lot->fill([
            'category_id' => $request->category_id,
            'product_id'  => $request->product_id,
            'lot_no'      => $request->lot_no,
            'mfg_date'    => $request->mfg_date,
            'mfg_time'    => $request->mfg_time,
            'qty'         => $request->qty,
            'product_no_old' => $request->product_no_old,
            'product_no_new' => $request->product_no_new,
            'received_date'  => $request->received_date,
            'supplier'       => $request->supplier,
            'stock_no'       => $request->stock_no,
            'remark'         => $request->remark,
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
