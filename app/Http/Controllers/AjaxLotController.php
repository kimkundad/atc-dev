<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\LotNumber;
use Carbon\Carbon;

class AjaxLotController extends Controller
{
    // คืน lot_no ที่ “ควรเป็น” สำหรับ product นั้น ๆ ณ เดือนปัจจุบัน
    public function next(Product $product)
    {
        // วันปัจจุบัน (ตามข้อกำหนดให้ใช้วันจริง ไม่ใช่วันที่ผลิตที่เลือก)
        $today   = Carbon::today();                  // timezone ตามแอป
        $yy      = $today->format('y');              // 25
        $mm      = $today->format('m');              // 08
        $suffix  = $yy.$mm;                          // 2508

        // รูปแบบนำหน้า เช่น "GR 3.2"
        $format  = $product->lot_format ?: $product->sku; // เผื่อไม่มี lot_format
        $prefix  = trim($format).'-'.$suffix.'-';         // GR 3.2-2508-

        // นับจำนวนล็อตของสินค้านี้ ในเดือน/ปี ปัจจุบัน
        // (อิงวันที่ผลิตในตาราง; ถ้าอยากอิง created_at แทน เปลี่ยน whereYear/Month เป็น created_at)
        $count = LotNumber::where('product_id', $product->id)
                  ->whereYear('mfg_date', $today->year)
                  ->whereMonth('mfg_date', $today->month)
                  ->count();

        $seq    = $count + 1;                                 // ถ้าไม่มีจะได้ 1
        $seq3   = str_pad($seq, 3, '0', STR_PAD_LEFT);        // 001, 006, ...

        return response()->json([
            'format'   => $format,
            'suffix'   => $suffix,            // 2508
            'seq'      => $seq,               // 1, 6, ...
            'seq3'     => $seq3,              // 001, 006, ...
            'prefix'   => $prefix,            // GR 3.2-2508-
            'lot_no'   => $prefix.$seq3,      // GR 3.2-2508-001
        ]);
    }
}
