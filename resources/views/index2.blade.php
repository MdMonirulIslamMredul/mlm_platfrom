@extends('layouts.app')

@section('title', 'Home - Canada Visa Processing')

@section('content')
    <!-- Hero Section -->
    <div class="container-fluid cover" style="background-image: url('{{ $sliderSettings->universal_slider ? asset($sliderSettings->universal_slider) : asset('static/home/img2.png') }}');margin-top: -250px; height: fit-content;">
        <div class="container pt-4">
            <div class="row g-1 py-md-5">
                <div class="container mb-md-5"  style="margin-top: 250px; z-index:2;">


                    <div class="row">
                      <div class="col-12 col-md-6 text-white text-center text-md-start">
                        <h1 class="display-4 fw-bold">Fulfill Your Dream</h1>
                        <p class="lead">We help people to apply visa in Canada. We handle all the hassle of submitting documents.</p>
                        <a href="{{ url('/about') }}" class="btn btn-danger btn-lg border-4 px-4">ABOUT US</a>
                      </div>
                      <div class="col-12 col-md-6 pb-4" id="visa-check">
                        <div class="bg-white p-4 rounded mt-3 shadow">
                            <h2 class="text-center mb-3">Check Your Visa Status</h2>

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <div class="container-fluid py-1">
                                <form action="{{ url('/search') }}" method="POST" class="d-flex" role="search" id="visaSearchForm">
                                    @csrf
                                  <input class="form-control me-2" name="search_number" type="text" placeholder="Enter Passport or IRCC Number" aria-label="Search" required>
                                  <button class="btn btn-success" type="submit">Search</button>
                                </form>
                            </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



        <!-- section 3 -->
        <section style="background-size: cover; background-image: url('{{ isset($logoSettings) && $logoSettings->header_logo ? asset($logoSettings->header_logo) : asset('static/home/logo.jpg') }}'); height: fit-content;">
        <div class="container-fluid" style="background-color:black;  opacity:0.9;">

            <div class="container my-5 text-danger">

                <div class="row align-items-center">
                    <!-- column 1 -->
                    <div class="col-12 col-lg-6 col-md-6 mb-4 about-images" style="z-index:2;">
                        <!-- 4 images -->
                        <div class="">
                            <!-- 1 -->
                            <div class="w-50 float-start mt-5">
                                <img src="{{ $aboutSettings->about_image_1 ? asset($aboutSettings->about_image_1) : asset('static/home/img1.png') }}" alt="Immigration Services" class="img-responsive">
                            </div>
                            <!-- 2 -->
                            <div class="w-50 float-start mt-1">
                                <img src="{{ $aboutSettings->about_image_2 ? asset($aboutSettings->about_image_2) : asset('static/home/img3.png') }}" alt="Visa Processing" class="img-responsive">
                            </div>
                            <!-- 3 -->
                            <div class="w-50 float-start">
                                <img src="{{ $aboutSettings->about_image_3 ? asset($aboutSettings->about_image_3) : asset('static/home/img2.png') }}" alt="Canada Immigration" class="img-responsive">
                            </div>
                            <!-- 4 -->
                            <div class="w-50 float-start">
                                <img src="{{ $aboutSettings->about_image_4 ? asset($aboutSettings->about_image_4) : asset('static/home/img4.jpg') }}" alt="Document Processing" class="img-responsive">
                            </div>

                        </div>
                    </div>
                    <!-- column 2 -->
                    <div class="col-12 col-lg-6 col-md-6 mb-4">
                        <!-- dots -->
                        <span class="texr-white" style="color: goldenrod; font-size: 40px;">........</span>
                        <h3 style="">
                                 {{ $aboutSettings->about_title }}
                        </h3>
                        <div>
                            {{ $aboutSettings->about_description }}
                        </div>
                        <br>
                        <!-- <button type="button" class="btn2 btnStyle"> MORE ABOUT&gt;</button> -->
                        <a href="{{ url('/about') }}" class="btn btn-lg btn-danger py-2 ">MORE ABOUT</a>
                    </div>
                </div>
            </div>

        </div>
        </section>

        <!-- section 4 -->
        <section  style="background-size: cover; background-image: url('{{ isset($logoSettings) && $logoSettings->header_logo ? asset($logoSettings->header_logo) : asset('static/home/logo.jpg') }}'); height: fit-content;">
            <div class="container-fluid py-md-4" style="background-color:black;  opacity:0.9;">
            <div class="container">
                <div class="row align-items-center">
                    <!-- column 1 -->
                    <div class="col-12 col-lg-6 col-md-6 mb-4 text-danger">
                        <!-- dots -->
                        <span style="color: goldenrod; font-size: 40px;">........</span>

                        <h3 class="text-danger">
                            Have Any Questions? <br>
                            Let’s Start To Talk
                        </h3>
                        <span>
                            We're here to help you with all your Canadian immigration needs. Reach out to us and let's make your dream come true.
                        </span>
                        <br><br>
                        <!-- round icons -->
                        <div>
                            <!-- icons -->
                            <div class="d-flex gap-3">
                                <a href="#" class="text-danger fs-1" aria-label="Facebook">
                                    <i class="bi bi-facebook"></i>
                                </a>
                                <a href="#" class="text-danger fs-1" aria-label="Twitter">
                                    <i class="bi bi-twitter"></i>
                                </a>
                                <a href="#" class="text-danger fs-1" aria-label="YouTube">
                                    <i class="bi bi-youtube"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- column 2 -->
                    <div class="col-12 col-lg-6 col-md-6 mb-4">
                        <!-- text input -->
                        <form action="{{ url('/contact/submit') }}" method="post" id="contactForm">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <input type="text" class="form-control" name="name" placeholder="Your Name" aria-label="Name" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input type="email" class="form-control" name="email" placeholder="Your Email" aria-label="Email" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-12 col-md-6 mb-3 mb-md-0">
                                    <input type="tel" class="form-control" name="phone" placeholder="Phone Number" aria-label="Phone" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <input type="text" class="form-control" name="subject" placeholder="Subject" aria-label="Subject" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" name="message" placeholder="Write Message" aria-label="Message" rows="4" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger btn-lg py-2">SEND A MESSAGE</button>
                        </form>
                    </div>
                </div>
            </div>

            </div>
        </section>




    <br><br>
@endsection
