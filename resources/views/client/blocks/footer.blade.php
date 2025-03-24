<footer class="footer-section bg-light pt-5">
    <div class="container relative">

        <!-- Hình ảnh trang trí -->
        <div class="sofa-img text-center mb-4">
            <img src="{{ asset('clients/images/sofa.png') }}" alt="Hình ảnh Sofa" class="img-fluid">
        </div>

        <!-- Form đăng ký nhận tin -->
        <div class="row">
            <div class="col-lg-8">
                <div class="subscription-form">
                    <h3 class="d-flex align-items-center"><span class="me-1"><img
                        src="{{ asset('clients/images/envelope-outline.svg') }}" alt="Image"
                        class="img-fluid"></span><span>Đăng ký nhận tin mới</span>
                    </h3>

                    <form action="#" class="row g-3">
                        <div class="col-auto">
                            <input type="text" class="form-control" placeholder="Nhập tên của bạn">
                        </div>
                        <div class="col-auto">
                            <input type="email" class="form-control" placeholder="Nhập email của bạn">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary">
                                <span class="fa fa-paper-plane"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Thông tin footer -->
        <div class="row g-5 mt-5">
            <div class="col-lg-4">
                <div class="mb-4 footer-logo-wrap">
                    <a href="#" class="footer-logo">Poly<span>.</span></a>
                </div>
                <p class="mb-4">Chúng tôi cam kết mang đến những sản phẩm nội thất chất lượng cao, an toàn và phù hợp với mọi không gian sống.</p>

                <ul class="list-unstyled custom-social d-flex">
                    <li class="me-3"><a href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                    <li class="me-3"><a href="#"><i class="fa-brands fa-twitter"></i></a></li>
                    <li class="me-3"><a href="#"><i class="fa-brands fa-instagram"></i></a></li>
                    <li><a href="#"><i class="fa-brands fa-linkedin"></i></a></li>
                </ul>                
            </div>

            <div class="col-lg-8">
                <div class="row links-wrap">
                    <div class="col-6 col-sm-6 col-md-3">
                        <ul class="list-unstyled">
                            <li><a href="#">Về chúng tôi</a></li>
                            <li><a href="#">Dịch vụ</a></li>
                            <li><a href="#">Blog</a></li>
                            <li><a href="#">Liên hệ</a></li>
                        </ul>
                    </div>

                    <div class="col-6 col-sm-6 col-md-3">
                        <ul class="list-unstyled">
                            <li><a href="#">Hỗ trợ</a></li>
                            <li><a href="#">Trung tâm trợ giúp</a></li>
                            <li><a href="#">Chat trực tiếp</a></li>
                        </ul>
                    </div>

                    <div class="col-6 col-sm-6 col-md-3">
                        <ul class="list-unstyled">
                            <li><a href="#">Tuyển dụng</a></li>
                            <li><a href="#">Đội ngũ của chúng tôi</a></li>
                            <li><a href="#">Chính sách bảo mật</a></li>
                        </ul>
                    </div>

                    <div class="col-6 col-sm-6 col-md-3">
                        <ul class="list-unstyled">
                            <li><a href="#">Ghế Nordic</a></li>
                            <li><a href="#">Kruzo Aero</a></li>
                            <li><a href="#">Ghế Ergonomic</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bản quyền -->
        <div class="border-top copyright mt-4 pt-3">
            <div class="row">
                <div class="col-lg-6 text-center text-lg-start">
                    <p class="mb-2">&copy;
                        <script>document.write(new Date().getFullYear());</script> Poly. Mọi quyền được bảo lưu. 
                        Được thiết kế với tình yêu bởi <a href="https://untree.co">Untree.co</a>, phân phối bởi 
                        <a href="https://themewagon.com">ThemeWagon</a>.
                    </p>
                </div>

                <div class="col-lg-6 text-center text-lg-end">
                    <ul class="list-unstyled d-inline-flex">
                        <li class="me-4"><a href="#">Điều khoản &amp; Điều kiện</a></li>
                        <li><a href="#">Chính sách bảo mật</a></li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</footer>
