@extends('admin.layouts.template')

@section('title')
    <title>บริษัท โหลดมาสเตอร์ โลจิสติกส์ จำกัด</title>
@stop
@section('stylesheet')


<style>

    .card .card-header{
        padding-top:20px;
    }
    select.w-200px + .select2-container { width: 200px !important; }
</style>

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

{{-- ======= OVERVIEW ======= --}}
<div class="d-flex justify-content-between align-items-center mb-5">
  <div>
    <h3 class="fw-bold mb-0">ภาพรวมสินค้าทั้งหมด</h3>
    <div class=" fw-semibold mt-1">ข้อมูลการผลิตสินค้า <span class="ms-1 text-success">เดือนมกราคม 2565</span></div>
  </div>
  <a href="#" class="btn btn-light-primary">Export Data</a>
</div>

{{-- FILTER BAR (บนสุด) --}}
<div class="d-flex gap-3 align-items-center my-5">
    <div class="fw-bold" style="font-size:12px;">ช่วงเวลา</div>
    <select class="form-select form-select-sm w-200px" data-control="select2" data-hide-search="true">
      <option selected>เลือกช่วงเวลา</option><option>ปี 2566</option><option>ปี 2567</option>
    </select>
    <button class="btn btn-success btn-sm">กรองข้อมูล</button>
  </div>

<div class="row g-5">
  {{-- Donut 1 --}}
  <div class="col-xl-6">
    <div class="card h-100">
      <div class="card-header border-0 align-items-start">
        <div>
          <div class="fs-6 fw-bold">ภาพรวมยอดการขายตามกลุ่มสินค้า</div>
          <div class="text-muted">ยอดผลิตรวม <span class="text-success fw-bold">15,700</span></div>
        </div>
        <div class="d-flex align-items-center gap-3">
          <div class="btn-group btn-group-sm" role="group" aria-label="mode1">
            <input class="btn-check" type="radio" name="mode1" id="m1a" checked>
            <label class="btn btn-light-success" for="m1a">สัดส่วนตามจำนวน</label>
            <input class="btn-check" type="radio" name="mode1" id="m1b">
            <label class="btn btn-light" for="m1b">สัดส่วนตามยอด</label>
          </div>
          <select class="form-select form-select-sm w-150px">
            <option selected>เลือก กลุ่มสินค้า</option>
            <option>AXGU</option>
          </select>
        </div>
      </div>
      <div class="card-body pt-0">
        <div id="chartDonutA" style="height:320px"></div>
      </div>
    </div>
  </div>

  {{-- Donut 2 --}}
  <div class="col-xl-6">
    <div class="card h-100">
      <div class="card-header border-0 align-items-start">
        <div>
          <div class="fs-6 fw-bold">ภาพรวมยอดการขายตามสินค้า</div>
          <div class="text-muted">ยอดผลิตรวม <span class="text-success fw-bold">15,700</span></div>
        </div>
        <div class="d-flex align-items-center gap-3">
          <div class="btn-group btn-group-sm" role="group" aria-label="mode2">
            <input class="btn-check" type="radio" name="mode2" id="m2a" checked>
            <label class="btn btn-light-success" for="m2a">สัดส่วนตามจำนวน</label>
            <input class="btn-check" type="radio" name="mode2" id="m2b">
            <label class="btn btn-light" for="m2b">สัดส่วนตามยอด</label>
          </div>
          <select class="form-select form-select-sm w-150px">
            <option selected>เลือกสินค้า</option>
            <option>เสาไฟ</option>
          </select>
        </div>
      </div>
      <div class="card-body pt-0">
        <div id="chartDonutB" style="height:320px"></div>
      </div>
    </div>
  </div>
</div>

{{-- ======= COMPARISON BY MONTH ======= --}}
<div class="mt-12">
  <h4 class="fw-bold">รายงานยอดสินค้าเทียบกันในแต่ละเดือน</h4>

  <div class="d-flex gap-3 align-items-center my-5">
    <div class="fw-bold" style="font-size:12px;">ช่วงเวลา</div>
    <select class="form-select form-select-sm w-200px" data-control="select2" data-hide-search="true">
      <option selected>เลือกช่วงเวลา</option><option>ปี 2566</option><option>ปี 2567</option>
    </select>
    <button class="btn btn-success btn-sm">กรองข้อมูล</button>
  </div>

  <div class="text-success fw-semibold mb-4">ข้อมูลการผลิตสินค้า <span class="ms-1">ปี 2568</span></div>

  <div class="card">
    <div class="card-header border-0 d-flex justify-content-between align-items-center">
      <div class="fw-bold">ยอดผลิตสินค้า</div>
      <div class="btn-group btn-group-sm" role="group">
        <input class="btn-check" id="modeBar1" type="radio" name="modeBar" checked>
        <label class="btn btn-light-success" for="modeBar1">จำนวน</label>
        <input class="btn-check" id="modeBar2" type="radio" name="modeBar">
        <label class="btn btn-light" for="modeBar2">ล็อต</label>
      </div>
    </div>
    <div class="card-body pt-2">
      <div id="chartBar" style="height:380px"></div>
    </div>
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

<script>
  // ---------- Donut Chart (A & B) ----------
  const donutOptions = (el) => ({
    chart: { type: 'donut', height: 320 },
    series: [35, 25, 15, 10, 8, 7],
    labels: ['สี', 'เสาไฟ', 'ราวกันอันตราย', 'น็อต/แป้น', 'ฝาครอบ', 'อื่นๆ'],
    legend: { position: 'bottom' },
    dataLabels: { enabled: true },
    stroke: { width: 1 },
    tooltip: { y: { formatter: (v) => v.toLocaleString() } },
  });
  new ApexCharts(document.querySelector("#chartDonutA"), donutOptions('A')).render();
  new ApexCharts(document.querySelector("#chartDonutB"), donutOptions('B')).render();

  // ---------- Column Chart (By Months) ----------
  const monthsTH = ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน',
                    'กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];

  const barOpt = {
    chart: { type: 'bar', height: 380, toolbar: { show: false } },
    series: [
      { name: 'สี', data: [2,3,6,5,2,1,6,9,5,3,1,4] },
      { name: 'เสาไฟ', data: [1,2,3,2,5,1,4,5,6,2,1,3] },
      { name: 'ราวกันอันตราย', data: [3,4,5,3,2,1,5,4,3,6,4,5] },
    ],
    xaxis: { categories: monthsTH },
    plotOptions: { bar: { columnWidth: '45%', borderRadius: 4 } },
    dataLabels: { enabled: false },
    stroke: { show: true, width: 2 },
    legend: { position: 'bottom' },
    yaxis: {
      labels: { formatter: (v)=> `${v}k` }
    },
    tooltip: { y: { formatter: (v)=> `${v.toLocaleString()} k` } }
  };
  new ApexCharts(document.querySelector("#chartBar"), barOpt).render();
</script>

@stop('scripts')
