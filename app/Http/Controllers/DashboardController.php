<?php

namespace App\Http\Controllers;

use App\Models\LotNumber;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class DashboardController extends Controller
{
    //


    public function index(Request $request)
    {
        // === ฟิลเตอร์ ===
        $year       = (int) $request->input('year', now()->year);
        $categoryId = $request->filled('category_id') ? (int)$request->input('category_id') : null;

        // ตัวเลือกใน select
        $years = range(now()->year, now()->year - 5); // 6 ปีล่าสุด
        $categories = ProductCategory::orderBy('name')->get(['id','name']);

        // === 1) Donut: ตาม "กลุ่มสินค้า" (sum(qty) / count(lots)) ===
        $catAgg = LotNumber::query()
            ->selectRaw('products.category_id, product_categories.name as category_name,
                         SUM(lot_numbers.qty) as qty_total, COUNT(*) as lot_count')
            ->join('products', 'products.id', '=', 'lot_numbers.product_id')
            ->join('product_categories', 'product_categories.id', '=', 'products.category_id')
            ->whereYear('lot_numbers.mfg_date', $year)
            ->groupBy('products.category_id', 'product_categories.name')
            ->orderBy('category_name')
            ->get();

        $donutCatQty = [
            'labels' => $catAgg->pluck('category_name'),
            'series' => $catAgg->pluck('qty_total')->map(fn($v)=>(float)$v),
            'total'  => (float)$catAgg->sum('qty_total'),
        ];
        $donutCatLots = [
            'labels' => $catAgg->pluck('category_name'),
            'series' => $catAgg->pluck('lot_count')->map(fn($v)=>(int)$v),
            'total'  => (int)$catAgg->sum('lot_count'),
        ];

        // === 2) Donut: ตาม "สินค้า" (Top 10) (กรณีเลือกกลุ่มจะกรองด้วย) ===
        $productAgg = LotNumber::query()
            ->selectRaw('products.id, products.sku, products.name,
                         SUM(lot_numbers.qty) as qty_total, COUNT(*) as lot_count')
            ->join('products', 'products.id', '=', 'lot_numbers.product_id')
            ->when($categoryId, fn($q)=>$q->where('products.category_id', $categoryId))
            ->whereYear('lot_numbers.mfg_date', $year)
            ->groupBy('products.id','products.sku','products.name')
            ->orderByDesc('qty_total')
            ->limit(10)
            ->get();

        $donutProdQty = [
            'labels' => $productAgg->map(fn($r)=> "{$r->sku} - {$r->name}"),
            'series' => $productAgg->pluck('qty_total')->map(fn($v)=>(float)$v),
            'total'  => (float)$productAgg->sum('qty_total'),
        ];
        $donutProdLots = [
            'labels' => $productAgg->map(fn($r)=> "{$r->sku} - {$r->name}"),
            'series' => $productAgg->pluck('lot_count')->map(fn($v)=>(int)$v),
            'total'  => (int)$productAgg->sum('lot_count'),
        ];

        // === 3) Monthly: ซีรีส์ = กลุ่มสินค้า, ค่า = sum(qty) / count(lots) แยกเดือน 1..12 ===
        $monthlyRaw = LotNumber::query()
            ->selectRaw('products.category_id, product_categories.name as category_name,
                         MONTH(lot_numbers.mfg_date) as m,
                         SUM(lot_numbers.qty) as qty_total, COUNT(*) as lot_count')
            ->join('products', 'products.id', '=', 'lot_numbers.product_id')
            ->join('product_categories', 'product_categories.id', '=', 'products.category_id')
            ->whereYear('lot_numbers.mfg_date', $year)
            ->groupBy('products.category_id','product_categories.name','m')
            ->get();

        // จัดเป็น series ต่อกลุ่มสินค้า 12 เดือน
        $months = range(1,12);
        $seriesMonthlyQty  = [];
        $seriesMonthlyLots = [];

        foreach ($monthlyRaw->groupBy('category_id') as $catId => $rows) {
            $name = optional($rows->first())->category_name ?? 'N/A';
            $map  = $rows->keyBy('m');

            $seriesMonthlyQty[] = [
                'name' => $name,
                'data' => array_map(fn($m)=> (float)($map[$m]->qty_total ?? 0), $months),
            ];
            $seriesMonthlyLots[] = [
                'name' => $name,
                'data' => array_map(fn($m)=> (int)($map[$m]->lot_count ?? 0), $months),
            ];
        }

        return view('admin.dashboard.index', [
            'year'            => $year,
            'years'           => $years,
            'categories'      => $categories,
            'categoryId'      => $categoryId,

            'donutCatQty'     => $donutCatQty,
            'donutCatLots'    => $donutCatLots,

            'donutProdQty'    => $donutProdQty,
            'donutProdLots'   => $donutProdLots,

            'seriesMonthlyQty'=> $seriesMonthlyQty,
            'seriesMonthlyLots'=> $seriesMonthlyLots,
        ]);
    }


    public function index2(){

        $sum = 1;
        return view('admin.dashboard.index', compact('sum'));
    }



}
