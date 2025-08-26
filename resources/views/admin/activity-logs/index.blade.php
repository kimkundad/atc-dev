@extends('admin.layouts.template')

@section('title')
  <title>ระบบตรวจสอบข้อมูลสินค้าออนไลน์ผ่าน QR Code (Traceability System)</title>
@stop

@section('stylesheet')
<style>
  .role-badge { text-transform: lowercase; }
</style>
@stop

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">

    {{-- Toolbar --}}
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">ประวัติการเข้าใช้งาน</h1>
          <div class="text-muted mt-1">จำนวนกิจกรรมทั้งหมด <span class="text-success fw-bold">{{ number_format($logs->total()) }}</span></div>
        </div>
      </div>
    </div>

    {{-- Content --}}
    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">

        <div class="card">
          {{-- Header: search + filter --}}
          <div class="card-header border-0 pt-6">
            <div class="card-title w-100">
              <form id="filterForm" method="GET" action="{{ route('activity-logs.activity') }}" class="w-100">
                <div class="row g-3 align-items-center">
                  <div class="col-lg">
                    <div class="input-group">
                      <input type="text" class="form-control" name="q" placeholder="ค้นหา (ชื่อ, นามสกุล, บัญชี, เมนู, คำอธิบาย)"
                             value="{{ $q }}">
                      <button class="btn btn-light" type="submit">
                        <span class="svg-icon svg-icon-1">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
														<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
													</svg>
												</span>
                      </button>
                    </div>
                  </div>

                  <div class="col-auto">
                    <select class="form-select form-select-sm w-200px" name="role" id="roleFilter"
                            data-control="select2" data-hide-search="true">
                      <option value="">ประเภทบัญชี: ทั้งหมด</option>
                      @foreach($roles as $r)
                        <option value="{{ $r }}" @selected(($role ?? '') === $r)>{{ $r }}</option>
                      @endforeach
                    </select>
                  </div>



                </div>
              </form>
            </div>
          </div>

          {{-- Body --}}
          <div class="card-body pt-0">
            <div class="table-responsive">
              <table class="table align-middle table-row-dashed fs-6 gy-4">
                <thead>
                  <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase">
                    <th class="min-w-60px">No.</th>
                    <th class="min-w-160px">ชื่อ</th>
                    <th class="min-w-140px">ชื่อบัญชี</th>
                    <th class="min-w-200px">เมนู / การกระทำ</th>
                    <th class="min-w-170px">เข้าใช้งานล่าสุด</th>
                  </tr>
                </thead>
                <tbody class="fw-semibold text-gray-700">
                  @forelse($logs as $i => $log)
                    @php
                      $user = $log->user;
                      $badge = match($log->action){
                        'create' => 'badge-light-success',
                        'update' => 'badge-light-warning',
                        'delete' => 'badge-light-danger',
                        'login'  => 'badge-light-primary',
                        default  => 'badge-light'
                      };
                    @endphp
                    <tr>
                      <td>{{ $logs->firstItem() + $i }}</td>
                      <td class="fw-bold">
                        {{ $user?->fname }} {{ $user?->lname }}
                        @if($user?->role?->name)
                          <span class="badge badge-light ms-2 role-badge">{{ $user->role->name }}</span>
                        @endif
                      </td>
                      <td>{{ $user?->name ?? $user?->email }}</td>
                      <td>
                        <div class="d-flex flex-column">
                            <div>
                            <span class="badge {{ $log->action_badge }} me-2">{{ $log->action_text }}</span>
                            @if($log->entity_name)
                                <span class="fw-semibold">{{ $log->entity_name }}</span>
                            @endif
                            </div>

                            @if($log->summary)
                            <div class="text-muted fs-8 mt-1">{{ $log->summary }}</div>
                            @endif

                            <div class="text-gray-500 fs-8 mt-1">
                            [{{ $log->route_name ?? '—' }}] {{ $log->uri }} ({{ strtoupper($log->method ?? '') }})
                            </div>
                        </div>
                        </td>

                      <td>{{ $log->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted py-10">ไม่พบข้อมูล</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>

            {{-- Footer: pagination --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center mt-5">
              <div class="text-muted">หน้า {{ $logs->currentPage() }} / {{ $logs->lastPage() }}</div>
              {{ $logs->onEachSide(1)->links('vendor.pagination.metronic') }}
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
  // เปลี่ยน role/per_page แล้ว submit อัตโนมัติ
  document.getElementById('roleFilter')?.addEventListener('change', () => {
    document.getElementById('filterForm').submit();
  });
  document.getElementById('perPage')?.addEventListener('change', () => {
    document.getElementById('filterForm').submit();
  });
</script>

<script>
(function(){
  const form = document.getElementById('filterForm');
  const role = document.getElementById('roleFilter');

  const submitNow = () => {
    // รีเซ็ต page -> 1 ทุกครั้งที่เปลี่ยน filter
    const pg = form.querySelector('input[name="page"]');
    if (pg) pg.remove();
    form.requestSubmit ? form.requestSubmit() : form.submit();
  };

  role?.addEventListener('change', submitNow);
  if (window.jQuery) {
    const $role = window.jQuery(role);
    $role.on('select2:select', submitNow);
    $role.on('select2:clear', submitNow);
  }
})();
</script>
@stop
