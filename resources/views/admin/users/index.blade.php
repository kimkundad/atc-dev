@extends('admin.layouts.template')

@section('title')
    <title>ATC | Users</title>
@stop

@section('stylesheet')
<style>.role-badge{text-transform:lowercase}</style>
@stop

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">

    {{-- Toolbar --}}
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">ผู้ใช้งานในระบบ</h1>
          <div class="text-muted mt-1">จำนวนผู้ใช้งานในระบบทั้งหมด
            <span class="text-success fw-bold">{{ number_format($users->total()) }}</span>
          </div>
        </div>
        <div class="d-flex align-items-center gap-2 gap-lg-3">
          <a href="{{ route('users.create') }}" class="btn btn-outline btn-outline-dashed btn-outline-success btn-active-light-success">
            เพิ่มผู้ใช้งาน
          </a>
        </div>
      </div>
    </div>

    {{-- Content --}}
    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="row g-5 g-xl-8">
          <div class="col-12">
            <div class="card">

              {{-- Header: search + filter (ใช้ GET) --}}
              <div class="card-header border-0 pt-6">
                <div class="card-title w-100">
                  <form method="GET" class="w-100 d-flex flex-column gap-3 gap-lg-0 flex-lg-row">
                    <div class="input-group me-lg-5">
                      <input type="text" class="form-control" placeholder="ค้นหา (ชื่อ, นามสกุล, อีเมล, ชื่อบัญชี)"
                             name="q" value="{{ $q }}">
                      <button class="btn btn-light" type="submit" id="btnSearch">
                        <span class="svg-icon svg-icon-1">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1" transform="rotate(45 17.0365 15.1223)" fill="currentColor"></rect>
														<path d="M11 19C6.55556 19 3 15.4444 3 11C3 6.55556 6.55556 3 11 3C15.4444 3 19 6.55556 19 11C19 15.4444 15.4444 19 11 19ZM11 5C7.53333 5 5 7.53333 5 11C5 14.4667 7.53333 17 11 17C14.4667 17 17 14.4667 17 11C17 7.53333 14.4667 5 11 5Z" fill="currentColor"></path>
													</svg>
												</span>
                      </button>
                    </div>

                    <div class="d-flex align-items-center ms-lg-auto">
                      <span class="me-3 w-200px fs-5">ประเภทบัญชี</span>
                      <select class="form-select form-select-sm w-200px" name="role_id" data-control="select2" data-hide-search="true"
                              onchange="this.form.submit()">
                        <option value="">ทั้งหมด</option>
                        @foreach($roles as $r)
                          <option value="{{ $r->id }}" {{ (string)$roleId===(string)$r->id ? 'selected':'' }}>
                            {{ $r->name }}
                          </option>
                        @endforeach
                      </select>
                    </div>
                  </form>
                </div>
              </div>

              {{-- Body --}}
              <div class="card-body pt-0">

                {{-- แสดงผลลัพธ์ --}}
                @if(session('success'))
                <div class="alert alert-success mb-5">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger mb-5">{{ session('error') }}</div>
                @endif


                <div class="table-responsive">
                  <table class="table align-middle table-row-dashed fs-6 gy-4">
                    <thead>
                      <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase">
                        <th class="w-30px">
                          <div class="form-check form-check-sm form-check-custom">
                            <input class="form-check-input" type="checkbox" id="checkAll" />
                          </div>
                        </th>
                        <th class="min-w-160px">ชื่อ</th>
                        <th class="min-w-160px">นามสกุล</th>
                        <th class="min-w-140px">ชื่อบัญชี</th>
                        <th class="min-w-140px">ประเภทบัญชี</th>
                        <th class="min-w-160px">อีเมล</th>
                        <th class="min-w-160px">สร้างเมื่อ</th>
                        <th class="text-end min-w-150px">การจัดการ</th>
                      </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-700">
                      @forelse($users as $u)
                        @php
                          $roleNames = $u->roles->pluck('description');
                          $firstRole = $roleNames->first();
                          $badgeMap  = [
                            'superadmin'=>'badge-light-danger',
                            'admin'     =>'badge-light-primary',
                            'manager'   =>'badge-light-warning',
                            'officer'   =>'badge-light-success',
                            'user'      =>'badge-light-secondary',
                          ];
                          $badgeClass = $badgeMap[$firstRole] ?? 'badge-light';
                        @endphp
                        <tr>
                          <td>
                            <div class="form-check form-check-sm form-check-custom">
                              <input class="form-check-input row-check" type="checkbox" value="{{ $u->id }}">
                            </div>
                          </td>
                          <td class="fw-bold">{{ $u->fname }}</td>
                          <td>{{ $u->lname }}</td>
                          <td>{{ $u->name }}</td>
                          <td>
                            @if($roleNames->isEmpty())
                              <span class="badge badge-light">-</span>
                            @else
                              <span class="badge role-badge {{ $badgeClass }}">{{ $roleNames->join(', ') }}</span>
                            @endif
                          </td>
                          <td>{{ $u->email }}</td>
                          <td>{{ optional($u->created_at)->format('d/m/Y H:i') }}</td>
                          <td class="text-end">
                            <div class="btn-group">
                              <a data-bs-toggle="dropdown" class="btn btn-light btn-sm">จัดการ</a>
                              <button type="button" class="btn btn-light btn-sm dropdown-toggle dropdown-toggle-split"
                                      data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle</span>
                              </button>
                              <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('users.edit',$u->id ?? 0) }}">แก้ไข</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" onsubmit="return confirmDelete(this);">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">ลบผู้ใช้งาน</button>
                                    </form>
                                </li>
                              </ul>
                            </div>
                          </td>
                        </tr>
                      @empty
                        <tr><td colspan="8" class="text-center text-muted py-10">ไม่พบข้อมูล</td></tr>
                      @endforelse
                    </tbody>
                  </table>
                </div>

                {{-- Footer: page size + pagination --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center mt-5">
                  <form method="GET" class="d-flex align-items-center gap-3">
                    {{-- คงพารามิเตอร์ค้นหา/กรองเดิม --}}
                    <input type="hidden" name="q" value="{{ $q }}">
                    <input type="hidden" name="role_id" value="{{ $roleId }}">
                    <span class="text-muted">แสดง</span>
                    <select class="form-select form-select-sm w-100px" name="per_page" onchange="this.form.submit()">
                      @foreach([25, 50, 100] as $pp)
                        <option value="{{ $pp }}" {{ (int)$perPage===$pp ? 'selected' : '' }}>
                          {{ $pp }} รายการ
                        </option>
                      @endforeach
                    </select>
                  </form>

                  {{-- ใช้ Bootstrap-5 pagination ของ Laravel --}}
                  {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
              </div> {{-- /card-body --}}
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
  // เลือกทั้งหมด
  document.getElementById('checkAll')?.addEventListener('change', e=>{
    document.querySelectorAll('.row-check').forEach(ch => ch.checked = e.target.checked);
  });
</script>
@stop
