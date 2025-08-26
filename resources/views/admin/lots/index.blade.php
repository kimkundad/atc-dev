@extends('admin.layouts.template')

@section('title')
<title>ระบบตรวจสอบข้อมูลสินค้าออนไลน์ผ่าน QR Code (Traceability System)</title>
@stop

@section('stylesheet')
<style>
    .kpi .value{font-size:28px;font-weight:800;line-height:1}
    .table thead tr.head-light th{
        background:#F5F8FA;color:#5E6278;font-weight:700;border-bottom:0;
        padding-top:.9rem;padding-bottom:.9rem
    }
    .table thead tr.head-light th:first-child{border-top-left-radius:8px}
    .table thead tr.head-light th:last-child{border-top-right-radius:8px}
</style>
@stop

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        {{-- Toolbar --}}
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">รายการล็อตนัมเบอร์</h1>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <a href="{{ route('lots.create') }}" class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success">เพิ่มรายการ</a>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                {{-- KPI --}}
                <div class="row g-5 mb-6">
                    <div class="col-md-4">
                        <div class="card card-flush p-5 kpi">
                            <div class="text-gray-600">วันนี้</div>
                            <div class="value text-success">{{ number_format($stats['today']) }}</div>
                            <div class="text-muted fs-8">จำนวนล็อตนัมเบอร์</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-flush p-5 kpi">
                            <div class="text-gray-600">เดือนนี้</div>
                            <div class="value">{{ number_format($stats['month']) }}</div>
                            <div class="text-muted fs-8">จำนวนล็อตนัมเบอร์</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-flush p-5 kpi">
                            <div class="text-gray-600">ปีนี้</div>
                            <div class="value">{{ number_format($stats['year']) }}</div>
                            <div class="text-muted fs-8">จำนวนล็อตนัมเบอร์</div>
                        </div>
                    </div>
                </div>



                {{-- ตาราง --}}
                <div class="card">
                    <div class="card-body pt-0">
                    <br>

                    {{-- ฟิลเตอร์ + ค้นหา --}}
                <form id="filterForm" method="GET" action="{{ route('lots.index') }}" class="mb-5">
                    {{-- search bar --}}
                    <div class="input-group mb-4">
                        <input type="text" class="form-control" name="q"
                               placeholder="ค้นหา"
                               value="{{ $q ?? '' }}">
                        <button class="btn btn-light" id="btnSearch" type="submit">
                            <span class="svg-icon svg-icon-1">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
														<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
													</svg>
												</span>
                        </button>
                    </div>

                    {{-- grey filter strip --}}
                    <div class="bg-light rounded px-4 py-3 d-flex align-items-center flex-wrap gap-6">
                        <div>
                            <div class="fw-semibold text-gray-600 fs-8 mb-1">เลือกดูตาม ประเภทสินค้า</div>
                            <select class="form-select form-select-sm w-200px"
                                    name="category_id" id="category_id" data-control="select2" data-hide-search="true">
                                <option value="">ทั้งหมด</option>
                                @foreach($categories as $c)
                                    <option value="{{ $c->id }}" @selected(($categoryId ?? '')==$c->id)>
                                        {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <div class="fw-semibold text-gray-600 fs-8 mb-1">เลือกดูตามวันที่ผลิต</div>
                            <input type="text" id="mfg_date" name="mfg_date"
                                   class="form-control form-control-sm w-200px"
                                   value="{{ $mfgDate ?? '' }}" placeholder="YYYY-MM-DD">
                        </div>



                    </div>
                </form>



                        <div class="table-responsive">
                            <table class="table  table-bordered align-middle table-row-dashed fs-6 gy-3 mb-0" style="    min-height: 300px;">
                                <thead>
                                    <tr class="fw-bold fs-7 text-gray-800">
                                        <th class="min-w-60px">No.</th>
                                        <th class="min-w-160px">Lot Number</th>
                                        <th class="min-w-170px">วันที่ผลิต</th>
                                        <th class="min-w-320px">หัวข้อ</th>
                                        <th class="min-w-220px">จำนวนสินค้า</th>
                                        <th class="min-w-200px">Supplier</th>
                                        <th class="min-w-140px">ผู้ทำรายการ</th>
                                        <th class="min-w-170px">วันที่บันทึก</th>
                                        <th class="text-end min-w-130px">จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-700">
                                    @forelse($lots as $idx => $lot)
                                        <tr>
                                            <td>{{ ($lots->firstItem() ?? 1) + $idx }}</td>

                                            <td class="fw-bold">{{ $lot->lot_no }}</td>

                                            <td>
                                                {{ \Carbon\Carbon::parse($lot->mfg_date)->format('d/m/Y') }}
                                                @if($lot->mfg_time) {{ \Carbon\Carbon::parse($lot->mfg_time)->format('H:i') }} @endif
                                            </td>

                                            {{-- ชื่อสินค้า + SKU ย่อย --}}
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <span class="fw-bold text-gray-900">{{ $lot->product->name ?? '-' }}</span>
                                                    <span class="fs-8 text-gray-500">{{ $lot->product->sku ?? '' }}</span>
                                                </div>
                                            </td>

                                            <td>{{ $lot->qty }} {{ $lot->run_range ? "({$lot->run_range})" : '' }}</td>
                                            <td>{{ $lot->supplier ?: '-' }}</td>
                                            <td>{{ $lot->creator->username ?? $lot->creator->name ?? '-' }}</td>
                                            <td>{{ $lot->created_at?->format('d/m/Y H:i') }}</td>

                                            <td class="text-end">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle"
                                                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                จัดการ
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                @if(Auth::user()->hasPermission('lot.edit'))
                                                    <li><a class="dropdown-item" href="{{ route('lots.edit', $lot->id) }}">แก้ไขรายการ</a></li>
                                                @endif

                                                @if(Auth::user()->hasPermission('lot.view'))
                                                    <li><a class="dropdown-item" href="{{ route('lots.show', $lot->id) }}">รายละเอียด</a></li>
                                                @endif

                                                <li><hr class="dropdown-divider"></li>

                                                @if(Auth::user()->hasPermission('lot.delete'))
                                                    <li>
                                                    <form id="delete-lot-{{ $lot->id }}" method="POST" action="{{ route('lots.destroy', $lot->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                class="dropdown-item text-danger btn-delete-lot"
                                                                data-id="{{ $lot->id }}"
                                                                data-lot="{{ $lot->lot_no }}">
                                                        ลบรายการ
                                                        </button>
                                                    </form>
                                                    </li>
                                                @endif
                                                </ul>
                                            </div>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr><td colspan="9" class="text-center text-muted py-10">ไม่พบข้อมูล</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        {{-- Footer: page size + pagination --}}
                        <div class="d-flex flex-wrap justify-content-between align-items-center p-5">
                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted">แสดง</span>
                                <form method="GET" action="{{ route('lots.index') }}">
                                    @foreach(request()->except('per_page','page') as $k=>$v)
                                        <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                                    @endforeach
                                    <select name="per_page" class="form-select form-select-sm w-120px" onchange="this.form.submit()">
                                        @foreach([12,25] as $n)
                                            <option value="{{ $n }}" @selected(($perPage ?? 12)==$n)>{{ $n }} รายการ</option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>

                            <div class="d-flex align-items-center gap-3">
                                <span class="text-muted">หน้า {{ $lots->currentPage() }}</span>
                                {{ $lots->onEachSide(1)->links('vendor.pagination.metronic') }}
                            </div>
                        </div>
                    </div>
                </div>

            </div> {{-- /container --}}
        </div> {{-- /content --}}
    </div>
</div>
@endsection

@section('scripts')



<script>
  // flatpickr (Metronic)
  if (typeof flatpickr !== 'undefined') {
    flatpickr("#mfg_date", {
      dateFormat: "Y-m-d",
      allowInput: true,
      defaultDate: "{{ $mfgDate ?? '' }}"
    });
  }

  // เปลี่ยนประเภทสินค้าแล้ว submit ทันที
  document.querySelector('select[name="category_id"]')
    ?.addEventListener('change', () => document.getElementById('filterForm').submit());

  // เปลี่ยน "วันที่ผลิต" แล้ว submit ทันที
  document.getElementById('mfg_date')
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
        flatpickr('#mfg_date', {
            dateFormat: 'Y-m-d',
            allowInput: true,
            defaultDate: "{{ $mfgDate ?? '' }}",
            onChange: function () {
                form.submit();
            }
        });
    } else {
        // เผื่อไม่ได้โหลด flatpickr
        const dateEl = document.getElementById('mfg_date');
        dateEl?.addEventListener('change', () => form.submit());
    }
});
</script>

<script>
document.querySelectorAll('.btn-delete-lot').forEach(btn => {
  btn.addEventListener('click', function () {
    const id  = this.dataset.id;
    const lot = this.dataset.lot || '';
    const form = document.getElementById('delete-lot-' + id);

    Swal.fire({
      title: 'ยืนยันการลบ?',
      html: lot ? `ต้องการลบ <b>Lot ${lot}</b> หรือไม่` : 'ต้องการลบรายการนี้หรือไม่',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'ลบรายการ',
      cancelButtonText: 'ยกเลิก',
      reverseButtons: true,
      customClass: { confirmButton: 'btn btn-danger', cancelButton: 'btn btn-light' },
      buttonsStyling: false
    }).then((r) => {
      if (r.isConfirmed) form.submit();
    });
  });
});
</script>

@if(session('success'))
<script>
Swal.fire({
  icon: 'success',
  title: 'สำเร็จ',
  text: @json(session('success')),
  timer: 1800,
  showConfirmButton: false
});
</script>
@endif


@stop
