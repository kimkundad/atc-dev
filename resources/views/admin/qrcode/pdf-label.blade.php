<!DOCTYPE html>
<html lang="th">
<meta charset="utf-8">
<style>
  @page { margin: 18pt; }
  body { font-family: DejaVu Sans, sans-serif; }
  .wrap{display:flex;gap:16pt;align-items:center}
  .left{width:48%}
  .right{width:52%; text-align:center}
  h1{margin:0 0 6pt 0; font-weight:900}
  .line{font-size:16pt; font-weight:800; margin:6pt 0}
  .big{font-size:24pt; font-weight:900}
  .qr svg{width:100%; height:auto}
</style>
<body>
  <div class="wrap">
    <div class="left">
      @if(!empty($logo))
        <img src="{{ $logo }}" style="height:80pt; margin-bottom:8pt">
      @endif
      <div class="line">บริษัท เอ ที ซี ทราฟฟิค จำกัด</div>
      <div class="line">วันที่ผลิต {{ $mfg_th }}</div>
      <div class="big">Lot : {{ $lot_no }}</div>
      <div class="line">Class 1&nbsp;&nbsp;Type 1</div>
      <div class="line">{{ $tc_mark }}</div>
    </div>
    <div class="right qr"><img src="file://{{ $qr_path }}" width="150"></div>
  </div>
</body>
</html>
