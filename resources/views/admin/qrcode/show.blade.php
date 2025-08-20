@extends('admin.layouts.template')

@section('title')
    <title>รายละเอียดล็อตนัมเบอร์</title>
@stop

@section('stylesheet')
<style>
    .section-title{
        font-weight: 700;
        margin: 1.25rem 0 .75rem;
    }
    .subtle-card{
        border: 1px solid var(--bs-gray-200);
        border-radius: .75rem;
        padding: 1.25rem;
        background: #fff;
    }
    .form-control.form-control-solid:disabled,
    .form-control.form-control-solid[readonly]{
        background-color: #F5F8FA;
        border-color: #F5F8FA;
        color:#5E6278;
    }
    .thumb-doc{
        width: 180px; height: 230px;
        border-radius: .5rem;
        border: 1px solid #eef3f7;
        object-fit: cover;
        box-shadow: 0 2px 6px rgba(0,0,0,.05);
    }
    .doc-actions a{
        font-weight: 600; text-decoration: none;
    }
</style>
@stop

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">รายละเอียดล็อตนัมเบอร์</h1>
          <div class="text-muted mt-1">Lot No. <span class="fw-bold text-gray-800">{{ $lot->lot_no }}</span></div>
        </div>
        <div class="d-flex align-items-center gap-2">

        </div>
      </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">

      <div class="card mb-7">
              <div class="card-body">
                <h4 class="section-title mb-5">รายละเอียด QR Code</h4>
                <div class="row g-5">
                  <div class="col-md-8">
                    <label class="form-label">ลิงก์ใช้งาน</label>
                    <input class="form-control read" readonly value="{{ $qrcode->link_url }}">
                    <div class="form-text">สร้างเมื่อ {{ optional($qrcode->created_at)->format('d/m/Y H:i') }}</div>
                    <div class="mt-4">
                      <span class="badge {{ $qrcode->is_active ? 'badge-light-success' : 'badge-light-warning' }}">
                        {{ $qrcode->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                      </span>
                    </div>
                  </div>
                  <div class="col-md-4 d-flex flex-column align-items-center">
                    <div id="qrPreview"></div>
                    <div class="text-muted mt-2">สแกนเพื่อตรวจสอบ</div>
                  </div>
                </div>
              </div>
            </div>
        <div class="card card-flush">
          <div class="card-body">

            {{-- รายละเอียดสินค้า --}}
            <h5 class="section-title">รายละเอียดสินค้า</h5>
            <div class="subtle-card">
              <div class="row g-5">
                <div class="col-md-6">
                  <label class="form-label">ประเภทสินค้า</label>
                  <input type="text" class="form-control form-control-solid" value="{{ $lot->product?->category?->name ?? '-' }}" readonly>
                </div>
                <div class="col-md-6 d-none d-md-block"></div>

                <div class="col-12">
                  <label class="form-label">สินค้า</label>
                  <input type="text" class="form-control form-control-solid"
                         value="{{ trim(($lot->product?->sku ?? '').' - '.($lot->product?->name ?? ''), ' -') ?: '-' }}"
                         readonly>
                </div>
              </div>
            </div>

            {{-- รายละเอียดการผลิต --}}
            <h5 class="section-title mt-7">รายละเอียดการผลิต</h5>
            <div class="subtle-card">
              <div class="row g-5">
                <div class="col-md-6">
                  <label class="form-label">ล็อตนัมเบอร์</label>
                  <input type="text" class="form-control form-control-solid" value="{{ $lot->lot_no }}" readonly>
                </div>
                <div class="col-md-6">
                  <label class="form-label">จำนวนสินค้า</label>
                  <input type="text" class="form-control form-control-solid" value="{{ number_format((int)($lot->qty ?? 0)) }}" readonly>
                </div>

                <div class="col-md-6">
                  <label class="form-label">วันที่ผลิต</label>
                  <input type="text" class="form-control form-control-solid"
                         value="{{ $lot->mfg_date ? \Carbon\Carbon::parse($lot->mfg_date)->format('d/m/Y') : '-' }}{{ $lot->mfg_time ? ' '.\Carbon\Carbon::parse($lot->mfg_time)->format('H:i') : '' }}"
                         readonly>
                </div>
                <div class="col-md-6 d-none d-md-block"></div>

                <div class="col-md-6">
                  <label class="form-label">Product No. เริ่มต้น</label>
                  <input type="text" class="form-control form-control-solid" value="{{ $lot->product_no_old ?? '-' }}" readonly>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Product No. สิ้นสุด</label>
                  <input type="text" class="form-control form-control-solid" value="{{ $lot->product_no_new ?? '-' }}" readonly>
                </div>
              </div>
            </div>

            {{-- รายละเอียดการผลิต (เพิ่มเติม) --}}
            <h5 class="section-title mt-7">รายละเอียดการผลิต (เพิ่มเติม)</h5>
            <div class="subtle-card">
              <div class="row g-5">
                <div class="col-md-6">
                  <label class="form-label">วันที่รับชุบ</label>
                  <input type="text" class="form-control form-control-solid"
                         value="{{ $lot->receive_date ? \Carbon\Carbon::parse($lot->receive_date)->format('d/m/Y') : '-' }}"
                         readonly>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Supplier</label>
                  <input type="text" class="form-control form-control-solid" value="{{ $lot->supplier ?? '-' }}" readonly>
                </div>

                <div class="col-md-6">
                  <label class="form-label">เลข Stock กรม</label>
                  <input type="text" class="form-control form-control-solid" value="{{ $lot->stock_no ?? '-' }}" readonly>
                </div>
                <div class="col-md-6 d-none d-md-block"></div>

                {{-- เอกสารอ้างอิง --}}
                <div class="col-12">
                  <label class="form-label d-block mb-3">เอกสารอ้างอิงผลิต</label>

                 @php
    use Illuminate\Support\Facades\Storage;
    use Illuminate\Support\Str;

    // รวมพาธ/ลิงก์ที่มี
    $paths = array_filter([
        $lot->galvanize_cert_path ?? null,
        $lot->steel_cert_path ?? null,
    ]);

    // ทำให้เป็น URL ที่เปิดได้จริง + ตรวจชนิดไฟล์
    $docs = [];
    foreach ($paths as $p) {
        // ถ้าเป็น storage path ให้แปลงเป็น URL จาก public disk
        $url = Str::startsWith($p, ['http://','https://','/','data:'])
            ? $p
            : Storage::url($p);

        $ext  = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
        $type = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp']) ? 'image' : ($ext === 'pdf' ? 'pdf' : 'file');

        $docs[] = ['url' => $url, 'type' => $type, 'name' => basename($p)];
    }
@endphp

@if(count($docs))
  <div class="d-flex flex-wrap gap-6 align-items-start">
    @foreach($docs as $i => $d)
      <div class="text-center">
        @if($d['type'] === 'image')
          <a href="{{ $d['url'] }}" target="_blank">
            <img
              src="{{ $d['url'] }}"
              alt="doc{{ $i+1 }}"
              class="thumb-doc mb-3"
              onerror="this.src='data:image/svg+xml;utf8,{{ rawurlencode('<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; width=&quot;180&quot; height=&quot;230&quot;><rect width=&quot;100%&quot; height=&quot;100%&quot; fill=&quot;#F5F8FA&quot;/><text x=&quot;50%&quot; y=&quot;50%&quot; dominant-baseline=&quot;middle&quot; text-anchor=&quot;middle&quot; font-size=&quot;12&quot; fill=&quot;#A1A5B7&quot;>Preview unavailable</text></svg>') }}'">
          </a>
        @elseif($d['type'] === 'pdf')
          <a href="{{ $d['url'] }}" target="_blank" class="d-inline-flex flex-column align-items-center text-decoration-none">
            <span class="mb-2">
              {{-- ไอคอน PDF แบบเบา ๆ --}}
              <svg width="70" height="90" viewBox="0 0 48 64" xmlns="http://www.w3.org/2000/svg">
                <path d="M30 0H6C2.7 0 0 2.7 0 6v52c0 3.3 2.7 6 6 6h36c3.3 0 6-2.7 6-6V18L30 0z" fill="#F5F8FA" stroke="#E4E6EF"/>
                <path d="M30 0v14c0 2.2 1.8 4 4 4h14" fill="#E4E6EF"/>
                <text x="24" y="50" text-anchor="middle" font-size="12" fill="#F1416C" font-family="Arial,Helvetica,sans-serif">PDF</text>
              </svg>
            </span>
            <span class="small text-muted">{{ Str::limit($d['name'], 18) }}</span>
          </a>
        @else
          <a href="{{ $d['url'] }}" target="_blank" class="btn btn-sm btn-light">เปิดไฟล์</a>
        @endif

        <div class="doc-actions mt-2">
          <a class="text-primary" href="{{ $d['url'] }}" download>โหลดเอกสาร</a>
        </div>
      </div>
    @endforeach
  </div>
@else
  <div class="text-muted">— ไม่มีเอกสารแนบ —</div>
@endif
                </div>
              </div>
            </div>

            {{-- footer note --}}
            <div class="d-flex justify-content-end text-muted fs-8 mt-6">
              รายการนี้ถูกสร้างโดย
              <span class="ms-1 fw-semibold text-gray-700">{{ $lot->creator->username ?? $lot->creator->name ?? 'system' }}</span>
              <span class="ms-1">เมื่อวันที่ {{ $lot->created_at?->format('d/m/Y H:i') }}</span>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection


@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" referrerpolicy="no-referrer"></script>
<script>
  // สร้างพรีวิว QR
  const box = document.getElementById('qrPreview');
  const text = @json($qrcode->is_active ? ($qrcode->link_url ?? '') : ('INACTIVE:' . ($qrcode->link_url ?? '')));
  if (text) new QRCode(box, { text, width: 180, height: 180 });
</script>
@endsection
