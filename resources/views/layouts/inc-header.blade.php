   <!-- Header Top Area -->

    <div class="header-top heaader-top-two dark-bg">
        <div class="container">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-12 col-xs-12">
                    <div class="hire-info">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-12 col-xs-12 text-end">
                    <div class="header_top_right">
                        <div class="contact-info">
                            <i class="las la-clock"></i>
                            <p>จันทร์ – อาทิตย์ 08.30 – 20.30</p>
                        </div>
                        <div class="social-area">
                            <a href=""><i class="lab la-facebook-f"></i></a>
                            <a href=""><i class="lab la-youtube"></i></a>
                            <a href=""><i class="lab la-twitter"></i></a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header Area -->

<div id="header-3" class="header-area">

        <div class="container">
            <div class="header-inner-box">
                <div class="logo">
                    <a class="navbar-Solar" href="{{ url('/') }}"><img src="{{ url('img/loadmaster_logo_v2.png') }}" alt="" style="height: 65px"></a>
                </div>
                <div class="header-right text-end">

                    <div class="contact-info" style="    font-size: 13px;">
                        <i class="las la-map-marker"></i>305 แขวงบางมด เขตจอมทอง กทม 10150
                    </div>

                    <div class="contact-info">
                        <i class="las la-envelope"></i> loadmasterlogisticsth@gmail.com
                    </div>

                    <div class="contact-info">
                        <i class="las la-phone"></i>+668 0591 8159
                    </div>
                </div>
            </div>
        </div>
</div>

<div class="navigation">
    <div class="sticky-area">
        <div class="auto-container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="main-menu">
                        <nav class="navbar navbar-expand-lg">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                                <span class="navbar-toggler-icon"></span>
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                                <ul class="navbar-nav m-auto">
                                    <li class="nav-item">
                                        <a class="nav-link " href="{{ url('/') }}">หน้าแรก</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/about') }}">เกี่ยวกับเรา</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/news') }}">ข่าวประชาสัมพันธ์</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/') }}">ศูนย์การเรียนรู้</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ url('/contact') }}">ติดต่อเรา</a>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-5 d-flex justify-content-end pr-50">
                    <div class="icon-wrapper">
                        <div class="search-icon search-trigger"><i class="las la-search"></i></div>

                    </div>
                    <div class="header-btn">
                        <a href="{{ url('/track') }}" class="main-btn primary">ติดตามพัสดุ</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Search Dropdown Area -->

<div class="search-popup">
    <span class="search-back-drop"></span>

    <div class="search-inner">
        <div class="container">
            <div class="upper-text">
                <div class="text">Search for anything.</div>
                <button class="close-search"><span class="la la-times"></span></button>
            </div>

            <form method="post" action="index.html">
                <div class="form-group">
                    <input type="search" name="search-field" value="" placeholder="Search..." required="">
                    <button type="submit"><i class="la la-search"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
