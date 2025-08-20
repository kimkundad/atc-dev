@extends('admin.layouts.template')

@section('title')
    <title>บริษัท เอ ที ซี ทราฟฟิค จำกัด</title>
@stop

@section('stylesheet')
<style>
    .kpi-card .value{font-size:32px;font-weight:800;line-height:1}
    .kpi-card .sub{font-size:12px;color:#7E8299}
    .table thead tr.table-head-gray th{
        background:#F5F8FA;color:#5E6278;font-weight:700;border-bottom:0;
        padding-top:.9rem;padding-bottom:.9rem
    }
    .table thead tr.table-head-gray th:first-child{border-top-left-radius:8px}
    .table thead tr.table-head-gray th:last-child{border-top-right-radius:8px}
</style>
@stop

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        {{-- Toolbar --}}
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        รายการ QR Code
                    </h1>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ url('admin/qrcode/create') }}" class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success">เพิ่มรายการ</a>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                {{-- KPIs --}}
                <div class="row g-5 mb-7">
                    <div class="col-md-4">
                        <div class="card kpi-card p-5">
                            <div class="fw-bold mb-2">วันนี้</div>
                            <div class="value text-success">{{ number_format($todayCount) }}</div>
                            <div class="sub mt-1">จำนวนป้ายแท็กทั้งหมด</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card kpi-card p-5">
                            <div class="fw-bold mb-2">เดือนนี้</div>
                            <div class="value">{{ number_format($monthCount) }}</div>
                            <div class="sub mt-1">จำนวนป้ายแท็กทั้งหมด</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card kpi-card p-5">
                            <div class="fw-bold mb-2">ปีนี้</div>
                            <div class="value">{{ number_format($yearCount) }}</div>
                            <div class="sub mt-1">จำนวนป้ายแท็กทั้งหมด</div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    {{-- Filters --}}
                    <div class="card-header border-0 pt-6">
                        <form id="filterForm" method="GET" action="{{ route('qrcode.index') }}" class="w-100">
                            {{-- search --}}
                            <div class="input-group mb-5">
                                <input type="text" class="form-control" name="q"
                                       placeholder="ค้นหา (QR Code, Lot Number, SKU, รายการสินค้า, Supplier)"
                                       value="{{ $q ?? '' }}">
                                <button class="btn btn-light" type="submit">
                                    <span class="svg-icon svg-icon-1">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                                  transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
                                            <path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z"
                                                  fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>

                            {{-- filter strip --}}
                            <div class="bg-light rounded px-4 py-3 d-flex align-items-center flex-wrap gap-6">
                                <div>
                                    <div class="fw-semibold text-gray-600 fs-8 mb-1">เลือกดูตาม ประเภทสินค้า</div>
                                    <select class="form-select form-select-sm w-200px"
                                            name="category_id" id="category_id"
                                            data-control="select2" data-hide-search="true">
                                        <option value="">ทั้งหมด</option>
                                        @foreach($categories as $c)
                                            <option value="{{ $c->id }}" @selected(($categoryId ?? '')==$c->id)>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                            <div>
                            <div class="fw-semibold text-gray-600 fs-8 mb-1">เลือกดูตามวันที่ผลิต  </div>
                            <input type="text"
       id="mfg_date1"
       name="mfg_date1"
       class="form-control form-control-sm w-200px"
       value="{{ $mfgDate ?? '' }}"
       placeholder="YYYY-MM-DD"
       autocomplete="off">
                        </div>
                            </div>
                        </form>
                    </div>

                    {{-- Table --}}
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-row-dashed fs-6 gy-5">
                                <thead>
                                    <tr class="fw-bold fs-7 text-gray-800">
                                        <th class="min-w-60px">No.</th>
                                        <th class="min-w-150px">สถานะ</th>
                                        <th class="min-w-160px">รหัส QR Code</th>
                                        <th class="min-w-170px">วันที่เชื่อมข้อมูล</th>
                                        <th class="min-w-140px">ล็อตนัมเบอร์</th>
                                        <th class="min-w-170px">วันที่ผลิต</th>
                                        <th class="min-w-280px">หัวข้อ</th>
                                        <th class="min-w-220px">จำนวนสินค้า</th>
                                        <th class="min-w-220px">Supplier</th>
                                        <th class="text-end min-w-100px">จัดการ</th>
                                    </tr>
                                </thead>

                                <tbody class="fw-semibold text-gray-700">
                                @forelse ($items as $idx => $qr)
                                    @php
                                        $lot     = $qr->lot;
                                        $product = $lot?->product;

                                        $statusText  = $lot ? ($qr->is_active ? 'ใช้งาน' : 'ปิดใช้งาน') : 'ไม่เชื่อม';
                                        $statusBadge = match ($statusText) {
                                            'ใช้งาน'    => 'badge-light-success',
                                            'ปิดใช้งาน' => 'badge-light-warning',
                                            default     => 'badge-light-secondary',
                                        };

                                        $createdAt = optional($qr->created_at)->format('d/m/Y H:i');
                                        $mfgDate   = $lot?->mfg_date1 ? \Carbon\Carbon::parse($lot->mfg_date1)->format('d/m/Y') : null;
                                        $mfgTime   = $lot?->mfg_time;
                                        $mfgDisplay= trim(($mfgDate ?? '').' '.($mfgTime ?? ''));

                                        $qtyText = $lot?->qty;
                                        if ($lot?->run_range) $qtyText = trim(($qtyText ?? '').' ('.$lot->run_range.')');
                                    @endphp

                                    <tr>
                                        <td>{{ $items->firstItem() + $idx }}</td>

                                        <td><span class="badge {{ $statusBadge }} fw-bold">{{ $statusText }}</span></td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold">{{ $qr->qr_code }}</span>
                                                @if($qr->link_url)
                                                    <a href="{{ $qr->link_url }}" target="_blank" class="text-primary text-hover-underline fs-8">Website Link</a>
                                                @endif
                                            </div>
                                        </td>

                                        <td>{{ $createdAt }}</td>
                                        <td>{{ $lot?->lot_no ?? '-' }}</td>
                                        <td>{{ $mfgDisplay ?: '-' }}</td>

                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-gray-900">{{ $product?->name ?? '-' }}</span>
                                                <span class="fs-8 text-gray-500">{{ $product?->sku ?? '' }}</span>
                                            </div>
                                        </td>

                                        <td>{{ $qtyText ?: '-' }}</td>
                                        <td>{{ $lot?->supplier ?: '-' }}</td>

                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    จัดการ
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li><a class="dropdown-item" href="{{ route('qrcode.edit',$qr->id) }}">แก้ไขรายการ</a></li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="10" class="text-center text-muted py-10">ไม่พบข้อมูล</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Footer: page size + pagination --}}
                        <div class="d-flex flex-wrap justify-content-between align-items-center mt-5">
                            {{-- page size --}}
                            <form method="GET" class="d-flex align-items-center gap-3">
                                <span class="text-muted">แสดง</span>
                                @php $pp = (int) request('per_page', $items->perPage()); @endphp
                                <select name="per_page" class="form-select form-select-sm w-100px" onchange="this.form.submit()">
                                    <option value="12" @selected($pp===12)>12 รายการ</option>
                                    <option value="25" @selected($pp===25)>25 รายการ</option>
                                    <option value="50" @selected($pp===50)>50 รายการ</option>
                                </select>

                                {{-- คืนค่าฟิลเตอร์เดิม --}}
                                @foreach(request()->except('page','per_page') as $k=>$v)
                                    @if(is_array($v))
                                        @foreach($v as $vv)
                                            <input type="hidden" name="{{ $k }}[]" value="{{ $vv }}">
                                        @endforeach
                                    @else
                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                    @endif
                                @endforeach
                            </form>

                            {{-- pagination --}}
                            <div>
                                {{ $items->onEachSide(1)->links('vendor.pagination.metronic') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        {{-- /Content --}}
    </div>
</div>
@endsection

@section('scripts')


<script>
  // flatpickr (Metronic)
  if (typeof flatpickr !== 'undefined') {
    flatpickr("#mfg_date1", {
      dateFormat: "Y-m-d",
      allowInput: true,
      defaultDate: "{{ $mfgDate ?? '' }}"
    });
  }

  // เปลี่ยนประเภทสินค้าแล้ว submit ทันที
  document.querySelector('select[name="category_id"]')
    ?.addEventListener('change', () => document.getElementById('filterForm').submit());

  // เปลี่ยน "วันที่ผลิต" แล้ว submit ทันที
  document.getElementById('mfg_date1')
    ?.addEventListener('change', () => document.getElementById('filterForm').submit());

  // (ถ้าต้องการ) เมื่อกด Enter ในช่องค้นหา จะ submit อยู่แล้วเพราะเป็นปุ่ม type=submit
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form   = document.getElementById('filterForm');
    const catEl  = document.getElementById('category_id');

    // --- Category filter ---
    if (catEl) {
        // native change (กรณีไม่ได้ใช้ select2 ก็ยังทำงาน)
        catEl.addEventListener('change', () => form.submit());

        // ถ้า select2 ถูก activate ให้ฟัง event ของ select2 ด้วย
        if (window.jQuery) {
            const $cat = window.jQuery(catEl);
            // Metronic จะ init select2 อัตโนมัติอยู่แล้ว
            $cat.on('select2:select', () => form.submit());
        }
    }

    // --- MFG date with flatpickr ---
    if (typeof flatpickr !== 'undefined') {
        flatpickr('#mfg_date1', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            defaultDate: "{{ $mfgDate ?? '' }}",
            onChange: function () {
                form.submit();
            }
        });
    } else {
        // เผื่อไม่ได้โหลด flatpickr
        const dateEl = document.getElementById('mfg_date1');
        dateEl?.addEventListener('change', () => form.submit());
    }
});
</script>

@stop


