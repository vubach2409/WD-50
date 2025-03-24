@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')

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

    <!-- Bắt đầu phần Câu Chuyện Thương Hiệu -->
    <div class="brand-story-section untree_co-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <h2 class="section-title">Câu Chuyện Thương Hiệu</h2>
                    <p>Xuất phát từ niềm đam mê với đồ gỗ và mong muốn tạo ra không gian sống tinh tế, chúng tôi đã bắt đầu hành trình từ một xưởng gỗ nhỏ.</p>
                    <p>Trải qua hơn một thập kỷ phát triển, thương hiệu của chúng tôi đã vươn xa và trở thành lựa chọn hàng đầu cho hàng ngàn gia đình.</p>
                    <p>Chúng tôi tin rằng mỗi sản phẩm nội thất không chỉ là một món đồ trang trí mà còn là một phần của câu chuyện cuộc sống, lưu giữ những kỷ niệm và giá trị bền vững.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc phần Câu Chuyện Thương Hiệu -->

    <!-- Bắt đầu phần Đội Ngũ -->
    <div class="untree_co-section">
        <div class="container">

            <div class="row mb-5">
                <div class="col-lg-5 mx-auto text-center">
                    <h2 class="section-title">Đội Ngũ Của Chúng Tôi</h2>
                </div>
            </div>

            <div class="row">

                @php
                    $teamMembers = [
                        ['name' => '1', 'position' => 'Giám đốc Điều Hành', 'image' => 'person_1.jpg'],
                        ['name' => '2', 'position' => 'Trưởng phòng Kinh Doanh', 'image' => 'person_2.jpg'],
                        ['name' => '3', 'position' => 'Giám đốc Sáng Tạo', 'image' => 'person_3.jpg'],
                        ['name' => '4', 'position' => 'Giám đốc Marketing', 'image' => 'person_4.jpg'],
                        ['name' => '5', 'position' => 'Trưởng phòng Công Nghệ', 'image' => 'person_5.jpg'],
                        ['name' => '6', 'position' => 'Chuyên viên Dịch Vụ Khách Hàng', 'image' => 'person_6.jpg'],
                        ['name' => '7', 'position' => 'Nhà Thiết Kế Sản Phẩm', 'image' => 'person_7.jpg'],
                    ];
                @endphp

                <div class="row team-container"> 
                    @foreach ($teamMembers as $member)
                        <div class="col-12 col-md-6 col-lg-3 mb-5">
                            <img src="{{ asset('clients/images/' . $member['image']) }}" alt="AVATAR" class="img-fluid mb-4">
                            <h3><a href="#"><span>{{ $member['name'] }}</span></a></h3>
                            <span class="d-block position mb-3">{{ $member['position'] }}</span>
                            <p>Là một phần quan trọng của đội ngũ, {{ $member['name'] }} đóng vai trò thiết yếu trong sự phát triển của công ty.</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc phần Đội Ngũ -->

    <!-- Bắt đầu phần Đánh Giá -->
    <div class="testimonial-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <h2 class="section-title">Khách Hàng Nói Gì?</h2>
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
                            @php
                                $testimonials = [
                                    ['name' => 'Nguyễn Văn A', 'position' => 'Khách hàng thân thiết', 'image' => 'person-1.jpg', 'review' => 'Dịch vụ tốt, sản phẩm chất lượng, tôi rất hài lòng!'],
                                    ['name' => 'Trần Thị B', 'position' => 'Doanh nhân', 'image' => 'person-2.jpg', 'review' => 'Giao hàng nhanh, nhân viên tư vấn nhiệt tình.'],
                                    ['name' => 'Lê Hoàng C', 'position' => 'Chủ cửa hàng nội thất', 'image' => 'person-3.jpg', 'review' => 'Sản phẩm đẹp, phù hợp với không gian nhà tôi.'],
                                ];
                            @endphp

                            @foreach ($testimonials as $testimonial)
                                <div class="item">
                                    <div class="row justify-content-center">
                                        <div class="col-lg-8 mx-auto">
                                            <div class="testimonial-block text-center">
                                                <blockquote class="mb-4">
                                                    <p>&ldquo;{{ $testimonial['review'] }}&rdquo;</p>
                                                </blockquote>

                                                <div class="author-info">
                                                    <div class="author-pic">
                                                        <img src="{{ asset('clients/images/' . $testimonial['image']) }}" alt="{{ $testimonial['name'] }}" class="img-fluid">
                                                    </div>
                                                    <h3 class="font-weight-bold">{{ $testimonial['name'] }}</h3>
                                                    <span class="position d-block mb-3">{{ $testimonial['position'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Kết thúc phần Đánh Giá -->

@endsection
