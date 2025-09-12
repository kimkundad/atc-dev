@extends('admin.layouts.template')

@section('title')
    <title>ระบบตรวจสอบข้อมูลสินค้าออนไลน์ผ่าน QR Code (Traceability System)</title>
@stop

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6"></div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="row g-5 g-xl-8">

                    <div class="col-12">
                        <form class="card" method="POST" action="{{ route('lots.update', $lot->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="card-header border-0">
                                <h3 class="card-title fw-bold">แก้ไขล็อตนัมเบอร์</h3>
                                <h3 class="card-title ms-3">Lot No. {{ $lot->lot_no }}</h3>
                            </div>

                            <div class="card-body">
                                {{-- รายละเอียดสินค้า --}}
                                {{-- รายละเอียดสินค้า --}}
<div class="border rounded p-6 mb-10">
    <h4 class="fw-bold mb-5">รายละเอียดสินค้า</h4>
    <div class="row g-5">

        {{-- ประเภทสินค้า --}}
        <div class="col-md-6">
            <label class="form-label required">ประเภทสินค้า</label>
            <select class="form-select" name="category_id" id="category_id"
                    data-control="select2" data-hide-search="true" required>
                <option value="">เลือกประเภทสินค้า</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('category_id', $lot->category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        </div>

        {{-- สินค้า --}}
        <div class="col-md-6">
            <label class="form-label required">สินค้า</label>
            <select class="form-select" name="product_id" id="product_id"
                    data-control="select2" required>
                <option value="">เลือกสินค้า</option>
                @foreach($products as $p)
                    <option value="{{ $p->id }}"
                            data-lot-format="{{ $p->lot_format }}"
                            {{ old('product_id', $lot->product_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->sku }} - {{ $p->name }}
                    </option>
                @endforeach
            </select>
            @error('product_id')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        </div>

    </div>
</div>

<div class="border rounded p-6 mb-10">
        <h4 class="fw-bold mb-3">สถานะ ล็อตนัมเบอร์</h4>
        {{-- pattern มาตรฐาน: hidden 0 + checkbox 1 --}}
        <div class="form-check form-switch form-check-custom form-check-solid mt-3">
            <input type="hidden" name="active" value="0">
            <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                   {{ old('active', $lot->active) ? 'checked' : '' }}>
            <label class="form-check-label" for="active">
                เปิดใช้งาน (ปิด = ยกเลิกการใช้งาน)
            </label>
        </div>
        <div class="form-text">เอาเครื่องหมายถูกออกเพื่อ “ยกเลิกการใช้งาน” ล็อตนัมเบอร์ นี้</div>
    </div>


                                {{-- รายละเอียดการผลิต --}}
                                <div  class="border rounded p-6 mb-10">
                                    <h4 class="fw-bold mb-5">รายละเอียดการผลิต</h4>
                                    <div class="row g-5">
                                        <div class="col-md-4">
                                            <label class="form-label required">ล็อตนัมเบอร์</label>
                                            <input type="text" class="form-control" name="lot_no" id="lot_no"
                                                value="{{ old('lot_no', $lot->lot_no) }}" />
                                            <small id="lotError" class="text-danger d-none">ล็อตนัมเบอร์นี้มีอยู่แล้ว</small>
                                        </div>


                                        <div class="col-md-4">
                                                <label class="form-label">วันที่ผลิต</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control" id="mfg_date" name="mfg_date"
                                                        value="{{ old('mfg_date', $lot->mfg_date ? \Carbon\Carbon::parse($lot->mfg_date)->format('Y-m-d') : '') }}" placeholder="เลือกวันที่"/>
                                                    <input type="text" class="form-control" id="mfg_time" name="mfg_time"
                                                        value="{{ old('mfg_time', $lot->mfg_time ? \Carbon\Carbon::parse($lot->mfg_time)->format('H:i') : '') }}" placeholder="เลือกเวลา"/>
                                                </div>
                                            </div>

                                        <div class="col-md-4">
                                            <label class="form-label required">จำนวนสินค้า</label>
                                            <input type="number" class="form-control" name="qty" min="1" step="1" id="qty"
                                                   value="{{ old('qty', $lot->qty) }}" />
                                            @error('qty')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label required">Product No. เดิม</label>
                                            <input type="number" class="form-control" name="product_no_old" id="product_no_old"
                                                   value="{{ old('product_no_old', $lot->product_no_old) }}" readonly/>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required">Product No. ล่าสุด</label>
                                            <input type="number" class="form-control" name="product_no_new" id="product_no_new"
                                                   value="{{ old('product_no_new', $lot->product_no_new) }}" readonly/>
                                        </div>





                                    </div>
                                </div>


                                <div id="myClass" class="border rounded p-6 mb-10">
                                        <div class="row g-5">

                                        <div class="col-md-6">
                                                <label class="form-label">Class</label>
                                                <input type="text" class="form-control" name="class1" value="{{ old('class1', $lot->class1) }}"
                                                    placeholder="1" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Type</label>
                                                <input type="text" class="form-control" name="type1" value="{{ old('type1', $lot->type1) }}"
                                                    placeholder="1" />
                                            </div>
                                        </div>
                                </div>





                                {{-- รายละเอียดการผลิต (เพิ่มเติม) --}}
                                <div id="additional_production_detail" class="border rounded p-6">
                                    <h4 class="fw-bold mb-5">รายละเอียดการผลิต (เพิ่มเติม)</h4>
                                    <div class="row g-5">



                                        <div class="col-md-4">
                                                <label class="form-label">วันรับเข้า </label>
                                                <input type="text"
                                                    class="form-control"
                                                    id="received_date"
                                                    name="received_date"
                                                    value="{{ $lot->received_date ? \Carbon\Carbon::parse($lot->received_date)->format('Y-m-d') : '' }}"
                                                    placeholder="เลือกวันรับเข้า">
                                            </div>

                                        <div class="col-md-4">
                                            <label class="form-label">Supplier</label>
                                            <input type="text" class="form-control" name="supplier"
                                                   value="{{ old('supplier', $lot->supplier) }}" />
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">เลข Stock หมด</label>
                                            <input type="text" class="form-control" name="stock_no"
                                                   value="{{ old('stock_no', $lot->stock_no) }}" />
                                        </div>



                                        <div class="col-12">
                                            <label class="form-label">หมายเหตุเพิ่มเติม</label>
                                            <textarea class="form-control" name="remark" rows="2">{{ old('remark', $lot->remark) }}</textarea>
                                        </div>

                                        {{-- แนบไฟล์ --}}
                                        <div class="col-md-3">
                                            <label class="form-label">แนบไฟล์ใบเชอร์ชุบกัลวาไนซ์</label>
                                            @if($lot->galvanize_cert_path)
                                                <div class="mb-2">
                                                    <a target="_blank" href="{{ Storage::disk('spaces')->url($lot->galvanize_cert_path) }}">ไฟล์ปัจจุบัน</a>
                                                </div>
                                            @endif
                                            <input type="file" name="galvanize_cert_file" accept=".png,.jpg,.jpeg,.pdf" class="form-control">
                                            <div class="form-text">รองรับ .jpg .png .pdf</div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">แนบไฟล์ใบเซอร์เหล็ก</label>
                                            @if($lot->steel_cert_path)
                                                <div class="mb-2">
                                                    <a target="_blank" href="{{ Storage::disk('spaces')->url($lot->steel_cert_path) }}">ไฟล์ปัจจุบัน</a>
                                                </div>
                                            @endif
                                            <input type="file" name="steel_cert_file" accept=".png,.jpg,.jpeg,.pdf" class="form-control">
                                            <div class="form-text">รองรับ .jpg .png .pdf</div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">แนบไฟล์ใบเซอร์กรม</label>
                                            @if($lot->official_cert_file)
                                                <div class="mb-2">
                                                    <a target="_blank" href="{{ Storage::disk('spaces')->url($lot->official_cert_file) }}">ไฟล์ปัจจุบัน</a>
                                                </div>
                                            @endif
                                            <input type="file" name="official_cert_file" accept=".png,.jpg,.jpeg,.pdf" class="form-control">
                                            <div class="form-text">รองรับ .jpg .png .pdf</div>
                                        </div>


                                    </div>
                                </div>
                            </div>

                            <div class="card-footer d-flex justify-content-start">
                                <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                                <a href="{{ route('lots.index') }}" class="btn btn-light ms-3">ยกเลิก</a>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {
  const now = new Date();

  flatpickr('#received_date', {
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'd/m/Y',
  });

  flatpickr('#mfg_date', {
    dateFormat: 'Y-m-d',
    altInput: true,
    altFormat: 'd/m/Y',
  });

  flatpickr('#mfg_time', {
    enableTime: true,
    noCalendar: true,
    time_24hr: true,
    dateFormat: 'H:i',
    defaultDate: now
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const $cat  = $('#category_id');
    const $prod = $('#product_id');

    // ✅ ทำให้เห็นได้ทุกสคริปต์
    window.isInitialLoad = true;

    function loadProducts(catId, selectedId = null) {
        if (!catId) {
            $prod.html('<option value="">เลือกสินค้า</option>').trigger('change', [true]); // ส่ง init flag
            return;
        }
        $prod.prop('disabled', true)
             .html('<option value="">กำลังโหลด...</option>')
             .trigger('change', [true]); // ส่ง init flag

        $.get('{{ route('ajax.products-by-category2') }}', { category_id: catId }, function (rows) {
            let html = '<option value="">เลือกสินค้า</option>';
            rows.forEach(function (p) {
                const sel = (selectedId && Number(selectedId) === Number(p.id)) ? 'selected' : '';
                html += `<option value="${p.id}" data-lot-format="${p.lot_format || ''}" ${sel}>${p.sku} - ${p.name}</option>`;
            });
            $prod.html(html).prop('disabled', false);

            // ดึงค่า lot_format ของ option ที่ถูกเลือกไว้ (ไม่ต้องไปแก้ lot_no)
            const $sel = $prod.find('option:selected');
            window.currentLotFormat = $sel.attr('data-lot-format') || '';

            // 🔸 ถ้าจำเป็นต้องกระตุ้น change เพื่อให้ UI อื่น ๆ รู้ ให้ส่ง init=true
            setTimeout(() => {
                $prod.trigger('change', [true]); // <-- บอกว่าเป็นการ init
                window.isInitialLoad = false;     // ✅ เปิดให้ event ต่อไปทำงานปกติ
            }, 100);
        });
    }

    // เปลี่ยนประเภท → โหลดสินค้าใหม่
    $cat.on('change', function () { loadProducts(this.value, null); });

    const initialCat     = $cat.val();
    const selectedProdId = '{{ old('product_id', $lot->product_id) }}';
    if (initialCat) {
        loadProducts(initialCat, selectedProdId);
    } else {
        window.isInitialLoad = false; // หากไม่โหลด product
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ✅ เก็บ lot format ปัจจุบันไว้ระดับ global เช่นกัน
    window.currentLotFormat = $('select[name="product_id"] option:selected').attr('data-lot-format') || '';

    // รับพารามิเตอร์ที่สองเป็น init flag
    $('#product_id').on('change', function (e, isInit = false) {
        // ถ้าเป็นช่วง initial load หรือเป็น change ที่มาจากการ init → ไม่ต้องอัปเดต lot_no
        if (window.isInitialLoad || isInit === true) return;

        const selected = $(this).find('option:selected');
        window.currentLotFormat = selected.attr('data-lot-format') || '';
        console.log('เมื่อเปลี่ยนสินค้า -->', window.currentLotFormat);
        updateLotNo();
    });

    // ถ้าอยากให้เปลี่ยนล็อตตามวันที่ผลิต ค่อยเปิดใช้
    // $('#mfg_date').on('change', updateLotNo);

    function updateLotNo() {
        const dateStr = $('#mfg_date').val();
        if (!dateStr || !window.currentLotFormat) return;

        const [year, month] = dateStr.split('-');
        if (!year || !month) return;

        const suffix = year.slice(-2) + month.padStart(2, '0');
        const generatedPrefix = `${window.currentLotFormat}-${suffix}-`;

        const currentValue = $('#lot_no').val();

        let trailing = '';
        if (currentValue && currentValue.includes('-')) {
            const parts = currentValue.split('-');
            if (parts.length >= 3) {
                trailing = parts.slice(2).join('-'); // เก็บเลขต่อท้าย เช่น "009" หรือ "009-XX"
            }
        }

        const finalLot = generatedPrefix + trailing;
        $('#lot_no').val(finalLot);
        checkLotDuplication(finalLot);
    }

    const $lotInput = $('#lot_no');
    const $errorMsg = $('#lotError');
    let debounceTimer;

    async function checkLotDuplication(lotNo) {
        const url = '{{ route('ajax.lotno-check', 'REPLACE_LOT') }}'.replace('REPLACE_LOT', encodeURIComponent(lotNo));
        const res = await fetch(url);
        const json = await res.json();
        if (json.exists && lotNo !== '{{ $lot->lot_no }}') {
            $errorMsg.removeClass('d-none');
            $lotInput.addClass('is-invalid');
        } else {
            $errorMsg.addClass('d-none');
            $lotInput.removeClass('is-invalid');
        }
    }

    $lotInput.on('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const lotNo = this.value.trim();
            if (!lotNo) return;
            checkLotDuplication(lotNo);
        }, 500);
    });

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
    const $myClass = $('#myClass');

    function toggleExtraSection(catId) {
        if (parseInt(catId) === 3) {
            $extraDetail.hide(); // ซ่อนเมื่อ id = 3
        } else {
            $extraDetail.show(); // แสดงเมื่อ id อื่น
        }

        if(parseInt(catId) === 2){
            $myClass.show();
        }else{
            $myClass.hide();
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
<script>
document.addEventListener('DOMContentLoaded', function () {
  const $qty  = $('#qty');
  const $old  = $('#product_no_old');
  const $neww = $('#product_no_new');

  function syncProductNo() {
    const n = parseInt($qty.val(), 10);
    if (Number.isFinite(n) && n > 0) {
      $old.val(1);      // เดิม = 1 เสมอ
      $neww.val(n);     // ล่าสุด = จำนวนที่กรอก
    } else {
      $old.val('');
      $neww.val('');
    }
  }

  // อัปเดตทันทีเมื่อพิมพ์/เปลี่ยนค่า และเรียกครั้งแรกตอนโหลดหน้า
  $qty.on('input change', syncProductNo);
  syncProductNo();
});
</script>
@endsection

