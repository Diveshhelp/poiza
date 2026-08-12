<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }} - Advanced Hardware Production & ERP Management</title>
    <link rel="icon" type="image/png" href="favicon.ico">
    <!-- SEO Meta Tags -->
    <meta name="description" content="Streamline your hardware manufacturing, mortise handle production, customer orders, and workflow tracking with our comprehensive ERP solution.">
    <meta name="keywords" content="hardware ERP, production tracking, manufacturing management, mortise handles production, order management system, inventory control, industrial workflow, brass and steel hardware software">
    <meta name="author" content="{{ env('APP_NAME') }}">

    <!-- Open Graph / Social Media Meta Tags -->
    <meta property="og:title" content="{{ env('APP_NAME') }} - Hardware Production & ERP Management">
    <meta property="og:description" content="Streamline your hardware manufacturing and order tracking workflow.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ env('APP_NAME') }} - Hardware Production & ERP Management">
    <meta name="twitter:description" content="Streamline your hardware manufacturing and order tracking workflow.">
    <meta name="twitter:image" content="{{ asset('images/twitter-image.jpg') }}">

    <!-- Canonical URL -->
    <meta rel="canonical" href="{{ url()->current() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
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

        .dropdown-item {
            padding: 0.6rem 1.8rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(198, 160, 85, 0.1);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .navbar .btn-primary {
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(198, 160, 85, 0.3);
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .navbar .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(198, 160, 85, 0.4);
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

        .text-gradient {
            background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .shadow-hover {
            transition: all 0.3s ease;
        }

        .shadow-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2) !important;
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
            0% { transform: translateY(0px); }
            50% { transform: translateY(-12px); }
            100% { transform: translateY(0px); }
        }

        .float-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .features-section {
            padding: 120px 0;
            background-color: #fafafa;
        }

        .feature-icon {
            font-size: 2.5rem;
            width: 80px;
            height: 80px;
            line-height: 80px;
            text-align: center;
            background-color: rgba(198, 160, 85, 0.1);
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

        /* Hardware Showcase & Highlights Grid Styles */
        .showcase-grid-section {
            padding: 120px 0;
            background-color: #ffffff;
        }

        .hardware-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            background: #fff;
            height: 100%;
        }

        .hardware-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(198, 160, 85, 0.15);
        }

        .hardware-card-body {
            padding: 35px 30px;
        }

        .hardware-card-icon {
            width: 65px;
            height: 65px;
            background: rgba(198, 160, 85, 0.1);
            color: var(--primary-color);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 25px;
        }

        .footer {
            background-color: #2c3e50;
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
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-tools me-2"></i>Diora Hardware ERP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Modules</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#highlights">Manufacturing Highlights</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#workflow">Process Flow</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#faq">Support</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('login') }}" class="nav-link">Log In</a>
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
        </div>

        <div class="container position-relative">
            <div class="row align-items-center min-vh-80">
                <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
                    <span class="badge bg-white text-primary px-3 py-2 rounded-pill mb-4 shadow-sm">
                        <i class="bi bi-gear-fill me-1"></i> Ultimate Manufacturing & ERP Hub
                    </span>
                    <h1 class="display-3 fw-bold mb-4 text-gradient">Master Your Hardware Production & Orders</h1>
                    <p class="lead mb-5 text-white-90 fw-light">
                        Seamlessly manage customers, track raw material weights, oversee mortise handle manufacturing, and automate your entire industrial workflow in one powerful ERP platform.
                    </p>

                    <div class="d-flex flex-wrap gap-4 mt-5">
                        <a href="{{ route('register') }}" class="btn btn-light btn-lg rounded-pill px-5 py-3 shadow-hover">
                            Start Free Trial
                            <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#features" class="btn btn-outline-light btn-lg rounded-pill px-5 py-3">
                            <i class="bi bi-cpu-fill me-2"></i>Explore Modules
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block position-relative" data-aos="fade-up" data-aos-duration="1000"
                    data-aos-delay="200">
                    <div class="hero-image-wrapper">
                        <img src="{{ asset('front/login-page.png') }}" alt="Hardware Production Dashboard"
                            class="img-fluid rounded-4 shadow-lg hero-main-image">
                        <div class="floating-card floating-card-1 shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="float-icon bg-success text-white">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-0 small text-black">Batch #DH-9350 Processed</p>
                                </div>
                            </div>
                        </div>
                        <div class="floating-card floating-card-2 shadow-lg">
                            <div class="d-flex align-items-center">
                                <div class="float-icon bg-primary text-white">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="ms-3">
                                    <p class="mb-0 small text-black">Quality Inspection Passed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section" id="features">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 fw-bold">Built for Hardware Manufacturers</h2>
                <p class="lead text-muted">A specialized suite of tools designed to handle every stage of industrial production</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <h4>Customer & Client CRM</h4>
                        <p class="text-muted">Maintain detailed client registries, track purchase histories, manage custom pricing agreements, and foster long-term distributor relationships effortlessly.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-cart-check-fill"></i>
                        </div>
                        <h4>Order Management</h4>
                        <p class="text-muted">Convert inquiries into confirmed sales orders, assign batch deadlines, dispatch shipments, and track fulfillment status from a single intuitive command center.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-diagram-3-fill"></i>
                        </div>
                        <h4>Process Tracking System</h4>
                        <p class="text-muted">Monitor raw material consumption down to precise weights (e.g., 9.350 kg specs), casting, machining, finishing, and packaging stages with full transparency.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-boxes"></i>
                        </div>
                        <h4>Inventory & Stock Control</h4>
                        <p class="text-muted">Never run out of raw materials or finished mortise handle components. Real-time alerts keep your assembly lines operating at peak efficiency.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-calculator-fill"></i>
                        </div>
                        <h4>Cost & Pricing Automation</h4>
                        <p class="text-muted">Instantly calculate production costs based on weight inputs, raw material fluctuations, and flat-rate adjustments without manual errors.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="600">
                    <div class="text-center p-4">
                        <div class="feature-icon mx-auto">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        </div>
                        <h4>Advanced Industrial Reports</h4>
                        <p class="text-muted">Generate comprehensive reports on worker productivity, material yields, order turnaround times, and financial summaries on demand.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New Static Section: Factory Floor & Precision Highlights -->
    <section class="showcase-grid-section" id="highlights">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-primary bg-opacity-15 text-primary px-3 py-2 rounded-pill mb-2">Industrial Excellence</span>
                <h2 class="display-5 fw-bold">Engineered for Mortise Handles & Heavy Hardware</h2>
                <p class="lead text-muted">Discover how our platform integrates factory-floor precision with streamlined enterprise workflows.</p>
            </div>
            
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="hardware-card">
                        <div class="hardware-card-body">
                            <div class="hardware-card-icon">
                                <i class="bi bi-speedometer2"></i>
                            </div>
                            <h4 class="fw-bold mb-3">High-Speed Batch Scheduling</h4>
                            <p class="text-muted mb-0">Prioritize production lines dynamically. Allocate casting and finishing machines based on urgent dealer orders to maximize output.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="hardware-card">
                        <div class="hardware-card-body">
                            <div class="hardware-card-icon">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Rigorous Quality Assurance</h4>
                            <p class="text-muted mb-0">Embed quality control checkpoints at every manufacturing phase—from raw alloy casting to polished surface finishing.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="hardware-card">
                        <div class="hardware-card-body">
                            <div class="hardware-card-icon">
                                <i class="bi bi-truck"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Seamless Supply Chain Sync</h4>
                            <p class="text-muted mb-0">Connect your manufacturing unit directly with dispatch departments, minimizing transit delays and keeping your clients updated.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section bg-light py-5" id="faq">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 fw-bold">Frequently Asked Questions</h2>
                <p class="lead text-muted">Everything you need to know about our Hardware Production ERP</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Can this ERP handle precise raw material weight calculations?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Yes! The system is specifically engineered for hardware and mortise handle manufacturers to manage exact raw material metrics (such as handling inputs down to gram precision like 9.350 kg) to avoid material wastage and accurately price batches.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    How does the process tracking system work for factory floors?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Our process tracking module monitors every item from initial order confirmation through casting, polishing, assembly, and final packaging. Supervisors can update batch statuses instantly via desktop or mobile terminals.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border rounded-3 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed rounded-3" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Is it possible to manage multiple customers and wholesale distributors?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Absolutely. You can maintain complete client databases, view past order frequencies, assign customized wholesale price tiers, and handle dispatch notes easily.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-5" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="text-white mb-4"><i class="bi bi-tools me-2"></i>{{ env('APP_NAME') }}</h4>
                    <p class="text-white-50 mb-4">Empowering hardware manufacturers and production hubs with advanced ERP solutions for order management, customer tracking, and streamlined plant workflows.</p>
                </div>
                
                <div class="col-lg-4 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-white mb-4">ERP Modules</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#features" class="footer-link">Customer CRM</a></li>
                        <li class="mb-2"><a href="#features" class="footer-link">Order Management</a></li>
                        <li class="mb-2"><a href="#highlights" class="footer-link">Production & Process Highlights</a></li>
                        <li class="mb-2"><a href="#features" class="footer-link">Inventory & Stock Control</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <h5 class="text-white mb-4">Legal & Support</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="footer-link">Privacy Policy</a></li>
                        <li class="mb-2"><a href="#" class="footer-link">Terms of Service</a></li>
                        <li class="mb-2"><a href="#faq" class="footer-link">Help Center</a></li>
                    </ul>
                </div>
            </div>
            
            <hr class="mt-5 mb-4 bg-secondary opacity-25">
            
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-50 mb-0 small">&copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.</p>
                    <p class="text-white-50 mb-0 small">Powered By <a href="https://deltantec.com" class="text-decoration-none text-white-50 fw-medium">Deltan Technologies</a></p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <p class="small text-white-50 mb-0">Engineered for Industrial Excellence</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS Animation JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    
    <script>
        AOS.init({
            duration: 800,
            once: true
        });

        $(window).scroll(function () {
            if ($(window).scrollTop() > 50) {
                $('#navbar').addClass('scrolled');
            } else {
                $('#navbar').removeClass('scrolled');
            }
        });

        $(document).ready(function () {
            if ($(window).scrollTop() > 50) {
                $('#navbar').addClass('scrolled');
            }
        });
    </script>
</body>

</html>