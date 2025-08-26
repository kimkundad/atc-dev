<?php

namespace App\Http\Controllers;

use App\Models\QRCode;
use App\Models\setting;     // ถ้าไม่มีให้ตัดทิ้ง/ใส่ข้อมูลบริษัทแบบฮาร์ดโค้ดได้
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class PublicQrController extends Controller
{
    //


public function show(string $code)
{
    $qr = QRCode::where('qr_code', $code)
        ->with(['lot.product.category'])
        ->first();

    // ไม่มีข้อมูล หรือ inactive → ส่งไปหน้า empty state
    if (!$qr || !$qr->lot || !$qr->lot->product || $qr->is_active == 0) {
        return view('public.qr.show', [
            'qr'        => $qr,
            'lot'       => null,
            'product'   => null,
            'productImg'=> null,
            'mfgTh'     => null,
            'company'   => $this->companyInfo(),
        ]);
    }

    $lot     = $qr->lot;
    $product = $lot->product;

    $productImg = $product->category?->img
        ?? $product->image_url
        ?? asset('assets/media/illustrations/blank.png');

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
        'docs'      => collect([
            ['label' => 'โหลดเอกสาร', 'url' => $lot->galvanize_cert_path ? Storage::disk('spaces')->url($lot->galvanize_cert_path) : null],
            ['label' => 'โหลดเอกสาร', 'url' => $lot->steel_cert_path ? Storage::disk('spaces')->url($lot->steel_cert_path) : null],
            ['label' => 'โหลดเอกสาร', 'url' => $lot->official_cert_file ? Storage::disk('spaces')->url($lot->official_cert_file) : null],
        ])->filter(fn($d) => $d['url']),
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
