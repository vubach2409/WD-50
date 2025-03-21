@extends('layouts.user')

<title>@yield('title', 'Dịch vụ')</title>

@section('content')
    <div class="why-choose-section">
        <div class="container">
            <div class="row my-5">
                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/truck.svg') }}" width="46px" height="46px" alt="Giao hàng nhanh" class="img-fluid">
                        </div>
                        <h3>Giao hàng nhanh & miễn phí</h3>
                        <p>Chúng tôi cam kết giao hàng nhanh chóng và hoàn toàn miễn phí trên toàn quốc.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/bag.svg') }}" width="46px" height="46px" alt="Mua sắm dễ dàng" class="img-fluid">
                        </div>
                        <h3>Mua sắm dễ dàng</h3>
                        <p>Trải nghiệm mua sắm tiện lợi với giao diện thân thiện và đơn giản.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/support.svg') }}" width="46px" height="46px" alt="Hỗ trợ 24/7" class="img-fluid">
                        </div>
                        <h3>Hỗ trợ 24/7</h3>
                        <p>Đội ngũ chăm sóc khách hàng luôn sẵn sàng hỗ trợ bạn mọi lúc, mọi nơi.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/return.svg') }}" width="46px" height="46px" alt="Đổi trả dễ dàng" class="img-fluid">
                        </div>
                        <h3>Đổi trả dễ dàng</h3>
                        <p>Chính sách đổi trả linh hoạt giúp bạn an tâm khi mua sắm.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/design.svg') }}" width="46px" height="46px" alt="Thiết kế tinh tế" class="img-fluid">
                        </div>
                        <h3>Thiết kế tinh tế</h3>
                        <p>Các sản phẩm được thiết kế hiện đại, phù hợp với nhiều phong cách nội thất.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/warranty.svg') }}" width="46px" height="46px" alt="Bảo hành dài hạn" class="img-fluid">
                        </div>
                        <h3>Bảo hành dài hạn</h3>
                        <p>Chính sách bảo hành lên đến 5 năm giúp bạn yên tâm sử dụng.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/eco.svg') }}" width="46px" height="46px" alt="Nguyên liệu thân thiện" class="img-fluid">
                        </div>
                        <h3>Nguyên liệu thân thiện</h3>
                        <p>Sử dụng gỗ tự nhiên, an toàn cho sức khỏe và bảo vệ môi trường.</p>
                    </div>
                </div>

                <div class="col-6 col-md-6 col-lg-3 mb-4">
                    <div class="feature">
                        <div class="icon">
                            <img src="{{ asset('clients/images/consult.svg') }}" width="46px" height="46px" alt="Tư vấn miễn phí" class="img-fluid">
                        </div>
                        <h3>Tư vấn miễn phí</h3>
                        <p>Đội ngũ chuyên gia sẵn sàng tư vấn giúp bạn chọn sản phẩm phù hợp nhất.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Bắt đầu phần sản phẩm phổ biến -->
    <div class="popular-product">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="product-item-sm d-flex">
                        <div class="thumbnail">
                            <img src="{{ asset('clients/images/product-1.png') }}" alt="Ghế Bắc Âu" class="img-fluid">
                        </div>
                        <div class="pt-3">
                            <h3>Ghế Bắc Âu</h3>
                            <p>Thiết kế hiện đại, phù hợp với mọi không gian nội thất.</p>
                            <p><a href="#">Xem chi tiết</a></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="product-item-sm d-flex">
                        <div class="thumbnail">
                            <img src="{{ asset('clients/images/product-2.png') }}" alt="Ghế Kruzo Aero" class="img-fluid">
                        </div>
                        <div class="pt-3">
                            <h3>Ghế Kruzo Aero</h3>
                            <p>Chất liệu cao cấp, mang lại sự thoải mái tối đa.</p>
                            <p><a href="#">Xem chi tiết</a></p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="product-item-sm d-flex">
                        <div class="thumbnail">
                            <img src="{{ asset('clients/images/product-3.png') }}" alt="Ghế công thái học" class="img-fluid">
                        </div>
                        <div class="pt-3">
                            <h3>Ghế công thái học</h3>
                            <p>Giúp bảo vệ sức khỏe cột sống khi làm việc lâu dài.</p>
                            <p><a href="#">Xem chi tiết</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc phần sản phẩm phổ biến -->

    <!-- Bắt đầu phần đánh giá khách hàng -->
    <div class="testimonial-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <h2 class="section-title">Khách hàng nói gì về chúng tôi</h2>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="testimonial-slider-wrap text-center">
                        <div id="testimonial-nav">
                            <span class="prev" data-controls="prev"><span class="fa fa-chevron-left"></span></span>
                            <span class="next" data-controls="next"><span class="fa fa-chevron-right"></span></span>
                        </div>

                        <div class="testimonial-slider">
                            <div class="item">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8 mx-auto">
                                        <div class="testimonial-block text-center">
                                            <blockquote class="mb-5">
                                                <p>&ldquo;Dịch vụ tuyệt vời! Sản phẩm chất lượng và giao hàng rất nhanh. Tôi sẽ tiếp tục ủng hộ.&rdquo;</p>
                                            </blockquote>
                                            <div class="author-info">
                                                <div class="author-pic">
                                                    <img src="{{ asset('clients/images/person-1.jpg') }}" alt="Nguyễn Minh" class="img-fluid">
                                                </div>
                                                <h3 class="font-weight-bold">Nguyễn Minh</h3>
                                                <span class="position d-block mb-3">Khách hàng thân thiết</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8 mx-auto">
                                        <div class="testimonial-block text-center">
                                            <blockquote class="mb-5">
                                                <p>&ldquo;Ghế ngồi rất êm, thiết kế đẹp, giá cả hợp lý. Tôi rất hài lòng với trải nghiệm mua hàng.&rdquo;</p>
                                            </blockquote>
                                            <div class="author-info">
                                                <div class="author-pic">
                                                    <img src="{{ asset('clients/images/person-2.jpg') }}" alt="Lê Thảo" class="img-fluid">
                                                </div>
                                                <h3 class="font-weight-bold">Lê Thảo</h3>
                                                <span class="position d-block mb-3">Nhà thiết kế nội thất</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="item">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8 mx-auto">
                                        <div class="testimonial-block text-center">
                                            <blockquote class="mb-5">
                                                <p>&ldquo;Nhân viên hỗ trợ rất nhiệt tình, sản phẩm đúng mô tả, chắc chắn sẽ quay lại mua hàng.&rdquo;</p>
                                            </blockquote>
                                            <div class="author-info">
                                                <div class="author-pic">
                                                    <img src="{{ asset('clients/images/person-3.jpg') }}" alt="Trần Hùng" class="img-fluid">
                                                </div>
                                                <h3 class="font-weight-bold">Trần Hùng</h3>
                                                <span class="position d-block mb-3">Doanh nhân</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Kết thúc phần đánh giá khách hàng -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
