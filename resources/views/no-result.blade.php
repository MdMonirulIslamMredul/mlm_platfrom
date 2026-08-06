@extends('layouts.app')

@section('title', 'No Result Found - Canada Visa Processing')

@section('content')
<div class="container-fluid" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; padding: 100px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="bg-white rounded shadow-lg p-5 text-center">
                    <div class="mb-4">
                        <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 5rem;"></i>
                    </div>
                    <h2 class="display-5 fw-bold text-danger mb-3">No Result Found</h2>
                    <div class="golden-dots mb-4" style="color: goldenrod; font-size: 30px;">........</div>
                    <p class="lead text-muted mb-4">
                        We couldn't find any visa application with the provided Passport or IRCC Number.
                    </p>
                    <p class="text-muted mb-5">
                        Please check the number you entered and try again. Make sure you've entered the correct Passport Number or IRCC Number.
                    </p>
                    <a href="{{ url('/') }}" class="btn btn-danger btn-lg px-5 py-3">
                        <i class="bi bi-search"></i> Search Again
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-white {
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .golden-dots {
        letter-spacing: 5px;
    }
</style>
@endsection
