@extends('admin.layouts.template')

@section('title')
    <title>บริษัท เอ ที ซี ทราฟฟิค จำกัด</title>
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
                        {{ old('product_id', $lot->product_id) == $p->id ? 'selected' : '' }}>
                        {{ $p->sku }} - {{ $p->name }}
                    </option>
                @endforeach
            </select>
            @error('product_id')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        </div>

    </div>
</div>


                                {{-- รายละเอียดการผลิต --}}
                                <div  class="border rounded p-6">
                                    <h4 class="fw-bold mb-5">รายละเอียดการผลิต</h4>
                                    <div class="row g-5">
                                        <div class="col-md-4">
                                            <label class="form-label required">ล็อตนัมเบอร์</label>
                                            <input type="text" class="form-control" name="lot_no"
                                                   value="{{ old('lot_no', $lot->lot_no) }}" />
                                            @error('lot_no')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label">วันที่ผลิต</label>
                                            <div class="input-group">
                                                <input type="date" class="form-control" name="mfg_date"
                                                       value="{{ old('mfg_date', $lot->mfg_date ? \Carbon\Carbon::parse($lot->mfg_date)->format('Y-m-d') : '') }}" />
                                                <input type="time" class="form-control" name="mfg_time"
                                                       value="{{ old('mfg_time', $lot->mfg_time ? \Carbon\Carbon::parse($lot->mfg_time)->format('H:i') : '') }}" />
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label class="form-label required">จำนวนสินค้า</label>
                                            <input type="number" class="form-control" name="qty" min="0" step="1"
                                                   value="{{ old('qty', $lot->qty) }}" />
                                            @error('qty')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label">Product No. เดิม</label>
                                            <input type="text" class="form-control" name="product_no_old"
                                                   value="{{ old('product_no_old', $lot->product_no_old) }}" />
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Product No. ล่าสุด</label>
                                            <input type="text" class="form-control" name="product_no_new"
                                                   value="{{ old('product_no_new', $lot->product_no_new) }}" />
                                        </div>


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
                                            <label class="form-label">วันรับเข้า</label>
                                            <input type="date" class="form-control" name="received_date"
                                                   value="{{ old('received_date', $lot->received_date ? \Carbon\Carbon::parse($lot->received_date)->format('Y-m-d') : '') }}">
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
                                                    <a target="_blank" href="{{ Storage::url($lot->galvanize_cert_path) }}">ไฟล์ปัจจุบัน</a>
                                                </div>
                                            @endif
                                            <input type="file" name="galvanize_cert_file" accept=".png,.jpg,.jpeg,.pdf" class="form-control">
                                            <div class="form-text">รองรับ .jpg .png .pdf</div>
                                        </div>

                                        <div class="col-md-3">
                                            <label class="form-label">แนบไฟล์ใบเซอร์เหล็ก</label>
                                            @if($lot->steel_cert_path)
                                                <div class="mb-2">
                                                    <a target="_blank" href="{{ Storage::url($lot->steel_cert_path) }}">ไฟล์ปัจจุบัน</a>
                                                </div>
                                            @endif
                                            <input type="file" name="steel_cert_file" accept=".png,.jpg,.jpeg,.pdf" class="form-control">
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
    const $cat  = $('#category_id');
    const $prod = $('#product_id');

    function loadProducts(catId, selectedId = null) {
        if (!catId) {
            $prod.html('<option value="">เลือกสินค้า</option>').trigger('change');
            return;
        }
        $prod.prop('disabled', true)
             .html('<option value="">กำลังโหลด...</option>')
             .trigger('change');

        $.get('{{ route('ajax.products-by-category2') }}', { category_id: catId }, function (rows) {
            let html = '<option value="">เลือกสินค้า</option>';
            rows.forEach(function (p) {
                const sel = (selectedId && Number(selectedId) === Number(p.id)) ? 'selected' : '';
                html += `<option value="${p.id}" ${sel}>${p.sku} - ${p.name}</option>`;
            });
            $prod.html(html).prop('disabled', false).trigger('change');
        });
    }

    // เปลี่ยนประเภท → โหลดสินค้า
    $cat.on('change', function () { loadProducts(this.value, null); });

    // เปิดหน้าครั้งแรก → ให้แน่ใจว่ารายการสินค้าเป็นของประเภทปัจจุบัน
    const initialCat     = $cat.val();
    const selectedProdId = '{{ old('product_id', $lot->product_id) }}';
    if (initialCat) {
        loadProducts(initialCat, selectedProdId);
    }
});
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
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const $category = $('#category_id');
    const $extraDetail = $('#additional_production_detail');

    function toggleExtraSection(categoryId) {
        if (parseInt(categoryId) === 3) {
            $extraDetail.hide();  // ซ่อนเมื่อเป็น สีเทอร์โมพลาสติก
        } else {
            $extraDetail.show();  // แสดงทุกกรณีอื่น
        }
    }

    // เรียกใช้เมื่อเปลี่ยนประเภทสินค้า
    $category.on('change', function () {
        toggleExtraSection(this.value);
    });

    // ตรวจสอบค่าเริ่มต้นเมื่อเปิดหน้า
    toggleExtraSection($category.val());
});
</script>
@endsection
