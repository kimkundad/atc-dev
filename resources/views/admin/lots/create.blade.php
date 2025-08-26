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

                        <div class="col-12">
                            <form class="card" method="POST" action="{{ url('admin/lots') }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="card-header border-0">
                                    <h3 class="card-title fw-bold">สร้างล็อตนัมเบอร์
                                    </h3>

                                </div>

                                <div class="card-body">

                                    {{-- รายละเอียดสินค้า --}}
                                       {{-- รายละเอียดสินค้า --}}
<div class="border rounded p-6 mb-10">
    <h4 class="fw-bold mb-5">รายละเอียดสินค้า</h4>
    <div class="row g-5">
        <div class="col-md-6">
            <label class="form-label required">ประเภทสินค้า</label>
            <select class="form-select"
                    name="category_id"
                    id="category_id"
                    data-control="select2"
                    data-hide-search="true"
                    data-products-url="{{ route('ajax.products-by-category', ['id' => 'ID_PLACEHOLDER']) }}"
                    required>
                <option value="">เลือกประเภทสินค้า</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" {{ old('category_id')==$c->id?'selected':'' }}>
                        {{ $c->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label required">สินค้า</label>
            <select class="form-select"
                    name="product_id"
                    id="product_id"
                    data-control="select2"
                    data-placeholder="เลือกสินค้า"
                    {{ old('category_id') ? '' : 'disabled' }}
                    required>
                <option value="">เลือกสินค้า</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}" {{ old('product_id')==$p->id?'selected':'' }}>
                        {{ $p->sku }} - {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

                                    {{-- รายละเอียดการผลิต --}}
                                    <div class="border rounded p-6 mb-10">
                                        <h4 class="fw-bold mb-5">รายละเอียดการผลิต</h4>
                                        <div class="row g-5">
                                            <div class="col-md-4">
                                                <label class="form-label required">ล็อตนัมเบอร์</label>
                                                <input type="text" class="form-control" name="lot_no" id="lot_no" placeholder="ระบบจะเติมให้" />
                                                <small id="lotError" class="text-danger d-none">ล็อตนัมเบอร์นี้มีอยู่แล้ว</small>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">วันที่ผลิต</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="mfg_date" name="mfg_date"
                                                        value="{{ old('mfg_date') }}" placeholder="เลือกวันที่"/>
                                                    <input type="text" class="form-control" id="mfg_time" name="mfg_time"
                                                        value="{{ old('mfg_time') }}" placeholder="เลือกเวลา"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label required">จำนวนสินค้า</label>
                                                <input type="number" class="form-control" name="qty" value="{{ old('qty') }}" value="50"
                                                    min="0" step="1" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label required">Product No. เดิม</label>
                                                <input type="number" class="form-control" name="product_no_old" value="{{ old('product_no_old') }}"
                                                    placeholder="รหัสตามเดิม" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label required">Product No. ล่าสุด</label>
                                                <input type="number" class="form-control" name="product_no_new" value="{{ old('product_no_new') }}"
                                                    placeholder="รหัสล่าสุด" />
                                            </div>



                                        </div>
                                    </div>

                                    {{-- รายละเอียดการผลิต (เพิ่มเติม) --}}
                                    <div id="additional_production_detail" class="border rounded p-6">
                                        <h4 class="fw-bold mb-5">รายละเอียดการผลิต (เพิ่มเติม)</h4>
                                        <div class="row g-5">
                                            <div class="col-md-4">
                                                <label class="form-label">วันรับเข้า</label>
                                                <input type="text"
                                                    class="form-control"
                                                    id="received_date"
                                                    name="received_date"
                                                    value="{{ old('received_date') }}"
                                                    placeholder="เลือกวันรับเข้า">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Supplier</label>
                                                <input type="text" class="form-control" name="supplier"
                                                    placeholder="ชื่อบริษัท" />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">เลข Stock กรม</label>
                                                <input type="text" class="form-control" name="stock_no"
                                                    placeholder="รายการสินค้า" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Class</label>
                                                <input type="text" class="form-control" name="class1" value="{{ old('class1') }}"
                                                    placeholder="1" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Type</label>
                                                <input type="text" class="form-control" name="type1" value="{{ old('type1') }}"
                                                    placeholder="1" />
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">หมายเหตุเพิ่มเติม</label>
                                                <textarea class="form-control" name="remark" rows="2" placeholder="รายละเอียดเพิ่มเติม"></textarea>
                                            </div>

                                            {{-- แนบไฟล์ --}}
                                            {{-- แนบไฟล์ --}}
                                            <div class="col-md-3">
                                            <label class="form-label">แนบไฟล์ใบเชอร์ชุบกัลวาไนซ์</label>
                                            <input type="file" name="galvanize_cert_file" class="form-control" accept=".png,.jpg,.jpeg,.pdf">
                                            <div class="form-text">รองรับ .jpg .png .pdf</div>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">แนบไฟล์ใบเซอร์เหล็ก</label>
                                                <input type="file" name="steel_cert_file" class="form-control" accept=".png,.jpg,.jpeg,.pdf">
                                                <div class="form-text">รองรับ .jpg .png .pdf</div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">แนบไฟล์ใบเซอร์กรม</label>
                                                <input type="file" name="official_cert_file" class="form-control" accept=".png,.jpg,.jpeg,.pdf">
                                                <div class="form-text">รองรับ .jpg .png .pdf</div>
                                            </div>


                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer d-flex justify-content-start">
                                    <button type="submit" class="btn btn-secondary">สร้างรายการล็อตนัมเบอร์</button>
                                    <a href="{{ url('admin/lots') }}" class="btn btn-light ms-3">ยกเลิก</a>
                                </div>
                            </form>
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
<script>
(function () {
  const $ = window.jQuery; // Metronic มี jQuery อยู่แล้ว
  const $cat = $('#category_id');
  const $prd = $('#product_id');
  const urlTpl = $cat.data('products-url'); // /admin/products/by-category/ID_PLACEHOLDER

  function refreshSelect2($el) {
    if ($ && $.fn.select2) $el.trigger('change.select2');
  }

function renderProductOptions(items, selected = null) {
    $prd.empty().append(new Option('เลือกสินค้า', ''));

    items.forEach(i => {
        const opt = new Option(`${i.sku} - ${i.name}`, i.id, false, String(selected) === String(i.id));
        opt.setAttribute('data-lot-format', i.lot_format); // <-- ใส่ตรงนี้
        $prd.append(opt);
    });

    $prd.prop('disabled', items.length === 0);
    refreshSelect2($prd);
}

// เมื่อเปลี่ยนสินค้า ให้เติม lot_format ใหม่
$('#product_id').on('change', function () {

    const selectedId = $(this).val();
    const allOptions = $('#product_id option');
    const selectedText = allOptions.filter(`[value="${selectedId}"]`).text();

    // หา lot_format จาก options ที่โหลดไว้
    const products = Array.from($prd[0].options).slice(1); // ข้าม option แรก
    const found = products.find(opt => opt.value === selectedId);

    // ดึง lot_format จาก attributes (ถ้าจะเพิ่ม data-* ก็ใช้ได้)
    const lotFormat = found?.dataset?.lotFormat;
    console.log('ให้เติม lot_format ใหม่', lotFormat)
    if (lotFormat) {
        $('input[name="lot_no"]').val(lotFormat);
    }
});


  async function loadProducts(categoryId, selected=null) {
    renderProductOptions([]);                 // clear
    $prd.prop('disabled', true);

    if (!categoryId) return;                  // ไม่มีหมวด -> ว่างไว้

    const url = urlTpl.replace('ID_PLACEHOLDER', categoryId);
    try {
      const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
      const data = await res.json();
      renderProductOptions(data, selected);
    } catch (err) {
      console.error('Load products error', err);
      renderProductOptions([]);              // กรณี error ให้คงว่าง
    }
  }

  // เมื่อเปลี่ยนประเภท (รองรับทั้ง change และ select2:select)
  $cat.on('change select2:select', function () {
    loadProducts(this.value);
  });

  // ถ้ามีค่าเก่าจาก validation -> โหลดรายการตอนเปิดหน้า
  @if(old('category_id'))
    loadProducts("{{ old('category_id') }}", "{{ old('product_id') }}");
  @endif
})();
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
  // วันนี้ (Date object) สำหรับ defaultDate
  const now = new Date();

  // วันรับเข้า: date only, ส่งค่า Y-m-d แต่แสดง d/m/Y
  flatpickr('#received_date', {
    dateFormat: 'Y-m-d',   // ค่าที่ submit
    altInput: true,
    altFormat: 'd/m/Y',    // รูปแบบที่แสดงให้ผู้ใช้เห็น
    defaultDate: now
  });

  // วันที่ผลิต: date only
  flatpickr('#mfg_date', {
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'd/m/Y',
    defaultDate: now
  });

  // เวลา: time only (24 ชม.), ค่าเริ่มต้น = เวลาปัจจุบัน
  flatpickr('#mfg_time', {
    enableTime: true,
    noCalendar: true,
    time_24hr: true,
    dateFormat: 'H:i',
    defaultDate: now
  });
});


document.addEventListener('DOMContentLoaded', function () {
    let currentLotFormat = ''; // ใช้เก็บค่า lot_format

    // เมื่อเลือกสินค้าใหม่: เก็บ lot_format
    $('#product_id').on('change', function () {
        const selectedId = $(this).val();
        const found = $(this).find(`option[value="${selectedId}"]`);
        currentLotFormat = found.data('lot-format') || '';
        updateLotNo(); // ถ้ามีวันที่แล้ว ให้เติมทันที
    });

    // เมื่อเลือกวันที่ผลิต
    $('#mfg_date').on('change', function () {
        updateLotNo();
    });

    function updateLotNo() {
        const dateStr = $('#mfg_date').val(); // ค่าที่ได้จะเป็น yyyy-mm-dd
        if (!dateStr || !currentLotFormat) return;

        const [year, month] = dateStr.split('-');
        if (!year || !month) return;

        const lotSuffix = year.slice(-2) + month.padStart(2, '0'); // เช่น "25" + "07" = "2507"
        const newLotNo = `${currentLotFormat}-${lotSuffix}-`;
        $('input[name="lot_no"]').val(newLotNo);
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const $lotInput = $('#lot_no');
    const $errorMsg = $('#lotError');
    let debounceTimer;

    async function checkLotNoDuplication(lotNo) {
        const url = '{{ route('ajax.lotno-check', 'REPLACE_LOT') }}'.replace('REPLACE_LOT', encodeURIComponent(lotNo));
        const res = await fetch(url);
        const json = await res.json();
        return json.exists;
    }

    $lotInput.on('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(async () => {
            const lotNo = this.value.trim();
            if (!lotNo) return $errorMsg.addClass('d-none');

            const exists = await checkLotNoDuplication(lotNo);
            if (exists) {
                $errorMsg.removeClass('d-none');
                this.classList.add('is-invalid');
            } else {
                $errorMsg.addClass('d-none');
                this.classList.remove('is-invalid');
            }
        }, 500); // wait 500ms after typing stops
    });

    // OPTIONAL: block form submission if duplicate
    $('form').on('submit', function (e) {
        if (!$errorMsg.hasClass('d-none')) {
            e.preventDefault();
            alert('ไม่สามารถบันทึกได้: ล็อตนัมเบอร์ซ้ำ');
        }
    });
});


</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const $cat = $('#category_id');
    const $extraDetail = $('#additional_production_detail');

    function toggleExtraSection(catId) {
        if (parseInt(catId) === 3 || parseInt(catId) === 1) {
            $extraDetail.hide(); // ซ่อนเมื่อ id = 3
        } else {
            $extraDetail.show(); // แสดงเมื่อ id อื่น
        }
    }

    // เมื่อมีการเลือกประเภทสินค้าใหม่
    $cat.on('change select2:select', function () {
        toggleExtraSection(this.value);
    });

    // เรียกครั้งแรกเมื่อโหลดหน้า
    toggleExtraSection($cat.val());
});
</script>
@endsection


