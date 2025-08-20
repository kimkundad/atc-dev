<?php

namespace App\Http\Controllers;

use App\Models\QRCode;
use App\Models\setting;     // ถ้าไม่มีให้ตัดทิ้ง/ใส่ข้อมูลบริษัทแบบฮาร์ดโค้ดได้
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PublicQrController extends Controller
{
    //


        public function show(string $code)
    {
        $qr = QRCode::where('qr_code', $code)
            ->where('is_active', true)
            ->with(['lot.product.category']) // สำคัญ: eager load category
            ->first();

        // ไม่มีข้อมูล -> โยนไปหน้า empty state
        if (!$qr || !$qr->lot || !$qr->lot->product) {
            return view('public.qr.show', [
                'qr'        => $qr,
                'lot'       => null,
                'product'   => null,
                'productImg'=> null,
                'mfgTh'     => null,
                'company'   => $this->companyInfo(), // ฟังก์ชันเดิมที่คุณใช้ดึงข้อมูลบริษัท
            ]);
        }

        $lot     = $qr->lot;
        $product = $lot->product;

      //  dd($product);

        // รูปสินค้า: เอาจากหมวดก่อน ถ้าไม่มีก็จาก product ถ้าไม่มีก็ placeholder
        $productImg = $product->category?->img
            ?? $product->image_url
            ?? asset('assets/media/illustrations/blank.png');

        // วันที่ไทย (ถ้ามี)
        $mfgTh = $lot->mfg_date
            ? Carbon::parse($lot->mfg_date)->translatedFormat('j F Y')
            : null;

        return view('public.qr.show', [
            'qr'        => $qr,
            'lot'       => $lot,
            'product'   => $product,
            'productImg'=> $productImg,
            'mfgTh'     => $mfgTh,
            'company'   => $this->companyInfo(),
        ]);
    }


    public function companyInfo(){


        return $company = (object)[
            'name'    => 'บริษัท เอ ที ซี ทราฟฟิค จำกัด',
            'address' => 'สำนักงานใหญ่: 99/1 หมู่ 9 ตำบลลำลูกกา อำเภอลำลูกกา จังหวัดปทุมธานี 77180',
            'phone'   => '02-xxx-xxxx',
            'email'   => 'info@atctraffic.co.th',
        ];

    }
}
