@extends('admin.layouts.template')

@section('title')
    <title>บริษัท เอ ที ซี ทราฟฟิค จำกัด</title>
@stop

@section('stylesheet')
@stop

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6"></div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="row g-5 g-xl-8">

          <div class="col-xxl-8">
            <form class="card" method="POST" action="{{ route('qrcode.update', $qr->id) }}">
              @csrf
              @method('PUT')

              <div class="card-header border-0">
                <h3 class="card-title fw-bold">แก้ไขรายการ QR Code</h3>
              </div>

              <div class="card-body pt-0">

                {{-- แจ้งเตือน validate --}}
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
                             value="{{ old('qr_code', $qr->qr_code) }}" />
                    </div>
                    <div class="col-md-6">
                      <label class="form-label">ลิงก์ใช้งาน</label>
                      <input type="text" class="form-control" id="link_url" name="link_url"
                             value="{{ old('link_url', $qr->link_url) }}" readonly>
                      <div class="form-text">ตัวอย่าง: {{ rtrim($qrBase,'/') }}/qr/AAAA</div>

                      <div class="form-check form-switch form-check-custom form-check-solid mt-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $qr->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">เปิดใช้งาน</label>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="separator my-6"></div>

                {{-- ข้อมูลล็อตนัมเบอร์ --}}
                <div class="row g-5">
                  @php
                    $selectedCategoryId = old('category_id', optional(optional($qr->lot)->product)->category_id);
                    $selectedLotId      = old('lot_id', $qr->lot_id);
                  @endphp

                  <div class="col-md-6">
                    <label class="form-label">เลือกประเภทสินค้า</label>
                    <select class="form-select" id="product_type" name="category_id"
                            data-control="select2" data-hide-search="true">
                      <option value="">-- เลือกประเภท --</option>
                      @foreach($categories as $c)
                        <option value="{{ $c->id }}" @selected($selectedCategoryId==$c->id)>{{ $c->name }}</option>
                      @endforeach
                    </select>
                  </div>

                  <div class="col-md-6">
                    <label class="form-label">เลือกล็อตนัมเบอร์</label>
                    <select class="form-select" id="lot_no" name="lot_id"
                            data-control="select2" data-hide-search="true">
                      <option value="">-- เลือกล็อต --</option>
                      {{-- จะถูกเติมโดย AJAX และเลือกค่าเดิมให้อัตโนมัติ --}}
                    </select>
                  </div>
                </div>

                <div class="separator my-6"></div>

                {{-- ข้อมูลสินค้า --}}
                <div>
                  <label class="form-label fw-bold">ข้อมูลสินค้า</label>
                  <div class="row g-5">
                    <div class="col-md-6">
                      <div class="d-flex justify-content-between mb-3">
                        <span class="text-gray-600">รหัสสินค้า</span>
                        <span id="p_code"  class="fw-bold">{{ $qr->lot->product->sku ?? '-' }}</span>
                      </div>
                      <div class="d-flex justify-content-between mb-3">
                        <span class="text-gray-600">วันที่ผลิต</span>
                        <span id="p_date"  class="fw-bold">
                          {{ optional($qr->lot?->mfg_date)->format('Y-m-d') ?? '-' }}
                          {{ $qr->lot?->mfg_time ? ' '.$qr->lot->mfg_time : '' }}
                        </span>
                      </div>
                      <div class="d-flex justify-content-between mb-3">
                        <span class="text-gray-600">รายการ</span>
                        <span id="p_title" class="fw-bold text-end">{{ $qr->lot->product->name ?? '-' }}</span>
                      </div>
                      <div class="d-flex justify-content-between mb-3">
                        <span class="text-gray-600">จำนวน</span>
                        <span id="p_qty"   class="fw-bold">{{ $qr->lot->qty ?? '-' }}</span>
                      </div>
                      <div class="d-flex justify-content-between">
                        <span class="text-gray-600">เลขรันของสินค้า</span>
                        <a href="javascript:void(0)" id="p_run" class="fw-bold text-primary text-hover-underline">
                          {{ $qr->lot->run_range ?? '-' }}
                        </a>
                      </div>
                    </div>
                  </div>
                </div>

              </div>

              <div class="card-footer d-flex justify-content-start">
                <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
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

        </div><!-- /row -->
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js" referrerpolicy="no-referrer"></script>
<script>
(function () {
  // ====== QR link & preview ======
  const QR_BASE = @json(rtrim($qrBase,'/'));
  const QR_SEG  = 'qr'; // segment กลาง
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

  // initial: สร้างลิงก์จาก qr_code ให้ตรง format /qr/{code}
  onCodeChange();
  // bind
  codeEl.addEventListener('input', onCodeChange);
  activeEl.addEventListener('change', renderQR);

  // ====== Dependent dropdown: category -> lots ======
  const $cat = $('#product_type');
  const $lot = $('#lot_no');

  const $code  = $('#p_code');
  const $date  = $('#p_date');
  const $title = $('#p_title');
  const $qty   = $('#p_qty');
  const $run   = $('#p_run');

  const initCategoryId = @json((int)($selectedCategoryId ?? 0));
  const initLotId      = @json((int)($selectedLotId ?? 0));

  function resetPreview(){
    $code.text('-'); $date.text('-'); $title.text('-'); $qty.text('-'); $run.text('-');
  }
  function resetLots(){
    $lot.empty().append(new Option('-- เลือกล็อต --',''));
    $lot.val('').trigger('change');
  }

  async function loadLots(categoryId, selectedId = null){
    resetLots(); resetPreview();
    if(!categoryId) return;
    const url = @json(route('ajax.lots-by-category','__ID__')).replace('__ID__', categoryId);
    try{
      const res = await fetch(url);
      const data = await res.json();
      data.forEach(row => $lot.append(new Option(row.lot_no, row.id)));
      if(selectedId){ $lot.val(String(selectedId)).trigger('change'); }
    }catch(e){ console.error('load lots error', e); }
  }

  async function loadLotDetail(lotId){
    resetPreview();
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
    }catch(e){ console.error('load lot info error', e); }
  }

  // events
  $(document).on('change', '#product_type', function(){
    loadLots($(this).val(), null);
  });
  $(document).on('change', '#lot_no', function(){
    loadLotDetail($(this).val());
  });

  // initial load (เลือกค่าเดิมให้ผู้ใช้)
  loadLots(initCategoryId, initLotId).then(()=> loadLotDetail(initLotId));
})();
</script>
@endsection
