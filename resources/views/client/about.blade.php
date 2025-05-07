@extends('layouts.user')

@section('title', 'About Us')

@section('content')
    <!-- Start Why Choose Us Section -->
    <div class="why-choose-section">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col-lg-6">
                    <h2 class="section-title">Why Choose Us</h2>
                    <p>We offer high-quality furniture, designed for comfort and elegance. Our commitment is to provide products that not only meet but exceed your expectations.</p>

                    <div class="row my-5">
                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/truck.svg') }}" alt="Image" class="img-fluid">
                                </div>
                                <h3>Fast &amp; Free Delivery</h3>
                                <p>We ensure that your orders are delivered quickly and at no extra cost, right to your doorstep.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/bag.svg') }}" class="img-fluid">
                                </div>
                                <h3>Easy Shopping Experience</h3>
                                <p>Our website is user-friendly, making it easy for you to browse and purchase furniture that suits your needs.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/support.svg') }}" alt="Image" class="img-fluid">
                                </div>
                                <h3>24/7 Customer Support</h3>
                                <p>We are available around the clock to assist you with any questions or concerns you may have about your orders.</p>
                            </div>
                        </div>

                        <div class="col-6 col-md-6">
                            <div class="feature">
                                <div class="icon">
                                    <img src="{{ asset('clients/images/return.svg') }}" alt="Image" class="img-fluid">
                                </div>
                                <h3>Easy Returns</h3>
                                <p>If you're not satisfied with your purchase, we offer a hassle-free return process to ensure your satisfaction.</p>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="img-wrap">
                        <img src="{{ asset('clients/images/why-choose-us-img.jpg') }}" alt="Image" class="img-fluid">
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End Why Choose Us Section -->


@endsection
