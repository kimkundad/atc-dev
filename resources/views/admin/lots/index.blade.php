@extends('admin.layouts.template')

@section('title')
    <title>ATC</title>
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
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        รายการล็อตนัมเบอร์
                    </h1>
                </div>
                <!--end::Page title-->
                <!--begin::Actions-->
                <div class="d-flex align-items-center gap-2 gap-lg-3">
                    <a href="{{ url('admin/lots/create') }}" class="btn btn-sm btn-light d-flex align-items-center px-4">เพิ่มรายการ</a>
                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <div class="row g-5 g-xl-8">

                    {{-- สรุปวันนี้ / เดือนนี้ / ปีนี้ --}}
                    <div class="col-md-4">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <div class="text-gray-600">วันนี้</div>
                                <div class="fs-2hx fw-bolder text-success lh-1">0000</div>
                                <div class="text-muted fs-7">จำนวนล็อตนัมเบอร์</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <div class="text-gray-600">เดือนนี้</div>
                                <div class="fs-2hx fw-bolder text-success lh-1">0000</div>
                                <div class="text-muted fs-7">จำนวนล็อตนัมเบอร์</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card card-flush h-100">
                            <div class="card-body">
                                <div class="text-gray-600">ปีนี้</div>
                                <div class="fs-2hx fw-bolder text-success lh-1">0000</div>
                                <div class="text-muted fs-7">จำนวนล็อตนัมเบอร์</div>
                            </div>
                        </div>
                    </div>

                    {{-- ตาราง --}}
                    <div class="col-12">
                        <div class="card">
                            <!--begin::Card header-->
                            <div class="card-header border-0 pt-6">
                                <div class="card-title w-100">
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="ค้นหา">
                                        <button class="btn btn-light" type="button" id="button-search">
                                            {{-- ไอคอนค้นหา --}}
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
                            <!--end::Card header-->

                            <!--begin::Card body-->
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            {{-- แถวตัวกรอง --}}
                                            <tr class="bg-light">
                                                <th colspan="9">
                                                    <div class="d-flex align-items-center flex-wrap gap-6">
                                                        <div class="d-flex align-items-center">
                                                            <span class="fw-bold me-3" style="font-size:12px">เลือกดูตาม ประเภทสินค้า</span>
                                                            <select class="form-select form-select-sm w-200px" data-control="select2" data-hide-search="true">
                                                                <option value="all" selected>ทั้งหมด</option>
                                                                <option value="guardrail">ราวกั้นอันตราย</option>
                                                                <option value="pole">เสาไฟฟ้า</option>
                                                            </select>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <span class="fw-bold me-3" style="font-size:12px">เลือกดูตามวันที่ผลิต</span>
                                                            <input type="date" class="form-control form-control-sm w-200px" value="2022-09-14" />
                                                        </div>
                                                    </div>
                                                </th>
                                            </tr>

                                            {{-- หัวตาราง --}}
                                            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                                                <th class="min-w-60px">No.</th>
                                                <th class="min-w-160px">Lot Number</th>
                                                <th class="min-w-170px">วันที่ผลิต</th>
                                                <th class="min-w-320px">หัวข้อ</th>
                                                <th class="min-w-220px">จำนวนสินค้า</th>
                                                <th class="min-w-220px">Supplier</th>
                                                <th class="min-w-140px">ผู้ทำรายการ</th>
                                                <th class="min-w-170px">วันที่บันทึก</th>
                                                <th class="text-end min-w-130px">จัดการ</th>
                                            </tr>
                                        </thead>

                                        <tbody class="fw-semibold text-gray-700">
                                            @php
                                                $rows = [
                                                    [
                                                        'no'=>1,'lot'=>'2507-010-01','mfg'=>'14/09/2565 15:00',
                                                        'title'=>'สีเทอร์โมพลาสติก สีขาว 0%','code'=>'9501-110-01',
                                                        'qty'=>'50 (TH(W)-2507010011 - TH(W)-2507010150)',
                                                        'supplier'=>'-','owner'=>'jaru089','saved'=>'14/09/2565 15:00'
                                                    ],
                                                    [
                                                        'no'=>2,'lot'=>'2507-010-07','mfg'=>'14/09/2565 15:35',
                                                        'title'=>'สีเทอร์โมพลาสติก สีเหลือง 0%','code'=>'9501-210-01',
                                                        'qty'=>'50 (TH(Y)-2507010071 - TH(Y)-2507010750)',
                                                        'supplier'=>'-','owner'=>'jpracha','saved'=>'14/09/2565 15:30'
                                                    ],
                                                    [
                                                        'no'=>3,'lot'=>'2507-020-09','mfg'=>'14/09/2565 16:12',
                                                        'title'=>'สีเทอร์โมพลาสติก สีขาว 10%','code'=>'9501-110-02',
                                                        'qty'=>'50 (TH(W)-2507020091 - TH(W)-2507020950)',
                                                        'supplier'=>'-','owner'=>'jjriya','saved'=>'14/09/2565 17:00'
                                                    ],
                                                    [
                                                        'no'=>4,'lot'=>'2507-020-15','mfg'=>'14/09/2565 16:35',
                                                        'title'=>'สีเทอร์โมพลาสติก สีเหลือง 10%','code'=>'9501-210-02',
                                                        'qty'=>'50 (TH(Y)-2507020151 - TH(Y)-2507021550)',
                                                        'supplier'=>'-','owner'=>'maroot','saved'=>'14/09/2565 18:22'
                                                    ],
                                                    [
                                                        'no'=>5,'lot'=>'6807-001','mfg'=>'14/09/2565 17:10',
                                                        'title'=>'เสาไฟฟ้า 9 เมตร (แบบเทเปอร์)','code'=>'9706-060-01',
                                                        'qty'=>'50 (LP-68070011 - LP-680700150)',
                                                        'supplier'=>'บจก.ที เอ็ม สตีล','owner'=>'kanok','saved'=>'15/09/2565 15:00'
                                                    ],
                                                    [
                                                        'no'=>6,'lot'=>'6807-002','mfg'=>'14/09/2565 17:35',
                                                        'title'=>'เสาไฟฟ้า 7 เมตร (แบบเทเปอร์)','code'=>'9501-110-02',
                                                        'qty'=>'50 (LP-68070021 - LP-680700250)',
                                                        'supplier'=>'บจก.ที เอ็ม สตีล','owner'=>'maroot','saved'=>'15/09/2565 18:15'
                                                    ],
                                                    [
                                                        'no'=>7,'lot'=>'6807-003','mfg'=>'14/09/2565 18:50',
                                                        'title'=>'เสาไฟฟ้า 6 เมตร (แบบสี่เหลี่ยม)','code'=>'9501-110-03',
                                                        'qty'=>'50 (LP-68070031 - LP-680700350)',
                                                        'supplier'=>'บจก.ที เอ็ม สตีล','owner'=>'jjriya','saved'=>'17/09/2565 08:00'
                                                    ],
                                                    [
                                                        'no'=>8,'lot'=>'6807-004','mfg'=>'15/09/2565 08:40',
                                                        'title'=>'เสาไฟฟ้า 6 (แบบท่อกลม)','code'=>'9501-110-04',
                                                        'qty'=>'50 (LP-68070041 - LP-680700450)',
                                                        'supplier'=>'บจก.ที เอ็ม สตีล','owner'=>'maroot','saved'=>'20/09/2565 11:35'
                                                    ],
                                                    [
                                                        'no'=>9,'lot'=>'6807-005','mfg'=>'15/09/2565 11:00',
                                                        'title'=>'เสาไฟฟ้า 6 (แบบเทเปอร์)','code'=>'9501-110-05',
                                                        'qty'=>'50 (LP-68070051 - LP-680700550)',
                                                        'supplier'=>'บจก.ที เอ็ม สตีล','owner'=>'maroot','saved'=>'20/09/2565 15:35'
                                                    ],
                                                    [
                                                        'no'=>10,'lot'=>'6807-006','mfg'=>'15/09/2565 15:00',
                                                        'title'=>'เสาไฟฟ้า 4 เมตร (แบบเทเปอร์)','code'=>'9501-110-06',
                                                        'qty'=>'50 (LP-68070061 - LP-680700650)',
                                                        'supplier'=>'บจก.ที เอ็ม สตีล','owner'=>'jjriya','saved'=>'24/09/2565 21:00'
                                                    ],
                                                    [
                                                        'no'=>11,'lot'=>'6808-001','mfg'=>'15/09/2565 15:50',
                                                        'title'=>'แผ่นราวกั้นอันตราย 3.2 มม.','code'=>'9603-031-02',
                                                        'qty'=>'50 ( GR3.2 - 68080011 - GR3.2 - 680800150)',
                                                        'supplier'=>'บจก.ไทยพรีเซี่ยมไฟพ์','owner'=>'kanok','saved'=>'25/09/2565 10:00'
                                                    ],
                                                    [
                                                        'no'=>12,'lot'=>'6808-002','mfg'=>'15/09/2565 15:50',
                                                        'title'=>'แผ่นราวกั้นอันตราย 2.5 มม.','code'=>'9603-030-04',
                                                        'qty'=>'50 (GR2.5 - 68080021 - GR2.5 - 680800250)',
                                                        'supplier'=>'บจก.ไทยพรีเซี่ยมไฟพ์','owner'=>'kanok','saved'=>'25/09/2565 15:00'
                                                    ],
                                                ];
                                            @endphp

                                            @foreach ($rows as $r)
                                                <tr>
                                                    <td>{{ $r['no'] }}</td>
                                                    <td class="fw-bold">{{ $r['lot'] }}</td>
                                                    <td>{{ $r['mfg'] }}</td>
                                                    <td>
                                                        <div class="d-flex flex-column">
                                                            <span class="fw-bold text-gray-900">{{ $r['title'] }}</span>
                                                            <span class="fs-8 text-gray-500">{{ $r['code'] }}</span>
                                                        </div>
                                                    </td>
                                                    <td>{{ $r['qty'] }}</td>
                                                    <td>{{ $r['supplier'] }}</td>
                                                    <td>{{ $r['owner'] }}</td>
                                                    <td>{{ $r['saved'] }}</td>
                                                    <td class="text-end">
                                                        <div class="btn-group">
                                                            <button type="button" class="btn btn-light btn-sm">จัดการ</button>
                                                            <button type="button" class="btn btn-light btn-sm dropdown-toggle dropdown-toggle-split"
                                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                                <span class="visually-hidden">Toggle Dropdown</span>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a class="dropdown-item" href="#">รายละเอียด</a></li>
                                                                <li><a class="dropdown-item" href="{{ url('/admin/lots/edit/1') }}">แก้ไขรายการ</a></li>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a class="dropdown-item text-danger" href="#">ลบรายการ</a></li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!--begin::Footer (page size + pagination mockup)-->
                                <div class="d-flex flex-wrap justify-content-between align-items-center mt-5">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="text-muted">แสดง</span>
                                        <select class="form-select form-select-sm w-100px">
                                            <option selected>12 รายการ</option>
                                            <option>25 รายการ</option>
                                            <option>50 รายการ</option>
                                        </select>
                                    </div>
                                    <ul class="pagination pagination-outline mb-0">
                                        <li class="page-item disabled"><a class="page-link">ก่อนหน้า</a></li>
                                        <li class="page-item active"><a class="page-link">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item"><a class="page-link" href="#">ถัดไป</a></li>
                                    </ul>
                                </div>
                                <!--end::Footer-->
                            </div>
                            <!--end::Card body-->
                        </div>
                    </div>

                </div>

            </div>
        </div>
        <!--end::Content-->
    </div>
    <!--end::Content wrapper-->

</div>

@endsection

@section('scripts')
<script type="text/javascript"></script>
@stop('scripts')
