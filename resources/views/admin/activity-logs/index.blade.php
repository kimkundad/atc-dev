@extends('admin.layouts.template')

@section('title')
    <title>ATC | User Activity</title>
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
                    <div class="text-muted mt-1">จำนวนผู้ใช้งานในระบบทั้งหมด <span class="text-success fw-bold">20</span></div>
                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3"></div>
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
                                    <table class="table align-middle table-row-dashed fs-6 gy-4" id="activityTable">
                                        <thead>
                                            {{-- filter row --}}
                                            <tr class="bg-light">
                                                <th colspan="6">
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
                                                <th class="min-w-60px">No.</th>
                                                <th class="min-w-160px">ชื่อ</th>
                                                <th class="min-w-160px">นามสกุล</th>
                                                <th class="min-w-140px">ชื่อบัญชี</th>
                                                <th class="min-w-220px">เมนูที่เข้าใช้งาน</th>
                                                <th class="min-w-170px">เข้าใช้งานล่าสุด</th>
                                            </tr>
                                        </thead>

                                        <tbody class="fw-semibold text-gray-700">
                                            @php
                                                $rows = [
                                                    ['no'=>1,'fname'=>'กมณิษฐ์','lname'=>'สาระชา','username'=>'kanok','role'=>'admin','menu'=>'รายการ QR Code','last'=>'14/09/2565 15:00'],
                                                    ['no'=>2,'fname'=>'จาริยา','lname'=>'นาคุศิริ','username'=>'jjriya','role'=>'admin','menu'=>'สร้าง QR Code','last'=>'15/09/2565 08:24'],
                                                    ['no'=>3,'fname'=>'จารุจันทร์','lname'=>'แสงเงิน','username'=>'jaru089','role'=>'manager','menu'=>'ภาพรวมสินค้าทั้งหมด','last'=>'16/09/2565 07:45'],
                                                    ['no'=>4,'fname'=>'ธิตินันท์','lname'=>'สุภาพ','username'=>'thitii','role'=>'manager','menu'=>'สร้าง QR Code','last'=>'16/09/2565 18:00'],
                                                    ['no'=>5,'fname'=>'ประพนธ์','lname'=>'บุญผะเณชร','username'=>'punyapp','role'=>'manager','menu'=>'ภาพรวมสินค้าทั้งหมด','last'=>'17/09/2565 11:45'],
                                                    ['no'=>6,'fname'=>'ปรัชญา','lname'=>'ใจมุ่ง','username'=>'jpracha','role'=>'manager','menu'=>'รายการ QR Code','last'=>'18/09/2565 08:20'],
                                                    ['no'=>7,'fname'=>'พงศกร','lname'=>'ชนโชติเจริญ','username'=>'pthachot','role'=>'officer','menu'=>'สร้าง QR Code','last'=>'19/09/2565 08:04'],
                                                    ['no'=>8,'fname'=>'พงษพร','lname'=>'รุฬพร','username'=>'pongp','role'=>'officer','menu'=>'รายการ QR Code','last'=>'19/09/2565 18:45'],
                                                    ['no'=>9,'fname'=>'มารุต','lname'=>'รัชนียา','username'=>'maroot','role'=>'officer','menu'=>'สร้าง QR Code','last'=>'19/09/2565 21:00'],
                                                    ['no'=>10,'fname'=>'พงศกร','lname'=>'ชนโชติเจริญ','username'=>'pthachot','role'=>'officer','menu'=>'สรุปรายการหมายเลขล็อต','last'=>'20/09/2565 21:24'],
                                                    ['no'=>11,'fname'=>'พงษพร','lname'=>'รุฬพร','username'=>'pongp','role'=>'officer','menu'=>'รายการล็อตนัมเบอร์','last'=>'20/09/2565 08:35'],
                                                    ['no'=>12,'fname'=>'มารุต','lname'=>'รัชนียา','username'=>'maroot','role'=>'officer','menu'=>'เชื่อมล็อตนัมเบอร์','last'=>'20/09/2565 15:00'],
                                                ];

                                                // สี badge สำหรับเมนู
                                                $menuBadge = [
                                                    'รายการ QR Code'           => 'badge-light-primary',
                                                    'สร้าง QR Code'            => 'badge-light-success',
                                                    'ภาพรวมสินค้าทั้งหมด'       => 'badge-light-info',
                                                    'สรุปรายการหมายเลขล็อต'     => 'badge-light-warning',
                                                    'รายการล็อตนัมเบอร์'        => 'badge-light-secondary',
                                                    'เชื่อมล็อตนัมเบอร์'        => 'badge-light-dark',
                                                ];
                                            @endphp

                                            @foreach ($rows as $r)
                                                <tr data-role="{{ $r['role'] }}">
                                                    <td>{{ $r['no'] }}</td>
                                                    <td class="fw-bold">{{ $r['fname'] }}</td>
                                                    <td>{{ $r['lname'] }}</td>
                                                    <td>{{ $r['username'] }}</td>
                                                    <td>
                                                        <span class="badge {{ $menuBadge[$r['menu']] ?? 'badge-light' }}">
                                                            {{ $r['menu'] }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $r['last'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                {{-- footer: page size + pagination (mock) --}}
                                <div class="d-flex flex-wrap justify-content-between align-items-center mt-5">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="text-muted">แสดง</span>
                                        <select class="form-select form-select-sm w-100px">
                                            <option>10 รายการ</option>
                                            <option selected>12 รายการ</option>
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
    // ค้นหา (mock)
    document.getElementById('btnSearch')?.addEventListener('click', function(){
        const q = (document.getElementById('searchBox').value || '').toLowerCase();
        document.querySelectorAll('#activityTable tbody tr').forEach(tr => {
            const text = tr.innerText.toLowerCase();
            tr.style.display = text.includes(q) ? '' : 'none';
        });
    });

    // ตัวกรอง role (mock)
    document.getElementById('roleFilter')?.addEventListener('change', function(){
        const r = this.value;
        document.querySelectorAll('#activityTable tbody tr').forEach(tr => {
            if(!r){ tr.style.display=''; return; }
            tr.style.display = tr.getAttribute('data-role') === r ? '' : 'none';
        });
    });
</script>
@stop
