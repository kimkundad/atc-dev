 <!-- Footer Area -->

    <footer class="footer-area">
        <div class="container">
            <div class="footer-up">
                <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 wow fadeInUp animated" data-wow-delay="100ms">
                        <div class="logo">
                            <img src="{{ url('img/loadmaster_logo_v2_w.png') }}" alt="experia-logo" style="width:280px">
                        </div>
                        <div class="contact-info">
                            <p><b>บริษัท โหลดมาสเตอร์ โลจิสติกส์ จำกัด</b></p>
                            <p><b>Location:</b> 305 ซอย พระรามที่ 2 ซ.38 แขวงบางมด เขตจอมทอง กรุงเทพมหานคร 10150</p>
                            <p><b>Phone:</b> +668 0591 8159</p>
                            <p><b>E-mail:</b> loadmasterlogisticsth@gmail.com</p>
                            <p><b>Opening Hour:</b> จันทร์ – อาทิตย์ 08.30 – 20.30</p>
                        </div>

                    </div>
                    <div class="col-lg-5 col-md-6 com-sm-12 wow fadeInUp animated" data-wow-delay="200ms">

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-12">
                                <h6>โหลดมาสเตอร์</h6>
                                <ul>
                                    <li>
                                        <a href="{{ url('/') }}">หน้าแรก</a>
                                        <a href="{{ url('/about') }}">เกี่ยวกับเรา</a>
                                        {{-- <a href="{{ url('/') }}">ร่วมงานกับเรา</a> --}}
                                        <a href="{{ url('/news') }}">ข่าวประชาสัมพันธ์</a>
                                        <a href="{{ url('/track') }}">ติดตามพัสดุ</a>
                                        <a href="{{ url('/contact') }}">ติดต่อเรา</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-lg-6 col-md-6 col-12 wow fadeInUp animated" data-wow-delay="300ms">
                                <h6>บริการของเรา</h6>
                                <ul>
                                    <li>
                                        <a href="{{ url('/') }}">เคลมสินค้า</a>
                                        <a href="{{ url('/') }}">เรียกรถเข้ารับพัสดุ</a>
                                        <a href="{{ url('/') }}">คำถามที่พบบ่อย</a>
                                        <a href="{{ url('/') }}">ศูนย์การเรียนรู้</a>

                                        @if (Auth::guest())
                            <a href="{{ url('/login') }}">Admin Login</a>
                            @else
                            @if(Auth::user()->roles[0]->name == 'superadmin' || Auth::user()->roles[0]->name == 'admin')
                            <a href="{{ url('/admin/dashboard') }}">Admin Login</a>
                            @endif
                            <a href="{{ url('/admin/myorderDri') }}">เข้าสู่ระบบหลังบ้าน</a>
                            @endif


                                    </li>
                                </ul>
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 wow fadeInUp animated" data-wow-delay="400ms">
                        <div class="subscribe-form">
                            <h6>จดหมายข่าว</h6>
                            <form action="index.html">
                                <input type="email" placeholder="Your email">
                                <button type="submit"><i class="las la-envelope"></i></button>
                            </form>
                            <p>ลงทะเบียนเพื่อรับอีเมลข่าวสารใหม่ๆจากเรา</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Footer Bottom Area  -->

    <div class="footer-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-3 col-lg-4 col-12">
                    <p class="copyright-line">© 2024 LoadMaster. All rights reserved.</p>
                </div>
                <div class="col-xl-6 col-lg-5 col-12">
                    <p class="privacy"><a href="{{ url('/terms_and_conditions') }}">ข้อกำหนดและเงื่อนไขในการขนส่ง</a> <a href="{{ url('/privacy') }}">ประกาศความเป็นส่วนตัว</a>
                    <a href="{{ url('/terms_of_use') }}">ข้อกําหนดการใช้งาน</a> </p>
                </div>
                <div class="col-xl-3 col-lg-3 col-12 text-end">
                    <div class="social-area">
                        <a href=""><i class="lab la-facebook-f"></i></a>
                        <a href=""><i class="lab la-youtube"></i></a>
                        <a href=""><i class="lab la-twitter"></i></a>
                        <a href=""><i class="lab la-instagram"></i></a>
                        <a href=""><i class="lab la-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Top Area -->
    <a href="#top" class="go-top"><i class="las la-angle-up"></i></a>
