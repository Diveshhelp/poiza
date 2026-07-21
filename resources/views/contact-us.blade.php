<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <title>{{ env('APP_NAME') }} - Privacy Policy</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- AOS Animation Library -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-BQVE9EPB0Z');
    </script>
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0F8389;
            --secondary-color: #06565A;
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

        /* Privacy Policy Header */
        .privacy-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 140px 0 80px;
            position: relative;
        }

        .privacy-header::before {
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

        .privacy-content {
            padding: 80px 0;
        }

        .privacy-section {
            margin-bottom: 50px;
        }

        .privacy-section h3 {
            color: var(--primary-color);
            margin-bottom: 25px;
            position: relative;
            padding-bottom: 15px;
        }

        .privacy-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 70px;
            height: 3px;
            background-color: var(--primary-color);
        }

        .footer {
            background-color: var(--primary-color);
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
            background-color: var(--secondary-color);
            transform: translateY(-3px);
        }

        .text-gradient {
            background: linear-gradient(135deg, #ffffff 0%, rgba(255, 255, 255, 0.8) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
        }

        .privacy-card {
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            background-color: white;
        }

        .privacy-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .privacy-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        .privacy-toc {
            background-color: var(--light-color);
            border-radius: 15px;
            padding: 30px;
        }

        .privacy-toc a {
            color: var(--dark-color);
            text-decoration: none;
            display: block;
            padding: 8px 0;
            transition: all 0.3s ease;
        }

        .privacy-toc a:hover {
            color: var(--primary-color);
            transform: translateX(5px);
        }

        .last-updated {
            background-color: rgba(15, 131, 137, 0.1);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 40px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top" id="navbar">
        <div class="container">
        <a class="navbar-brand" href="#">
                <img src="{{ asset('DOCMEY_LOGO.png') }}" alt="Logo" class="w-22"  style="width: 132px;">
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
                <a href="{{ route('login') }}" class="nav-link d-lg-block">Log In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary rounded-pill px-4 py-2">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contact Header -->
    <section class="privacy-header position-relative">
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up" data-aos-duration="1000">
                    <h1 class="display-4 fw-bold mb-4 text-gradient">Contact Us</h1>
                    <p class="lead mb-4 text-white-90">Have questions or need assistance? Our team is here to help you
                        get the most out of {{ env('APP_NAME') }}.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="privacy-content">
        <div class="container">
            <div class="row g-5">
                <!-- Contact Information Cards -->
                <div class="col-lg-5" >
                    <div class="privacy-card mb-4 p-4 shadow-sm rounded-3 border-top border-primary border-3" data-aos="fade-left" data-aos-duration="1000">
                        <div class="privacy-icon mb-3 d-inline-block bg-light p-3 rounded-circle">
                            <i class="bi bi-headset fs-2 text-primary"></i>
                        </div>
                        <h4 class="mb-3 fw-bold">We're Here To Help</h4>
                        <p class="text-muted">Our dedicated support team is available to assist you weekdays from <span class="fw-semibold">9:00 AM to 7:30 PM IST</span>.</p>
                        
                        <div class="contact-info mt-4">
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-geo-alt-fill me-3 text-primary"></i>
                                <div>
                                    <h6 class="mb-1 small text-uppercase">Visit Us</h6>
                                    <p class="mb-0 small">1023, RK Empire 150 Feet Ring Road, Rajkot, Gujarat 360004</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mb-3">
                                <i class="bi bi-envelope-fill me-3 text-primary"></i>
                                <div>
                                    <h6 class="mb-1 small text-uppercase">Email Us</h6>
                                    <a href="mailto:info@deltantec.com" class="text-primary text-decoration-none">info@deltantec.com</a><br>
                                    <a href="mailto:support@docmey.com" class="text-primary text-decoration-none">support@docmey.com</a>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <i class="bi bi-telephone-fill me-3 text-primary"></i>
                                <div>
                                    <h6 class="mb-1 small text-uppercase">Call Us</h6>
                                    <a href="tel:+919429895795" class="text-primary text-decoration-none fw-semibold">+91 94298 95795</a>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="col-lg-7" data-aos="fade-right" data-aos-duration="1000">
                    <div class="privacy-card">
                        <h3 class="mb-4">Send Us a Message</h3>
                        <form id="contactForm">
                        @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstName" class="form-label">First Name*</label>
                                    <input type="text" class="form-control rounded-pill" id="firstName" name="firstName"
                                        placeholder="Enter your first name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastName" class="form-label">Last Name*</label>
                                    <input type="text" class="form-control rounded-pill" id="lastName" name="lastName"
                                        placeholder="Enter your last name" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address*</label>
                                <input type="email" class="form-control rounded-pill" id="email" name="email"
                                    placeholder="Enter your email address" required>
                            </div>

                            <div class="mb-3">
                                <label for="company" class="form-label">Company</label>
                                <input type="text" class="form-control rounded-pill" id="company" name="company"
                                    placeholder="Enter your company name">
                            </div>

                            <div class="mb-3">
                                <label for="inquiryType" class="form-label">Inquiry Type*</label>
                                <select class="form-select rounded-pill" id="inquiryType" name="inquiryType" required>
                                    <option value="" selected disabled>Select an inquiry type</option>
                                    <option value="support">Technical Support</option>
                                    <option value="billing">Billing Question</option>
                                    <option value="sales">Sales Inquiry</option>
                                    <option value="partnership">Partnership Opportunity</option>
                                    <option value="feedback">Feedback</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="message" class="form-label">Message*</label>
                                <textarea class="form-control" id="message" rows="5" name="message"
                                    placeholder="Please provide details about your inquiry..." required></textarea>
                            </div>

                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="privacyConsent" required>
                                <label class="form-check-label" for="privacyConsent">
                                    I agree to the processing of my personal data according to the <a href="{{ route('privacy-policy') }}"
                                        class="text-primary">Privacy Policy</a>.*
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary rounded-pill px-4 py-2 px-5">
                                <i class="bi bi-send me-2"></i>Send Message
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Custom script for form handling -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
            const contactForm = document.getElementById('contactForm');
            
            if (contactForm) {
                contactForm.addEventListener('submit', function(event) {
                    event.preventDefault();
                    
                    // Create form data object
                    const formData = new FormData(contactForm);
                    
                    // Show loading state
                    const submitBtn = contactForm.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Sending...';
                    
                    // Send AJAX request to Laravel backend
                    fetch('/api/contact', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Handle success
                        const formContainer = contactForm.parentElement;
                        formContainer.innerHTML = `
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <i class="bi bi-check-circle text-success" style="font-size: 5rem;"></i>
                                </div>
                                <h3>Thank You!</h3>
                                <p class="lead mb-4">Your message has been successfully sent. We'll get back to you as soon as possible.</p>
                                <p>Reference ID: ${data.reference_id || ('DV-' + Date.now().toString().slice(-8))}</p>
                            </div>
                        `;
                    })
                    .catch(error => {
                        // Handle error
                        console.error('Error:', error);
                        
                        // Reset button state
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalBtnText;
                        
                        // Show error message
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'alert alert-danger mt-3';
                        errorDiv.role = 'alert';
                        errorDiv.innerHTML = 'There was a problem submitting your form. Please try again.';
                        
                        // Insert error message after the form
                        contactForm.insertAdjacentElement('beforeend', errorDiv);
                        
                        // Remove error message after 5 seconds
                        setTimeout(() => {
                            errorDiv.remove();
                        }, 5000);
                    });
                });
            }
        });
    </script>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="text-white mb-4"><i class="bi bi-folder2-open me-2"></i>{{ env('APP_NAME') }}</h4>
                    <p class="text-white-50 mb-4">Transforming how businesses manage, secure, and collaborate on
                        documents in the digital age.</p>
                </div>
                
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="text-white mb-4">Support</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('contact-us') }}" class="footer-link">Contact Us</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="text-white mb-4">Legal</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('privacy-policy') }}" class="footer-link">Privacy Policy</a></li>
                        <li class="mb-2"><a href="{{ route('terms-conditions') }}" class="footer-link">Terms of Service</a></li>
                    </ul>
                </div>
               <div class="col-lg-3 col-md-4">
                    <h5 class="text-white mb-4">Contact Information</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="bi bi-geo-alt me-2"></i>1023, RK Empire 150 Feet Ring Road, Rajkot, Gujarat 360004</li>
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i><a href="tel:+919429895795" class="footer-link"> +91 94298 95795</a></li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i><a href="mailto:info@deltantec.com" class="footer-link">info@deltantec.com</a></li>
                        <li class="mb-2"><i class="bi bi-envelope me-2"></i><a href="mailto:support@docmey.com" class="footer-link">support@docmey.com</a></li>
                    </ul>
                </div>
                
            </div>
            <hr class="mt-5 mb-4 bg-secondary">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="text-white-50 mb-0">&copy; {{ date('Y') }} {{ env('APP_NAME') }}. All rights reserved.</p>
                    <p class="text-white-50 mb-0">Powered By <a href="https://deltantec.com" class="text-decoration-none text-white-50 fw-medium">Deltan Technologies</a></p>
                </div>
                <div class="col-md-6 text-center text-md-end mt-3 mt-md-0">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-light dropdown-toggle rounded-pill" type="button">
                            <i class="bi bi-globe me-1"></i> English
                        </button>
                        <p class="text-2xs dark:text-zinc-500 mt-2 sm:mt-0">Made with ❤️ for our valued customers</p>

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