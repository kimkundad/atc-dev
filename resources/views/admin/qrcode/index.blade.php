@extends('admin.layouts.template')

@section('title')
    <title>บริษัท โหลดมาสเตอร์ โลจิสติกส์ จำกัด</title>
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
                        <!--begin::Title-->
                        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                            รายการ QR Code</h1>
                        <!--end::Title-->
                        {{-- <!--begin::Breadcrumb-->
                        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">
                                <a href="{{ url('dashboard') }}" class="text-muted text-hover-primary">Dashboard</a>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item">
                                <span class="bullet bg-gray-400 w-5px h-2px"></span>
                            </li>
                            <!--end::Item-->
                            <!--begin::Item-->
                            <li class="breadcrumb-item text-muted">ดูสถิติต่างๆ</li>
                            <!--end::Item-->
                        </ul>
                        <!--end::Breadcrumb--> --}}
                    </div>
                    <!--end::Page title-->
                    <!--begin::Actions-->
                    <div class="d-flex align-items-center gap-2 gap-lg-3">

                    </div>
                    <!--end::Actions-->
                </div>
                <!--end::Toolbar container-->
            </div>
            <!--end::Toolbar-->
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxl">

                    <div class="row g-5 g-xl-8">

        <div class="col-12">
    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Title-->
            <div class="card-title">
                <div class="d-flex align-items-center">
                    <span class="fw-bold me-3" style="font-size:12px">เลือกดูตาม ประเภทสินค้า</span>
                    <select class="form-select form-select-sm w-200px me-6" data-control="select2" data-hide-search="true">
                        <option value="all" selected>ทั้งหมด</option>
                        <option value="AXGU">AXGU</option>
                    </select>

                    <span class="fw-bold me-3" style="font-size:12px">เลือกดูตามวันที่ผลิต</span>
                    <input type="date" class="form-control form-control-sm w-200px" value="2022-09-14" />
                </div>
            </div>
            <!--end::Title-->

            <!--begin::Toolbar-->
            <div class="card-toolbar">
                <!-- you can place export buttons here if needed -->
            </div>
            <!--end::Toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Table-->
            <div class="table-responsive">
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                    <thead>
                        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
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

                        @php
                            $rows = [
                                [1,'ไม่เชื่อม','AXGU','14/09/2565 15:00','2507-010-01','14/09/2565 15:00','สีเทอร์โมพลาสติก สีขาว 0%','50 (TH(W)-2507010011 - TH(W)-2507010150)','-','9501-110-01'],
                                [2,'ใช้งาน','AXGU','14/09/2565 15:35','2507-010-07','14/09/2565 15:35','สีเทอร์โมพลาสติก สีเหลือง 0%','50 (TH(Y)-2507010071 - TH(Y)-2507010750)','-','9501-210-01'],
                                [3,'ปิดใช้งาน','AXGU','14/09/2565 16:12','2507-020-09','14/09/2565 16:12','สีเทอร์โมพลาสติก สีขาว 10%','50 (TH(W)-2507020091 - TH(W)-2507020950)','-','9501-110-02'],
                                [4,'ใช้งาน','AXGU','14/09/2565 16:35','2507-020-15','14/09/2565 16:35','สีเทอร์โมพลาสติก สีเหลือง 10%','50 (TH(Y)-2507020151 - TH(Y)-2507021550)','-','9501-210-02'],
                                [5,'ใช้งาน','AXGU','14/09/2565 17:10','6807-001','14/09/2565 17:10','เสาไฟฟ้า 9 เมตร (แบบเทเปอร์)','50 (LP-68070011 - LP-680700150)','บจก.ที เอ็ม สตีล','9706-060-01'],
                                [6,'ใช้งาน','AXGU','14/09/2565 17:35','6807-002','14/09/2565 17:35','เสาไฟฟ้า 7 เมตร (แบบเทเปอร์)','50 (LP-68070021 - LP-680700250)','บจก.ที เอ็ม สตีล','9501-110-02'],
                                [7,'ใช้งาน','AXGU','14/09/2565 18:50','6807-003','14/09/2565 18:50','เสาไฟฟ้า 6 เมตร (แบบสี่เหลี่ยม)','50 (LP-68070031 - LP-680700350)','บจก.ที เอ็ม สตีล','9501-110-03'],
                                [8,'ใช้งาน','AXGU','15/09/2565 08:40','6807-004','15/09/2565 08:40','เสาไฟฟ้า 6 (แบบท่อกลม)','50 (LP-68070041 - LP-680700450)','บจก.ที เอ็ม สตีล','9501-110-04'],
                                [9,'ใช้งาน','AXGU','15/09/2565 11:00','6807-005','15/09/2565 11:00','เสาไฟฟ้า 6 (แบบเทเปอร์)','50 (LP-68070051 - LP-680700550)','บจก.ที เอ็ม สตีล','9501-110-05'],
                                [10,'ใช้งาน','AXGU','15/09/2565 15:00','6807-006','15/09/2565 15:00','เสาไฟฟ้า 4 เมตร (แบบเทเปอร์)','50 (LP-68070061 - LP-680700650)','บจก.ที เอ็ม สตีล','9501-110-06'],
                                [11,'ใช้งาน','AXGU','15/09/2565 15:50','6808-001','15/09/2565 15:50','แผ่นราวกั้นอันตราย 3.2 มม.','50 ( GR3.2 - 68080011 - GR3.2 - 680800150)','บจก.ไทยพรีเซี่ยมไฟพ์','9603-030-02'],
                                [12,'ไม่เชื่อม','AXGU','15/09/2565 15:50','6808-002','15/09/2565 15:50','แผ่นราวกั้นอันตราย 2.5 มม.','50 (GR2.5 - 68080021 - GR2.5- 680800250)','บจก.ไทยพรีเซี่ยมไฟพ์','9603-030-04'],
                            ];
                        @endphp

                        @foreach ($rows as $r)
                            <tr>
                                <td>{{ $r[0] }}</td>
                                <!-- สถานะ -->
                                <td>
                                    @php
                                        $status = $r[1];
                                        $badge = match ($status) {
                                            'ใช้งาน' => 'badge-light-success',
                                            'ปิดใช้งาน' => 'badge-light-warning',
                                            default => 'badge-light-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }} fw-bold">{{ $status }}</span>
                                </td>

                                <!-- รหัส QR Code + subtext -->
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold">{{ $r[2] }}</span>
                                        <a href="#" class="text-primary text-hover-underline fs-8">Website Link</a>
                                    </div>
                                </td>

                                <td>{{ $r[3] }}</td>
                                <td>{{ $r[4] }}</td>
                                <td>{{ $r[5] }}</td>

                                <!-- หัวข้อ + subtext (code) -->
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-bold text-gray-900">{{ $r[6] }}</span>
                                        <span class="fs-8 text-gray-500">{{ $r[9] }}</span>
                                    </div>
                                </td>

                                <!-- จำนวนสินค้า -->
                                <td>{{ $r[7] }}</td>

                                <!-- Supplier -->
                                <td>{{ $r[8] }}</td>

                                <!-- เมนูจัดการ -->
                                <td class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light btn-active-light-primary dropdown-toggle"
                                                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            จัดการ
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ url('admin/qrcode/edit/1') }}">
                                                    แก้ไขรายการ
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    ดาวน์โหลดไฟล์
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#">
                                                    แสดงข้อมูลสินค้า
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" data-kt-menu-dismiss="true">
                                                    ลบรายการ
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
            <!--end::Table-->

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

    <script type="text/javascript"></script>

@stop('scripts')
