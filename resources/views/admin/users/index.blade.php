@extends('admin.layouts.template')

@section('title')
    <title>ATC | Users</title>
@stop

@section('stylesheet')
{{-- เพิ่มสไตล์เล็กน้อยถ้าต้องการ --}}
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
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">ผู้ใช้งานในระบบ</h1>
                    <div class="text-muted mt-1">จำนวนผู้ใช้งานในระบบทั้งหมด <span class="text-success fw-bold">20</span></div>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ url('admin/users/create') }}" class="btn btn-sm btn-flex bg-body btn-color-gray-700 btn-active-color-primary fw-bold">
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

                            {{-- Header: search --}}
                            <div class="card-header border-0 pt-6">
                                <div class="card-title w-100">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="ค้นหา" id="searchBox">
                                        <button class="btn btn-light" type="button" id="btnSearch">
                                            <span class="svg-icon svg-icon-2">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <rect opacity="0.5" x="17.0365" y="15.1223" width="8.15546" height="2" rx="1"
                                                          transform="rotate(45 17.0365 15.1223)" fill="currentColor"/>
                                                    <path d="M11 19C6.58172 19 3 15.4183 3 11C3 6.58172 6.58172 3 11 3C15.4183 3 19 6.58172 19 11C19 15.4183 15.4183 19 11 19Z"
                                                          fill="currentColor"/>
                                                </svg>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            {{-- Body --}}
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-4" id="usersTable">
                                        <thead>
                                            {{-- filter row --}}
                                            <tr class="bg-light">
                                                <th colspan="9">
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-3">ประเภทบัญชี</span>
                                                        <select class="form-select form-select-sm w-200px" id="roleFilter" data-control="select2" data-hide-search="true">
                                                            <option value="">ทั้งหมด</option>
                                                            <option value="admin">admin</option>
                                                            <option value="manager">manager</option>
                                                            <option value="officer">officer</option>
                                                        </select>
                                                    </div>
                                                </th>
                                            </tr>

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
                                                <th class="min-w-160px">เข้าใช้งานล่าสุด</th>
                                                <th class="min-w-100px">สถานะ</th>
                                                <th class="text-end min-w-150px">การจัดการ</th>
                                            </tr>
                                        </thead>

                                        <tbody class="fw-semibold text-gray-700">
                                            @php
                                                $rows = [
                                                    ['fname'=>'กมณิษฐ์','lname'=>'สาระชา','username'=>'kanok','role'=>'admin','last'=>'14/09/2565 15:00','active'=>true],
                                                    ['fname'=>'จารุชา','lname'=>'นาคุศิริ','username'=>'jjriya','role'=>'admin','last'=>'14/09/2565 08:24','active'=>true],
                                                    ['fname'=>'จักรพินธ์','lname'=>'แสงเงิน','username'=>'jaru089','role'=>'manager','last'=>'14/09/2565 07:45','active'=>true],
                                                    ['fname'=>'ธิติพันธ์','lname'=>'สุภาพ','username'=>'thitii','role'=>'manager','last'=>'14/09/2565 18:00','active'=>true],
                                                    ['fname'=>'ประพนธ์','lname'=>'บุญผะเณชร','username'=>'punyapp','role'=>'manager','last'=>'14/09/2565 11:45','active'=>true],
                                                    ['fname'=>'ปรัชญา','lname'=>'ใจมุ่ง','username'=>'jpracha','role'=>'manager','last'=>'14/09/2565 08:20','active'=>true],
                                                    ['fname'=>'พงศกร','lname'=>'ชนโชติเจริญ','username'=>'pthachot','role'=>'officer','last'=>'14/09/2565 08:04','active'=>true],
                                                    ['fname'=>'พงษพร','lname'=>'รุฬพงศ์','username'=>'jjriya','role'=>'officer','last'=>'14/09/2565 07:45','active'=>true],
                                                    ['fname'=>'มารุต','lname'=>'รัชนียา','username'=>'maroot','role'=>'officer','last'=>'14/09/2565 21:00','active'=>true],
                                                    ['fname'=>'พงศกร','lname'=>'ชนโชติเจริญ','username'=>'pthachot','role'=>'officer','last'=>'15/09/2565 08:25','active'=>true],
                                                    ['fname'=>'พงษพร','lname'=>'รุฬพร','username'=>'pongp','role'=>'officer','last'=>'15/09/2565 07:45','active'=>true],
                                                    ['fname'=>'มารุต','lname'=>'รัชนียา','username'=>'maroot','role'=>'officer','last'=>'25/09/2565 15:00','active'=>true],
                                                ];

                                                $roleBadge = [
                                                    'admin'   => 'badge-light-primary',
                                                    'manager' => 'badge-light-warning',
                                                    'officer' => 'badge-light-success',
                                                ];
                                            @endphp

                                            @foreach ($rows as $i => $u)
                                                <tr>
                                                    {{-- checkbox --}}
                                                    <td>
                                                        <div class="form-check form-check-sm form-check-custom">
                                                            <input class="form-check-input row-check" type="checkbox" value="{{ $i }}">
                                                        </div>
                                                    </td>

                                                    <td class="fw-bold">{{ $u['fname'] }}</td>
                                                    <td>{{ $u['lname'] }}</td>
                                                    <td>{{ $u['username'] }}</td>

                                                    {{-- role badge --}}
                                                    <td>
                                                        <span class="badge role-badge {{ $roleBadge[$u['role']] ?? 'badge-light' }}">
                                                            {{ $u['role'] }}
                                                        </span>
                                                    </td>

                                                    <td>{{ $u['last'] }}</td>

                                                    {{-- status switch --}}
                                                    <td>
                                                        <div class="form-check form-switch form-check-custom form-check-solid">
                                                            <input class="form-check-input user-active-switch" type="checkbox"
                                                                   {{ $u['active'] ? 'checked' : '' }}>
                                                        </div>
                                                    </td>

                                                    {{-- actions --}}
                                                    <td class="text-end">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-light btn-sm">จัดการ</button>
                                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle dropdown-toggle-split"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                <span class="visually-hidden">Toggle Dropdown</span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="{{ url('admin/users/'.($i+1).'/edit') }}">แก้ไข</a></li>
                                                                <li><a class="dropdown-item" href="#">ปิดการใช้งาน</a></li>
                                                                <li><a class="dropdown-item" href="#">ดูประวัติการใช้งาน</a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item text-danger" href="#">ลบผู้ใช้งาน</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- footer: page size + pagination --}}
                                <div class="d-flex flex-wrap justify-content-between align-items-center mt-5">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="text-muted">แสดง</span>
                                        <select class="form-select form-select-sm w-100px">
                                            <option>10 รายการ</option>
                                            <option selected>13 รายการ</option>
                                            <option>25 รายการ</option>
                                        </select>
                                    </div>

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="text-muted">หน้า 1</span>
                                        <ul class="pagination pagination-outline mb-0">
                                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">ถัดไป</a></li>
                                        </ul>
                                    </div>
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
    document.getElementById('checkAll')?.addEventListener('change', function(e){
        document.querySelectorAll('.row-check').forEach(ch => ch.checked = e.target.checked);
    });

    // ตัวอย่างจับเหตุการณ์ toggle สถานะ
    document.querySelectorAll('.user-active-switch').forEach(sw => {
        sw.addEventListener('change', function(){
            // เรียก AJAX ไปอัปเดตสถานะจริงได้ที่นี่
            // fetch('/admin/users/toggle', {method:'POST', body: ...})
            console.log('active?', this.checked);
        });
    });

    // ค้นหา (mock)
    document.getElementById('btnSearch')?.addEventListener('click', function(){
        const q = (document.getElementById('searchBox').value || '').toLowerCase();
        document.querySelectorAll('#usersTable tbody tr').forEach(tr => {
            const text = tr.innerText.toLowerCase();
            tr.style.display = text.includes(q) ? '' : 'none';
        });
    });

    // ตัวกรอง role (mock)
    document.getElementById('roleFilter')?.addEventListener('change', function(){
        const r = this.value;
        document.querySelectorAll('#usersTable tbody tr').forEach(tr => {
            if(!r){ tr.style.display=''; return; }
            const has = tr.querySelector('.role-badge')?.innerText.trim() === r;
            tr.style.display = has ? '' : 'none';
        });
    });
</script>
@stop
