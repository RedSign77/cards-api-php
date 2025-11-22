<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Webtech Solutions')</title>
    <meta name="keywords" content="@yield('keywords', 'IT solutions, web hosting, e-commerce maintenance, no-code automation, application security, data protection compliance, cloud infrastructure, DevOps, GDPR compliance, AI solutions, artificial intelligence')">
    <meta name="description" content="@yield('description', 'Enhance your business with Webtech Solutions tailored IT services. From hosting to security, we optimize your digital operations!')">
    <meta property="og:description" content="@yield('og_description', 'Enhance your business with Webtech Solutions tailored IT services. From hosting to security, we optimize your digital operations!')">
    <meta name="icbm" content="47.497912,19.040235">
    <meta name="geo.position" content="47.497912;19.040235">
    <meta name="geo.placename" content="Budapest">
    <meta property="og:title" content="@yield('og_title', 'Webtech Solutions')">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en">
    <meta property="og:image" content="@yield('og_image', '/images/webtech/logo-1200x630.png')">
    <meta property="og:url" content="@yield('og_url', 'https://webtech-solutions.hu/en')">
    <link rel="icon" type="image/png" href="/images/webtech/logo-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/images/webtech/logo-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/images/webtech/logo-96x96.png" sizes="96x96">
    <link rel="apple-touch-icon-precomposed" sizes="152x152" href="/images/webtech/logo-152x152.png">
    <link rel="canonical" href="@yield('canonical', 'https://webtech-solutions.hu/en')">
    <link rel="alternate" hreflang="en" href="https://webtech-solutions.hu/en" title="Home">
    <link rel="alternate" hreflang="hu" href="https://webtech-solutions.hu/hu" title="Home">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/webtech-solutions.css">
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav id="navbar">
        <div class="nav-container">
            <a href="/"><img src="/images/webtech/logo-240.png" alt="Webtech Solutions" class="logo"></a>
            <ul class="nav-links">
                <li><a href="/">Home</a></li>
                <li><a href="/#services">Services</a></li>
                <li><a href="/ai-solutions">AI Solutions</a></li>
                <li><a href="/#about">About</a></li>
                <li><a href="/#contact">Contact</a></li>
            </ul>
            <button class="mobile-menu-btn">☰</button>
        </div>
    </nav>

    @yield('content')

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-links">
                <a href="/">Home</a>
                <a href="/#services">Services</a>
                <a href="/ai-solutions">AI Solutions</a>
                <a href="/#about">About</a>
                <a href="/terms-and-conditions">Terms & Conditions</a>
                <a href="/privacy-policy">Privacy Policy</a>
            </div>
            <p class="copyright">&copy; 2025 Webtech Solutions - Zoltán Németh EV. All rights reserved.</p>
        </div>
    </footer>

    <script src="/js/webtech-solutions.js"></script>

    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4TFP8M0NH7"></script>
    <script>
        var disableStr = 'ga-disable-G-4TFP8M0NH7';
        if (document.cookie.indexOf(disableStr + '=true') > -1) {
            window[disableStr] = true;
        }
        function gaOptout() {
            document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
            window[disableStr] = true;
        }
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-4TFP8M0NH7');
    </script>

    @yield('scripts')
</body>
</html>
