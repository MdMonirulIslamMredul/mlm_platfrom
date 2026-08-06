@extends('layouts.app')

@section('title', 'About Us - Canada Visa Processing')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endpush

@section('content')
    <div class="container-fluid cover" style="background-image: url('{{ $sliderSettings->universal_slider ? asset($sliderSettings->universal_slider) : asset('static/home/img2.png') }}');margin-top: -250px; height: fit-content;">
    <div class="container pt-4">
        <div class="row g-1 py-md-5">
            <div class="container mb-md-5"  style="margin-top: 250px; z-index:2;">
                <div class="">
                  <div class="text-white text-center">
                    <h1 class="display-3 fw-bold">About Us</h1>
                    <p class="lead">Your trusted partner in Canadian immigration services</p>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>



        <!-- section 3 - About Us Story -->
        <section class="py-5">
            <div class="container my-5">
                <div class="row align-items-center">
                    <!-- column 1 - Image Grid -->
                    <div class="col-12 col-lg-6 col-md-6 mb-4 about-images">
                        <div class="image-grid">
                            <div class="w-50 float-start mt-5">
                                <img src="{{ $aboutSettings->about_image_1 ? asset($aboutSettings->about_image_1) : asset('static/home/img1.png') }}" alt="Immigration Services" class="img-responsive">
                            </div>
                            <div class="w-50 float-start mt-1">
                                <img src="{{ $aboutSettings->about_image_2 ? asset($aboutSettings->about_image_2) : asset('static/home/img3.png') }}" alt="Visa Processing" class="img-responsive">
                            </div>
                            <div class="w-50 float-start">
                                <img src="{{ $aboutSettings->about_image_3 ? asset($aboutSettings->about_image_3) : asset('static/home/img2.png') }}" alt="Canada Immigration" class="img-responsive">
                            </div>
                            <div class="w-50 float-start">
                                <img src="{{ $aboutSettings->about_image_4 ? asset($aboutSettings->about_image_4) : asset('static/home/img4.jpg') }}" alt="Document Processing" class="img-responsive">
                            </div>
                        </div>
                    </div>
                    <!-- column 2 - Content -->
                    <div class="col-12 col-lg-6 col-md-6 mb-4">
                        <span class="dots-decoration">........</span>
                        <h2 class="mb-4 text-danger fw-bold">
                            {{ $aboutSettings->about_title }}
                        </h2>
                        <div class="about-content">
                            <p class="lead">
                                {{ $aboutSettings->about_subtitle }}
                            </p>
                            <p>
                                 {{ $aboutSettings->about_description }}
                            </p>
                            <p>
                                We understand that immigrating to a new country is a life-changing decision, and we're here to make that journey as smooth and successful as possible.
                            </p>
                        </div>
                        <a href="{{ url('/contact') }}" class="btn btn-lg btn-danger py-3 px-4 mt-3">CONTACT US</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission & Vision Section -->
        <section class="mission-vision-section py-5 bg-light">
            <div class="container my-5">
                <div class="row g-4">
                    <!-- Mission Card -->
                    <div class="col-12 col-lg-6">
                        <div class="mission-vision-card h-100 p-5 bg-white rounded shadow-sm">
                            <div class="icon-wrapper mb-4">
                                <div class="icon-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px;">
                                    <i class="bi bi-bullseye text-danger" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                            <h3 class="fw-bold text-danger mb-4">Our Mission</h3>
                            <p class="text-muted lead">
                                {{ $aboutSettings->mission_statement }}
                            </p>
                        </div>
                    </div>

                    <!-- Vision Card -->
                    <div class="col-12 col-lg-6">
                        <div class="mission-vision-card h-100 p-5 bg-white rounded shadow-sm">
                            <div class="icon-wrapper mb-4">
                                <div class="icon-circle bg-danger bg-opacity-10 d-inline-flex align-items-center justify-content-center rounded-circle" style="width: 80px; height: 80px;">
                                    <i class="bi bi-eye text-danger" style="font-size: 2.5rem;"></i>
                                </div>
                            </div>
                            <h3 class="fw-bold text-danger mb-4">Our Vision</h3>
                            <p class="text-muted lead">
                                {{ $aboutSettings->vision_statement }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Choose Us Section -->
        <section class="why-choose-us py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5">
                    <span class="dots-decoration">........</span>
                    <h2 class="fw-bold text-danger mb-3">Why Choose Us</h2>
                    <p class="lead text-muted">We provide comprehensive immigration solutions tailored to your needs</p>
                </div>
                <div class="row g-4">
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-card text-center p-4 h-100">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-shield-check text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="mb-3">Trusted Experts</h4>
                            <p class="text-muted">Years of experience in Canadian immigration services</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-card text-center p-4 h-100">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-clock-history text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="mb-3">Fast Processing</h4>
                            <p class="text-muted">Quick and efficient visa application handling</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-card text-center p-4 h-100">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-headset text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="mb-3">24/7 Support</h4>
                            <p class="text-muted">Always available to answer your questions</p>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-card text-center p-4 h-100">
                            <div class="feature-icon mb-3">
                                <i class="bi bi-trophy text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h4 class="mb-3">High Success Rate</h4>
                            <p class="text-muted">Proven track record of successful applications</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Services Section -->
        <section class="our-services py-5">
            <div class="container my-5">
                <div class="text-center mb-5">
                    <span class="dots-decoration">........</span>
                    <h2 class="fw-bold text-danger mb-3">Our Services</h2>
                    <p class="lead text-muted">Comprehensive immigration solutions for every need</p>
                </div>
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="service-card p-4 shadow-sm rounded h-100">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-briefcase-fill text-danger me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-3">Work Permits</h4>
                                    <p class="text-muted">We help you secure work permits to start your career in Canada with proper documentation and guidance.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="service-card p-4 shadow-sm rounded h-100">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-mortarboard-fill text-danger me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-3">Study Permits</h4>
                                    <p class="text-muted">Complete assistance with student visa applications for those pursuing education in Canada.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="service-card p-4 shadow-sm rounded h-100">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-house-heart-fill text-danger me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-3">Permanent Residency</h4>
                                    <p class="text-muted">Navigate the PR application process with expert guidance and maximize your chances of approval.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="service-card p-4 shadow-sm rounded h-100">
                            <div class="d-flex align-items-start">
                                <i class="bi bi-people-fill text-danger me-3" style="font-size: 2rem;"></i>
                                <div>
                                    <h4 class="mb-3">Family Sponsorship</h4>
                                    <p class="text-muted">Reunite with your loved ones through family sponsorship immigration programs.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



<br> <br>
<!-- section5 : footer -->


@endsection

@push('scripts')
    <script src="{{ asset('js/about.js') }}"></script>
@endpush
