<footer class="footer">
    <div class="footer-links-row row align-items-center text-center mx-auto container">
        <!-- Column 1 - Company Info -->
        <div class="col-12 col-md-4 mt-5 text-center text-md-start">
            <a href="{{ url('/') }}">
                <img src="{{ isset($logoSettings) && $logoSettings->footer_logo ? asset($logoSettings->footer_logo) : asset('static/home/logo.jpeg') }}" style="width: 190px;" alt="Company Logo">
            </a>
            <br><br>
            <p>
                We are dedicated to helping individuals achieve their dreams of living and working in Canada through professional immigration services.
            </p>
            <!-- Social Icons -->
            <div class="d-flex gap-3">
                @if($contactSettings->facebook_url)
                <a href="{{ $contactSettings->facebook_url }}" class="text-white fs-1" aria-label="Facebook" target="_blank">
                    <i class="bi bi-facebook"></i>
                </a>
                @endif
                @if($contactSettings->twitter_url)
                <a href="{{ $contactSettings->twitter_url }}" class="text-white fs-1" aria-label="Twitter" target="_blank">
                    <i class="bi bi-twitter"></i>
                </a>
                @endif
                @if($contactSettings->youtube_url)
                <a href="{{ $contactSettings->youtube_url }}" class="text-white fs-1" aria-label="YouTube" target="_blank">
                    <i class="bi bi-youtube"></i>
                </a>
                @endif
                @if($contactSettings->linkedin_url)
                <a href="{{ $contactSettings->linkedin_url }}" class="text-white fs-1" aria-label="LinkedIn" target="_blank">
                    <i class="bi bi-linkedin"></i>
                </a>
                @endif
            </div>
        </div>

        <!-- Column 2 - Contact Info -->
        <div class="col-12 col-md-4 text-center text-md-start">
            <span style="color: goldenrod; font-size: 40px;">........</span>
            <h4>CONTACT US</h4>
            @if($contactSettings->contact_address)
            <p>
                <i class="bi bi-geo-alt pe-2"></i>
                {{ $contactSettings->contact_address }}
            </p>
            @endif
            @if($contactSettings->contact_phone)
            <p>
                <i class="bi bi-telephone pe-2"></i>
                <a href="tel:{{ preg_replace('/[^0-9]/', '', $contactSettings->contact_phone) }}" class="text-white text-decoration-none">{{ $contactSettings->contact_phone }}</a>
            </p>
            @endif
            @if($contactSettings->contact_email)
            <p>
                <i class="bi bi-envelope pe-2"></i>
                <a href="mailto:{{ $contactSettings->contact_email }}" class="text-white text-decoration-none">{{ $contactSettings->contact_email }}</a>
            </p>
            @endif
            @if($contactSettings->contact_email_secondary)
            <p>
                <i class="bi bi-envelope pe-2"></i>
                <a href="mailto:{{ $contactSettings->contact_email_secondary }}" class="text-white text-decoration-none">{{ $contactSettings->contact_email_secondary }}</a>
            </p>
            @endif
            @if($contactSettings->opening_hours)
            <p>Opening Hours: {{ $contactSettings->opening_hours }}</p>
            @endif
        </div>

        <!-- Column 3 - Newsletter -->
        <div class="col-12 col-md-4 text-center text-md-start">
            <span style="color: goldenrod; font-size: 40px; margin-top:-50p">........</span>
            <h4>NEWSLETTER</h4>
            <p>
                Subscribe to get the latest updates on visa processing and immigration news
            </p>
            <!-- Newsletter Form -->
            <form action="{{ url('/newsletter/subscribe') }}" method="post" class="d-flex" id="newsletterForm">
                @csrf
                <input type="email" name="email" class="form-control mx-2 footerInput" placeholder="Email" aria-label="Email" required>
                <button type="submit" class="btn btn-danger btnFour btn-lg py-2">SUBMIT</button>
            </form>
        </div>
    </div>

    <!-- Copyright Section -->
    <div class="text-center py-3 mt-4 border-top border-secondary">
        <p class="mb-0 text-white-50">
            &copy; {{ date('Y') }} Techweb bd it. All rights reserved.
        </p>
    </div>
</footer>
