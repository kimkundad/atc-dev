@extends('admin.layouts.template')

@section('title')
    <title>ATC | เพิ่มผู้ใช้งาน</title>
@stop

@section('stylesheet')
<style>
    .section-title{
        border-left: 4px solid #1B5E20; /* เขียวเข้ม */
        padding-left: .75rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }
</style>
@stop

@section('content')
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <div class="d-flex flex-column flex-column-fluid">

        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">เพิ่มผู้ใช้งานในระบบ</h1>
                </div>
            </div>
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">

                <div class="row g-5 g-xl-8">
                    <div class="col-12">
                        <form class="card" method="POST" action="{{ route('users.store') }}">
                            @csrf

                            <div class="card-body p-10">

                                {{-- ข้อมูลทั่วไป --}}
                                <h4 class="section-title">ข้อมูลทั่วไป</h4>
                                <div class="row g-5 mb-10">
                                    <div class="col-md-12">
                                        <label class="form-label required">ชื่อ</label>
                                        <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                               value="{{ old('first_name') }}">
                                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label required">นามสกุล</label>
                                        <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                               value="{{ old('last_name') }}">
                                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">เบอร์ติดต่อ</label>
                                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                               value="{{ old('phone') }}">
                                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label required">อีเมล</label>
                                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                               value="{{ old('email') }}">
                                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                {{-- ตั้งค่าบัญชี --}}
                                <h4 class="section-title">ตั้งค่าบัญชี</h4>
                                <div class="row g-5">
                                    <div class="col-md-12">
                                        <label class="form-label required">ชื่อบัญชี</label>
                                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                               value="{{ old('username') }}" placeholder="เช่น admin008">
                                        @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>



                                    <div class="col-md-12">
                                        <label class="form-label required">รหัสผ่านใหม่</label>
                                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label required">รหัสผ่านใหม่ (อีกครั้ง)</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>

                                    {{-- ...ด้านบนเหมือนเดิม... --}}

                                    <div class="col-md-12">
                                        <label class="form-label required">กำหนดประเภทผู้ใช้งาน</label>
                                        <select class="form-select @error('role_id') is-invalid @enderror"
                                                name="role_id" data-control="select2" data-hide-search="true" required>
                                            <option value="">ระบุประเภทผู้ใช้งาน</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id')==$role->id ? 'selected':'' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                            </div>

                            <div class="card-footer d-flex justify-content-start gap-3">
                                <button type="submit" class="btn btn-primary">สร้างบัญชีผู้ใช้งาน</button>
                                <a href="{{ route('users.index') }}" class="btn btn-light">ยกเลิก</a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
{{-- ถ้าต้องการ: script เพิ่มเติม --}}
@stop
