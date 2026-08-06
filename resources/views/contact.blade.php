@extends('layouts.app')

@section('title', 'Contact Us - Canada Visa Processing')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush

@section('content')
    <div class="container-fluid cover" style="background-image: url('{{ $sliderSettings->universal_slider ? asset($sliderSettings->universal_slider) : asset('static/home/img2.png') }}');margin-top: -250px; height: fit-content;">
        <div class="container pt-4">
            <div class="row g-1 py-md-5">
                <div class="container mb-md-5"  style="margin-top: 250px; z-index:2;">

                    <div class="">
                      <div class="text-white text-center">
                        <h1 class="display-3 fw-bold">Contact Us</h1>
                        <p class="lead">Get in touch with us for your visa processing needs</p>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



        <!-- section 3 -->
        <section class="contact-info-section py-5">
            <div class="container my-5">
                <div class="row align-items-stretch g-4">
                    <!-- Phone -->
                    <div class="col-12 col-md-4">
                        <div class="contact-info-card text-center p-4 h-100 shadow-sm rounded">
                            <div class="contact-icon mb-3">
                                <i class="bi bi-telephone-fill text-danger fs-1"></i>
                            </div>
                            <h4 class="mb-2">Call Anytime</h4>
                            @if($contactSettings->contact_phone)
                            <a href="tel:{{ preg_replace('/[^0-9]/', '', $contactSettings->contact_phone) }}" class="text-decoration-none text-dark">
                                <h5 class="fw-bold">{{ $contactSettings->contact_phone }}</h5>
                            </a>
                            @endif
                            <p class="text-muted">Available 24/7</p>
                        </div>
                    </div>
                    <!-- Email -->
                    <div class="col-12 col-md-4">
                        <div class="contact-info-card text-center p-4 h-100 shadow-sm rounded">
                            <div class="contact-icon mb-3">
                                <i class="bi bi-envelope-fill text-danger fs-1"></i>
                            </div>
                            <h4 class="mb-2">Email Us</h4>
                            @if($contactSettings->contact_email)
                            <a href="mailto:{{ $contactSettings->contact_email }}" class="text-decoration-none text-dark d-block">
                                <p class="mb-1 fw-bold">{{ $contactSettings->contact_email }}</p>
                            </a>
                            @endif
                            @if($contactSettings->contact_email_secondary)
                            <a href="mailto:{{ $contactSettings->contact_email_secondary }}" class="text-decoration-none text-dark d-block">
                                <p class="mb-0 fw-bold">{{ $contactSettings->contact_email_secondary }}</p>
                            </a>
                            @endif
                        </div>
                    </div>
                    <!-- Location -->
                    <div class="col-12 col-md-4">
                        <div class="contact-info-card text-center p-4 h-100 shadow-sm rounded">
                            <div class="contact-icon mb-3">
                                <i class="bi bi-geo-alt-fill text-danger fs-1"></i>
                            </div>
                            <h4 class="mb-2">Our Location</h4>
                            @if($contactSettings->contact_address)
                            <p class="fw-bold mb-1">{{ $contactSettings->contact_address }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <br>
        <!-- section 4 -->
        <section>
            <div class="container">
                <div class="row align-items-center">
                    <!-- column 1 -->
                    <div class="col-12 col-lg-6 col-md-6 mb-4">
                        <!-- dots -->
                        <span style="color: goldenrod; font-size: 40px;">........</span>
                        <h3 style="color: #0F3186">
                            Have Any Questions? <br>
                            Let’s Start To Talk
                        </h3>
                        <span>
                            Nascetur sed cursus habitasse eleifend montes torquent porta natoque, dis sollicitudin lobortis ad dignissim mauris netus, fermentum
                        </span>
                        <br><br>
                        <!-- round icons -->
                        <div>
                            <!-- icons -->
                            <div class="d-flex gap-3">
                                @if($contactSettings->facebook_url)
                                <a href="{{ $contactSettings->facebook_url }}" class="bg-white text-danger fs-1" target="_blank"> <i class="bi bi-facebook"></i> </a>
                                @endif
                                @if($contactSettings->twitter_url)
                                <a href="{{ $contactSettings->twitter_url }}" class="bg-white text-danger fs-1" target="_blank">  <i class="bi bi-twitter"></i> </a>
                                @endif
                                @if($contactSettings->youtube_url)
                                <a href="{{ $contactSettings->youtube_url }}" class="bg-white text-danger fs-1" target="_blank"><i class="bi bi-youtube"></i> </a>
                                @endif
                                @if($contactSettings->linkedin_url)
                                <a href="{{ $contactSettings->linkedin_url }}" class="bg-white text-danger fs-1" target="_blank"><i class="bi bi-linkedin"></i> </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- column 2 -->
                    <div class="col-12 d-none d-md-inline col-lg-6 col-md-6 mb-4">
                        <!-- text input -->
                        <form action="{{ url('/contact/submit') }}" method="post" id="contactForm" class="needs-validation" novalidate>
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" class="form-control mx-3" name="name" placeholder="Your Name" aria-label="Name" required>
                                <input type="email" class="form-control" name="email" placeholder="Your Email" aria-label="Email" required>
                            </div>
                            <div class="input-group mb-3">
                                <input type="tel" class="form-control mx-3" name="phone" placeholder="Phone Number" aria-label="Phone" required>
                                <input type="text" class="form-control" name="subject" placeholder="Subject" aria-label="Subject" required>
                            </div>
                            <!-- text area -->
                            <div class="input-group px-3">
                                <textarea class="form-control" name="message" placeholder="Write Message" aria-label="Message" rows="5" required></textarea>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-danger btn-lg py-2 mx-3">
                                <span class="btn-text">SEND A MESSAGE</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                    <div class="col-12 d-inline d-md-none mb-4">
                        <!-- text input -->
                        <form action="{{ url('/contact/submit') }}" method="post" id="contactFormMobile" class="needs-validation" novalidate>
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" class="form-control mx-3" name="name" placeholder="Your Name" aria-label="Name" required>
                            </div>
                            <div class="input-group mb-3">
                                <input type="email" class="form-control mx-3" name="email" placeholder="Your Email" aria-label="Email" required>
                            </div>
                            <div class="input-group mb-3">
                                <input type="tel" class="form-control mx-3" name="phone" placeholder="Phone Number" aria-label="Phone" required>
                            </div>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control mx-3" name="subject" placeholder="Subject" aria-label="Subject" required>
                            </div>
                            <!-- text area -->
                            <div class="input-group px-3">
                                <textarea class="form-control" name="message" placeholder="Write Message" aria-label="Message" rows="5" required></textarea>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-danger btn-lg py-2 mx-3">
                                <span class="btn-text">SEND A MESSAGE</span>
                                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>




    <br><br>
@endsection

@push('scripts')
    <script src="{{ asset('js/contact.js') }}"></script>
@endpush
