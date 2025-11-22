@extends('layouts.webtech')

@section('title', 'Terms and Conditions - Webtech Solutions')
@section('description', 'Terms and Conditions for Webtech Solutions IT services')

@section('styles')
    <style>
        .container {
            max-width: 900px;
            margin: 3rem auto;
            padding: 3rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        }

        .container h1 {
            font-size: 2.5rem;
            color: #7C3AED;
            margin-bottom: 1rem;
        }

        .container h2 {
            font-size: 1.75rem;
            color: #1F2937;
            margin-top: 2.5rem;
            margin-bottom: 1rem;
        }

        .container p {
            margin-bottom: 1.5rem;
            color: #6B7280;
        }

        .container ul {
            margin-left: 2rem;
            margin-bottom: 1.5rem;
        }

        .container li {
            margin-bottom: 0.5rem;
            color: #6B7280;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 2rem;
            color: #7C3AED;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .updated {
            color: #9CA3AF;
            font-size: 0.9rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 768px) {
            .container {
                margin: 1.5rem;
                padding: 1.5rem;
            }

            .container h1 {
                font-size: 2rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <a href="/" class="back-link">← Back to Home</a>

        <h1>General Terms and Conditions (GTC)</h1>
        <p class="updated">Effective from: April 2, 2025</p>

        <h2>1. Service Provider Details</h2>
        <ul>
            <li><strong>Name:</strong> Zoltán Németh Sole Proprietor (EV)</li>
            <li><strong>Registered Office:</strong> Veres Péter út 51., 1163 Budapest, Hungary</li>
            <li><strong>Email:</strong> info@webtech-solutions.hu</li>
            <li><strong>Phone:</strong> +36 20 280 2024</li>
            <li><strong>Tax Number:</strong> 90946080-1-42</li>
            <li><strong>Registration Number:</strong> 60302250</li>
        </ul>

        <h2>2. General Provisions</h2>
        <p>These General Terms and Conditions apply to the IT services provided by Webtech Solutions, specifically including:</p>
        <ul>
            <li>Web development and operation</li>
            <li>Web hosting services</li>
            <li>DevOps, cybersecurity, system optimization</li>
            <li>Legacy system maintenance</li>
            <li>Webshop maintenance (Magento, WooCommerce)</li>
            <li>ISO consulting and implementation</li>
        </ul>
        <p>By using our services, the client accepts the terms outlined in this GTC.</p>

        <h2>3. Ordering and Fulfilment Process</h2>
        <ul>
            <li>Services are ordered via email or formal contract.</li>
            <li>Fulfilment is based on a custom offer or framework agreement.</li>
            <li>Invoicing occurs after delivery, with a specified payment deadline.</li>
            <li>The client is required to provide all necessary data and access credentials for proper fulfilment.</li>
        </ul>

        <h2>4. Fees and Payment Terms</h2>
        <ul>
            <li>Service fees are always determined on a case-by-case basis.</li>
            <li>Payment is made via bank transfer within the due date stated on the invoice.</li>
            <li>In case of late payment, statutory interest may apply as per Hungarian Civil Code.</li>
        </ul>

        <h2>5. Limitation of Liability</h2>
        <ul>
            <li>The Provider acts with due care but is not liable for damages arising from incorrect information provided by the client, denial of access, or third-party interference.</li>
            <li>The Provider's liability is limited to the value of the relevant contract.</li>
        </ul>

        <h2>6. Withdrawal and Termination</h2>
        <ul>
            <li>For custom projects, termination is governed by the specific contract terms.</li>
            <li>For ongoing services (e.g., hosting, maintenance), a 30-day notice period applies.</li>
            <li>Termination must be submitted in writing, via email or postal mail.</li>
        </ul>

        <h2>7. Complaint Handling</h2>
        <p>Complaints can be submitted through the following contact channels:</p>
        <ul>
            <li>📧 Email: info@webtech-solutions.hu</li>
            <li>📞 Phone: +36 20 280 2024</li>
        </ul>
        <p>All complaints will be investigated and responded to within 5 business days.</p>

        <h2>8. Data Protection</h2>
        <p>Data is processed in accordance with our <a href="/privacy-policy" style="color: #7C3AED; text-decoration: underline;">Privacy Policy</a>, available on our website.</p>

        <h2>9. Dispute Resolution</h2>
        <p>The parties will strive to resolve disputes amicably. If this fails, jurisdiction is assigned to the Metropolitan Court of Budapest.</p>

        <h2>10. Final Provisions</h2>
        <p>The Provider reserves the right to unilaterally modify the GTC. The latest version is always available on the website.</p>
    </div>
@endsection
