<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} - Modern Document Management System</title>
    <link rel="icon" type="image/png" href="favicon.ico">
    <!-- SEO Meta Tags -->
    <meta name="description" content="Streamline your workflow with our secure, cloud-based document management system. Organize, collaborate, and access your documents from anywhere.">
    <meta name="keywords" content="document management system, paperless office, cloud document storage, file organization, digital document management, secure document sharing, document workflow, enterprise document solution, document collaboration, document scanning, document archiving, content management, business process automation">
    <meta name="author" content="{{ env('APP_NAME') }}">

    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:title" content="{{ env('APP_NAME') }} - Modern Document Management System">
    <meta property="og:description" content="Streamline your workflow with our secure, cloud-based document management system.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ env('APP_NAME') }} - Modern Document Management System">
    <meta name="twitter:description" content="Streamline your workflow with our secure, cloud-based document management system.">
    <meta name="twitter:image" content="{{ asset('images/twitter-image.jpg') }}">

    <!-- Canonical URL -->
    <meta rel="canonical" href="{{ url()->current() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-BQVE9EPB0Z"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-BQVE9EPB0Z');
    </script>
    <style>
        :root {
            --primary-color: #c6a055;
            --secondary-color: #d0bd96;
            --accent-color: #e74c3c;
            --dark-color: #34495e;
            --light-color: #ecf0f1;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            line-height: 1.6;
        }

        section {
            padding: 5rem 0;
        }

        .navbar {
            transition: all 0.3s ease;
            padding: 1.2rem 0;
            background-color: #fff !important;
            --light-color: #ecf0f1;
        }

        .navbar.scrolled {
            background-color: #fff !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color);
            font-size: 1.7rem;
        }

        .nav-link {
            font-weight: 500;
            color: var(--dark-color);
            padding: 0.6rem 1.2rem !important;
            position: relative;
            transition: all 0.3s ease;
            margin: 0 0.2rem;
        }

        .navbar-nav .nav-link:hover {
            color: var(--primary-color);
        }

        .navbar-nav .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 1.2rem;
            background-color: var(--primary-color);
            transition: width 0.3s ease;
        }

        .navbar-nav .nav-link:hover::after {
            width: calc(100% - 2.4rem);
        }

        .dropdown-menu {
            border-radius: 12px;
            padding: 1.2rem 0;
            margin-top: 1.2rem;
        }

        .dropdown-menu-animated {
            animation: fadeIn 0.3s ease-in-out;
        }

        .dropdown-item {
            padding: 0.6rem 1.8rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(15, 131, 137, 0.1);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar .btn-primary {
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(15, 131, 137, 0.3);
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .navbar .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(15, 131, 137, 0.4);
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 140px 0 100px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('https://picsum.photos/1920/1080');
            background-size: cover;
            opacity: 0.1;
        }

        .min-vh-80 {
            min-height: 85vh;
        }

        .hero-shapes .shape {
            position: absolute;
            z-index: 0;
            border-radius: 50%;
        }

        .hero-shapes .shape-1 {
            width: 350px;
            height: 350px;
            background: rgba(255, 255, 255, 0.03);
            top: -120px;
            right: -120px;
        }

        .hero-shapes .shape-2 {
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, 0.05);
            bottom: -60px;
            left: -60px;
        }

        .hero-shapes .shape-3 {
            width: 170px;
            height: 170px;
            background: rgba(255, 255, 255, 0.03);
            bottom: 60px;
            right: 12%;
        }

        .hero-shapes .shape-4 {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.05);
            top: 35%;
            left: 22%;
        }

        .text-gradient {
            background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        .text-white-90 {
            color: rgba(255, 255, 255, 0.9);
        }

        .text-white-80 {
            color: rgba(255, 255, 255, 0.8);
        }

        .shadow-hover {
            transition: all 0.3s ease;
        }

        .shadow-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2) !important;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.4);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(255, 255, 255, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
            }
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        .hero-image-wrapper {
            position: relative;
            padding: 25px;
        }

        .hero-main-image {
            position: relative;
            z-index: 2;
            transform: perspective(1000px) rotateY(-5deg) rotateX(5deg);
            transition: all 0.5s ease;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
        }

        .hero-main-image:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg);
        }

        .floating-card {
            position: absolute;
            background: white;
            padding: 12px 18px;
            border-radius: 12px;
            z-index: 3;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: float 3s ease-in-out infinite;
        }

        .floating-card-1 {
            top: 10%;
            right: -35px;
            animation-delay: 0.5s;
        }

        .floating-card-2 {
            bottom: 15%;
            left: -35px;
            animation-delay: 1s;
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-12px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .float-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-pattern-dots {
            position: absolute;
            bottom: -35px;
            right: -35px;
            width: 170px;
            height: 170px;
            background-image: radial-gradient(rgba(255, 255, 255, 0.3) 2px, transparent 2px);
            background-size: 16px 16px;
            z-index: 1;
        }

        .users-avatars {
            display: flex;
        }

        .users-avatars img {
            width: 45px;
            height: 45px;
            margin-right: -17px;
        }

        .trusted-logos {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 40px;
            margin-top: 3rem;
        }

        .opacity-70 {
            opacity: 0.7;
            transition: all 0.3s ease;
        }

        .opacity-70:hover {
            opacity: 1;
        }

        .stats-section {
            background-color: var(--primary-color);
            color: white;
            padding: 0 0 100px;
            position: relative;
        }

        .stats-wave-top {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .stats-wave-bottom {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .separator-light {
            width: 90px;
            height: 3px;
            background-color: rgba(255, 255, 255, 0.5);
            margin: 20px 0;
        }

        .stats-row {
            position: relative;
            z-index: 1;
            margin-top: 2rem;
        }

        .stat-card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 18px;
            padding: 35px 25px;
            transition: all 0.3s ease;
            height: 100%;
            margin-bottom: 1.5rem;
        }

        .stat-card:hover {
            transform: translateY(-7px);
            background-color: rgba(255, 255, 255, 0.15);
        }

        .stat-icon {
            font-size: 2.8rem;
            margin-bottom: 20px;
            color: rgba(255, 255, 255, 0.8);
        }

        .counter {
            font-size: 3.2rem;
            font-weight: 700;
            margin-bottom: 0;
            background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        .stat-label {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 18px;
        }

        .stat-progress {
            padding-top: 12px;
        }

        .testimonial-section {
            background-color: var(--light-color);
            padding: 120px 0;
        }

        .testimonial-card {
            background-color: white;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            height: 100%;
            margin-bottom: 1.5rem;
        }

        .testimonial-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
        }

        .quote-icon {
            font-size: 3.5rem;
            color: rgba(15, 131, 137, 0.2);
            position: absolute;
            top: 20px;
            right: 30px;
        }

        .cta-section {
            background: linear-gradient(135deg, var(--secondary-color) 0%, var(--primary-color) 100%);
            color: white;
            padding: 120px 0;
        }

        /* Enhanced Feature Section */
        .features-section {
            padding: 120px 0;
        }

        .feature-icon {
            font-size: 2.5rem;
            width: 80px;
            height: 80px;
            line-height: 80px;
            text-align: center;
            background-color: rgba(15, 131, 137, 0.1);
            color: var(--primary-color);
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-icon:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-5px);
        }

        /* Enhanced Showcase Cards */
        .showcase-section {
            background-color: #f8f9fa;
            padding: 120px 0;
        }

        .showcase-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .showcase-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        /* Enhanced Pricing Cards */
        .pricing-section {
            padding: 120px 0;
        }

        .pricing-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .pricing-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .pricing-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 18px 18px 0 0;
        }

        .pricing-features {
            padding: 30px;
        }

        .pricing-popular {
            position: absolute;
            top: 15px;
            right: 15px;
            background-color: #e74c3c;
            color: white;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 5px 15px;
            border-radius: 20px;
            z-index: 2;
        }

        /* Enhanced FAQ Section */
        .faq-section {
            padding: 120px 0;
        }

        /* Enhanced Footer */
        .footer {
            background-color: #0F8389;
            color: white;
            padding: 80px 0 40px;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .footer-link:hover {
            color: white;
            transform: translateX(5px);
            display: inline-block;
        }

        .social-links a {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                SecrateRoom 
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Solutions
                        </a>
                        <ul class="dropdown-menu dropdown-menu-animated shadow-sm border-0">
                            <li><a class="dropdown-item" href="#"><i
                                        class="bi bi-building me-2 text-primary"></i>Enterprise</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-laptop me-2 text-primary"></i>Small
                                    Business</a></li>
                            <li><a class="dropdown-item" href="#"><i
                                        class="bi bi-people me-2 text-primary"></i>Teams</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-briefcase me-2 text-primary"></i>By
                                    Industry</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#showcase">Showcase</a>
                    </li>
                    <li class="nav-item relative group">
                        <a class="nav-link" href="#referralSection">Referral Program</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pricing">Pricing</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Resources
                        </a>
                        <ul class="dropdown-menu dropdown-menu-animated shadow-sm border-0">
                            <li><a class="dropdown-item" href="#"><i
                                        class="bi bi-book me-2 text-primary"></i>Documentation</a></li>
                            <li><a class="dropdown-item" href="#"><i
                                        class="bi bi-journal-text me-2 text-primary"></i>Blog</a></li>
                            <li><a class="dropdown-item" href="#"><i
                                        class="bi bi-play-circle me-2 text-primary"></i>Video Tutorials</a></li>
                            <li><a class="dropdown-item" href="#"><i
                                        class="bi bi-question-circle me-2 text-primary"></i>Help Center</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('login') }}" class="nav-link  d-lg-block">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 py-2">Get Started</a>
                </div>
            </div>
        </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="hero-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>

        <!-- LIMITED TIME OFFER Banner at Top Right -->
   <!-- LIMITED TIME OFFER Banner at Top Right -->
<div class="hidden md:block limited-time-offer">
    <div class="offer-content">
        <div class="offer-badge">LIMITED TIME OFFER</div>
        <div class="offer-details">
            <span class="offer-title">30 Days FREE DEMO</span>
            <span class="offer-subtitle">₹0 INR • No Credit Card Required</span>
        </div>
    </div>
</div>

        <div class="container position-relative">
            <div class="row align-items-center min-vh-80">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill mb-4 shadow-sm">
                        <i class="bi bi-stars me-1"></i> Trusted by 32+ organizations
                    </span>
             <h1 class="display-3 fw-bold mb-4 text-gradient">Preserve the Moments That Matter Most</h1>
                <p class="lead mb-5 text-white-90 fw-light">
                    Securely store, organize, and relive your favorite photos and videos from anywhere. 
                    Keep your life’s journey safe in one beautiful place.
                </p>

                    <!-- Removed the duplicated offer badge as it's now at the top right of the page -->

                    <div class="d-flex flex-wrap gap-4 mt-5">
                        <a href="#signup" class="btn btn-light btn-lg rounded-pill px-5 py-3 shadow-hover">
                            Start Free Trial
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#demo" class="btn btn-outline-light btn-lg rounded-pill px-5 py-3 pulse-animation">
                            <i class="bi bi-play-circle-fill me-2"></i>Watch Demo
                        </a>
                    </div>
                    <div class="mt-5 d-flex align-items-center">
                        <div class="users-avatars">
                            <img src="https://picsum.photos/45/45?random=1" alt="User"
                                class="rounded-circle border border-2 border-white">
                            <img src="https://picsum.photos/45/45?random=2" alt="User"
                                class="rounded-circle border border-2 border-white">
                            <img src="https://picsum.photos/45/45?random=3" alt="User"
                                class="rounded-circle border border-2 border-white">
                            <img src="https://picsum.photos/45/45?random=4" alt="User"
                                class="rounded-circle border border-2 border-white">
                        </div>
                        <div class="ms-4">
                            <div class="d-flex align-items-center mb-1">
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <i class="bi bi-star-fill text-warning"></i>
                                <span class="ms-2 text-white-90">4.9/5</span>
                            </div>
                            <p class="mb-0 text-white-80">From 2,300+ reviews</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block position-relative" data-aos="fade-up" data-aos-duration="1000"
                    data-aos-delay="200">

                    <div class="hero-image-wrapper">
                        <img src="{{ asset('front/login-page.png') }}" alt="Document Management Dashboard"
                            class="img-fluid rounded-4 shadow-lg hero-main-image">
                        <div class="floating-card floating-card-1 shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="float-icon bg-success text-white">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-0 small text-black">Document uploaded successfully</p>
                                </div>
                            </div>
                        </div>
                        <div class="floating-card floating-card-2 shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="float-icon bg-primary text-white">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-0 small text-black">Advanced encryption enabled</p>
                                </div>
                            </div>
                        </div>

                        <!-- Floating Card for Free Demo -->
                        <div class="floating-card floating-card-3 shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="float-icon bg-warning text-dark">
                                    <i class="bi bi-gift"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-0 small text-black fw-bold">7 Days FREE • ₹0 INR</p>
                                </div>
                            </div>
                        </div>

                        <div class="hero-pattern-dots"></div>
                    </div>
                </div>
            </div>
            <!-- <div class="trusted-logos mt-5 pb-4 d-none d-lg-block">
                <p class="text-white-50 text-center mb-4 small">TRUSTED BY LEADING COMPANIES WORLDWIDE</p>
                <div class="row justify-content-center align-items-center">
                        @php
                        $logos=App\Models\ClientLogos::get();
                        @endphp
                        @foreach($logos as $k=>$v)
                            <div class="col-lg-2 col-md-4 col-6 text-center" data-aos="fade-up" data-aos-delay="100">
                                <img src="{{ $v->logo_url }}" alt="Company Logo"
                                    class="img-fluid opacity-70">
                            </div>
                        @endforeach
                </div>
            </div> -->

        </div>
    </section>

    <!-- Additional CSS for the new elements -->
    <style>
        .free-demo-badge {
            transform: rotate(-2deg);
        }

        .pulse-glow {
            animation: pulse-glow 2s infinite;
        }

        @keyframes pulse-glow {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.7);
            }

            70% {
                box-shadow: 0 0 0 15px rgba(255, 193, 7, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(255, 193, 7, 0);
            }
        }

        .floating-card-3 {
            position: absolute;
            bottom: 20px;
            right: -30px;
            background: white;
            border-radius: 12px;
            padding: 10px 15px;
            z-index: 3;
            animation: float 3s ease-in-out infinite;
        }

        .float-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        /* HOT Badge Styles */
        .hot-offer-ribbon {
            position: absolute;
            top: -25px;
            right: 30px;
            z-index: 10;
            width: 280px;
            height: 60px;
            overflow: hidden;
        }

        .ribbon-content {
            background: linear-gradient(135deg, rgb(171, 96, 93) 0%, #ff9500 100%);
            height: 100%;
            width: 100%;
            display: flex;
            align-items: center;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(255, 59, 48, 0.35);
            position: relative;
            transition: all 0.3s ease;
        }

        .ribbon-content:before {
            content: "";
            position: absolute;
            top: 5px;
            left: 5px;
            right: 5px;
            bottom: 5px;
            border: 1px dashed rgba(255, 255, 255, 0.5);
            border-radius: 5px;
            pointer-events: none;
        }

        .ribbon-icon {
            flex: 0 0 60px;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            position: relative;
        }

        .ribbon-icon:after {
            content: "";
            position: absolute;
            right: 0;
            top: 10px;
            bottom: 10px;
            width: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .ribbon-icon i {
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.3));
            animation: flame-flicker 2s infinite alternate ease-in-out;
        }

        .ribbon-text {
            padding: 0 15px;
            display: flex;
            flex-direction: column;
        }

        .ribbon-title {
            font-weight: 800;
            font-size: 1.2rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
            letter-spacing: 0.5px;
        }

        .ribbon-subtitle {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
        }

        .ribbon-shine {
            position: absolute;
            top: 0;
            left: -150px;
            width: 50px;
            height: 100%;
            background: rgba(255, 255, 255, 0.3);
            transform: skewX(-25deg);
            animation: shine 3s infinite;
        }

        @keyframes flame-flicker {

            0%,
            100% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(0.95);
                opacity: 0.9;
            }
        }

        @keyframes shine {
            0% {
                left: -150px;
            }

            20% {
                left: 400px;
            }

            100% {
                left: 400px;
            }
        }
/* Limited Time Offer Banner Styles - Base styles */
.limited-time-offer {
    position: absolute;
    z-index: 1000;
    max-width: 380px;
    display: none; /* Hidden by default */
}

/* Media query for mobile phones (Portrait and Landscape) */
@media only screen and (max-width: 767px) {
    .limited-time-offer {
        display: none; /* Explicitly hidden on mobile */
    }
}

/* Media query for iPads (Portrait and Landscape) */
@media only screen and (min-width: 768px) and (max-width: 1024px) {
    .limited-time-offer {
        display: block; /* Visible on iPads */
        top: 100px;
        right: 30px;
        max-width: 320px; /* Slightly smaller for iPad */
    }
}

/* Media query for laptops and larger screens */
@media only screen and (min-width: 1025px) {
    .limited-time-offer {
        display: block; /* Visible on larger screens */
        top: 132px;
        right: 380px;
        max-width: 380px;
    }
}

        .offer-content {
            background: linear-gradient(135deg, #878787, #ff4b2b);
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(255, 65, 108, 0.3);
            overflow: hidden;
            position: relative;
            display: flex;
            flex-direction: column;
            animation: float-gentle 3s ease-in-out infinite;
        }

        .offer-badge {
            background: rgba(0, 0, 0, 0.2);
            color: white;
            font-size: 0.9rem;
            font-weight: 800;
            text-align: center;
            padding: 8px 0;
            position: relative;
            letter-spacing: 1px;
        }

        .offer-badge:after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 12px solid transparent;
            border-right: 12px solid transparent;
            border-top: 8px solid rgba(0, 0, 0, 0.2);
        }

        .offer-details {
            padding: 15px 20px;
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .offer-details:before {
            content: "";
            position: absolute;
            top: 8px;
            left: 8px;
            right: 8px;
            bottom: 8px;
            border: 2px dashed rgba(255, 255, 255, 0.5);
            border-radius: 5px;
            z-index: -1;
        }

        .offer-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 900;
            display: block;
            margin-bottom: 5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .offer-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            font-weight: 600;
            display: block;
        }

        @keyframes float-gentle {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }
        }
    </style>


    <!-- Particle Background Canvas for the Section -->
    <canvas id="particles-canvas" class="particles-js"></canvas>

    <!-- Advanced 3D Feature Cards Section -->
    <section class="feature-cards-section py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold">Everything Your <span class="text-primary">Memories</span> Need</h2>
                <p class="lead text-muted col-lg-8 mx-auto">Discover the powerful tools that make {{ env('APP_NAME') }} the safest home for your personal photos and videos.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-3d-card" data-tilt data-tilt-max="10" data-tilt-scale="1.05">
                        <div class="feature-card-content">
                            <div class="feature-card-icon">
                                <i class="bi bi-images"></i> </div>
                            <h4 class="feature-card-title">Smart Organization</h4>
                            <p class="feature-card-text">Automatically sort your photos and videos by date, location, or custom tags. Keep your gallery clutter-free and find exactly what you're looking for in seconds.</p>
                            <a href="#" class="feature-card-link">
                                <span>Explore Gallery</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="feature-card-bg"></div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-3d-card" data-tilt data-tilt-max="10" data-tilt-scale="1.05">
                        <div class="feature-card-content">
                            <div class="feature-card-icon">
                                <i class="bi bi-shield-lock"></i> </div>
                            <h4 class="feature-card-title">Private Vault</h4>
                            <p class="feature-card-text">Your privacy is our priority. Secure your most sensitive media with end-to-end encryption and biometric locks, ensuring your personal life stays personal.</p>
                            <a href="#" class="feature-card-link">
                                <span>Security Features</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="feature-card-bg"></div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6">
                    <div class="feature-3d-card" data-tilt data-tilt-max="10" data-tilt-scale="1.05">
                        <div class="feature-card-content">
                            <div class="feature-card-icon">
                                <i class="bi bi-share"></i> </div>
                            <h4 class="feature-card-title">Seamless Sharing</h4>
                            <p class="feature-card-text">Share high-resolution albums with friends and family without losing quality. Create shared spaces where everyone can contribute to the memory collection.</p>
                            <a href="#" class="feature-card-link">
                                <span>Start Sharing</span>
                                <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                        <div class="feature-card-bg"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="referral-section py-5" id="referralSection">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-left">
                <div class="text-center mb-4">
                    <h2 class="display-5 fw-bold">Refer Friends & Extend <span class="text-primary">Your Subscription</span></h2>
                    <p class="lead text-muted">Invite colleagues and friends to experience better document management while you earn free subscription days</p>
                    <div class="separator-light bg-primary opacity-25 mx-auto mt-3" style="width: 80px; height: 3px;"></div>
                </div>

                <!-- Row 1: Left Content, Right Image -->
                <div class="row align-items-center py-4 content-row" >
                    <div class="col-lg-6 mb-4 mb-lg-0" data-aos="fade-right">
                        <div class="content-block pe-lg-5">
                            <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fs-4 rounded-3 mb-3">
                                <i class="bi bi-coin"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Free Subscription Days For Every Referral</h3>
                            <p class="text-muted mb-4">Extend your subscription by referring friends and colleagues. You'll earn bonus days for each successful referral, allowing you to enjoy our premium features longer.</p>
                            <div class="how-it-works-box mb-4">
                            <div class="box-title">
                                <i class="bi bi-info-circle"></i>
                                <h4>How It Works</h4>
                            </div>
                            
                            <ul class="step-list">
                                <li>
                                    <div class="step-icon">1</div>
                                    <div class="step-text">Share your unique referral link with friends</div>
                                </li>
                                <li>
                                    <div class="step-icon">2</div>
                                    <div class="step-text">When they sign up using your link, they'll be counted as your referral</div>
                                </li>
                                <li class="highlighted">
                                    <div class="step-icon">3</div>
                                    <div class="step-text">
                                        <span class="text-primary">You'll earn a {{ env('DEFAULT_DAY_BONUS') }}-day subscription bonus for each successful referral</span>
                                    </div>
                                </li>
                                <li>
                                    <div class="step-icon">4</div>
                                    <div class="step-text">Track your referrals and bonus days in this dashboard</div>
                                </li>
                            </ul>
                      </div>      
                            <ul class="list-unstyled text-left">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Unlimited referrals - no cap on how many days you can earn</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Instant credit to your account - no waiting period</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Your friends also get {{ env('DEFAULT_DAY_BONUS') }} free days to try our premium features</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-6" data-aos="fade-left">
                        <div class="image-wrapper rounded-4 overflow-hidden shadow-lg position-relative">
                            <img src="{{ asset('front/refer-one.png') }}" alt="Referral Rewards" class="img-fluid w-100">
                            
                        <div class="image-overlay bg-dark bg-opacity-10 expandable-image" data-bs-toggle="modal"
                            data-bs-target="#referScreenOneModal"></div>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Right Content, Left Image -->
                <div class="row align-items-center py-4 content-row" >
                    <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0" data-aos="fade-left">
                        <div class="content-block ps-lg-5">
                            <div class="feature-icon d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fs-4 rounded-3 mb-3">
                                <i class="bi bi-people"></i>
                            </div>
                            <h3 class="fw-bold mb-3">Simple 3-Step Referral Process</h3>
                            <p class="text-muted mb-4">Our referral program is designed to be straightforward and rewarding. Follow these simple steps to start earning bonus days for spreading the word.</p>
                            
                            <div class="referral-steps">
                                <div class="d-flex mb-4">
                                    <div class="step-number">1</div>
                                    <div class="ms-3">
                                        <h4 class="h5 fw-medium mb-1">Get Your Unique Link</h4>
                                        <p class="text-muted mb-0">Generate your personal referral link from your dashboard in seconds</p>
                                    </div>
                                </div>
                                <div class="d-flex mb-4">
                                    <div class="step-number">2</div>
                                    <div class="ms-3">
                                        <h4 class="h5 fw-medium mb-1">Share With Your Network</h4>
                                        <p class="text-muted mb-0">Share via email, social media, or direct message with one click</p>
                                    </div>
                                </div>
                                <div class="d-flex mb-4">
                                    <div class="step-number">3</div>
                                    <div class="ms-3">
                                        <h4 class="h5 fw-medium mb-1">Extend Your Subscription</h4>
                                        <p class="text-muted mb-0">Track referrals in real-time and watch your subscription grow</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <a href="{{ route('register') }}" class="btn btn-primary d-inline-flex align-items-center px-4 py-2">
                                    Start Referring Now
                                    <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1" data-aos="fade-right">
                        <div class="image-wrapper rounded-4 overflow-hidden shadow-lg position-relative">
                            <img src="{{ asset('front/refer-two.png') }}" alt="Referral Process" class="img-fluid w-100">
                            
                        <div class="image-overlay bg-dark bg-opacity-10 expandable-image" data-bs-toggle="modal"
                            data-bs-target="#referScreenTwoModal"></div>
                        </div>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="stats-section mt-5 p-4 p-md-5 rounded-4 shadow-sm" data-aos="fade-up">
                    <div class="text-center mb-4">
                        <h3 class="text-primary mb-2">Join Our Growing Referral Community</h3>
                        <p class="text-muted">See what our referral program has achieved so far</p>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card p-4 rounded-3 text-center h-100">
                                <div class="stat-number text-primary fw-bold display-6 mb-1">340+</div>
                                <p class="text-muted mb-0">Bonus Days Awarded</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card p-4 rounded-3 text-center h-100">
                                <div class="stat-number text-primary fw-bold display-6 mb-1">18+</div>
                                <p class="text-muted mb-0">Active Referrers</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card p-4 rounded-3 text-center h-100">
                                <div class="stat-number text-primary fw-bold display-6 mb-1">25+</div>
                                <p class="text-muted mb-0">Successful Referrals</p>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="stat-card p-4 rounded-3 text-center h-100">
                                <div class="stat-number text-primary fw-bold display-6 mb-1">49</div>
                                <p class="text-muted mb-0">Longest Bonus Period</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <p class="small text-muted mb-0">*Statistics updated monthly. Last updated May 2025.</p>
                    </div>
                </div>

                <!-- CTA Banner -->
                <div class="cta-banner mt-5 p-4 p-md-5 rounded-4 text-center" data-aos="fade-up">
                    <h3 class="text-white fw-bold mb-3">Ready to Extend Your Subscription?</h3>
                    <p class="text-white-90 mx-auto mb-4" style="max-width: 600px;">Join thousands of satisfied users who are enjoying extra subscription days while helping their network discover better document management.</p>
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        <a href="{{ route('register') }}" class="btn btn-light text-primary px-4 py-2 fw-medium">
                            Get Your Referral Link
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light px-4 py-2">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        
        .how-it-works-box {
            background-color: rgba(15, 131, 137, 0.08);
            border-radius: 12px;
            padding: 28px;
            box-shadow: 0 10px 30px -15px rgba(15, 131, 137, 0.15);
            border-left: 4px solid var(--primary-color);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .how-it-works-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px -15px rgba(15, 131, 137, 0.25);
        }

        .how-it-works-box:before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            background-color: rgba(15, 131, 137, 0.05);
            border-radius: 0 0 0 100%;
            z-index: 0;
        }

        .box-title {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .box-title i {
            font-size: 24px;
            background-color: var(--primary-color);
            color: white;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 15px;
        }

        .box-title h4 {
            font-weight: 600;
            margin: 0;
            color: var(--dark-color);
        }

        .step-list {
            list-style: none;
            padding: 0;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        .step-list li {
            position: relative;
            padding-left: 35px;
            padding-bottom: 18px;
            color: #586275;
        }

        .step-list li:last-child {
            padding-bottom: 0;
        }

        .step-list li:before {
            content: "";
            position: absolute;
            left: 11px;
            top: 5px;
            height: calc(100% - 5px);
            width: 2px;
            background-color: rgba(15, 131, 137, 0.2);
        }

        .step-list li:last-child:before {
            display: none;
        }

        .step-list li .step-icon {
            position: absolute;
            left: 0;
            top: 2px;
            background-color: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            z-index: 2;
        }

        .step-list li.highlighted {
            font-weight: 500;
        }

        .step-list li.highlighted .step-icon {
            background-color: var(--primary-color);
            color: white;
        }

        .step-list li .step-text {
            line-height: 1.6;
            text-align: left;
        }

        @media (max-width: 576px) {
            .how-it-works-box {
                padding: 20px;
            }
        }
   
        /* Override Bootstrap primary color */
        .text-primary {
            color: var(--primary-color) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .btn-primary {
            background-color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--secondary-color) !important;
            border-color: var(--secondary-color) !important;
        }

        .btn-outline-primary {
            color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn-outline-primary:hover, .btn-outline-primary:focus {
            background-color: var(--primary-color) !important;
            color: white !important;
        }

        /* Element-specific styles */
        .referral-section {
            padding-top: 80px;
            padding-bottom: 80px;
        }

        .feature-icon {
            width: 48px;
            height: 48px;
        }

        .bg-light-blue {
            background-color: rgba(15, 131, 137, 0.1);
        }

        .step-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background-color: var(--primary-color);
            color: white;
            font-weight: bold;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .stats-section {
            background-color: rgba(15, 131, 137, 0.05);
        }

        .stat-card {
            background-color: white;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            height: 100%;
        }

        .stat-number {
            font-size: 2rem;
            line-height: 1.2;
        }

        .cta-banner {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .text-white-90 {
            color: rgba(255, 255, 255, 0.9);
        }

        /* Accordion styles override */
        .accordion-button:not(.collapsed) {
            background-color: #f8f9fa;
            color: var(--primary-color);
            box-shadow: none;
        }

        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(15, 131, 137, 0.25);
        }

        .accordion-body {
            padding: 1.5rem;
            line-height: 1.6;
        }

        .image-wrapper {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease;
        }

        .image-wrapper:hover {
            transform: translateY(-5px);
        }

        .bg-primary.bg-opacity-10 {
            background-color: rgba(15, 131, 137, 0.1) !important;
        }

        .btn-light.text-primary {
            color: var(--primary-color) !important;
        }

        @media (max-width: 991.98px) {
            .content-row {
                padding-top: 2rem;
                padding-bottom: 2rem;
            }
            
            .content-block {
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }
    </style>

    <style>
    
        .client-logos-section {
        background-color: rgba(20, 20, 30, 0.03);
        padding: 20px 0;
        border-radius: 12px;
        }

    .logo-compact-grid {
        padding: 10px;
        background: rgba(255, 255, 255, 0.01);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    .logo-compact-item-wrapper {
        padding: 5px;
    }

    .logo-compact-item {
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.03);
        border-radius: 8px;
        padding: 8px;
        transition: all 0.2s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }

    .logo-compact-item img {
        max-height: 100px;
        max-width: 100%;
        filter: grayscale(0%);
        transition: all 0.2s ease;
        object-fit: contain;
    }

    .logo-compact-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(78, 88, 245, 0.1);
    }

    .logo-compact-item:hover img {
        filter: grayscale(0%);
    }

    .logo-compact-tooltip {
        position: absolute;
        bottom: -30px;
        left: 0;
        width: 100%;
        padding: 4px;
        background: rgba(0, 0, 0, 0.7);
        text-align: center;
        transition: all 0.2s ease;
        opacity: 0;
        font-size: 10px;
    }

    .logo-compact-item:hover .logo-compact-tooltip {
        bottom: 0;
        opacity: 1;
    }

    /* Responsive adjustments */
    @media (max-width: 576px) {
        .logo-compact-item {
            height: 50px;
        }
        
        .logo-compact-item img {
            max-height: 30px;
        }
        
        .logo-compact-tooltip {
            font-size: 9px;
        }
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Optional: Add fade-in animation for logos
        const allLogoItems = document.querySelectorAll('.logo-compact-item-wrapper');
        allLogoItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(10px)';
            item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            item.style.transitionDelay = (index * 0.02) + 's';
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100);
        });
    });
    </script>

 <section class="interactive-cta-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="cta-card">
                    <div class="row align-items-center">
                        <div class="col-lg-8 pe-lg-5">
                            <h3 class="fw-bold mb-4">Ready to secure your digital legacy?</h3>
                            <p class="mb-4">Join thousands of people who trust {{ env('APP_NAME') }} to protect their most private memories and share their favorite stories with the world.</p>
                            <div class="form-wrapper">
                                <form class="d-flex flex-wrap gap-2">
                                    <a href="{{ route('register') }}"
                                        class="btn btn-gradient-primary rounded-pill px-4">
                                        <span>Start Your Free Gallery</span>
                                        <i class="bi bi-arrow-right ms-2"></i>
                                    </a>
                                </form>
                                <p class="small mt-2 text-muted">Set up your vault in seconds. 7 Days free trial.</p>
                            </div>
                        </div>
                        <div class="col-lg-4 d-none d-lg-block">
                            <div class="cta-image-wrapper">
                                <div class="cta-image">
                                    <img src="{{ asset('DOCMEY SITE USED LOGO.png') }}" alt="{{ env('APP_NAME') }} App"
                                        class="img-fluid rounded-circle">
                                    <div class="cta-image-shape"></div>
                                </div>
                                <div class="cta-badge">
                                    <div class="cta-badge-inner">
                                        <i class="bi bi-heart-fill text-danger"></i> <span>Trusted</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- CSS for the additional interactive features -->
    <style>
        :root {
            --primary-color: #c6a055;
            --primary-light: #c6a055;
            --primary-dark: #c6a055;
            --primary-gradient: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-light) 100%);
            --primary-transparent: rgba(15, 131, 137, 0.1);
        }

        .particles-js {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        /* 3D Feature Cards Styles */
        .feature-cards-section {
            position: relative;
            padding: 80px 0;
            background-color: #fafafa;
            z-index: 1;
        }

        .feature-3d-card {
            position: relative;
            height: 350px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transform-style: preserve-3d;
            transform: perspective(1000px);
            transition: all 0.5s ease;
            cursor: pointer;
        }

        .feature-3d-card:hover {
            box-shadow: 0 20px 40px rgba(15, 131, 137, 0.2);
        }

        .feature-card-content {
            position: relative;
            padding: 30px;
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            background-color: white;
            z-index: 2;
            transform: translateZ(40px);
        }

        .feature-card-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            border-radius: 20px;
            background: var(--primary-gradient);
            font-size: 2rem;
            color: white;
            margin-bottom: 20px;
            box-shadow: 0 10px 20px rgba(15, 131, 137, 0.2);
            transition: all 0.3s ease;
        }

        .feature-3d-card:hover .feature-card-icon {
            transform: scale(1.1) translateZ(10px);
        }

        .feature-card-title {
            font-weight: bold;
            margin-bottom: 15px;
            transition: all 0.3s ease;
            transform: translateZ(5px);
        }

        .feature-3d-card:hover .feature-card-title {
            color: var(--primary-color);
        }

        .feature-card-text {
            color: #666;
            margin-bottom: 20px;
            transform: translateZ(5px);
        }

        .feature-card-link {
            display: flex;
            align-items: center;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            margin-top: auto;
            transition: all 0.3s ease;
            transform: translateZ(5px);
        }

        .feature-card-link i {
            margin-left: 8px;
            transition: all 0.3s ease;
        }

        .feature-3d-card:hover .feature-card-link i {
            transform: translateX(5px);
        }

        .feature-card-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--primary-gradient);
            z-index: 1;
            opacity: 0;
            transform: translateZ(20px);
            transition: all 0.5s ease;
        }

        .feature-3d-card:hover .feature-card-bg {
            opacity: 0.03;
        }

        /* Stats Counter Styles */
        .stats-counter-section {
            position: relative;
            padding: 80px 0;
            overflow: hidden;
            z-index: 1;
        }

        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 40px 30px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
        }

        .stat-item {
            padding: 20px 10px;
            position: relative;
        }

        .stat-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-suffix {
            font-size: 1.8rem;
            color: var(--primary-color);
        }

        .stat-title {
            font-size: 1rem;
            color: #666;
            margin-bottom: 15px;
        }

        .stat-bar {
            width: 100%;
            height: 6px;
            background-color: #eee;
            border-radius: 3px;
            overflow: hidden;
        }

        .stat-progress {
            height: 100%;
            background: var(--primary-gradient);
            border-radius: 3px;
            transition: width 2s ease;
        }

        /* Interactive CTA Styles */
        .interactive-cta-section {
            position: relative;
            padding: 80px 0;
            background-color: #fafafa;
            z-index: 1;
        }

        .cta-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.05);
            position: relative;
            overflow: hidden;
        }

        .cta-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--primary-gradient);
        }

        .cta-image-wrapper {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        .cta-image {
            position: relative;
            width: 200px;
            height: 200px;
            animation: rotate-slow 15s linear infinite;
        }

        @keyframes rotate-slow {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .cta-image img {
            position: relative;
            z-index: 2;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .cta-image-shape {
            position: absolute;
            top: -20px;
            left: -20px;
            width: calc(100% + 40px);
            height: calc(100% + 40px);
            border-radius: 50%;
            border: 2px dashed var(--primary-color);
            opacity: 0.2;
            animation: rotate-reverse 20s linear infinite;
        }

        @keyframes rotate-reverse {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(-360deg);
            }
        }

        .cta-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            z-index: 3;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .cta-badge-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .cta-badge-inner i {
            color: var(--primary-color);
            font-size: 0.8rem;
            margin-bottom: 3px;
        }

        .cta-badge-inner span {
            font-weight: bold;
            font-size: 0.8rem;
            color: #333;
        }

        .form-wrapper {
            position: relative;
            z-index: 2;
        }

        .btn-gradient-primary {
            background: var(--primary-gradient);
            border: none;
            color: white;
            padding: 12px 25px;
            font-weight: 600;
            transition: all 0.5s ease;
        }

        .btn-gradient-primary:hover {
            box-shadow: 0 10px 20px rgba(15, 131, 137, 0.3);
            transform: translateY(-3px);
            color: white;
        }

        .btn-gradient-primary i {
            transition: all 0.3s ease;
        }

        .btn-gradient-primary:hover i {
            transform: translateX(5px);
        }

        .text-primary,
        .btn-outline-primary {
            color: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .btn:hover {
            background-color: var(--primary-color) !important;
            color: white !important;
        }
    </style>

    <!-- JavaScript for the interactive features -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize Tilt.js for 3D cards if available
            if (typeof VanillaTilt !== 'undefined') {
                VanillaTilt.init(document.querySelectorAll(".feature-3d-card"), {
                    max: 10,
                    speed: 400,
                    glare: true,
                    "max-glare": 0.2,
                });
            }

            // Initialize Particle.js if available
            if (typeof particlesJS !== 'undefined') {
                particlesJS('particles-canvas', {
                    "particles": {
                        "number": {
                            "value": 80,
                            "density": {
                                "enable": true,
                                "value_area": 800
                            }
                        },
                        "color": {
                            "value": "#0F8389"
                        },
                        "shape": {
                            "type": "circle",
                            "stroke": {
                                "width": 0,
                                "color": "#000000"
                            },
                            "polygon": {
                                "nb_sides": 5
                            }
                        },
                        "opacity": {
                            "value": 0.5,
                            "random": false,
                            "anim": {
                                "enable": false,
                                "speed": 1,
                                "opacity_min": 0.1,
                                "sync": false
                            }
                        },
                        "size": {
                            "value": 3,
                            "random": true,
                            "anim": {
                                "enable": false,
                                "speed": 40,
                                "size_min": 0.1,
                                "sync": false
                            }
                        },
                        "line_linked": {
                            "enable": true,
                            "distance": 150,
                            "color": "#0F8389",
                            "opacity": 0.4,
                            "width": 1
                        },
                        "move": {
                            "enable": true,
                            "speed": 2,
                            "direction": "none",
                            "random": false,
                            "straight": false,
                            "out_mode": "out",
                            "bounce": false,
                            "attract": {
                                "enable": false,
                                "rotateX": 600,
                                "rotateY": 1200
                            }
                        }
                    },
                    "interactivity": {
                        "detect_on": "canvas",
                        "events": {
                            "onhover": {
                                "enable": true,
                                "mode": "grab"
                            },
                            "onclick": {
                                "enable": true,
                                "mode": "push"
                            },
                            "resize": true
                        },
                        "modes": {
                            "grab": {
                                "distance": 140,
                                "line_linked": {
                                    "opacity": 1
                                }
                            },
                            "bubble": {
                                "distance": 400,
                                "size": 40,
                                "duration": 2,
                                "opacity": 8,
                                "speed": 3
                            },
                            "repulse": {
                                "distance": 200,
                                "duration": 0.4
                            },
                            "push": {
                                "particles_nb": 4
                            },
                            "remove": {
                                "particles_nb": 2
                            }
                        }
                    },
                    "retina_detect": true
                });
            }

            // Initialize counter animation
            function initCounters() {
                const counters = document.querySelectorAll('.counter');
                const progressBars = document.querySelectorAll('.stat-progress');

                // Check if element is in viewport
                function isInViewport(element) {
                    const rect = element.getBoundingClientRect();
                    return (
                        rect.top >= 0 &&
                        rect.left >= 0 &&
                        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                    );
                }

                // Animate counter
                function animateCounter(counter, target) {
                    let current = 0;
                    const increment = target > 100 ? Math.ceil(target / 100) : (target / 100);
                    const duration = 2000; // 2 seconds
                    const interval = Math.floor(duration / (target / increment));

                    const timer = setInterval(() => {
                        current += increment;
                        counter.textContent = current > target ? target : current.toFixed(target > 100 ? 0 : 1);

                        if (current >= target) {
                            clearInterval(timer);
                        }
                    }, interval);
                }

                // Animate progress bars
                function animateProgressBars() {
                    progressBars.forEach(progressBar => {
                        const width = progressBar.getAttribute('data-width');
                        progressBar.style.width = width;
                    });
                }

                // Check if stats section is in viewport and trigger animation
                function checkCounters() {
                    if (counters.length > 0 && isInViewport(document.querySelector('.stats-counter-section'))) {
                        counters.forEach(counter => {
                            const target = parseFloat(counter.closest('.stat-number').getAttribute('data-count'));
                            animateCounter(counter, target);
                        });

                        animateProgressBars();

                        // Remove scroll event after animation is triggered
                        window.removeEventListener('scroll', checkCounters);
                    }
                }

                // Initial check
                checkCounters();

                // Add scroll event
                window.addEventListener('scroll', checkCounters);
            }

            // Initialize counters
            initCounters();
        });
    </script>


    <!-- Features Section -->
    <section class="features-section" id="features">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold">Everything You Need</h2>
            <p class="lead text-muted">A powerful suite of tools to protect and enjoy your personal media collection</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-cloud-check"></i>
                    </div>
                    <h4>Automatic Backup</h4>
                    <p class="text-muted">Never lose a memory again. Securely sync your photos and videos to the cloud and access them on any device, anywhere.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-magic"></i>
                    </div>
                    <h4>Smart Search</h4>
                    <p class="text-muted">Find that one specific sunset or birthday party instantly. Our AI scans faces, locations, and dates to find your favorites fast.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-incognito"></i>
                    </div>
                    <h4>Private Vault</h4>
                    <p class="text-muted">Your life isn't public business. Protect your most sensitive media with biometric locks and end-to-end encryption.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-camera-reels"></i>
                    </div>
                    <h4>Original Resolution</h4>
                    <p class="text-muted">No heavy compression here. We preserve every pixel and frame of your 4K videos and high-res photos exactly as you took them.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-hearts"></i>
                    </div>
                    <h4>Shared Albums</h4>
                    <p class="text-muted">Invite friends and family to contribute. Create collaborative spaces for weddings, trips, and family milestones.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                <div class="text-center">
                    <div class="feature-icon mx-auto">
                        <i class="bi bi-calendar3-event"></i>
                    </div>
                    <h4>Life Timeline</h4>
                    <p class="text-muted">Travel back in time. Browse your life through a beautiful, chronological feed that makes rediscovering old memories a joy.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="alternating-content-section py-5">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 fw-bold">Why Choose {{ env('APP_NAME') }}</h2>
                <p class="lead text-muted">The most intuitive way to protect, organize, and relive your personal media collection</p>
                <div class="separator-light bg-primary opacity-25 mx-auto mt-3" style="width: 80px; height: 3px;"></div>
            </div>

            <div class="row align-items-center py-5 content-row" data-aos="fade-up">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="content-block pe-lg-5">
                        <div class="feature-icon-small d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fs-4 rounded-3 mb-3">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Your Private Media Vault</h3>
                        <p class="text-muted mb-4">Your personal photos and videos belong to you, not a social media algorithm. Our secure vault ensures your most private moments are protected with industry-leading encryption and biometric access.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> End-to-end media encryption</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Private hidden folders</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Automatic secure logout</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="image-wrapper rounded-4 overflow-hidden shadow-lg position-relative">
                        <img src="{{ asset('front/login-page.png') }}" alt="Secure Vault Interface" class="img-fluid w-100 expandable-image">
                        <div class="image-overlay bg-dark bg-opacity-10 expandable-image" data-bs-toggle="modal" data-bs-target="#imageModal"></div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center py-5 content-row" data-aos="fade-up">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <div class="content-block ps-lg-5">
                        <div class="feature-icon-small d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fs-4 rounded-3 mb-3">
                            <i class="bi bi-search-heart"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Search Your Life Story</h3>
                        <p class="text-muted mb-4">Don't spend hours scrolling. Our unified search helps you find specific memories across all your devices instantly—from a vacation five years ago to a video you took yesterday.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Search by date, location, or people</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> AI-powered content recognition</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Instant results across cloud and local storage</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="image-wrapper rounded-4 overflow-hidden shadow-lg position-relative">
                        <img src="{{ asset('front/search-doc.png') }}" alt="Memory Search Interface" class="img-fluid w-100 expandable-image">
                        <div class="image-overlay bg-dark bg-opacity-10 expandable-image" data-bs-toggle="modal" data-bs-target="#searchImageModal"></div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center py-5 content-row" data-aos="fade-up">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="content-block pe-lg-5">
                        <div class="feature-icon-small d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fs-4 rounded-3 mb-3">
                            <i class="bi bi-grid-3x3-gap"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Media Management Dashboard</h3>
                        <p class="text-muted mb-4">Get a bird's-eye view of your entire digital legacy. Monitor your storage usage, organize recently uploaded videos, and see which albums are currently shared with family.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Real-time storage tracking</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Recent activity and upload history</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Quick access to favorite albums</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="image-wrapper rounded-4 overflow-hidden shadow-lg position-relative">
                        <img src="{{ asset('front/overview.png') }}" alt="Media Dashboard" class="img-fluid w-100 expandable-image">
                        <div class="image-overlay bg-dark bg-opacity-10 expandable-image" data-bs-toggle="modal" data-bs-target="#overviewImageModal"></div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center py-5 content-row" data-aos="fade-up">
                <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                    <div class="content-block ps-lg-5">
                        <div class="feature-icon-small d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fs-4 rounded-3 mb-3">
                            <i class="bi bi-collection-play"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Beautiful Album Creation</h3>
                        <p class="text-muted mb-4">Turn a messy gallery into a beautiful collection. Create digital albums with custom covers, descriptions, and layouts to tell the story of your life’s milestones.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Custom album layouts and themes</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Add stories and captions to photos</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Export albums to high-quality PDF or Slideshows</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <div class="image-wrapper rounded-4 overflow-hidden shadow-lg position-relative">
                        <img src="{{ asset('front/create-doc.png') }}" alt="Album Creator" class="img-fluid w-100 expandable-image">
                        <div class="image-overlay bg-dark bg-opacity-10 expandable-image" data-bs-toggle="modal" data-bs-target="#createDocImageModal"></div>
                    </div>
                </div>
            </div>

            <div class="row align-items-center py-5 content-row" data-aos="fade-up">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="content-block pe-lg-5">
                        <div class="feature-icon-small d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary fs-4 rounded-3 mb-3">
                            <i class="bi bi-send-check"></i>
                        </div>
                        <h3 class="fw-bold mb-3">Safe Link Sharing</h3>
                        <p class="text-muted mb-4">Share full-resolution videos and photos via secure links. Send them to family members' emails directly from the platform—perfect for long videos that are too big for texting.</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Set password protection on shared links</li>
                            <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> Expiry dates for temporary sharing</li>
                            <li class="bi bi-check-circle-fill text-primary me-2"></i> Direct email delivery with no download required</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="image-wrapper rounded-4 overflow-hidden shadow-lg position-relative">
                        <img src="{{ asset('front/doc-send-mail.png') }}" alt="Secure Sharing" class="img-fluid w-100 expandable-image">
                        <div class="image-overlay bg-dark bg-opacity-10 expandable-image" data-bs-toggle="modal" data-bs-target="#emailDocImageModal"></div>
                    </div>
                </div>
            </div>

            

        </div>
    </section>

  
    <!-- Add these modals at the end of your section, just before the closing </section> tag -->

    <!-- Modal for Login Page Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ asset('front/login-page.png') }}" alt="Secure Login Interface" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Refer SCreen one Image -->
    <div class="modal fade" id="referScreenOneModal" tabindex="-1" aria-labelledby="referScreenOneModal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ asset('front/refer-one.png') }}" alt="Global Search Features" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Refer SCreen two Image -->
    <div class="modal fade" id="referScreenTwoModal" tabindex="-1" aria-labelledby="referScreenTwoModal"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ asset('front/refer-two.png') }}" alt="Global Search Features" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
    <!-- Modal for Search Doc Image -->
    <div class="modal fade" id="searchImageModal" tabindex="-1" aria-labelledby="searchImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ asset('front/search-doc.png') }}" alt="Global Search Features" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Team Overview Image -->
    <div class="modal fade" id="overviewImageModal" tabindex="-1" aria-labelledby="overviewImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ asset('front/overview.png') }}" alt="Team Overview Dashboard" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Document Creation Image -->
    <div class="modal fade" id="createDocImageModal" tabindex="-1" aria-labelledby="createDocImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ asset('front/create-doc.png') }}" alt="Document Creation Interface" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Email Document Sharing Image -->
    <div class="modal fade" id="emailDocImageModal" tabindex="-1" aria-labelledby="emailDocImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ asset('front/doc-send-mail.png') }}" alt="Email Document Sharing" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Task Creation Image -->
    <div class="modal fade" id="taskCreationImageModal" tabindex="-1" aria-labelledby="taskCreationImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ asset('front/create-task.png') }}" alt="Task Creation Interface" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Task List Image -->
    <div class="modal fade" id="taskListImageModal" tabindex="-1" aria-labelledby="taskListImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ asset('front/task-list.png') }}" alt="Task List with Tabs" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Add this CSS if it's not already included -->
    <style>
        .expandable-image {
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .expandable-image:hover {
            transform: scale(1.02);
        }

        /* Optional: Style for modal animations */
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: scale(0.95);
        }

        .modal.show .modal-dialog {
            transform: scale(1);
        }

        /* Optional: Style for modal content */
        .modal-content {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            padding: 0.75rem 1rem;
            position: absolute;
            right: 0;
            z-index: 1050;
        }

        .btn-close {
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            opacity: 0.8;
        }

        .btn-close:hover {
            opacity: 1;
        }
    </style>

    <!-- Custom CSS for the alternating content section -->
    <style>
        .alternating-content-section {
            padding: 80px 0;
            background-color: #fafafa;
        }

        .content-row {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .content-row:last-child {
            border-bottom: none;
        }

        .feature-icon-small {
            width: 50px;
            height: 50px;
        }

        .content-block {
            padding: 20px 0;
        }

        .image-wrapper {
            transition: all 0.5s ease;
            transform: perspective(1000px) rotateY(0deg);
        }

        .image-wrapper:hover {
            transform: perspective(1000px) rotateY(-5deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            transition: all 0.3s ease;
        }

        .image-wrapper:hover .image-overlay {
            background-color: rgba(15, 131, 137, 0.1) !important;
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .content-block {
                padding: 20px;
            }

            .content-row {
                padding-top: 40px;
                padding-bottom: 40px;
            }
        }

        /* Additional animation for the feature icons */
        .feature-icon-small {
            position: relative;
            overflow: hidden;
        }

        .feature-icon-small::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(15, 131, 137, 0.2) 0%, rgba(255, 255, 255, 0) 70%);
            transform: scale(0);
            transform-origin: center;
            transition: transform 0.5s ease;
        }

        .content-block:hover .feature-icon-small::after {
            transform: scale(2);
        }
    </style>

    <!-- Pricing Section -->
    <section class="pricing-section" id="pricing">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 fw-bold">Simple, Transparent Pricing</h2>
                <p class="lead text-muted">Choose the plan that fits your needs</p>
            </div>

            @php
                $plans = config('plans');
                $trialPlan = $plans['free'];
                unset($plans['free']);
            @endphp

            <!-- Special Trial Card -->
            <div class="trial-section mb-5" data-aos="fade-up">
                <div class="trial-card">
                    <div class="row align-items-center">
                        <div class="col-lg-7 trial-content">
                            <h3 class="mb-3">Start Your Memory Journey</h3>
                            <h2 class="display-5 fw-bold text-primary mb-4">{{ $trialPlan['name'] }} — 
                                {{ $trialPlan['currency'] }}{{ $trialPlan['price'] }}
                            </h2>
                            <p class="lead mb-4">Experience the peace of mind that comes with a perfectly organized, private media vault. Start today and see how easy it is to safeguard your life's highlights.</p>

                            <div class="trial-features d-flex flex-wrap">
                                @foreach($trialPlan['features'] as $feature)
                                    <div class="trial-feature-item mb-3 me-4">
                                        <i class="bi {{ $feature['included'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-muted' }} me-2"></i>
                                        {{ $feature['text'] }}
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('register') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">
                                    Claim Your Free Week <i class="bi bi-arrow-right ms-2"></i>
                                </a>
                                <span class="ms-3 text-muted">No credit card required to start</span>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block">
                            <div class="trial-image">
                                <div class="trial-badge">
                                    <span class="days">7</span>
                                    <span class="text">Days</span>
                                    <span class="free">FREE</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="text-center mb-4" data-aos="fade-up">Paid Plans</h3>

            <!-- Regular Plans Row -->
            <div class="row g-4">
                @foreach($plans as $key => $plan)
                    <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                        <div class="card pricing-card h-100 {{ $plan['popular'] ? 'popular-plan' : '' }}">
                            @if($plan['popular'])
                                <div class="popular-badge">Popular</div>
                            @endif
                            <div class="pricing-header text-center">
                                <h5 class="mb-4">{{ $plan['name'] }}</h5>
                                <h2 class="display-4 fw-bold mb-0">{{ $plan['currency'] }} {{ $plan['price'] }}</h2>
                                <p class="text-white-50">per user/month</p>
                            </div>
                            <div class="pricing-features">
                                <ul class="list-unstyled">
                                    @foreach($plan['features'] as $feature)
                                        <li class="mb-3 {{ $feature['included'] ? '' : 'text-muted' }}">
                                            <i
                                                class="bi {{ $feature['included'] ? 'bi-check-circle-fill text-success' : 'bi-x-circle-fill text-muted' }} me-2"></i>
                                            {{ $feature['text'] }}
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="text-center mt-4">
                                    @if($plan['show_button'])
                                        <a href="#signup"
                                            class="btn btn-{{ $plan['popular'] ? 'primary' : 'outline-primary' }} rounded-pill px-4 py-2 w-100">Get
                                            Started</a>
                                    @endif
                                </div>
                                <p class="text-center mt-3 small">{{ $plan['lines'] }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <!-- Add GST disclaimer at the bottom -->
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <p class="small text-muted">* All prices are exclusive of {{ $gst_rate ?? 18 }}% GST</p>
                </div>
            </div>
        </div>
    </section>
    <style>
        /* Enhanced Pricing Section Styles */

        .pricing-section {
            padding: 80px 0;
        }

        /* Trial Card Styles */
        .trial-section {
            margin-bottom: 50px;
        }

        .trial-card {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            position: relative;
            overflow: hidden;
            border: 2px dashed var(--bs-primary);
        }

        .trial-card::before {
            content: '';
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background-color: rgba(var(--bs-primary-rgb), 0.1);
            z-index: 0;
        }

        .trial-content {
            position: relative;
            z-index: 2;
        }

        .trial-features {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .trial-feature-item {
            flex: 0 0 40%;
            font-weight: 500;
            padding: 8px 0;
        }

        .trial-image {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            position: relative;
        }

        .trial-badge {
            width: 180px;
            height: 180px;
            background: var(--bs-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transform: rotate(10deg);
            box-shadow: 0 8px 25px rgba(var(--bs-primary-rgb), 0.5);
            animation: pulse 2s infinite;
        }

        .trial-badge .days {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1;
            margin-top: -5px;
        }

        .trial-badge .text {
            font-size: 1.2rem;
            letter-spacing: 2px;
        }

        .trial-badge .free {
            font-size: 1.8rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-top: 5px;
        }

        @keyframes pulse {
            0% {
                transform: rotate(10deg) scale(1);
                box-shadow: 0 8px 25px rgba(var(--bs-primary-rgb), 0.5);
            }

            50% {
                transform: rotate(10deg) scale(1.05);
                box-shadow: 0 12px 30px rgba(var(--bs-primary-rgb), 0.6);
            }

            100% {
                transform: rotate(10deg) scale(1);
                box-shadow: 0 8px 25px rgba(var(--bs-primary-rgb), 0.5);
            }
        }

        /* Regular Plans Styling */
        .pricing-card {
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .pricing-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
        }

        .pricing-card.popular-plan {
            border: 2px solid var(--bs-primary);
            transform: scale(1.03);
        }

        .pricing-card.popular-plan:hover {
            transform: scale(1.05) translateY(-5px);
        }

        .pricing-header {
            background: linear-gradient(135deg, #0F8389 0%, #343a40 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 12px 12px 0 0;
        }

        .pricing-features {
            padding: 30px;
        }

        .popular-badge {
            position: absolute;
            top: 20px;
            right: -30px;
            background-color: var(--bs-primary);
            color: white;
            padding: 5px 30px;
            transform: rotate(45deg);
            font-size: 0.8rem;
            font-weight: bold;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        /* Responsive adjustments */
        @media (max-width: 992px) {
            .trial-card {
                padding: 30px;
            }

            .trial-feature-item {
                flex: 0 0 100%;
            }
        }

        @media (max-width: 768px) {
            .pricing-card.popular-plan {
                transform: scale(1);
            }

            .pricing-card.popular-plan:hover {
                transform: translateY(-10px);
            }
        }
    </style>


   <!-- FAQ Section -->
    <section class="faq-section bg-light py-5" id="faq">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 fw-bold">Common Questions</h2>
                <p class="lead text-muted">Everything you need to know about your personal media vault</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="accordion" id="faqAccordion">
                        
                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Are my personal photos and videos private?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Absolutely. {{ env('APP_NAME') }} uses end-to-end encryption, meaning only you have the keys to view your media. We never sell your data to advertisers or use your photos for AI training. Your memories remain yours alone.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Do you compress my photos or videos?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    No. We believe in preserving your memories in their original glory. Unlike social media platforms, we store every file at its full resolution—supporting 4K video, RAW photo formats, and high-fidelity audio without any quality loss.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    What media formats can I upload?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We support virtually all modern media formats, including images (JPG, PNG, HEIC, GIF, WEBP, TIFF), videos (MP4, MOV, AVI, MKV), and even Live Photos. Our built-in player allows you to stream your videos directly from the cloud on any device.
                                </div>
                            </div>
                        </div>
                    
                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    What happens if I run out of storage space?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    We’ll notify you when you’re reaching your limit. You can easily upgrade your plan at any time to add more storage. If you choose not to upgrade, your existing photos remain safe and accessible, but you won't be able to upload new ones until space is cleared.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    Can I share albums with people who don't have an account?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes! You can create secure, password-protected share links. Your friends and family can view or download the photos you've selected through their web browser without needing to sign up for an account.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #0d6efd;
        box-shadow: none;
    }

    .accordion-button:focus {
        box-shadow: none;
        border-color: rgba(13, 110, 253, 0.25);
    }

    .accordion-body {
        padding: 1.5rem;
        line-height: 1.6;
    }

    .faq-section {
        padding-top: 80px;
        padding-bottom: 80px;
    }
    </style>

 <section class="testimonial-section py-5">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="display-5 fw-bold">Stories from Our Community</h2>
            <p class="lead text-muted">Join thousands of families across India preserving their digital legacy</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-card h-100 position-relative">
                    <i class="bi bi-quote quote-icon"></i>
                    <p class="mb-4">"{{ env('APP_NAME') }} has been a lifesaver for our family photos. I used to worry about losing my phone or running out of cloud space, but now everything is backed up in full resolution. Finding a photo from my daughter's first birthday took seconds thanks to the smart search. It’s more than an app; it’s our family’s digital home."</p>
                    <div class="d-flex align-items-center">
                        <div class="testimonial-avatar me-3">RP</div>
                        <div>
                            <h5 class="mb-0">Rajesh Patel</h5>
                            <p class="text-muted mb-0">Father of two, Mumbai</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-card h-100 position-relative">
                    <i class="bi bi-quote quote-icon"></i>
                    <p class="mb-4">"As a travel blogger, privacy and quality are non-negotiable for me. I love that I can keep my private vlogs in a secure vault while easily sharing high-res albums with my followers. The fact that they don't compress my 4K videos is a game changer. It's the only place I trust with my life's work and my most personal moments."</p>
                    <div class="d-flex align-items-center">
                        <div class="testimonial-avatar me-3">AS</div>
                        <div>
                            <h5 class="mb-0">Ananya Sharma</h5>
                            <p class="text-muted mb-0">Travel Content Creator</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                <div class="testimonial-card h-100 position-relative">
                    <i class="bi bi-quote quote-icon"></i>
                    <p class="mb-4">"My siblings live across three different time zones, and {{ env('APP_NAME') }} has become our central hub for sharing family videos. We created a shared album for my parents' anniversary, and everyone contributed their favorite clips. It made the distance feel so much smaller. The mobile app is incredibly smooth and easy for my parents to use too!"</p>
                    <div class="d-flex align-items-center">
                        <div class="testimonial-avatar me-3">VK</div>
                        <div>
                            <h5 class="mb-0">Vikram Krishnan</h5>
                            <p class="text-muted mb-0">Tech Professional & Photography Enthusiast</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        .testimonial-card {
            background-color: #fff;
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }
        .testimonial-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        .quote-icon {
            font-size: 2rem;
            color: #6c63ff;
            opacity: 0.2;
            position: absolute;
            top: 1rem;
            right: 1.5rem;
        }
        .testimonial-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #6c63ff;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</section>
    <!-- CTA Section -->
    <section class="cta-section bg-primary text-white py-5" id="signup">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8" data-aos="fade-up">
                    <h2 class="display-4 fw-bold mb-4">Give Your Memories a Forever Home</h2>
                    <p class="lead mb-5">Join thousands of people who trust {{ env('APP_NAME') }} to keep their most precious photos and videos safe, organized, and always within reach.</p>
                    <form class="row g-3 justify-content-center">
                        <div class="col-md-auto">
                            <a href="{{ route('register') }}" class="btn btn-light btn-lg rounded-pill px-5 shadow">
                                Start Your Free Gallery
                            </a>
                        </div>
                    </form>
                    <p class="small mt-3 text-white-50">No credit card required. Instant access for 7 days.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer bg-dark py-5" id="contact">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <h4 class="text-white mb-4"><i class="bi bi-camera-reels me-2"></i>{{ env('APP_NAME') }}</h4>
                <p class="text-white-50 mb-4">Dedicated to preserving your life's most precious moments. Securely store, organize, and relive your photos and videos in your personal digital vault.</p>
            </div>
            
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="text-white mb-4">Support</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('contact-us') }}" class="footer-link text-white-50 text-decoration-none">Contact Us</a></li>
                    <li class="mb-2"><a href="#faq" class="footer-link text-white-50 text-decoration-none">Help Center</a></li>
                </ul>
            </div>
            
            <div class="col-lg-2 col-md-4">
                <h5 class="text-white mb-4">Legal</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="{{ route('privacy-policy') }}" class="footer-link text-white-50 text-decoration-none">Privacy Policy</a></li>
                    <li class="mb-2"><a href="{{ route('terms-conditions') }}" class="footer-link text-white-50 text-decoration-none">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        
        <hr class="mt-5 mb-4 bg-secondary opacity-25">
        
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-white-50 mb-0 small">&copy; {{ date('Y') }} {{ env('APP_NAME') }}. Your memories, safely stored.</p>
                <p class="text-white-50 mb-0 small">Powered By <a href="https://deltantec.com" class="text-decoration-none text-white-50 fw-medium">Deltan Technologies</a></p>
            </div>
            <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle rounded-pill px-3" type="button">
                        <i class="bi bi-globe me-1"></i> English
                    </button>
                    <p class="small text-white-50 mt-2 mb-0">Made with ❤️ for your most valued memories</p>
                </div>
            </div>
        </div>
    </div>
</footer>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <!-- Custom JS -->
    <script>
        // Initialize AOS animations
        AOS.init({
            duration: 800,
            once: true
        });

        // Navbar scroll effect
        $(window).scroll(function () {
            if ($(window).scrollTop() > 50) {
                $('#navbar').addClass('scrolled');
            } else {
                $('#navbar').removeClass('scrolled');
            }
        });

        // Apply initial navbar state on page load
        $(document).ready(function () {
            if ($(window).scrollTop() > 50) {
                $('#navbar').addClass('scrolled');
            }

            // Add active class to current nav item
            $('.navbar-nav .nav-link').each(function () {
                const sectionId = $(this).attr('href');
                if (sectionId && sectionId !== '#' && $(sectionId).length) {
                    $(this).click(function () {
                        $('.navbar-nav .nav-link').removeClass('active');
                        $(this).addClass('active');

                        // Close mobile menu when item is clicked
                        if ($('.navbar-collapse').hasClass('show')) {
                            $('.navbar-toggler').click();
                        }
                    });
                }
            });

            // Handle dropdown hover effect on desktop
            if (window.innerWidth > 992) {
                $('.dropdown').hover(
                    function () {
                        $(this).find('.dropdown-menu').addClass('show');
                    },
                    function () {
                        $(this).find('.dropdown-menu').removeClass('show');
                    }
                );
            }
        });

        // Counter animation
        const counterAnimation = () => {
            const counters = document.querySelectorAll('.counter');
            const speed = 200;

            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.innerText.replace(/[^\d]/g, '');
                    const count = +counter.getAttribute('data-count') || 0;
                    const increment = target / speed;

                    if (count < target) {
                        counter.setAttribute('data-count', Math.ceil(count + increment));
                        counter.innerText = counter.getAttribute('data-count').replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                        if (counter.innerText.includes('k')) {
                            counter.innerText = counter.innerText.replace(/\B(?=(\d{3})+(?!\d))/g, ',') + 'k+';
                        } else if (counter.innerText.includes('M')) {
                            counter.innerText = counter.innerText.replace(/\B(?=(\d{3})+(?!\d))/g, ',') + 'M+';
                        }
                    }
                };

                updateCount();
            });
        };

        // Initialize counter animation when stats section is in view
        const statsSection = document.querySelector('.stats-section');
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                counterAnimation();
                observer.unobserve(entries[0].target);
            }
        }, { threshold: 0.5 });

        if (statsSection) {
            observer.observe(statsSection);
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
    </script>
</body>

</html>