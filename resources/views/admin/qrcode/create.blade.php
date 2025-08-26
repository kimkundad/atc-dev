@extends('admin.layouts.template')

@section('title')
    <title>ระบบตรวจสอบข้อมูลสินค้าออนไลน์ผ่าน QR Code (Traceability System)</title>
@stop
@section('stylesheet')

@stop('stylesheet')

@section('content')

    <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <!--begin::Toolbar-->
            <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
                <!--begin::Toolbar container-->


                <!--end::Toolbar container-->
            </div>
            <!--end::Toolbar-->
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxl">

                    <div class="row g-5 g-xl-8">


                    <div class="col-xxl-8">
    <form class="card" method="POST" action="{{ url('admin/qrcode') }}">
        @csrf
        <div class="card-header border-0">
            <h3 class="card-title fw-bold">เพิ่มรายการ QR Code</h3>
        </div>

        <div class="card-body pt-0">

        @if ($errors->any())
    <div class="alert alert-danger d-flex align-items-start mb-6">
        <div>
            <div class="fw-bold mb-1">กรอกข้อมูลไม่ถูกต้อง</div>
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

            {{-- รายละเอียด --}}
            <div class="mb-6">
                <label class="form-label fw-bold">รายละเอียด</label>
                <div class="row g-5">
                    <div class="col-md-6">
                        <label class="form-label required">รหัส QR Code</label>
                        <input type="text" class="form-control" id="qr_code" name="qr_code"
                            value="{{ old('qr_code', $qrCode ?? '') }}" readonly />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ลิงก์ใช้งาน</label>
                        <input type="text" class="form-control" id="link_url" name="link_url"
                            placeholder="จะถูกสร้างจากรหัสอัตโนมัติ" value="{{ old('link_url') }}" readonly>
                        <div class="form-text">ตัวอย่าง: {{ rtrim($qrBase,'/') }}/qr/AAAA</div>

                        <div class="form-check form-switch form-check-custom form-check-solid mt-3">
                            <input class="form-check-input"
                                type="checkbox"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">เปิดใช้งาน</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="separator my-6"></div>

            {{-- ข้อมูลล็อตนัมเบอร์ --}}
            <div class="row g-5">
            <div class="col-md-6">
                <label class="form-label">เลือกประเภทสินค้า</label>
                <select class="form-select" id="product_type" name="category_id"
                        data-control="select2" data-hide-search="true">
                    <option value="">-- เลือกประเภท --</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
                </div>

                <div class="col-md-6">
                <label class="form-label">เลือกล็อตนัมเบอร์</label>
                <select class="form-select" id="lot_no" name="lot_id"
                        data-control="select2" data-hide-search="true">
                    <option value="">-- เลือกล็อต --</option>
                    {{-- ไม่ใส่รายการล่วงหน้า ปล่อยให้ AJAX เติม --}}
                </select>
                </div>
                </div>

            <div class="separator my-6"></div>

            {{-- ข้อมูลสินค้า --}}
            <div>
                <label class="form-label fw-bold">ข้อมูลสินค้า</label>
                <div class="row g-5">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-gray-600">รหัสสินค้า</span>
                            <span id="p_code"  class="fw-bold">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-gray-600">วันที่ผลิต</span>
                            <span id="p_date"  class="fw-bold">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-gray-600">รายการ</span>
                            <span id="p_title" class="fw-bold text-end">-</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-gray-600">จำนวน</span>
                            <span id="p_qty"   class="fw-bold">-</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-gray-600">เลขรันของสินค้า</span>
                            <a href="javascript:void(0)" id="p_run" class="fw-bold text-primary text-hover-underline">-</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer d-flex justify-content-start">
            <button type="submit" class="btn btn-secondary">สร้างรายการ</button>
        </div>
    </form>
</div>

        {{-- พรีวิว QR ทางขวา --}}
        <div class="col-xxl-4">
        <div class="card h-100">
            <div class="card-body d-flex flex-column align-items-center justify-content-center">
            <div id="qrContainer" class="mb-5"></div>
            <div class="text-gray-600">สแกนเพื่อตรวจสอบ</div>
            </div>
        </div>
        </div>






                    </div>





                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
        <!--begin::Footer-->


        <!--end::Footer-->
    </div>

@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        referrerpolicy="no-referrer"></script>
<script>
(function () {
  // ====== QR link & preview ======
  const QR_BASE = @json(rtrim($qrBase,'/'));
  const QR_SEG  = 'qr'; // <<<<<< segment กลาง
  const codeEl   = document.getElementById('qr_code');
  const linkEl   = document.getElementById('link_url');
  const activeEl = document.getElementById('is_active');
  const qrBox    = document.getElementById('qrContainer');

  function buildLinkFromCode(code){
    code = (code||'').trim();
    return code ? `${QR_BASE}/${QR_SEG}/${encodeURIComponent(code)}` : '';
  }
  function renderQR(){
    const url = (linkEl.value||'').trim();
    qrBox.innerHTML = '';
    if(!url) return;
    new QRCode(qrBox, { text: (activeEl.checked ? url : 'INACTIVE:'+url), width: 260, height: 260 });
  }
  function onCodeChange(){ linkEl.value = buildLinkFromCode(codeEl.value); renderQR(); }

  // bind
  codeEl.addEventListener('input', onCodeChange);
  activeEl.addEventListener('change', renderQR);
  linkEl.addEventListener('input', renderQR);
  onCodeChange();

  // ====== Dependent dropdown: category -> lots ======
  const $cat = $('#product_type');
  const $lot = $('#lot_no');

  const $code  = $('#p_code');
  const $date  = $('#p_date');
  const $title = $('#p_title');
  const $qty   = $('#p_qty');
  const $run   = $('#p_run');

  function resetPreview(){
    $code.text('-'); $date.text('-'); $title.text('-'); $qty.text('-'); $run.text('-');
  }

  function resetLots(){
    // เคลียร์และตั้ง option แรก
    $lot.empty().append(new Option('-- เลือกล็อต --',''));
    $lot.val('').trigger('change'); // sync กับ select2
  }

  // เมื่อเลือกประเภท -> โหลดล็อตเฉพาะประเภท
  $(document).on('change', '#product_type', async function(){
    resetLots();
    resetPreview();

    const categoryId = $(this).val();
    if(!categoryId) return;

    const url = @json(route('ajax.lots-by-category','__ID__')).replace('__ID__', categoryId);
    try{
      const res = await fetch(url);
      const data = await res.json();

      data.forEach(row => {
        $lot.append(new Option(row.lot_no, row.id));
      });
      // คงค่าเป็นว่างให้ผู้ใช้เลือกเอง
      $lot.val('').trigger('change');
    }catch(e){
      console.error('load lots error', e);
    }
  });

  // เมื่อเลือกล็อต -> เติมข้อมูลสินค้า
  $(document).on('change', '#lot_no', async function(){
    resetPreview();
    const lotId = $(this).val();
    if(!lotId) return;

    const url = @json(route('ajax.lot-detail','__ID__')).replace('__ID__', lotId);
    try{
      const res = await fetch(url);
      const d   = await res.json();
      $code.text(d.sku || '-');
      $date.text(d.mfg_date_th || d.mfg_date || '-');
      $title.text(d.name || '-');
      $qty.text(d.qty ?? '-');
      $run.text(d.run_range || '-');
    }catch(e){
      console.error('load lot info error', e);
    }
  });

})();
</script>
@endsection

