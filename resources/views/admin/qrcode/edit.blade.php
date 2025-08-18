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


                <!--end::Toolbar container-->
            </div>
            <!--end::Toolbar-->
            <!--begin::Content-->
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxl">

                    <div class="row g-5 g-xl-8">


                    <div class="col-xxl-8">
    <form class="card" method="POST" action="{{ url('admin/qrcode') }}">
        @csrf
        <div class="card-header border-0">
            <h3 class="card-title fw-bold">แก้ไขรายการ QR Code</h3>
        </div>

        <div class="card-body pt-0">

            {{-- รายละเอียด --}}
            <div class="mb-6">
                <label class="form-label fw-bold">รายละเอียด</label>
                <div class="row g-5">
                    <div class="col-md-6">
                        <label class="form-label required">รหัส QR Code</label>
                        <input type="text" class="form-control" id="qr_code" name="qr_code" value="AXRD" />
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">ลิงก์ใช้งาน</label>
                        <input type="text" class="form-control" id="link_url" name="link_url"
                               value="http://qr.atctraffic.co.th/AAAA">
                        <div class="form-text">ตัวอย่าง: http://qr.atctraffic.co.th/AAAA</div>
                        <div class="form-check form-switch form-check-custom form-check-solid mt-3">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked />
                            <label class="form-check-label" for="is_active">เปิดใช้งาน</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="separator my-6"></div>

            {{-- ข้อมูลล็อตนัมเบอร์ --}}
            <div class="mb-6">
                <label class="form-label fw-bold">ข้อมูลล็อตนัมเบอร์</label>
                <div class="row g-5">
                    <div class="col-md-6">
                        <label class="form-label">เลือกประเภทสินค้า</label>
                        <select class="form-select" id="product_type" data-control="select2" data-hide-search="true">
                            <option value="guardrail" selected>ราวกั้นอันตราย</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">เลือกล็อตนัมเบอร์</label>
                        <select class="form-select" id="lot_no" data-control="select2" data-hide-search="true">
                            <option value="250701001" selected>250701001</option>
                            <option value="250701002">250701002</option>
                            <option value="68080021">68080021</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="separator my-6"></div>

            {{-- ข้อมูลสินค้า --}}
            <div>
                <label class="form-label fw-bold">ข้อมูลสินค้า</label>
                <div class="row g-5">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-gray-600">รหัสสินค้า</span>
                            <span id="p_code" class="fw-bold">9603-031-02</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-gray-600">วันที่ผลิต</span>
                            <span id="p_date" class="fw-bold">1-ก.ค.-2568</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-gray-600">รายการ</span>
                            <span id="p_title" class="fw-bold text-end">แผ่นราวกั้นอันตราย 3.2 มม.</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span class="text-gray-600">จำนวน</span>
                            <span id="p_qty" class="fw-bold">300</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-gray-600">เลขรันของสินค้า</span>
                            <a href="javascript:void(0)" id="p_run" class="fw-bold text-primary text-hover-underline">
                                GR3.2-2500001 - GR3.2-250300
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-footer d-flex justify-content-start">
            <button type="submit" class="btn btn-primary">แก้ไขรายการ</button>
        </div>
    </form>
</div>

{{-- พรีวิว QR ทางขวา --}}
<div class="col-xxl-4">
    <div class="card h-100">
        <div class="card-body d-flex flex-column align-items-center justify-content-center">
            <div id="qrContainer" class="mb-5"></div>
            <div class="text-gray-600">สแกนเพื่อตรวจสอบ</div>
        </div>
    </div>
</div>

{{-- ==== Scripts (QR + เปลี่ยนข้อมูลสินค้าเมื่อเลือกล็อต) ==== --}}
@push('scripts')
    {{-- ใช้ qrcodejs จาก CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
            integrity="sha512-Mq3P3bW9i8wqM1uKj1h1n9mL9Ue4C3K0q3k3XcQq7kzq4m3yG/9c0Qd1lH2mQb2KQkP7w4z3lQ4yW1U0E7gR0A=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        // ตัวอย่างข้อมูลสินค้า (mock ตามภาพ)
        const LOT_DATA = {
            "250701001": {
                code: "9603-031-02",
                date: "1-ก.ค.-2568",
                title: "แผ่นราวกั้นอันตราย 3.2 มม.",
                qty: "300",
                run: "GR3.2-2500001 - GR3.2-250300"
            },
            "250701002": {
                code: "9603-031-02",
                date: "2-ก.ค.-2568",
                title: "แผ่นราวกั้นอันตราย 3.2 มม.",
                qty: "250",
                run: "GR3.2-2500301 - GR3.2-2500550"
            },
            "68080021": {
                code: "9603-030-04",
                date: "15-ก.ย.-2565",
                title: "แผ่นราวกั้นอันตราย 2.5 มม.",
                qty: "50",
                run: "GR2.5-68080021 - GR2.5-68080250"
            }
        };

        // พรีวิว QR
        const qrEl = document.getElementById('qrContainer');
        let qr;
        function makeQR() {
            const url = document.getElementById('link_url').value.trim();
            const active = document.getElementById('is_active').checked;
            const text = active ? url : 'INACTIVE:' + url;
            qrEl.innerHTML = '';
            qr = new QRCode(qrEl, {text, width: 240, height: 240});
        }
        makeQR();

        // อัปเดตเมื่อเปลี่ยนค่า
        document.getElementById('link_url').addEventListener('input', makeQR);
        document.getElementById('is_active').addEventListener('change', makeQR);
        document.getElementById('qr_code').addEventListener('input', makeQR);

        // เปลี่ยนข้อมูลสินค้าเมื่อเลือกล็อต
        function renderProduct(lot) {
            const d = LOT_DATA[lot];
            if (!d) return;
            document.getElementById('p_code').textContent  = d.code;
            document.getElementById('p_date').textContent  = d.date;
            document.getElementById('p_title').textContent = d.title;
            document.getElementById('p_qty').textContent   = d.qty;
            document.getElementById('p_run').textContent   = d.run;
        }

        const lotSel = document.getElementById('lot_no');
        lotSel.addEventListener('change', e => renderProduct(e.target.value));
        renderProduct(lotSel.value);
    </script>
@endpush



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

<script type="text/javascript">
    $(document).ready(function(){
      $("input:checkbox").change(function() {
        var user_id = $(this).closest('tr').attr('id');

        $.ajax({
                type:'POST',
                url:'{{url('api/api_post_status_contact')}}',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                data: { "user_id" : user_id },
                success: function(data){
                  if(data.data.success){

                    Swal.fire({
                        text: "ระบบได้ทำการอัพเดทข้อมูลสำเร็จ!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });



                  }
                }
            });
        });
    });
    </script>

@stop('scripts')
