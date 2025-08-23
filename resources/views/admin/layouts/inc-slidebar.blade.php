<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
    data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
    data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo" style="justify-content: center;">
        <!--begin::Logo image-->
        <a target="_blank" href="{{ url('/') }}">


            <img src="{{ url('img/logo.png') }}" alt="Logo" class="h-72px app-sidebar-logo-default" />

            <img src="{{ url('img/logo.png') }}" alt="Logo" class="h-72px app-sidebar-logo-minimize" />


        </a>
        <!--end::Logo image-->
        <!--begin::Sidebar toggle-->
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
            <span class="svg-icon svg-icon-2 rotate-180">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.5"
                        d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z"
                        fill="currentColor" />
                    <path
                        d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z"
                        fill="currentColor" />
                </svg>
            </span>
            <!--end::Svg Icon-->
        </div>
        <!--end::Sidebar toggle-->
    </div>
    <!--end::Logo-->
    <!--begin::sidebar menu-->
    <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
        <!--begin::Menu wrapper-->
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">



                @if (Auth::user()->roles[0]->name == 'SuperAdmin' || Auth::user()->roles[0]->name == 'Supervisor' || Auth::user()->roles[0]->name == 'Admin')
                    {{-- <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="{{ url('admin/dashboard') }}">
                        <span class="menu-icon">
                            <!--begin::Svg Icon | path: icons/duotune/general/gen014.svg-->
                            <span class="svg-icon svg-icon-2">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M11.2929 2.70711C11.6834 2.31658 12.3166 2.31658 12.7071 2.70711L15.2929 5.29289C15.6834 5.68342 15.6834 6.31658 15.2929 6.70711L12.7071 9.29289C12.3166 9.68342 11.6834 9.68342 11.2929 9.29289L8.70711 6.70711C8.31658 6.31658 8.31658 5.68342 8.70711 5.29289L11.2929 2.70711Z"
                                        fill="currentColor"></path>
                                    <path
                                        d="M11.2929 14.7071C11.6834 14.3166 12.3166 14.3166 12.7071 14.7071L15.2929 17.2929C15.6834 17.6834 15.6834 18.3166 15.2929 18.7071L12.7071 21.2929C12.3166 21.6834 11.6834 21.6834 11.2929 21.2929L8.70711 18.7071C8.31658 18.3166 8.31658 17.6834 8.70711 17.2929L11.2929 14.7071Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.3"
                                        d="M5.29289 8.70711C5.68342 8.31658 6.31658 8.31658 6.70711 8.70711L9.29289 11.2929C9.68342 11.6834 9.68342 12.3166 9.29289 12.7071L6.70711 15.2929C6.31658 15.6834 5.68342 15.6834 5.29289 15.2929L2.70711 12.7071C2.31658 12.3166 2.31658 11.6834 2.70711 11.2929L5.29289 8.70711Z"
                                        fill="currentColor"></path>
                                    <path opacity="0.3"
                                        d="M17.2929 8.70711C17.6834 8.31658 18.3166 8.31658 18.7071 8.70711L21.2929 11.2929C21.6834 11.6834 21.6834 12.3166 21.2929 12.7071L18.7071 15.2929C18.3166 15.6834 17.6834 15.6834 17.2929 15.2929L14.7071 12.7071C14.3166 12.3166 14.3166 11.6834 14.7071 11.2929L17.2929 8.70711Z"
                                        fill="currentColor"></path>
                                </svg>
                            </span>
                            <!--end::Svg Icon-->
                        </span>
                        <span class="menu-title">Dashboard</span>
                    </a>
                    <!--end:Menu link-->
                </div> --}}

                    <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('admin/dashboard*') ? 'here show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <!--begin::Svg Icon | path: icons/duotune/finance/fin001.svg-->
                                <span class="svg-icon svg-icon-2">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path d="M13 5.91517C15.8 6.41517 18 8.81519 18 11.8152C18 12.5152 17.9 13.2152 17.6 13.9152L20.1 15.3152C20.6 15.6152 21.4 15.4152 21.6 14.8152C21.9 13.9152 22.1 12.9152 22.1 11.8152C22.1 7.01519 18.8 3.11521 14.3 2.01521C13.7 1.91521 13.1 2.31521 13.1 3.01521V5.91517H13Z" fill="currentColor"></path>
														<path opacity="0.3" d="M19.1 17.0152C19.7 17.3152 19.8 18.1152 19.3 18.5152C17.5 20.5152 14.9 21.7152 12 21.7152C9.1 21.7152 6.50001 20.5152 4.70001 18.5152C4.30001 18.0152 4.39999 17.3152 4.89999 17.0152L7.39999 15.6152C8.49999 16.9152 10.2 17.8152 12 17.8152C13.8 17.8152 15.5 17.0152 16.6 15.6152L19.1 17.0152ZM6.39999 13.9151C6.19999 13.2151 6 12.5152 6 11.8152C6 8.81517 8.2 6.41515 11 5.91515V3.01519C11 2.41519 10.4 1.91519 9.79999 2.01519C5.29999 3.01519 2 7.01517 2 11.8152C2 12.8152 2.2 13.8152 2.5 14.8152C2.7 15.4152 3.4 15.7152 4 15.3152L6.39999 13.9151Z" fill="currentColor"></path>
													</svg>
												</span>
                                <!--end::Svg Icon-->
                            </span>
                            <span class="menu-title">สถิติและรายงาน</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <!--begin:Menu sub-->
                        <div class="menu-sub menu-sub-accordion menu-active-bg">
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="{{ url('admin/dashboard') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">ภาพรวมสินค้าทั้งหมด</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                            <!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link {{ request()->is('admin/dashboard2') ? 'active' : '' }}" href="{{ url('admin/dashboard') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title">สรุปรายการหมายเลขล็อต</span>
                                </a>
                                <!--end:Menu link-->
                            </div>
                            <!--end:Menu item-->
                        </div>
                        <!--end:Menu sub-->
                    </div>


                    {{-- QR Code --}}
<div data-kt-menu-trigger="click"
     class="menu-item menu-accordion {{ request()->is('admin/qrcode*') ? 'here show' : '' }}">
    <span class="menu-link">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                {{-- ไอคอนตัวอย่าง --}}
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="3" width="7" height="7" rx="2" fill="currentColor"/>
                    <rect x="14" y="3" width="7" height="7" rx="2" fill="currentColor" opacity="0.3"/>
                    <rect x="3" y="14" width="7" height="7" rx="2" fill="currentColor" opacity="0.3"/>
                    <rect x="14" y="14" width="3" height="3" rx="1" fill="currentColor"/>
                    <rect x="18" y="18" width="3" height="3" rx="1" fill="currentColor"/>
                </svg>
            </span>
        </span>
        <span class="menu-title">QR Code</span>
        <span class="menu-arrow"></span>
    </span>

    <div class="menu-sub menu-sub-accordion menu-active-bg">
        <div class="menu-item">
            <a class="menu-link {{ request()->is('admin/qrcode') ? 'active' : '' }}"
               href="{{ url('admin/qrcode') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">รายการ QR Code</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->is('admin/qrcode/create') ? 'active' : '' }}"
               href="{{ url('admin/qrcode/create') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">สร้าง QR Code</span>
            </a>
        </div>
    </div>
</div>

{{-- ล็อตนัมเบอร์ --}}
<div data-kt-menu-trigger="click"
     class="menu-item menu-accordion {{ request()->is('admin/lots*') ? 'here show' : '' }}">
    <span class="menu-link">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                {{-- ไอคอนคลิปบอร์ด --}}
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <rect x="6" y="5" width="12" height="16" rx="2" fill="currentColor" opacity="0.3"/>
                    <rect x="8" y="3" width="8" height="4" rx="1" fill="currentColor"/>
                </svg>
            </span>
        </span>
        <span class="menu-title">ล็อตนัมเบอร์</span>
        <span class="menu-arrow"></span>
    </span>

    <div class="menu-sub menu-sub-accordion menu-active-bg">
        <div class="menu-item">
            <a class="menu-link {{ request()->is('admin/lots') ? 'active' : '' }}"
               href="{{ url('admin/lots') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">รายการล็อตนัมเบอร์</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->is('admin/lots/create') ? 'active' : '' }}"
               href="{{ url('admin/lots/create') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">สร้างล็อตนัมเบอร์</span>
            </a>
        </div>
    </div>
</div>




                @endif


                @if (Auth::user()->roles[0]->name == 'SuperAdmin' )


                {{-- ผู้ใช้งานระบบ --}}
<div data-kt-menu-trigger="click"
     class="menu-item menu-accordion {{ request()->is('admin/users*') || request()->is('admin/activity-logs*') ? 'here show' : '' }}">
    <span class="menu-link">
        <span class="menu-icon">
            <span class="svg-icon svg-icon-2">
                {{-- ไอคอนผู้ใช้ --}}
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.3" d="M3 20C3 16.6863 6.58172 14 11 14C15.4183 14 19 16.6863 19 20V21H3V20Z" fill="currentColor"/>
                    <circle cx="11" cy="8" r="4" fill="currentColor"/>
                </svg>
            </span>
        </span>
        <span class="menu-title">ผู้ใช้งานระบบ</span>
        <span class="menu-arrow"></span>
    </span>

    <div class="menu-sub menu-sub-accordion menu-active-bg">
        <div class="menu-item">
            <a class="menu-link {{ request()->is('admin/users') ? 'active' : '' }}"
               href="{{ url('admin/users') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">ผู้ใช้งานในระบบ</span>
            </a>
        </div>
        <div class="menu-item">
            <a class="menu-link {{ request()->is('admin/activity-logs') ? 'active' : '' }}"
               href="{{ url('admin/activity-logs') }}">
                <span class="menu-bullet"><span class="bullet bullet-dot"></span></span>
                <span class="menu-title">ประวัติการเข้าใช้งาน</span>
            </a>
        </div>
    </div>
</div>

                @endif






            </div>
            <!--end::Menu-->


            {{-- begin::Sidebar footer - Profile --}}
@php
    $u = Auth::user();
    $fullName = trim(($u->first_name ?? '').' '.($u->last_name ?? '')) ?: ($u->name ?? $u->username ?? 'ผู้ใช้');
    $roleName = optional($u->roles->first())->name ?? 'user';
    $avatarUrl = $u->avatar_url ?? ($u->profile_photo_url ?? null);
@endphp

<div class="app-sidebar-footer flex-column-auto pb-6 px-6" id="kt_app_sidebar_footer">
    <div class="card bg-light-success rounded-3 border-0">
        <a href="{{ route('profile.edit') }}" class="d-flex align-items-center p-4 text-gray-900 text-hover-primary">
            <div class="symbol symbol-45px me-4">
                @if ($avatarUrl)
                    <img src="{{ $avatarUrl }}" alt="avatar" />
                @else
                    <span class="symbol-label bg-success text-white fs-2 fw-bold rounded-2">☺</span>
                @endif
            </div>
            <div class="d-flex flex-column">
                <span class="fw-bold">{{ $fullName }}</span>
                <span class="fs-8 text-gray-600">ประเภทบัญชีผู้ใช้งาน: {{ $roleName }}</span>
            </div>
        </a>
    </div>

    {{-- quick actions under the card --}}
    <div class="d-flex align-items-center gap-4 mt-4 ps-2">
        {{-- Logout --}}
        <a href="#" class="btn btn-icon btn-light btn-active-light-danger"
           data-bs-toggle="tooltip" title="ออกจากระบบ"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <span class="svg-icon svg-icon-2">
                {{-- box-arrow-right --}}
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.3" d="M4 4H12C13.1 4 14 4.9 14 6V9H12V6H4V18H12V15H14V18C14 19.1 13.1 20 12 20H4C2.9 20 2 19.1 2 18V6C2 4.9 2.9 4 4 4Z" fill="currentColor"/>
                    <path d="M15 12L20 7V10H24V14H20V17L15 12Z" transform="translate(-2 0)" fill="currentColor"/>
                </svg>
            </span>
        </a>

        {{-- Change/Lock password (ไปหน้าเปลี่ยนรหัสผ่าน) --}}
        <a href="{{ url('admin/profile/security') }}" class="btn btn-icon btn-light btn-active-light-primary"
           data-bs-toggle="tooltip" title="เปลี่ยนรหัสผ่าน">
            <span class="svg-icon svg-icon-2">
                {{-- unlock --}}
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.3" d="M6 10H18C19.1 10 20 10.9 20 12V20C20 21.1 19.1 22 18 22H6C4.9 22 4 21.1 4 20V12C4 10.9 4.9 10 6 10Z" fill="currentColor"/>
                    <path d="M12 2C9.8 2 8 3.8 8 6H10C10 4.9 10.9 4 12 4C13.1 4 14 4.9 14 6V10H16V6C16 3.8 14.2 2 12 2Z" fill="currentColor"/>
                </svg>
            </span>
        </a>
    </div>

    {{-- logout form --}}
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
</div>
{{-- end::Sidebar footer - Profile --}}


        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->

</div>



