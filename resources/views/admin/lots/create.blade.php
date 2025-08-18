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


                <!--end::Toolbar container-->
            </div>
            <!--end::Toolbar-->
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxl">

                    <div class="row g-5 g-xl-8">

                        <div class="col-12">
                            <form class="card" method="POST" action="{{ url('admin/lots') }}"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="card-header border-0">
                                    <h3 class="card-title fw-bold">สร้างล็อตนัมเบอร์
                                    </h3>

                                </div>

                                <div class="card-body">

                                    {{-- รายละเอียดสินค้า --}}
                                    <div class="border rounded p-6 mb-10">
                                        <h4 class="fw-bold mb-5">รายละเอียดสินค้า</h4>
                                        <div class="row g-5">
                                            <div class="col-md-6">
                                                <label class="form-label required">ประเภทสินค้า</label>
                                                <select class="form-select" name="product_type" data-control="select2"
                                                    data-hide-search="true">
                                                    <option value="">เลือกประเภทสินค้า</option>
                                                    <option value="steel" selected>เหล็ก</option>
                                                    <option value="pole">เสาไฟฟ้า</option>
                                                    <option value="guardrail">ราวกั้นอันตราย</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label required">สินค้า</label>
                                                <select class="form-select" name="product_id" data-control="select2">
                                                    <option value="">เลือกสินค้า</option>
                                                    <option value="9706-060-01" selected>9706-060-01 - เสาไฟฟ้า 9 เมตร
                                                        (แบบเทเปอร์)</option>
                                                    <option value="9501-110-06">9501-110-06 - เสาไฟฟ้า 4 เมตร (แบบเทเปอร์)
                                                    </option>
                                                    <option value="9603-031-02">9603-031-02 - แผ่นราวกั้นอันตราย 3.2 มม.
                                                    </option>
                                                    <option value="9603-030-04">9603-030-04 - แผ่นราวกั้นอันตราย 2.5 มม.
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- รายละเอียดการผลิต --}}
                                    <div class="border rounded p-6 mb-10">
                                        <h4 class="fw-bold mb-5">รายละเอียดการผลิต</h4>
                                        <div class="row g-5">
                                            <div class="col-md-4">
                                                <label class="form-label required">ล็อตนัมเบอร์</label>
                                                <input type="text" class="form-control" name="lot_no"
                                                    placeholder="เช่น 250701001" />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">วันที่ผลิต</label>
                                                <div class="input-group">
                                                    <input type="date" class="form-control" name="mfg_date"
                                                        value="2022-09-14" />
                                                    <input type="time" class="form-control" name="mfg_time"
                                                        value="15:00" />
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label required">จำนวนสินค้า</label>
                                                <input type="number" class="form-control" name="qty" value="50"
                                                    min="0" step="1" />
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label">Product No. เดิม</label>
                                                <input type="text" class="form-control" name="product_no_old"
                                                    placeholder="รหัสตามเดิม" />
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Product No. ล่าสุด</label>
                                                <input type="text" class="form-control" name="product_no_new"
                                                    placeholder="รหัสล่าสุด" />
                                            </div>
                                        </div>
                                    </div>

                                    {{-- รายละเอียดการผลิต (เพิ่มเติม) --}}
                                    <div class="border rounded p-6">
                                        <h4 class="fw-bold mb-5">รายละเอียดการผลิต (เพิ่มเติม)</h4>
                                        <div class="row g-5">
                                            <div class="col-md-4">
                                                <label class="form-label">วันรับเข้า</label>
                                                <select class="form-select" name="received_date" data-control="select2"
                                                    data-hide-search="true">
                                                    <option value="">เลือกวันรับเข้า</option>
                                                    <option value="2022-09-14">14/09/2565</option>
                                                    <option value="2022-09-15">15/09/2565</option>
                                                    <option value="2022-09-17">17/09/2565</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Supplier</label>
                                                <input type="text" class="form-control" name="supplier"
                                                    placeholder="ชื่อบริษัท" />
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">เลข Stock หมด</label>
                                                <input type="text" class="form-control" name="stock_no"
                                                    placeholder="รายการสินค้า" />
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label">หมายเหตุเพิ่มเติม</label>
                                                <textarea class="form-control" name="remark" rows="2" placeholder="รายละเอียดเพิ่มเติม"></textarea>
                                            </div>

                                            {{-- แนบไฟล์ --}}
                                            <div class="col-md-3">
                                                <label class="form-label">แนบไฟล์ใบเชอร์ชุบกัลวาไนซ์</label>
                                                <div class="image-input image-input-outline" data-kt-image-input="true">
                                                    <div class="image-input-wrapper w-150px h-150px"></div>
                                                    <label
                                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                        title="อัปโหลด">
                                                        <i class="bi bi-pencil-fill"></i>
                                                        <input type="file" name="factory_doc"
                                                            accept=".png,.jpg,.jpeg,.pdf" />
                                                        <input type="hidden" name="factory_doc_remove" />
                                                    </label>
                                                    <span
                                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                        title="ยกเลิก">
                                                        <i class="bi bi-x"></i>
                                                    </span>
                                                </div>
                                                <div class="form-text">รองรับ .jpg .png .pdf</div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">แนบไฟล์ใบเซอร์เหล็ก</label>
                                                <br>
                                                <div class="image-input image-input-outline" data-kt-image-input="true">
                                                    <div class="image-input-wrapper w-150px h-150px"></div>
                                                    <label
                                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                        data-kt-image-input-action="change" data-bs-toggle="tooltip"
                                                        title="อัปโหลด">
                                                        <i class="bi bi-pencil-fill"></i>
                                                        <input type="file" name="factory_doc"
                                                            accept=".png,.jpg,.jpeg,.pdf" />
                                                        <input type="hidden" name="factory_doc_remove" />
                                                    </label>
                                                    <span
                                                        class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                                                        data-kt-image-input-action="cancel" data-bs-toggle="tooltip"
                                                        title="ยกเลิก">
                                                        <i class="bi bi-x"></i>
                                                    </span>
                                                </div>
                                                <div class="form-text">รองรับ .jpg .png .pdf</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="card-footer d-flex justify-content-start">
                                    <button type="submit" class="btn btn-primary">สร้างรายการล็อตนัมเบอร์</button>
                                    <a href="{{ url('admin/lots') }}" class="btn btn-light ms-3">ยกเลิก</a>
                                </div>
                            </form>
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


@stop('scripts')
