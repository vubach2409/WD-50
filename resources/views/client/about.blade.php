@extends('layouts.user')

@section('title', 'Giới thiệu')

@section('content')
    <!-- Bắt đầu phần Giới Thiệu Công Ty -->
    <div class="about-us-section untree_co-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <h2 class="section-title">Giới Thiệu Về Chúng Tôi</h2>
                    <p>Được thành lập vào năm 2010, chúng tôi tự hào là đơn vị hàng đầu trong lĩnh vực nội thất gỗ cao cấp. Với sứ mệnh mang đến không gian sống tinh tế và bền vững, chúng tôi luôn chú trọng vào chất lượng và dịch vụ.</p>
                    <p>Tầm nhìn của chúng tôi là trở thành thương hiệu nội thất hàng đầu, được khách hàng tin tưởng và lựa chọn.</p>
                    <p>Chúng tôi cam kết:</p>
                    <ul>
                        <li>Chất lượng sản phẩm đạt tiêu chuẩn cao.</li>
                        <li>Dịch vụ chăm sóc khách hàng chuyên nghiệp.</li>
                        <li>Thiết kế sáng tạo, phù hợp với mọi không gian.</li>
                    </ul>
                </div>

                <div class="col-lg-5">
                    <div class="img-wrap">
                        <img src="https://lh6.googleusercontent.com/HJMP8moxgROLlYhCHE_MdCDDzjkvwA0ig_0C1tu1K7n3R6jpf50cXjnkEjrAWI60F9c6pPZb-PJBjOEo_cTXt_P-Id89RBRpbBMojd9Hloqsc94FEftFTCwBDzGVIk3yha8KwAic" alt="Về chúng tôi" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc phần Giới Thiệu Công Ty -->

    <!-- Bắt đầu phần Vì Sao Chọn Chúng Tôi -->
    <div class="why-choose-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <h2 class="section-title">Vì Sao Chọn Chúng Tôi?</h2>
                    <p>Chúng tôi cam kết mang đến sản phẩm chất lượng cao, dịch vụ chuyên nghiệp và trải nghiệm mua sắm tuyệt vời nhất.</p>

                    <div class="row my-5">
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/truck.svg') }}" alt="Giao hàng nhanh" class="img-fluid">
                                </div>
                                <h3>Giao Hàng Nhanh & Miễn Phí</h3>
                                <p>Giao hàng tận nơi nhanh chóng, miễn phí cho đơn hàng đủ điều kiện.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/bag.svg') }}" class="img-fluid">
                                </div>
                                <h3>Mua Sắm Dễ Dàng</h3>
                                <p>Hệ thống đặt hàng đơn giản, tiện lợi với nhiều phương thức thanh toán.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/warranty.svg') }}" width="46px" height="46px" alt="Bảo hành dài hạn" class="img-fluid">
                                </div>
                                <h3>Bảo hành dài hạn</h3>
                                <p>Chính sách bảo hành lên đến 5 năm giúp bạn yên tâm sử dụng.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/return.svg') }}" alt="Đổi trả dễ dàng" class="img-fluid">
                                </div>
                                <h3>Đổi Trả Dễ Dàng</h3>
                                <p>Chính sách đổi trả minh bạch, dễ dàng và nhanh chóng.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="img-wrap">
                        <img src="{{ asset('clients/images/why-choose-us-img.jpg') }}" alt="Hình ảnh" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc phần Vì Sao Chọn Chúng Tôi -->

    <!-- Start We Help Section -->
    <div class="we-help-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-7 mb-5 mb-lg-0">
                    <div class="imgs-grid">
                        <div class="grid grid-1"><img src="{{ asset('clients/images/img-grid-1.jpg') }}" alt="Untree.co">
                        </div>
                        <div class="grid grid-2"><img src="{{ asset('clients/images/img-grid-1.jpg') }}"></div>
                        <div class="grid grid-3"><img src="{{ asset('clients/images/img-grid-1.jpg') }}"></div>
                    </div>
                </div>
                <div class="col-lg-5 ps-lg-5">
                    <h2 class="section-title mb-4">Giúp Bạn Thiết Kế Nội Thất Hiện Đại</h2>
                    <p>Chúng tôi giúp bạn tạo không gian sống tiện nghi và hiện đại.</p>

                    <ul class="list-unstyled custom-list my-4">
                        <li>Chất liệu cao cấp, bền đẹp.</li>
                        <li>Thiết kế hiện đại, tinh tế.</li>
                        <li>Giá cả hợp lý, cạnh tranh.</li>
                        <li>Hỗ trợ khách hàng tận tình.</li>
                    </ul>
                    <p><a href="{{ route('products') }}" class="btn">Khám Phá</a></p>
                </div>
            </div>
        </div>
    </div>
    <!-- End We Help Section -->


@endsection
