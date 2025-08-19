@extends('admin.layouts.template')

@section('title')
<title>ATC | My Profile</title>
@stop

@section('content')

<style>

  .invalid-feedback{
  display: none;
  width: 100%;
  margin-top: .5rem;
  font-size: .925rem;
  color: #f1416c;
}

/* แสดงข้อความเมื่อมี error */
.is-invalid ~ .invalid-feedback,
.was-validated :invalid ~ .invalid-feedback,
.form-control.is-invalid ~ .invalid-feedback,
.form-select.is-invalid ~ .invalid-feedback,
.form-check-input.is-invalid ~ .invalid-feedback,
textarea.is-invalid ~ .invalid-feedback {
  display: block !important;
}

/* แสดงข้อความเมื่อมี error */
.form-control.is-invalid + .invalid-feedback,
.form-select.is-invalid + .invalid-feedback,
textarea.is-invalid + .invalid-feedback {
  display: block !important;
}
</style>
<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
  <div class="d-flex flex-column flex-column-fluid">
    {{-- Toolbar --}}
    <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
      <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
          <h1 class="page-heading d-flex text-dark fw-bold fs-3 my-0">ข้อมูลบัญชี</h1>
        </div>
      </div>
    </div>

    {{-- Content --}}
    <div id="kt_app_content" class="app-content flex-column-fluid">
      <div id="kt_app_content_container" class="app-container container-xxl">
        <div class="row g-5 g-xl-8">
          <div class="col-12">
            <div class="card">

            {{-- คำนวณแท็บที่ต้อง active --}}
            @php
    // ลำดับความสำคัญ: old('tab') -> session('tab') -> error bag -> default 'general'
    $activeTab = old('tab')
        ?? session('tab')
        ?? ($errors->account->any() ? 'account' : 'general');
@endphp

              {{-- Tabs --}}
              <div class="card-header border-0 pt-6">
                <ul class="nav nav-tabs nav-line-tabs nav-stretch fs-6 border-0">
                <li class="nav-item">
    <a class="nav-link {{ $activeTab==='general' ? 'active' : '' }}" data-bs-toggle="tab" href="#tab_general">ข้อมูลทั่วไป</a>
  </li>
  <li class="nav-item">
    <a class="nav-link {{ $activeTab==='account' ? 'active' : '' }}" data-bs-toggle="tab" href="#tab_account">ตั้งค่าบัญชี</a>
  </li>
                </ul>
              </div>

              <div class="card-body">
                <div class="tab-content" id="profileTabsContent">

                  {{-- TAB 1: ข้อมูลทั่วไป --}}
                  <div class="tab-pane fade {{ $activeTab==='general' ? 'show active' : '' }}" id="tab_general" role="tabpanel">
                    @if(session('success_general'))
                      <div class="alert alert-success mb-6">{{ session('success_general') }}</div>
                    @endif

                    <form method="POST" action="{{ route('profile.update.general') }}" class="w-600px">
                      @csrf
                      @method('PUT')
<input type="hidden" name="tab" value="general">
                      <div class="mb-5">
                        <label class="form-label required">ชื่อ</label>
                        <input type="text" name="fname" class="form-control @error('fname') is-invalid @enderror"
                               value="{{ old('fname', $user->fname) }}">
                        @error('fname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>

                      <div class="mb-5">
                        <label class="form-label required">นามสกุล</label>
                        <input type="text" name="lname" class="form-control @error('lname') is-invalid @enderror"
                               value="{{ old('lname', $user->lname) }}">
                        @error('lname')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>

                      <div class="mb-7">
                        <label class="form-label">ตำแหน่ง</label>
                        <input type="text" name="user_type" class="form-control @error('user_type') is-invalid @enderror"
                               value="{{ old('user_type', $user->user_type) }}" placeholder="เช่น ผู้ตรวจสอบ">
                        @error('user_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                      </div>

                      <button type="submit" class="btn btn-primary">บันทึก</button>
                    </form>
                  </div>

                  {{-- TAB 2: ตั้งค่าบัญชี --}}
                  <div class="tab-pane fade {{ $activeTab==='account' ? 'show active' : '' }}" id="tab_account" role="tabpanel">
                    @if(session('success_account'))
                      <div class="alert alert-success mb-6">{{ session('success_account') }}</div>
                    @endif

                    <form method="POST" action="{{ route('profile.update.account') }}" class="w-600px">
                      @csrf
                      @method('PUT')
<input type="hidden" name="tab" value="account">
                      <div class="mb-5">
                        <label class="form-label required">ชื่อบัญชี</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $user->name) }}">
                        @error('name','account')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                      </div>

                      <div class="mb-5">
                        <label class="form-label">รหัสผ่านปัจจุบัน</label>
                        <input type="password" name="current_password"
                               class="form-control @error('current_password') is-invalid @enderror"
                               placeholder="">
                        @error('current_password','account')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                      </div>

                      <div class="mb-5">
                        <label class="form-label">รหัสผ่านใหม่</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password','account')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                      </div>

                      <div class="mb-7">
                        <label class="form-label">รหัสผ่านใหม่ (อีกครั้ง)</label>
                        <input type="password" name="password_confirmation" class="form-control">
                      </div>

                      <button type="submit" class="btn btn-primary">บันทึก</button>
                    </form>

                    {{-- ข้อมูลเพิ่มเติมใต้ฟอร์ม (ไม่บังคับ) --}}
                    <div class="mt-10 text-muted fs-7">
                      ประเภทบัญชีผู้ใช้งาน: <span class="fw-semibold">{{ $role ?? '-' }}</span>
                    </div>
                  </div>

                </div>
              </div>
            </div> {{-- /card --}}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
