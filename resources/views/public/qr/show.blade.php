{{-- resources/views/public/qr/show.blade.php --}}
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8" />
    <title>ATC Traffic | QR Scan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />

    {{-- Metronic core CSS --}}
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Google Font: Prompt -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap&subset=thai" rel="stylesheet">


    <style>
        :root{ --panel-w: 420px; }

        /* พื้นหลังแบบหน้า public (อิงภาพ layout ตัวอย่าง) */
        body.public-bg{
            background: rgb(213, 217, 226);
            min-height: 100svh;
        }

        /* กล่องกลางคุมความกว้าง (ตามภาพตัวอย่างที่สอง) */
        .public-shell{
            min-height: 100svh;
            display: grid;
            place-items: center;
            padding: 20px;
        }
        .public-panel{
            width: min(100%, var(--panel-w));
            background: #fff;
            border-radius: 22px;
            box-shadow: 0 12px 36px rgba(0,0,0,.22);
            overflow: hidden;
        }

        .header{ padding: 28px 24px 0; text-align: center; }
        .header .logo{ height: 80px; }

        .content{ padding: 20px 24px 28px; }

        .product-top{ display: flex; gap: 16px; }
        .product-top .thumb{
            width: 74px; height: 74px; border-radius: 14px;
            background: #f5f8fa; display: flex; align-items: center; justify-content: center;
            overflow: hidden;
        }
        .product-top .thumb img{ max-width: 100%; max-height: 100%; object-fit: cover; }
        .h1-title{ font-size: 18px; font-weight: 800; line-height: 1.3; color: #101828; }
        .sku{ color: #ef4444; font-size: 12px; margin-top: 4px; }

        .kv{ margin-top: 18px; line-height: 1.1; }
        .kv .label{ color: #6b7280; }
        .kv .value{ font-weight: 800; font-size: 20px; color: #111827; }
        .kv + .kv{ margin-top: 10px; }

        .section-title{ margin-top: 20px; margin-bottom: 10px; font-weight: 700; color: #111827; }

        .docs{ display: flex; gap: 14px; }
        .doc-card{
            flex: 1 1 0;
            background: #f9fafb;
            border: 1px dashed #e5e7eb;
            border-radius: 12px;
            padding: 10px;
            text-align: center;
        }
        .doc-card .preview{
            height: 120px; background: #fff; border-radius: 8px;
            overflow: hidden; display: flex; align-items: center; justify-content: center;
        }
        .doc-card img{ max-width: 100%; max-height: 100%; }
        .doc-card .link{
            display: inline-flex; align-items: center; gap: 6px;
            margin-top: 8px; color: #2563eb; font-weight: 600;
        }

        .company{ margin-top: 18px; font-size: 12px; color: #4b5563; text-align: center; }
        .company .title{ color:#2563EB; font-weight:400; margin-bottom:4px; }

        .cta{
            display:block; margin-top:14px;
            border-radius:999px; padding:12px 16px; text-align:center;
            color:#fff; font-weight:700;
            background: linear-gradient(90deg,#1d4ed8,#22c55e);
            text-decoration: none;
        }

        .empty-illustration{
            width: 120px; height: 120px; border-radius: 100%;
            background: #eef2ff; display: flex; align-items: center; justify-content: center;
            margin-inline: auto; margin-top: 6px;
        }
        .empty-illustration .cross{ font-size: 48px; color: #2563eb; line-height: 1; }

        .footer-dots{
            height: 56px;
            border-radius: 0 0 22px 22px;
            background:
                radial-gradient(ellipse at 30% 30%, rgba(37,99,235,.18), rgba(37,99,235,0) 60%),
                radial-gradient(ellipse at 70% 70%, rgba(34,197,94,.22), rgba(34,197,94,0) 60%);
        }

        @media (min-width:480px){
            .h1-title{ font-size: 20px; }
            .public-panel{ border-radius: 24px; }
        }

        /* ให้กล่องเป็นตำแหน่งอ้างอิงและมีพื้นที่ด้านล่างพอสำหรับรูป */
.section-bottom-bg{
  position: relative;
  padding: 24px 18px 20px; /* เผื่อพื้นที่ให้รูป */
  text-align: center;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(0,0,0,.06);
  overflow: hidden; /* กันรูปล้นมุมโค้ง */
}

/* วางรูปเป็นพื้นหลัง “ชั้นล่างสุด” ติดด้านล่าง-กึ่งกลาง */
.section-bottom-bg::after{
  content: "";
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
  bottom: -42px;                /* เลื่อนลงนิดให้ฟุ้งใต้ขอบ */
  width: min(960px, 110%);      /* กว้างเกินกล่องนิดให้ดูลื่น */
  height: 240px;                /* ความสูงของพื้นหลัง */
  background: url("{{ asset('img/dots-footer.png') }}")
              no-repeat center bottom / cover;
  z-index: 0;
  pointer-events: none;         /* ไม่ให้บังการคลิกปุ่ม */
  opacity: .75;                 /* ปรับความฟุ้งได้ */
}

/* เนื้อหาในกล่องให้ลอยอยู่เหนือพื้นหลัง */
.section-bottom-bg > *{
  position: relative;
  z-index: 1;
}

/* ตัวอย่างสไตล์หัวข้อ/ปุ่ม (ปรับตามดีไซน์จริงได้เลย) */
.qr-heading{ font-weight: 700; margin-bottom: 12px; }
.qr-company{ color:#344767; line-height: 1.7; margin-bottom: 18px; }
.qr-cta{
  display:inline-block;
  padding: 14px 28px;
  border-radius: 999px;
  background: linear-gradient(90deg,#0ea5e9,#0b5ed7);
  color: #fff; text-decoration:none; font-weight: 700;
  box-shadow: 0 8px 18px rgba(11,94,215,.25);
}

    </style>

    <style>
    /* ตั้งฟอนต์หลักของ Bootstrap/Metronic */
    :root{
        --bs-font-sans-serif: 'Prompt', 'Noto Sans Thai', system-ui, -apple-system,
        'Segoe UI', Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Liberation Sans', sans-serif;
    }
    body{ font-family: var(--bs-font-sans-serif); }
    /* น้ำหนักที่ใช้บ่อย */
    .fw-300{ font-weight:300 }
    .fw-400{ font-weight:400 }
    .fw-500{ font-weight:500 }
    .fw-600{ font-weight:600 }
    .fw-700{ font-weight:700 }
    </style>

</head>
<body class="public-bg">
<div class="public-shell ">
    <div class="public-panel section-bottom-bg">

        {{-- โลโก้ --}}
        <div class="header">
            <img src="{{ url('img/logo.png') }}" class="logo" alt="ATC TRAFFIC">
        </div>

        @if($lot && $product)
            {{-- ========= STATE: มีข้อมูล ========= --}}
            <div class="content">
                <div class="product-top">
                    <div class="thumb">
                        <img src="{{ $productImg }}" alt="product">
                    </div>
                    <div class="flex-grow-1">
                        <div class="h1-title">{{ $product->name }}</div>
                        @if($product->sku)
                            <div class="sku">รหัสสินค้า: {{ $product->sku }}</div>
                        @endif
                    </div>
                </div>

                <div class="kv">
                    <div class="label">หมายเลขล็อต</div>
                    <div class="value">{{ $lot->lot_no }}</div>
                </div>
                <div class="kv">
                    <div class="label">วันที่ผลิต</div>
                    <div class="value">{{ $mfgTh ?? '-' }}</div>
                </div>

                <div class="section-title">เอกสาร / ใบรับรอง</div>
                <div class="docs">
                    @php
                        $docs = collect([
                            ['label'=>'โหลดเอกสาร', 'url'=>$lot->doc1_url ?? null, 'thumb'=>$lot->doc1_thumb ?? null],
                            ['label'=>'โหลดเอกสาร', 'url'=>$lot->doc2_url ?? null, 'thumb'=>$lot->doc2_thumb ?? null],
                        ])->filter(fn($d)=>$d['url']);
                    @endphp

                    @forelse($docs as $d)
                        <div class="doc-card">
                            <div class="preview">
                                @if($d['thumb'])
                                    <img src="{{ $d['thumb'] }}" alt="document">
                                @else
                                    <img src="{{ asset('assets/media/illustrations/blank-doc.png') }}" alt="document">
                                @endif
                            </div>
                            <a class="link" target="_blank" href="{{ $d['url'] }}">
                                <span class="svg-icon svg-icon-3">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor">
                                        <path d="M5 20h14a1 1 0 001-1V8.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0013.586 2H6a1 1 0 00-1 1v16a1 1 0 001 1zM13 3.828L18.172 9H14a1 1 0 01-1-1V3.828zM8 13h8v2H8v-2zm0 4h8v2H8v-2z"/>
                                    </svg>
                                </span>
                                {{ $d['label'] }}
                            </a>
                        </div>
                    @empty
                        <div class="text-gray-500">ไม่มีไฟล์เอกสาร</div>
                    @endforelse
                </div>

                <section class="company ">
                    <div class="title" style="font-size:16px">ข้อมูลผู้ผลิต</div>
                    <div>{{ $company->name }}</div>
                    <div>สำนักงานใหญ่: 89/1 หมู่ 9 ตำบลศิลาลอย <br> อำเภอสามร้อยยอด จังหวัดประจวบคีรีขันธ์ 77180</div>
                    <div>โทร: {{ $company->phone }} <br> อีเมล: {{ $company->email }}</div>
                    <a class="cta" href="tel:{{ preg_replace('/\D/','', $company->phone) ?: $company->phone }}">ติดต่อสอบถาม</a>
                </section>
            </div>



        @else
            {{-- ========= STATE: ไม่พบข้อมูล ========= --}}
            <div class="content">
                <div class="empty-illustration">
                    <span class="cross">✖</span>
                </div>
                <div class="text-center fw-bold fs-3 mt-4">ไม่พบข้อมูล</div>

                <section class="company text-center" style="margin-top:18px">
                    <div class="title">ข้อมูลผู้ผลิต</div>
                    <div>{{ $company->name }}</div>
                    <div>{{ $company->address }}</div>
                    <div>โทร: {{ $company->phone }} | อีเมล: {{ $company->email }}</div>
                    <a class="cta mt-3" href="tel:{{ preg_replace('/\D/','', $company->phone) ?: $company->phone }}">ติดต่อสอบถาม</a>
                </section>
            </div>

            <div class="footer-dots"></div>
        @endif
    </div>
</div>

{{-- Metronic core JS --}}
<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
</body>
</html>
