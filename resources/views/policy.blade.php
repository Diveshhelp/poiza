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

    <!-- Privacy Policy Header -->
    <section class="privacy-header position-relative">
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up" data-aos-duration="1000">
                    <h1 class="display-4 fw-bold mb-4 text-gradient">Privacy Policy</h1>
                    <p class="lead mb-4 text-white-90">We are committed to protecting your privacy and personal data. This Privacy Policy explains how we collect, use, process, and store your information.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="privacy-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="privacy-toc sticky-lg-top" style="top: 120px;" data-aos="fade-right" data-aos-duration="1000">
                        <h4 class="mb-4">Table of Contents</h4>
                        <div class="last-updated">
                            <p class="mb-0"><i class="bi bi-calendar3 me-2"></i> Last Updated: May 07, 2025</p>
                        </div>
                        <a href="#section1">1. Information We Collect</a>
                        <a href="#section2">2. How We Use Your Information</a>
                        <a href="#section3">3. Information Sharing and Disclosure</a>
                        <a href="#section4">4. Data Storage and Security</a>
                        <a href="#section5">5. Your Rights and Choices</a>
                        <a href="#section6">6. Children's Privacy</a>
                        <a href="#section7">7. International Data Transfers</a>
                        <a href="#section8">8. Updates to This Policy</a>
                        <a href="#section9">9. Refund Policy and Payment Term</a>
                        <a href="#section10">10. Contact Us</a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="privacy-section" id="section1" data-aos="fade-up" data-aos-duration="1000">
                        <h3>1. Information We Collect</h3>
                        <p>{{ env('APP_NAME') }} collects information to provide better services to all our users. The types of information we collect include:</p>
                        
                        <h5 class="mt-4 mb-3">Information you provide to us</h5>
                        <p>When you sign up for a {{ env('APP_NAME') }} account, we collect personal information such as:</p>
                        <ul>
                            <li>Name, email address, phone number, and billing information</li>
                            <li>Profile information including your photo, job title, and organization</li>
                            <li>Content you upload, download, or otherwise access through our services</li>
                            <li>Communications you send to us, including support requests and feedback</li>
                        </ul>
                        
                        <h5 class="mt-4 mb-3">Information we collect automatically</h5>
                        <p>When you use our services, we automatically collect certain information, including:</p>
                        <ul>
                            <li>Device information (operating system, hardware model, unique device identifiers)</li>
                            <li>Log information (IP address, browser type, pages visited, referral URL)</li>
                            <li>Location information (derived from your IP address)</li>
                            <li>Usage information (features accessed, search queries, document interactions)</li>
                            <li>Cookies and similar technologies that collect information about your interactions</li>
                        </ul>
                    </div>
                    
                    <div class="privacy-section" id="section2" data-aos="fade-up" data-aos-duration="1000">
                        <h3>2. How We Use Your Information</h3>
                        <p>We use the information we collect for the following purposes:</p>
                        <ul>
                            <li><strong>Providing our services:</strong> To create and maintain your account, process transactions, and enable document storage, sharing, and management</li>
                            <li><strong>Service improvements:</strong> To improve, maintain, and develop new features for our platform</li>
                            <li><strong>Communication:</strong> To communicate with you about our services, respond to inquiries, and provide customer support</li>
                            <li><strong>Security:</strong> To detect, prevent, and address technical issues, fraud, and illegal activities</li>
                            <li><strong>Personalization:</strong> To customize content and recommend features that might interest you</li>
                            <li><strong>Analytics:</strong> To understand how our services are used and improve user experience</li>
                            <li><strong>Legal compliance:</strong> To comply with applicable laws, regulations, and legal processes</li>
                        </ul>
                    </div>
                    
                    <div class="privacy-section" id="section3" data-aos="fade-up" data-aos-duration="1000">
                        <h3>3. Information Sharing and Disclosure</h3>
                        <p>We do not sell your personal information. We may share your information in the following limited circumstances:</p>
                        <ul>
                            <li><strong>With your consent:</strong> When you explicitly authorize us to share your information</li>
                            <li><strong>For external processing:</strong> We provide information to trusted businesses or persons to process it for us, based on our instructions and in compliance with our Privacy Policy</li>
                            <li><strong>For legal reasons:</strong> We will share information if we have a good-faith belief that access, use, preservation, or disclosure of the information is reasonably necessary to comply with any applicable law, regulation, legal process, or enforceable governmental request</li>
                            <li><strong>During business transfers:</strong> If {{ env('APP_NAME') }} is involved in a merger, acquisition, or asset sale, we will continue to ensure the confidentiality of any personal information and give affected users notice before personal information is transferred</li>
                        </ul>
                    </div>
                    
                    <div class="privacy-section" id="section4" data-aos="fade-up" data-aos-duration="1000">
                        <h3>4. Data Storage and Security</h3>
                        <p>{{ env('APP_NAME') }} takes data security seriously. We implement appropriate technical and organizational measures to protect your information:</p>
                        <ul>
                            <li>All data is encrypted both in transit and at rest using industry-standard encryption protocols</li>
                            <li>We maintain physical, electronic, and procedural safeguards in connection with the collection, storage, and disclosure of personal information</li>
                            <li>Access to personal information is restricted to employees, contractors, and agents who need to know that information to process it for us</li>
                            <li>Regular security assessments and penetration testing to identify and address potential vulnerabilities</li>
                            <li>Comprehensive disaster recovery and business continuity plans to ensure data integrity</li>
                        </ul>
                        <p>While we implement safeguards designed to protect your information, no security system is impenetrable. We cannot guarantee the security of your data transmitted to our site; any transmission is at your own risk.</p>
                    </div>
                    
                    <div class="privacy-section" id="section5" data-aos="fade-up" data-aos-duration="1000">
                        <h3>5. Your Rights and Choices</h3>
                        <p>Depending on your location, you may have certain rights regarding your personal information:</p>
                        <ul>
                            <li><strong>Access:</strong> Request information about the personal data we hold about you</li>
                            <li><strong>Correction:</strong> Request that we correct inaccurate or incomplete information</li>
                            <li><strong>Deletion:</strong> Request that we delete your personal information</li>
                            <li><strong>Restriction:</strong> Request that we restrict the processing of your information</li>
                            <li><strong>Data portability:</strong> Request a copy of your personal data in a structured, machine-readable format</li>
                            <li><strong>Objection:</strong> Object to our processing of your personal information</li>
                            <li><strong>Withdrawal of consent:</strong> Withdraw consent at any time where we relied on your consent to process your information</li>
                        </ul>
                        <p>To exercise these rights, please contact us using the information provided in the "Contact Us" section. We will respond to your request within the timeframe required by applicable law.</p>
                    </div>
                    
                    <div class="privacy-section" id="section6" data-aos="fade-up" data-aos-duration="1000">
                        <h3>6. Children's Privacy</h3>
                        <p>{{ env('APP_NAME') }} services are not directed to individuals under the age of 16. We do not knowingly collect personal information from children. If we become aware that a child has provided us with personal information, we will take steps to delete such information. If you become aware that a child has provided us with personal information, please contact us.</p>
                    </div>
                    
                    <div class="privacy-section" id="section7" data-aos="fade-up" data-aos-duration="1000">
                        <h3>7. International Data Transfers</h3>
                        <p>{{ env('APP_NAME') }} operates globally, and your information may be transferred to, stored, and processed in countries other than the one in which you reside. These countries may have data protection laws that are different from those of your country. We have taken appropriate safeguards to require that your personal information will remain protected in accordance with this Privacy Policy when transferred internationally.</p>
                    </div>
                    
                    <div class="privacy-section" id="section8" data-aos="fade-up" data-aos-duration="1000">
                        <h3>8. Updates to This Policy</h3>
                        <p>We may update this Privacy Policy from time to time in response to changing legal, technical, or business developments. When we update our Privacy Policy, we will take appropriate measures to inform you, consistent with the significance of the changes we make. We will obtain your consent to any material Privacy Policy changes if and where this is required by applicable data protection laws.</p>
                        <p>We encourage you to periodically review this page for the latest information on our privacy practices.</p>
                    </div>
                    
                    
                    <div class="privacy-section" id="section9" data-aos="fade-up" data-aos-duration="1000">
                        <h3>9. Refund Policy and Payment Terms</h3>
                        <p>{{ env('APP_NAME') }} operates on a subscription-based model. By using our services, you agree to the following payment and refund terms:</p>
                        
                        <h5 class="mt-4 mb-3">Subscription Payments</h5>
                        <p>Our subscription services operate on the following terms:</p>
                        <ul>
                            <li>All subscriptions are billed in advance according to the billing cycle you select (monthly, quarterly, or annual)</li>
                            <li>Payment is due within 7 days of invoice date</li>
                            <li>We accept payments via credit card, debit card, and selected digital payment methods</li>
                            <li>Subscription fees are subject to change with 30 days' prior notice</li>
                            <li>Failure to make timely payments may result in service interruption or termination</li>
                            <li>Late payments may be subject to a 1.5% monthly interest charge (or the maximum rate permitted by law)</li>
                        </ul>
                        
                        <h5 class="mt-4 mb-3">Automatic Renewal</h5>
                        <p>All subscriptions automatically renew at the end of the subscription period unless canceled. You may cancel your subscription at any time by accessing your account settings or contacting our support team.</p>
                        
                        <h5 class="mt-4 mb-3">Refund Policy</h5>
                        <p>Our refund policy for subscription services is as follows:</p>
                        <ul>
                            <li><strong>14-Day Money Back Guarantee:</strong> New subscribers may request a full refund within 14 days of initial subscription purchase if they are not satisfied with our services</li>
                            <li><strong>Pro-rated Refunds:</strong> After the initial 14-day period, refunds for unused subscription time may be issued on a pro-rated basis at our discretion</li>
                            <li><strong>No Refunds for Partial Use:</strong> We do not provide refunds for partial use of subscription services within a billing period</li>
                            <li><strong>Service Interruptions:</strong> Refunds may be issued for extended service outages or technical issues at our discretion</li>
                            <li><strong>How to Request a Refund:</strong> All refund requests must be submitted in writing to <a href="mailto:support@docmey.com" class="text-primary">support@docmey.com</a></li>
                        </ul>
                        
                        <h5 class="mt-4 mb-3">Subscription Cancellation</h5>
                        <p>You may cancel your subscription at any time:</p>
                        <ul>
                            <li>Cancellation will take effect at the end of your current billing cycle</li>
                            <li>No partial refunds will be issued for unused time in the current billing period</li>
                            <li>Upon cancellation, you will retain access to the service until the end of your paid subscription period</li>
                            <li>Data retention after cancellation is subject to our Data Storage and Security policy</li>
                        </ul>
                        
                        <h5 class="mt-4 mb-3">Payment Processing</h5>
                        <p>Payment processing is handled through secure third-party payment processors. Your payment information is subject to the privacy policy and terms of these third-party services.</p>
                        
                        <h5 class="mt-4 mb-3">Tax</h5>
                        <p>Subscription fees are exclusive of all taxes, levies, or duties imposed by taxing authorities. You are responsible for payment of all applicable taxes.</p>
                        
                        <h5 class="mt-4 mb-3">Currency</h5>
                        <p>All fees are payable in Indian Rupees (INR) unless otherwise specified.</p>
                        
                        <p class="mt-4"><strong>Last Updated:</strong> May 7, 2025</p>
                    </div>
                    <div class="privacy-section" id="section10" data-aos="fade-up" data-aos-duration="1000">
                        <h3>10. Contact Us</h3>
                        <p>If you have any questions or concerns about this Privacy Policy or our data practices, please contact us:</p>
                        <div class="row mt-4">
                            <div class="col-md-6 mb-4">
                                <div class="privacy-card">
                                    <div class="privacy-icon">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <h5>Email</h5>
                                    <p>support@docmey.com</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="privacy-card">
                                    <div class="privacy-icon">
                                        <i class="bi bi-geo-alt"></i>
                                    </div>
                                    <h5>Address</h5>
                                    <p>1023, RK Empire <br>150 Feet Ring Road,<br> Rajkot, Gujarat 360004</p>
                                </div>
                            </div>
                        </div>
                        <p>Our Data Protection Officer can be contacted at support@docmey.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

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