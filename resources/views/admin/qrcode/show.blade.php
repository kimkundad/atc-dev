@extends('admin.layouts.template')

@section('title')
  <title>รายละเอียด QR Code</title>
@stop

@section('stylesheet')
<style>
  .section-title{font-weight:700;margin:1.25rem 0 .75rem}
  .subtle-card{border:1px solid var(--bs-gray-200);border-radius:.75rem;padding:1.25rem;background:#fff}
  .form-control.form-control-solid:disabled,.form-control.form-control-solid[readonly]{background:#F5F8FA;border-color:#F5F8FA;color:#5E6278}
  .thumb-doc{width:180px;height:230px;border-radius:.5rem;border:1px solid #eef3f7;object-fit:cover;box-shadow:0 2px 6px rgba(0,0,0,.05)}
  .doc-actions a{font-weight:600;text-decoration:none}
</style>
@stop

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">

    {{-- Toolbar --}}
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">รายละเอียด QR Code</h1>

          {{-- แสดงสถานะการเชื่อม Lot --}}
          @if($lot)
            <div class="text-muted mt-1">
              เชื่อมกับ Lot No. <span class="fw-bold text-gray-800">{{ $lot->lot_no }}</span>
            </div>
          @else
            <div class="mt-2">
              <span class="badge badge-light-warning">ยังไม่เชื่อมกับล็อตนัมเบอร์</span>
            </div>
          @endif
        </div>
      </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">

        {{-- กล่องข้อมูล QR --}}
        <div class="card mb-7">
          <div class="card-body">
            <h4 class="section-title mb-5">รายละเอียด QR Code</h4>
            <div class="row g-5">
              <div class="col-md-8">
                <label class="form-label">ลิงก์ใช้งาน</label>
                <input class="form-control" readonly value="{{ $qrcode->link_url }}">
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

        {{-- ถ้าเชื่อม Lot แล้วแสดงรายละเอียดสินค้า/การผลิต --}}
        @if($lot)
          <div class="card card-flush">
            <div class="card-body">

              {{-- รายละเอียดสินค้า --}}
              <h5 class="section-title">รายละเอียดสินค้า</h5>
              <div class="subtle-card">
                <div class="row g-5">
                  <div class="col-md-6">
                    <label class="form-label">ประเภทสินค้า</label>
                    <input type="text" class="form-control form-control-solid"
                           value="{{ $category->name ?? '-' }}" readonly>
                  </div>
                  <div class="col-md-6 d-none d-md-block"></div>

                  <div class="col-12">
                    <label class="form-label">สินค้า</label>
                    <input type="text" class="form-control form-control-solid"
                           value="{{ trim(($product->sku ?? '').' - '.($product->name ?? ''), ' -') ?: '-' }}"
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
                    <input type="text" class="form-control form-control-solid"
                           value="{{ number_format((int)($lot->qty ?? 0)) }}" readonly>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">วันที่ผลิต</label>
                    <input type="text" class="form-control form-control-solid"
                           value="{{ $lot->mfg_date
      ? \Carbon\Carbon::parse($lot->mfg_date)
            ->locale('th')                               // ใช้ภาษาไทย
            ->translatedFormat('j F ')                   // วัน + เดือนภาษาไทย
        . (\Carbon\Carbon::parse($lot->mfg_date)->year + 543)  // ปี พ.ศ.
      : '' }} {{ $lot->mfg_time ? ' '.\Carbon\Carbon::parse($lot->mfg_time)->format('H:i') : '' }}"
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
                           value="{{ $lot->received_date
      ? \Carbon\Carbon::parse($lot->received_date)
            ->locale('th')                               // ใช้ภาษาไทย
            ->translatedFormat('j F ')                   // วัน + เดือนภาษาไทย
        . (\Carbon\Carbon::parse($lot->received_date)->year + 543)  // ปี พ.ศ.
      : '' }}" readonly>
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

                  {{-- เอกสารอ้างอิงจากตัวแปร $docs (ประกอบให้แล้วใน Controller) --}}
                  <div class="col-12">
                    <label class="form-label d-block mb-3">เอกสารอ้างอิงผลิต</label>


                    @if(count($docs))
                      <div class="d-flex flex-wrap gap-6 align-items-start">
                        @foreach($docs as $i => $url)
                          @php
                            $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION));
                            $isImg = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp']);
                          @endphp
                          <div class="text-center">
                            @if($isImg)
                              <a href="{{ $url }}" target="_blank">
                                <img src="{{ $url }}" alt="doc{{ $i+1 }}" class="thumb-doc mb-3">
                              </a>
                            @else
                              <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-light">เปิดไฟล์</a>
                            @endif
                            <div class="doc-actions mt-2">
                              <a class="text-primary" href="{{ $url }}" download>โหลดเอกสาร</a>
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
                <span class="ms-1 fw-semibold text-gray-700">
                  {{ $lot->creator->username ?? $lot->creator->name ?? 'system' }}
                </span>
                <span class="ms-1">เมื่อวันที่ {{ $lot->created_at?->format('d/m/Y H:i') }}</span>
              </div>
            </div>
          </div>
        @else
          {{-- กรณีไม่ผูก Lot --}}
          <div class="alert alert-warning d-flex align-items-center" role="alert">
            <span class="svg-icon svg-icon-2 me-2">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                   xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.3" d="M2 11C2 6.58 5.58 3 10 3C14.42 3 18 6.58 18 11C18 15.42 14.42 19 10 19C5.58 19 2 15.42 2 11Z" fill="currentColor"/>
                <path d="M11 7H9V13H11V7ZM11 15H9V17H11V15Z" fill="currentColor"/>
              </svg>
            </span>
            <div>QR Code นี้ยังไม่ได้เชื่อมกับล็อตนัมเบอร์ จึงไม่มีรายละเอียดสินค้า/การผลิตให้แสดง</div>
          </div>
        @endif

      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" referrerpolicy="no-referrer"></script>
<script>
  // พรีวิว QR
  const box  = document.getElementById('qrPreview');
  const text = @json($qrcode->link_url);
  if (text) new QRCode(box, { text, width: 180, height: 180 });
</script>
@endsection
