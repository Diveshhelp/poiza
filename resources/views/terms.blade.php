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

    <title>{{env('APP_NAME')}} - Terms & Conditions</title>
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

    <!-- Terms & Conditions Header -->
    <section class="privacy-header position-relative">
        <div class="container position-relative">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center" data-aos="fade-up" data-aos-duration="1000">
                    <h1 class="display-4 fw-bold mb-4 text-gradient">Terms & Conditions</h1>
                    <p class="lead mb-4 text-white-90">Please read these Terms and Conditions carefully before using our
                        {{env('APP_NAME')}} services. These terms govern your use of our platform and represent an agreement
                        between you and {{env('APP_NAME')}}.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Privacy Policy Content -->
    <section class="privacy-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="last-updated" data-aos="fade-up">
                        <p class="mb-0"><strong>Last Updated:</strong> May 07, 2025</p>
                    </div>

                    <div class="privacy-toc mb-5" data-aos="fade-up">
                        <h4 class="mb-4">Table of Contents</h4>
                        <ol class="list-unstyled">
                            <li><a href="#agreement">1. Agreement to Terms</a></li>
                            <li><a href="#definitions">2. Definitions</a></li>
                            <li><a href="#account">3. User Accounts</a></li>
                            <li><a href="#services">4. Services and Subscription</a></li>
                            <li><a href="#payment">5. Payment Terms</a></li>
                            <li><a href="#content">6. User Content</a></li>
                            <li><a href="#intellectual">7. Intellectual Property</a></li>
                            <li><a href="#privacy">8. Privacy and Data Protection</a></li>
                            <li><a href="#prohibited">9. Prohibited Uses</a></li>
                            <li><a href="#warranty">10. Disclaimer of Warranties</a></li>
                            <li><a href="#liability">11. Limitation of Liability</a></li>
                            <li><a href="#indemnity">12. Indemnification</a></li>
                            <li><a href="#termination">13. Term and Termination</a></li>
                            <li><a href="#modifications">14. Modifications to Terms</a></li>
                            <li><a href="#governing">15. Governing Law</a></li>
                            <li><a href="#dispute">16. Dispute Resolution</a></li>
                            <li><a href="#misc">17. Miscellaneous Provisions</a></li>
                        </ol>
                    </div>

                    <div class="privacy-section" id="agreement" data-aos="fade-up">
                        <h3>1. Agreement to Terms</h3>
                        <p>By accessing or using {{env('APP_NAME')}}'s services, website, applications, or any other content or
                            services provided by {{env('APP_NAME')}} (collectively, the "Services"), you agree to be bound by
                            these Terms and Conditions (the "Terms"). These Terms constitute a legally binding agreement
                            between you and {{env('APP_NAME')}} Inc. ("{{env('APP_NAME')}}," "we," "us," or "our").</p>
                        <p>If you do not agree to these Terms, you must not access or use our Services. We recommend
                            that you print a copy of these Terms for future reference.</p>
                    </div>

                    <div class="privacy-section" id="definitions" data-aos="fade-up">
                        <h3>2. Definitions</h3>
                        <p>Throughout these Terms, the following definitions apply:</p>
                        <ul>
                            <li><strong>"User"</strong> refers to any individual or entity that accesses or uses the
                                Services.</li>
                            <li><strong>"Subscriber"</strong> refers to Users who have registered for a paid
                                subscription to the Services.</li>
                            <li><strong>"Content"</strong> refers to any information, data, text, software, images,
                                audio, video, or other materials that are uploaded, stored, or processed through the
                                Services.</li>
                            <li><strong>"User Content"</strong> refers to any Content that is submitted, uploaded,
                                transmitted, or otherwise made available by Users through the Services.</li>
                        </ul>
                    </div>

                    <div class="privacy-section" id="account" data-aos="fade-up">
                        <h3>3. User Accounts</h3>
                        <p><strong>3.1 Account Creation.</strong> To access certain features of the Services, you must
                            create an account. You agree to provide accurate, current, and complete information during
                            the registration process and to update such information to keep it accurate, current, and
                            complete.</p>

                        <p><strong>3.2 Account Security.</strong> You are responsible for safeguarding the password and
                            access credentials that you use to access the Services. You agree not to disclose your
                            password to any third party and to take sole responsibility for any activities or actions
                            under your account, whether or not you have authorized such activities or actions.</p>

                        <p><strong>3.3 Account Types.</strong> {{env('APP_NAME')}} offers various account types, including
                            individual, team, and enterprise accounts. The specific features, limitations, and pricing
                            for each account type are described on our website and may be updated from time to time.</p>
                    </div>

                    <div class="privacy-section" id="services" data-aos="fade-up">
                        <h3>4. Services and Subscription</h3>
                        <p><strong>4.1 Services Description.</strong> {{env('APP_NAME')}} provides a document management system
                            that allows Users to organize, secure, store, share, and access documents. The specific
                            functionalities and features of the Services may vary based on the subscription plan
                            selected.</p>

                        <p><strong>4.2 Service Modifications.</strong> {{env('APP_NAME')}} reserves the right to modify, suspend,
                            or discontinue any part of the Services at any time, with or without notice. {{env('APP_NAME')}} shall
                            not be liable to you or any third party for any modification, suspension, or discontinuation
                            of the Services.</p>

                        <p><strong>4.3 Subscription Plans.</strong> {{env('APP_NAME')}} offers various subscription plans with
                            different features, storage capacities, and pricing. You agree to pay the applicable fees
                            for the subscription plan you select. Subscription plans may be modified, and new plans may
                            be introduced at any time.</p>

                        <p><strong>4.4 Free Trial.</strong> {{env('APP_NAME')}} may offer a free trial of its Services. At the end
                            of the free trial period, you will be automatically charged the applicable subscription fee
                            unless you cancel your subscription before the end of the trial period.</p>
                    </div>

                    <div class="privacy-section" id="payment" data-aos="fade-up">
                        <h3>5. Payment Terms</h3>
                        <p><strong>5.1 Fees.</strong> You agree to pay all fees specified for the subscription plan you
                            select. All fees are in USD unless otherwise specified. Fees are non-refundable except as
                            expressly provided in these Terms.</p>

                        <p><strong>5.2 Billing Cycle.</strong> Subscription fees are billed in advance on either a
                            monthly or annual basis, depending on the subscription plan you select. Your subscription
                            will automatically renew at the end of each billing cycle unless you cancel it prior to the
                            renewal date.</p>

                        <p><strong>5.3 Taxes.</strong> All fees stated are exclusive of any applicable taxes (such as
                            sales tax, value-added tax, or goods and services tax), which you are responsible for
                            paying.</p>

                        <p><strong>5.4 Late Payments.</strong> If your payment is declined or fails for any reason, we
                            may suspend your access to the Services until payment is successfully processed. For late
                            payments, we reserve the right to charge a late fee of 1.5% per month (or the maximum rate
                            permitted by law, if less).</p>

                        <p><strong>5.5 Price Changes.</strong> {{env('APP_NAME')}} reserves the right to change its prices at any
                            time. Price changes for existing subscriptions will take effect at the start of the next
                            billing cycle. We will provide you with reasonable notice of any price changes.</p>
                    </div>

                    <div class="privacy-section" id="content" data-aos="fade-up">
                        <h3>6. User Content</h3>
                        <p><strong>6.1 Ownership.</strong> You retain all ownership rights to your User Content. By
                            uploading, storing, or processing User Content through the Services, you grant {{env('APP_NAME')}} a
                            worldwide, non-exclusive, royalty-free license to host, store, transfer, display, perform,
                            reproduce, and modify your User Content solely for the purpose of providing the Services to
                            you.</p>

                        <p><strong>6.2 Content Responsibility.</strong> You are solely responsible for all User Content
                            that you upload, post, email, transmit, or otherwise make available through the Services.
                            You represent and warrant that:</p>
                        <ul>
                            <li>You either own or have the necessary rights to use and authorize {{env('APP_NAME')}} to use your
                                User Content as described in these Terms;</li>
                            <li>Your User Content does not violate any third-party rights, including intellectual
                                property rights and privacy rights;</li>
                            <li>Your User Content complies with all applicable laws and regulations.</li>
                        </ul>

                        <p><strong>6.3 Content Limitations.</strong> You agree not to upload, store, or process any User
                            Content that:</p>
                        <ul>
                            <li>Is unlawful, harmful, threatening, abusive, harassing, defamatory, vulgar, obscene,
                                invasive of another's privacy, or otherwise objectionable;</li>
                            <li>Contains viruses, malware, or other harmful code;</li>
                            <li>Infringes upon or violates any third-party rights;</li>
                            <li>Violates any applicable laws or regulations.</li>
                        </ul>

                        <p><strong>6.4 Content Removal.</strong> {{env('APP_NAME')}} reserves the right to access, review, screen,
                            and delete any User Content at any time and for any reason, including if we believe the User
                            Content violates these Terms or applicable laws. We have no obligation to monitor User
                            Content but may do so as required by law or to operate the Services effectively.</p>
                    </div>

                    <div class="privacy-section" id="intellectual" data-aos="fade-up">
                        <h3>7. Intellectual Property</h3>
                        <p><strong>7.1 {{env('APP_NAME')}} IP.</strong> The Services, including all content, features,
                            functionality, software, algorithms, design, and appearance, are owned by {{env('APP_NAME')}} or its
                            licensors and are protected by copyright, trademark, patent, and other intellectual property
                            laws. These Terms do not grant you any rights to use {{env('APP_NAME')}}'s trademarks, logos, domain
                            names, or other brand features.</p>

                        <p><strong>7.2 Feedback.</strong> If you provide any feedback, suggestions, or ideas regarding
                            the Services ("Feedback"), you grant {{env('APP_NAME')}} an irrevocable, perpetual, worldwide,
                            royalty-free license to use, modify, and incorporate the Feedback in any manner without
                            restriction or attribution.</p>

                        <p><strong>7.3 Open Source.</strong> The Services may include certain open-source software
                            components that are subject to open-source licenses. Nothing in these Terms will limit your
                            rights under, or grant you rights that supersede, the terms of any applicable open-source
                            license.</p>
                    </div>

                    <div class="privacy-section" id="privacy" data-aos="fade-up">
                        <h3>8. Privacy and Data Protection</h3>
                        <p><strong>8.1 Privacy Policy.</strong> Our <a href="{{ route('privacy-policy') }}" class="link-primary">Privacy Policy</a>
                            describes how we collect, use, and disclose information about you and your use of the
                            Services. By using the Services, you agree to our collection, use, and disclosure of
                            information as described in the Privacy Policy.</p>

                        <p><strong>8.2 Data Security.</strong> {{env('APP_NAME')}} implements reasonable security measures to
                            protect your data. However, no method of transmission or storage is 100% secure, and we
                            cannot guarantee absolute security. You understand and agree that you use the Services at
                            your own risk.</p>

                        <p><strong>8.3 GDPR Compliance.</strong> If you are located in the European Economic Area (EEA),
                            the United Kingdom, or Switzerland, additional privacy terms may apply to our processing of
                            your personal data as outlined in our Privacy Policy.</p>

                        <p><strong>8.4 Data Processing Agreements.</strong> For business customers who require a Data
                            Processing Agreement (DPA) for compliance with applicable data protection laws, {{env('APP_NAME')}}
                            offers a standard DPA that can be requested through our customer support.</p>
                    </div>

                    <div class="privacy-section" id="prohibited" data-aos="fade-up">
                        <h3>9. Prohibited Uses</h3>
                        <p>You agree not to use the Services to:</p>
                        <ul>
                            <li>Violate any applicable laws, regulations, or third-party rights;</li>
                            <li>Transmit any material that contains viruses, Trojan horses, worms, or any other harmful
                                or destructive code;</li>
                            <li>Attempt to gain unauthorized access to the Services, other users' accounts, or
                                {{env('APP_NAME')}}'s systems or networks;</li>
                            <li>Interfere with or disrupt the Services or servers or networks connected to the Services;
                            </li>
                            <li>Engage in any activity that could disable, overburden, or impair the proper functioning
                                of the Services;</li>
                            <li>Use any robot, spider, or other automated device to access the Services except for
                                search engines and public archives;</li>
                            <li>Use the Services to store, process, or transmit data that is regulated under specific
                                compliance standards (such as HIPAA, PCI-DSS) unless you have purchased a subscription
                                plan that specifically supports such compliance requirements.</li>
                        </ul>
                    </div>

                    <div class="privacy-section" id="warranty" data-aos="fade-up">
                        <h3>10. Disclaimer of Warranties</h3>
                        <p>THE SERVICES ARE PROVIDED "AS IS" AND "AS AVAILABLE" WITHOUT WARRANTIES OF ANY KIND, EITHER
                            EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY,
                            FITNESS FOR A PARTICULAR PURPOSE, AND NON-INFRINGEMENT.</p>

                        <p>{{env('APP_NAME')}} DOES NOT WARRANT THAT THE SERVICES WILL BE UNINTERRUPTED OR ERROR-FREE, THAT
                            DEFECTS WILL BE CORRECTED, OR THAT THE SERVICES OR THE SERVERS THAT MAKE THEM AVAILABLE ARE
                            FREE OF VIRUSES OR OTHER HARMFUL COMPONENTS.</p>

                        <p>{{env('APP_NAME')}} DOES NOT WARRANT OR MAKE ANY REPRESENTATIONS REGARDING THE USE OR THE RESULTS OF
                            THE USE OF THE SERVICES IN TERMS OF THEIR CORRECTNESS, ACCURACY, RELIABILITY, OR OTHERWISE.
                        </p>
                    </div>

                    <div class="privacy-section" id="liability" data-aos="fade-up">
                        <h3>11. Limitation of Liability</h3>
                        <p>TO THE MAXIMUM EXTENT PERMITTED BY APPLICABLE LAW, {{env('APP_NAME')}}, ITS AFFILIATES, OFFICERS,
                            DIRECTORS, EMPLOYEES, AGENTS, SUPPLIERS, AND LICENSORS SHALL NOT BE LIABLE FOR ANY INDIRECT,
                            INCIDENTAL, SPECIAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, INCLUDING WITHOUT LIMITATION, LOSS
                            OF PROFITS, DATA, USE, GOODWILL, OR OTHER INTANGIBLE LOSSES, RESULTING FROM:</p>
                        <ul>
                            <li>YOUR ACCESS TO OR USE OF OR INABILITY TO ACCESS OR USE THE SERVICES;</li>
                            <li>ANY CONDUCT OR CONTENT OF ANY THIRD PARTY ON THE SERVICES;</li>
                            <li>ANY CONTENT OBTAINED FROM THE SERVICES; AND</li>
                            <li>UNAUTHORIZED ACCESS, USE, OR ALTERATION OF YOUR TRANSMISSIONS OR CONTENT.</li>
                        </ul>

                        <p>IN NO EVENT SHALL {{env('APP_NAME')}}'S TOTAL LIABILITY TO YOU FOR ALL CLAIMS RELATED TO THE SERVICES
                            EXCEED THE AMOUNT PAID BY YOU TO {{env('APP_NAME')}} DURING THE TWELVE (12) MONTHS IMMEDIATELY
                            PRECEDING THE EVENT GIVING RISE TO THE CLAIM OR $100, WHICHEVER IS GREATER.</p>

                        <p>THE LIMITATIONS OF THIS SECTION SHALL APPLY TO ANY THEORY OF LIABILITY, WHETHER BASED ON
                            WARRANTY, CONTRACT, STATUTE, TORT (INCLUDING NEGLIGENCE) OR OTHERWISE, AND WHETHER OR NOT
                            {{env('APP_NAME')}} HAS BEEN INFORMED OF THE POSSIBILITY OF ANY SUCH DAMAGE.</p>
                    </div>

                    <div class="privacy-section" id="indemnity" data-aos="fade-up">
                        <h3>12. Indemnification</h3>
                        <p>You agree to defend, indemnify, and hold harmless {{env('APP_NAME')}}, its affiliates, officers,
                            directors, employees, agents, licensors, and suppliers from and against any claims,
                            liabilities, damages, judgments, awards, losses, costs, expenses, or fees (including
                            reasonable attorneys' fees) arising out of or relating to:</p>
                        <ul>
                            <li>Your violation of these Terms;</li>
                            <li>Your User Content;</li>
                            <li>Your use of the Services.</li>
                        </ul>
                    </div>

                    <div class="privacy-section" id="termination" data-aos="fade-up">
                        <h3>13. Term and Termination</h3>
                        <p><strong>13.1 Term.</strong> These Terms shall remain in full force and effect while you use
                            the Services or maintain an account.</p>

                        <p><strong>13.2 Termination by You.</strong> You may terminate your account and these Terms at
                            any time by following the cancellation procedures in your account settings or by contacting
                            our customer support. Termination will be effective at the end of your current billing
                            cycle, and you will not receive a refund for any fees already paid.</p>

                        <p><strong>13.3 Termination by {{env('APP_NAME')}}.</strong> {{env('APP_NAME')}} may terminate or suspend your
                            account and these Terms at any time, with or without notice, for any reason, including if
                            {{env('APP_NAME')}} believes that you have violated these Terms. Upon termination, your right to use
                            the Services will immediately cease.</p>

                        <p><strong>13.4 Effects of Termination.</strong> Upon termination:</p>
                        <ul>
                            <li>Your access to the Services will be terminated;</li>
                            <li>Your User Content may be deleted from our active systems within a reasonable time
                                period;</li>
                            <li>Provisions of these Terms that by their nature should survive termination shall survive,
                                including ownership provisions, warranty disclaimers, indemnification, and limitations
                                of liability.</li>
                        </ul>

                        <p><strong>13.5 Data Retention and Export.</strong> Following termination, we may retain your
                            User Content for a commercially reasonable period of time for backup, archival, or audit
                            purposes. You may export your data prior to account termination through the export tools
                            provided in the Services.</p>
                    </div>

                    <div class="privacy-section" id="modifications" data-aos="fade-up">
                        <h3>14. Modifications to Terms</h3>
                        <p>{{env('APP_NAME')}} reserves the right to modify these Terms at any time. We will provide notice of
                            material changes by posting the updated Terms on our website or by sending you an email or
                            notification within the Services. Your continued use of the Services after the changes take
                            effect constitutes your acceptance of the revised Terms.</p>
                    </div>

                    <div class="privacy-section" id="governing" data-aos="fade-up">
                        <h3>15. Governing Law</h3>
                        <p>These Terms shall be governed by and construed in accordance with the laws of the State of
                            Delaware, without regard to its conflict of law principles. The United Nations Convention on
                            Contracts for the International Sale of Goods shall not apply to these Terms.</p>
                    </div>

                    <div class="privacy-section" id="dispute" data-aos="fade-up">
                        <h3>16. Dispute Resolution</h3>
                        <p><strong>16.1 Informal Resolution.</strong> Before filing a claim against {{env('APP_NAME')}}, you agree
                            to try to resolve the dispute informally by contacting us at legal@{{env('APP_NAME')}}.com. We'll try
                            to resolve the dispute informally by contacting you through email. If the dispute is not
                            resolved within 30 days after submission, you or {{env('APP_NAME')}} may proceed with a formal
                            proceeding.</p>

                        <p><strong>16.2 Arbitration.</strong> Except for disputes that qualify for small claims court,
                            all disputes arising out of or related to these Terms or the Services shall be resolved
                            through binding arbitration under the rules of the American Arbitration Association. The
                            arbitration will be conducted in English and will take place in San Francisco, California,
                            or another location mutually agreed upon by the parties. The arbitrator's award shall be
                            binding and may be entered as a judgment in any court of competent jurisdiction.</p>

                        <p><strong>16.3 Waiver of Class Actions.</strong> You agree that any dispute resolution
                            proceedings will be conducted only on an individual basis and not in a class, consolidated,
                            or representative action. If for any reason a claim proceeds in court rather than in
                            arbitration, you and {{env('APP_NAME')}} waive any right to a jury trial.</p>

                        <p><strong>16.4 Exceptions.</strong> Nothing in these Terms will prevent either party from
                            seeking injunctive or other equitable relief for intellectual property infringement or other
                            misuse of intellectual property rights.</p>
                    </div>

                    <div class="privacy-section" id="misc" data-aos="fade-up">
                        <h3>17. Miscellaneous Provisions</h3>
                        <p><strong>17.1 Entire Agreement.</strong> These Terms, together with the Privacy Policy and any
                            additional terms referenced herein, constitute the entire agreement between you and
                            {{env('APP_NAME')}} regarding the Services and supersede all prior agreements and understandings.</p>

                        <p><strong>17.2 Waiver.</strong> {{env('APP_NAME')}}'s failure to enforce any right or provision of these
                            Terms will not be considered a waiver of such right or provision. The waiver of any such
                            right or provision will be effective only if in writing and signed by a duly authorized
                            representative of {{env('APP_NAME')}}.</p>

                        <p><strong>17.3 Severability.</strong> If any provision of these Terms is held to be invalid or
                            unenforceable, such provision shall be struck and the remaining provisions shall be enforced
                            to the fullest extent under law.</p>

                        <p><strong>17.4 Assignment.</strong> You may not assign or transfer these Terms, by operation of
                            law or otherwise, without {{env('APP_NAME')}}'s prior written consent. {{env('APP_NAME')}} may assign these
                            Terms in whole or in part to any affiliate or in connection with a merger, acquisition,
                            corporate reorganization, or sale of all or substantially all of its assets.</p>

                        <p><strong>17.5 Force Majeure.</strong> {{env('APP_NAME')}} shall not be liable for any failure or delay
                            in performance due to circumstances beyond its reasonable control, including but not limited
                            to acts of God, natural disasters, pandemic, terrorism, riot, war, power failures, or
                            internet or telecommunications failures.</p>

                        <p><strong>17.6 Contact Information.</strong> If you have any questions about these Terms,
                            please contact us at:</p>
                        <p>Docmey<br>
                        <p>1023, RK Empire <br>150 Feet Ring Road,<br> Rajkot, Gujarat 360004</p>
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