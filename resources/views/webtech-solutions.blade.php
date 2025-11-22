@extends('layouts.webtech')

@section('title', 'Tailored IT Solutions for SMEs - Webtech Solutions')
@section('keywords', 'IT solutions, web hosting, e-commerce maintenance, no-code automation, application security, data protection compliance, cloud infrastructure, DevOps, GDPR compliance, AI solutions')
@section('description', 'Enhance your business with Webtech Solutions tailored IT services. From hosting to security, we optimize your digital operations!')
@section('og_title', 'Tailored IT Solutions for SMEs')
@section('og_url', 'https://webtech-solutions.hu/en')

@section('content')
    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="binary-stream">01001001 01010100 00100000 01010011 01101111 01101100 01110101 01110100 01101001 01101111 01101110 01110011</div>
        <div class="binary-stream">01000001 01001001 00100000 01010000 01101111 01110111 01100101 01110010 01100101 01100100</div>
        <div class="binary-stream">01000100 01101001 01100111 01101001 01110100 01100001 01101100</div>
        <div class="neural-line"></div>
        <div class="neural-line"></div>
        <!-- Floating Orbs -->
        <div class="floating-orb orb-1"></div>
        <div class="floating-orb orb-2"></div>
        <div class="floating-orb orb-3"></div>
        <div class="hero-content">
            <h1>Trusted IT Solutions<br>for Modern Businesses</h1>
            <p>Enhance your business with tailored IT services. From web hosting to security, we optimize your digital operations for sustainable growth.</p>
            <div class="cta-buttons">
                <a href="#services" class="btn btn-primary">Explore Services</a>
                <a href="#contact" class="btn btn-secondary">Get In Touch</a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section class="services" id="services">
        <h2 class="section-title">Our Services</h2>
        <p class="section-subtitle">Comprehensive IT solutions tailored to your business needs</p>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2" ry="2"></rect><rect x="2" y="14" width="20" height="8" rx="2" ry="2"></rect><line x1="6" y1="6" x2="6.01" y2="6"></line><line x1="6" y1="18" x2="6.01" y2="18"></line></svg>
                </div>
                <h3>Web Hosting</h3>
                <p>Reliable, high-performance hosting solutions with 99.9% uptime. Scalable infrastructure that grows with your business.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <h3>24/7 Support</h3>
                <p>Round-the-clock technical support from our expert team. We're here when you need us most.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                </div>
                <h3>E-commerce Solutions</h3>
                <p>Custom webshop development and maintenance. Magento, WooCommerce, and more - optimized for conversions.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>
                </div>
                <h3>No-Code Automation & AI</h3>
                <p>Streamline your workflows with Make.com and Zapier integrations. Automate repetitive tasks without coding. <a href="/ai-solutions" style="color: var(--primary); font-weight: 600;">Explore AI Solutions →</a></p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <h3>Security & Compliance</h3>
                <p>Comprehensive security audits and GDPR compliance consulting. Protect your business and customer data.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                </div>
                <h3>DevOps & Cloud</h3>
                <p>Cloud infrastructure management and DevOps best practices. Automated deployments and monitoring.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="about-content">
            <div class="about-text">
                <h2>Why Choose Webtech Solutions?</h2>
                <p>With years of experience serving SMEs across Europe, we understand the unique challenges small and medium businesses face in the digital world.</p>
                <p>Our team combines technical expertise with business acumen to deliver solutions that truly make a difference. We don't just solve problems – we help you grow.</p>
                <a href="#contact" class="btn btn-primary">Start Your Project</a>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?w=800&h=600&fit=crop" alt="Team collaboration" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section" id="contact">
        <h2>Ready to Transform Your Business?</h2>
        <p>Let's discuss how we can help you achieve your digital goals</p>
        <a href="mailto:info@webtech-solutions.hu" class="btn btn-primary">Contact Us Today</a>
    </section>
@endsection
