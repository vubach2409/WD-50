@extends('layouts.user')

@section('title', 'Trang Chủ')

@section('content')

    <!-- Start Blog Section -->
    <div class="blog-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-6">
                    <h2 class="section-title">Recent Blog</h2>
                </div>
                <div class="col-md-6 text-start text-md-end">
                    <a href="#" class="more">View All Posts</a>
                </div>
            </div>

            <div class="row">

                <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="post-entry">
                        <a href="#" class="post-thumbnail"><img src="{{ asset('clients/images/post1.jpg') }}"
                                alt="Image" class="img-fluid"></a>
                        <div class="post-content-entry">
                            <h3><a href="#">Kích Thước Tiêu Chuẩn Bàn Làm Việc Thông Minh Cho Gác Xép</a></h3>
                            <div class="meta">
                                <span>by <a href="#">Nguyễn Thị Cúc Hoa</a></span> <span>on <a href="#">Dec 19,
                                        2021</a></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="post-entry">
                        <a href="#" class="post-thumbnail"><img src="{{ asset('clients/images/post2.jpg') }}"
                                alt="Image" class="img-fluid"></a>
                        <div class="post-content-entry">
                            <h3><a href="#">Phụ Kiện Trang Trí Phòng Ngủ Trẻ Em 2 Tầng Siêu Dễ Thương</a></h3>
                            <div class="meta">
                                <span>by <a href="#">Nguyễn Thị Cúc Hoa</a></span> <span>on <a href="#">Dec 15,
                                        2021</a></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 mb-4 mb-md-0">
                    <div class="post-entry">
                        <a href="#" class="post-thumbnail"><img src="{{ asset('clients/images/post3.jpg') }}"
                                alt="Image" class="img-fluid"></a>
                        <div class="post-content-entry">
                            <h3><a href="#">Mua Bếp Hafele Loại Nào Phù Hợp Cho Gia Đình 2 – 4 Người?</a></h3>
                            <div class="meta">
                                <span>by <a href="#">Nguyễn Thị Cúc Hoa</a></span> <span>on <a href="#">Dec 12,
                                        2021</a></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End Blog Section -->



    <!-- Start Testimonial Slider -->
    <div class="testimonial-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-7 mx-auto text-center">
                    <h2 class="section-title">Testimonials</h2>
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
                                                <p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio
                                                    quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate
                                                    velit imperdiet dolor tempor tristique. Pellentesque habitant morbi
                                                    tristique senectus et netus et malesuada fames ac turpis egestas.
                                                    Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
                                            </blockquote>

                                            <div class="author-info">
                                                <div class="author-pic">
                                                    <img src="{{ asset('clients/images/person-1.jpg') }}" alt="Maria Jones"
                                                        class="img-fluid">
                                                </div>
                                                <h3 class="font-weight-bold">Maria Jones</h3>
                                                <span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- END item -->

                            <div class="item">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8 mx-auto">

                                        <div class="testimonial-block text-center">
                                            <blockquote class="mb-5">
                                                <p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio
                                                    quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate
                                                    velit imperdiet dolor tempor tristique. Pellentesque habitant morbi
                                                    tristique senectus et netus et malesuada fames ac turpis egestas.
                                                    Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
                                            </blockquote>

                                            <div class="author-info">
                                                <div class="author-pic">
                                                    <img src="{{ asset('clients/images/person-2.jpg') }}" alt="Maria Jones"
                                                        class="img-fluid">
                                                </div>
                                                <h3 class="font-weight-bold">Maria Jones</h3>
                                                <span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- END item -->

                            <div class="item">
                                <div class="row justify-content-center">
                                    <div class="col-lg-8 mx-auto">

                                        <div class="testimonial-block text-center">
                                            <blockquote class="mb-5">
                                                <p>&ldquo;Donec facilisis quam ut purus rutrum lobortis. Donec vitae odio
                                                    quis nisl dapibus malesuada. Nullam ac aliquet velit. Aliquam vulputate
                                                    velit imperdiet dolor tempor tristique. Pellentesque habitant morbi
                                                    tristique senectus et netus et malesuada fames ac turpis egestas.
                                                    Integer convallis volutpat dui quis scelerisque.&rdquo;</p>
                                            </blockquote>

                                            <div class="author-info">
                                                <div class="author-pic">
                                                    <img src="{{ asset('clients/images/person-3.jpg') }}" alt="Maria Jones"
                                                        class="img-fluid">
                                                </div>
                                                <h3 class="font-weight-bold">Maria Jones</h3>
                                                <span class="position d-block mb-3">CEO, Co-Founder, XYZ Inc.</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <!-- END item -->

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Testimonial Slider -->
@endsection
