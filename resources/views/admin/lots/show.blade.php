@extends('admin.layouts.template')

@section('title')
    <title>บริษัท เอ ที ซี ทราฟฟิค จำกัด</title>
@stop

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6"></div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <div class="row g-5 g-xl-8">

                    <div class="col-12">



            <div class="card mb-10">
                <div class="card-header">
                    <h3 class="card-title fw-bold">รายละเอียดล็อตนัมเบอร์ <Br> Lot No. {{ $lot->lot_no }}</h3><br>
                    <h4 class="ms-4 mt-5"></h4>
                </div>
                <div class="card-body">
                    {{-- รายละเอียดสินค้า --}}
                    <div class="border rounded p-6 mb-10">
                        <h4 class="fw-bold mb-5">รายละเอียดสินค้า</h4>
                        <div class="row g-5">
                            <div class="col-md-6">
                                <label class="form-label">ประเภทสินค้า</label>
                                <input type="text" readonly class="form-control" value="{{ $lot->category->name ?? '-' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">สินค้า</label>
                                <input type="text" readonly class="form-control" value="{{ $lot->product->sku }} - {{ $lot->product->name }}">
                            </div>
                        </div>
                    </div>

                    {{-- รายละเอียดการผลิต --}}
                    <div class="border rounded p-6 mb-10">
                        <h4 class="fw-bold mb-5">รายละเอียดการผลิต</h4>
                        <div class="row g-5">
                            <div class="col-md-4">
                                <label class="form-label">ล็อตนัมเบอร์</label>
                                <input type="text" readonly class="form-control" value="{{ $lot->lot_no }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">วันที่ผลิต</label>
                                <input type="text" readonly class="form-control" value="{{ \Carbon\Carbon::parse($lot->mfg_date)->format('d F Y') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">จำนวนสินค้า</label>
                                <input type="text" readonly class="form-control" value="{{ $lot->qty }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product No. เดิม</label>
                                <input type="text" readonly class="form-control" value="{{ $lot->product_no_old }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Product No. ล่าสุด</label>
                                <input type="text" readonly class="form-control" value="{{ $lot->product_no_new }}">
                            </div>
                        </div>
                    </div>

                    {{-- รายละเอียดการผลิต (เพิ่มเติม) --}}
                    <div class="border rounded p-6 mb-10">
                        <h4 class="fw-bold mb-5">รายละเอียดการผลิต (เพิ่มเติม)</h4>
                        <div class="row g-5">
                            <div class="col-md-4">
                                <label class="form-label">วันรับเข้า</label>
                                <input type="text" readonly class="form-control" value="{{ \Carbon\Carbon::parse($lot->received_date)->format('d F Y') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Supplier</label>
                                <input type="text" readonly class="form-control" value="{{ $lot->supplier }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">เลข Stock กรม</label>
                                <input type="text" readonly class="form-control" value="{{ $lot->stock_no }}">
                            </div>
                        </div>
                    </div>

                    {{-- เอกสารแนบ --}}
                    {{-- เอกสารแนบ --}}
<div class="border rounded p-6">
    <h4 class="fw-bold mb-5">เอกสารแนบทั้งหมด</h4>
    <div class="row g-5">

        @if($lot->galvanize_cert_path)
            <div class="col-md-3 text-center">
                @php
                    $url = Storage::disk('spaces')->url($lot->galvanize_cert_path);
                    $isImage = Str::endsWith(strtolower($lot->galvanize_cert_path), ['jpg', 'jpeg', 'png']);
                @endphp

                @if($isImage)
                    <img src="{{ $url }}" class="img-fluid rounded mb-2" />
                @else
                    <img src="{{ asset('img/file-pdf-icon.png') }}" class="img-fluid rounded mb-2" alt="PDF Preview" />
                @endif

                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-light-primary">โหลดเอกสาร</a>
            </div>
        @endif

        @if($lot->steel_cert_path)
            <div class="col-md-3 text-center">
                @php
                    $url = Storage::disk('spaces')->url($lot->steel_cert_path);
                    $isImage = Str::endsWith(strtolower($lot->steel_cert_path), ['jpg', 'jpeg', 'png']);
                @endphp

                @if($isImage)
                    <img src="{{ $url }}" class="img-fluid rounded mb-2" />
                @else
                    <img src="{{ asset('img/file-pdf-icon.png') }}" class="img-fluid rounded mb-2" alt="PDF Preview" />
                @endif

                <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-light-primary">โหลดเอกสาร</a>
            </div>
        @endif

    </div>
</div>


                    {{-- ลงชื่อโดย --}}
                    <div class="mt-6 text-end text-muted">
                        รายการนี้ถูกสร้างโดย <strong>{{ $lot->creator->name ?? 'ไม่ทราบชื่อ' }}</strong>
                        เมื่อวันที่ {{ \Carbon\Carbon::parse($lot->created_at)->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>


</div>
        </div>
    </div>
</div>
</div>
    </div>
</div>
@endsection
