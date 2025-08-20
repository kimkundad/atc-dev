@extends('admin.layouts.template')

@section('title')
  <title>บริษัท โหลดมาสเตอร์ โลจิสติกส์ จำกัด</title>
@stop

@section('stylesheet')
<style>
  .card .card-header{ padding-top:20px; }
  select.w-200px + .select2-container { width: 200px !important; }
</style>
@stop

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">

    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack"></div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">

        {{-- ======= OVERVIEW ======= --}}
        <div class="d-flex justify-content-between align-items-center mb-5">
          <div>
            <h3 class="fw-bold mb-0">ภาพรวมสินค้าทั้งหมด</h3>
            <div class="fw-semibold mt-1">ข้อมูลการผลิตสินค้า
              <span class="ms-1 text-success">ปี {{ $year + 543 }}</span>
            </div>
          </div>
          <a href="#" class="btn btn-light-primary">Export Data</a>
        </div>

        {{-- FILTER BAR (บนสุด) --}}
        <form id="filterForm" class="d-flex gap-3 align-items-center my-5" method="GET" action="{{ route('dashboard.index') }}">
          <div class="fw-bold fs-8">ปีข้อมูล</div>
          <select name="year" class="form-select form-select-sm w-200px" data-control="select2" data-hide-search="true"
                  onchange="this.form.submit()">
            @foreach($years as $y)
              <option value="{{ $y }}" @selected($y==$year)>{{ $y + 543 }}</option>
            @endforeach
          </select>

          <div class="fw-bold fs-8">กลุ่มสินค้า</div>
          <select name="category_id" class="form-select form-select-sm w-200px" data-control="select2" data-hide-search="true"
                  onchange="this.form.submit()">
            <option value="">ทั้งหมด</option>
            @foreach($categories as $c)
              <option value="{{ $c->id }}" @selected($categoryId==$c->id)>{{ $c->name }}</option>
            @endforeach
          </select>
        </form>

        <div class="row g-5">
          {{-- Donut 1 --}}
          <div class="col-xl-6">
            <div class="card h-100">
              <div class="card-header border-0 align-items-start">
                <div>
                  <div class="fs-6 fw-bold">ภาพรวมตามกลุ่มสินค้า</div>
                  <div class="text-muted">ยอดรวม
                    <span class="text-success fw-bold" id="sumCat"></span>
                  </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                  <div class="btn-group btn-group-sm" role="group">
                    <input class="btn-check" type="radio" name="mode1" id="m1_qty" checked>
                    <label class="btn btn-light-success" for="m1_qty">สัดส่วนตามจำนวน</label>
                    <input class="btn-check" type="radio" name="mode1" id="m1_lot">
                    <label class="btn btn-light" for="m1_lot">สัดส่วนตามล็อต</label>
                  </div>
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
                  <div class="fs-6 fw-bold">ภาพรวมตามสินค้า (Top 10)</div>
                  <div class="text-muted">ปี <span class="text-success fw-bold">{{ $year + 543 }}</span></div>
                </div>
                <div class="d-flex align-items-center gap-3">
                  <div class="btn-group btn-group-sm" role="group">
                    <input class="btn-check" type="radio" name="mode2" id="m2_qty" checked>
                    <label class="btn btn-light-success" for="m2_qty">สัดส่วนตามจำนวน</label>
                    <input class="btn-check" type="radio" name="mode2" id="m2_lot">
                    <label class="btn btn-light" for="m2_lot">สัดส่วนตามล็อต</label>
                  </div>
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

          <div class="card mt-4">
            <div class="card-header border-0 d-flex justify-content-between align-items-center">
              <div class="fw-bold">ยอดผลิตสินค้า (ปี {{ $year + 543 }})</div>
              <div class="btn-group btn-group-sm" role="group">
                <input class="btn-check" id="modeBarQty" type="radio" name="modeBar" checked>
                <label class="btn btn-light-success" for="modeBarQty">จำนวน</label>
                <input class="btn-check" id="modeBarLot" type="radio" name="modeBar">
                <label class="btn btn-light" for="modeBarLot">ล็อต</label>
              </div>
            </div>
            <div class="card-body pt-2">
              <div id="chartBar" style="height:380px"></div>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection

@section('scripts')
{{-- ApexCharts (โหลดครั้งเดียวพอ) --}}

<script>
(function(){
  // ------ ข้อมูลจาก PHP ------
  const donutCatQty   = @json($donutCatQty);
  const donutCatLots  = @json($donutCatLots);
  const donutProdQty  = @json($donutProdQty);
  const donutProdLots = @json($donutProdLots);
  const seriesQty     = @json($seriesMonthlyQty);
  const seriesLots    = @json($seriesMonthlyLots);

  const monthsTH = ['มกราคม','กุมภาพันธ์','มีนาคม','เมษายน','พฤษภาคม','มิถุนายน','กรกฎาคม','สิงหาคม','กันยายน','ตุลาคม','พฤศจิกายน','ธันวาคม'];

  // ------ Donut Chart Helper ------
  const makeDonut = (labels, series) => ({
    chart: { type: 'donut', height: 320 },
    labels, series,
    legend: { position: 'bottom' },
    dataLabels: { enabled: true },
    stroke: { width: 1 },
    tooltip: { y: { formatter: (v)=> v.toLocaleString() } }
  });

  // Chart A (กลุ่มสินค้า)
  let chartA = new ApexCharts(document.querySelector("#chartDonutA"),
                makeDonut(donutCatQty.labels, donutCatQty.series));
  chartA.render();

  const sumCatEl = document.getElementById('sumCat');
  const renderSumCat = (useLots=false) => {
    const t = useLots ? donutCatLots.total : donutCatQty.total;
    sumCatEl.textContent = t.toLocaleString();
  }
  renderSumCat(false);

  document.getElementById('m1_qty').addEventListener('change', ()=>{
    chartA.updateOptions(makeDonut(donutCatQty.labels, donutCatQty.series));
    renderSumCat(false);
  });
  document.getElementById('m1_lot').addEventListener('change', ()=>{
    chartA.updateOptions(makeDonut(donutCatLots.labels, donutCatLots.series));
    renderSumCat(true);
  });

  // Chart B (สินค้า Top10)
  let chartB = new ApexCharts(document.querySelector("#chartDonutB"),
                makeDonut(donutProdQty.labels, donutProdQty.series));
  chartB.render();

  document.getElementById('m2_qty').addEventListener('change', ()=>{
    chartB.updateOptions(makeDonut(donutProdQty.labels, donutProdQty.series));
  });
  document.getElementById('m2_lot').addEventListener('change', ()=>{
    chartB.updateOptions(makeDonut(donutProdLots.labels, donutProdLots.series));
  });

  // ------ Bar (Monthly) ------
  const makeBar = (series)=> ({
    chart: { type:'bar', height:380, toolbar:{show:false} },
    series: series,
    xaxis: { categories: monthsTH },
    plotOptions: { bar: { columnWidth:'45%', borderRadius:4 } },
    dataLabels: { enabled:false },
    stroke: { show:true, width:2 },
    legend: { position:'bottom' },
    yaxis: { labels: { formatter: (v)=> v.toLocaleString() } },
    tooltip: { y: { formatter: (v)=> v.toLocaleString() } }
  });

  let chartBar = new ApexCharts(document.querySelector("#chartBar"), makeBar(seriesQty));
  chartBar.render();

  document.getElementById('modeBarQty').addEventListener('change', ()=>{
    chartBar.updateOptions(makeBar(seriesQty));
  });
  document.getElementById('modeBarLot').addEventListener('change', ()=>{
    chartBar.updateOptions(makeBar(seriesLots));
  });
})();
</script>
@stop
