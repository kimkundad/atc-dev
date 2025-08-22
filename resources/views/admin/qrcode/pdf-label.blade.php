<!DOCTYPE html>
<html lang="th">
<meta charset="utf-8">
<style>
  @font-face {
      font-family: 'Prompt';
      src: url('{{ storage_path('fonts/Prompt-Regular.ttf') }}') format('truetype');
      font-weight: normal;
  }
  @font-face {
      font-family: 'Prompt';
      src: url('{{ storage_path('fonts/Prompt-Bold.ttf') }}') format('truetype');
      font-weight: bold;
  }

  html, body {
      margin: 0;
      padding: 0;
      font-family: 'Prompt', sans-serif;
      font-size: 12pt;
  }

  table {
      width: 100%;
      height: 100%;
      border-collapse: collapse;
  }

  td {
      vertical-align: top;
      padding: 10pt;
  }

  .left {
      text-align: left;
      padding-left: 10pt;
  }

  .right {
      text-align: right;
      padding-right: 10pt;
  }

  .logo {
      height: 80pt;
      margin-bottom: 0pt;
  }

  .logo_ban {
      height: 46pt;
      margin-top: 4pt;
  }

  .title {
      font-size: 14.5pt;
      font-weight: bold;
margin-top: -5px
  }

  .qr {
      width: 240pt;
      height: auto;
      margin-top: 10px
  }

  .text-bold {
      font-weight: bold;
      font-size: 12.9pt;
      margin-top:-2px
  }

  .line {
      margin-top: -5px;
      font-size: 14.9pt;
      font-weight: bold;
  }
  .lineC{
    margin-top: -10px;
      font-size: 17.6pt;
      font-weight: bold;
  }

</style>
<body>
  <table>
    <tr>
      <td class="left" style="width: 50%; text-align:center">
        @if(!empty($logo))
          <img src="{{ $logo }}" class="logo"><br>
        @endif
        <div class="text-bold">บริษัท เอ ที ซี ทราฟฟิค จำกัด</div>
        <div class="line">วันที่ผลิต {{ $mfg_th }}</div>
        <div class="title">Lot : {{ $lot_no }}</div>
        <div class="lineC">Class 1 &nbsp;&nbsp;&nbsp; Type 1</div>

        <img src="{{ $logo_ban }}" class="logo_ban">
      </td>
      <td class="right" style="width: 50%; ">
        <img src="{{ $qr_path }}" class="qr">
      </td>
    </tr>
  </table>
</body>
</html>
