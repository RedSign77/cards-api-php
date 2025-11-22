@extends('layouts.webtech')

@section('title', 'AI-Powered Solutions for Modern Business - Webtech Solutions')
@section('keywords', 'AI solutions, artificial intelligence, AI development, AI analytics, AI content creation, machine learning, automation, business intelligence, AI transformation')
@section('description', 'Transform your business with AI-powered solutions. From AI-enhanced development to content creation and analytics - practical AI implementation for real results.')
@section('og_title', 'AI-Powered Solutions for Modern Business')
@section('og_url', 'https://webtech-solutions.hu/ai-solutions')

@section('content')
    <!-- Hero Section -->
    <section class="ai-hero">
        <div class="ai-hero-bg"></div>
        <div class="ai-hero-content">
            <h1>AI-Powered Solutions for Modern Business</h1>
            <p class="ai-hero-subtitle">Transform your business operations and development processes with practical AI implementation. We leverage cutting-edge AI tools to deliver real, measurable results - not just hype.</p>
            <div class="cta-buttons">
                <a href="#ai-services" class="btn btn-primary">Explore AI Services</a>
                <a href="#contact-ai" class="btn btn-secondary">Request AI Consultation</a>
            </div>
        </div>
    </section>

    <!-- Introduction Section -->
    <section class="ai-intro">
        <div class="container">
            <h2 class="section-title">Why AI Matters for Your Business</h2>
            <div class="ai-intro-content">
                <p>Artificial Intelligence is transforming how businesses operate, develop software, and engage with customers. At Webtech Solutions, we don't chase AI hype - we implement proven AI tools that deliver tangible value.</p>
                <p>Our approach is pragmatic: AI as a powerful tool to augment human expertise, not replace it. We help you leverage AI to accelerate development, gain deeper insights, and scale content production while maintaining quality and control.</p>
            </div>
            <div class="ai-stats">
                <div class="stat-card">
                    <div class="stat-number">60%</div>
                    <div class="stat-label">Time Savings</div>
                    <p>Average reduction in development time using AI-assisted tools</p>
                </div>
                <div class="stat-card">
                    <div class="stat-number">45%</div>
                    <div class="stat-label">Cost Reduction</div>
                    <p>Lower operational costs through intelligent automation</p>
                </div>
                <div class="stat-card">
                    <div class="stat-number">3x</div>
                    <div class="stat-label">Productivity Boost</div>
                    <p>Increased team output with AI-enhanced workflows</p>
                </div>
                <div class="stat-card">
                    <div class="stat-number">10+</div>
                    <div class="stat-label">Projects Completed</div>
                    <p>Successful AI-assisted implementations delivered</p>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Services Overview -->
    <section class="ai-services" id="ai-services">
        <div class="container">
            <h2 class="section-title">Our AI Solutions</h2>
            <p class="section-subtitle">Three core pillars of AI implementation for maximum business impact</p>

            <!-- Service 1: AI in Development -->
            <div class="ai-service-pillar">
                <div class="pillar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline></svg>
                </div>
                <h3>AI-Enhanced Software Development</h3>
                <p class="pillar-description">Accelerate your development cycles with AI-powered coding assistants, automated testing, and intelligent code review. We leverage the latest AI tools to build better software faster.</p>

                <h4>Specific Applications:</h4>
                <ul class="pillar-applications">
                    <li><strong>Code Generation & Optimization:</strong> AI-assisted coding with GitHub Copilot and Claude Code for faster implementation</li>
                    <li><strong>Automated Testing & Debugging:</strong> AI-powered test generation and intelligent bug detection</li>
                    <li><strong>Architecture Planning:</strong> AI-assisted system design and code review for better structure</li>
                    <li><strong>Documentation Generation:</strong> Automatic creation of comprehensive technical documentation</li>
                    <li><strong>API Development:</strong> Rapid API design and implementation with AI assistance</li>
                </ul>

                <h4>Key Benefits:</h4>
                <div class="benefits-grid">
                    <div class="benefit-item">✓ 50-70% faster time-to-market</div>
                    <div class="benefit-item">✓ Reduced development costs</div>
                    <div class="benefit-item">✓ Higher code quality & consistency</div>
                    <div class="benefit-item">✓ Better, auto-generated documentation</div>
                </div>

                <div class="tech-stack">
                    <strong>Technologies:</strong> GitHub Copilot, Claude Code, ChatGPT
                </div>
            </div>

            <!-- Service 2: AI in Analytics -->
            <div class="ai-service-pillar">
                <div class="pillar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                </div>
                <h3>Data-Driven Insights with AI Analytics</h3>
                <p class="pillar-description">Transform raw data into actionable intelligence. Our AI-powered analytics solutions help you understand customer behavior, predict trends, and make data-driven decisions with confidence.</p>

                <h4>Specific Applications:</h4>
                <ul class="pillar-applications">
                    <li><strong>Customer Behavior Analysis:</strong> Deep insights into user patterns and preferences</li>
                    <li><strong>Sales Forecasting:</strong> Predictive models for accurate revenue projections</li>
                    <li><strong>Performance Monitoring:</strong> Real-time anomaly detection and alerting</li>
                    <li><strong>Automated Reporting:</strong> Dynamic dashboards and scheduled insights delivery</li>
                    <li><strong>Predictive Analytics:</strong> E-commerce optimization and trend prediction</li>
                </ul>

                <h4>Key Benefits:</h4>
                <div class="benefits-grid">
                    <div class="benefit-item">✓ Better decision making with data</div>
                    <div class="benefit-item">✓ Identify trends before competitors</div>
                    <div class="benefit-item">✓ Optimize marketing spend & ROI</div>
                    <div class="benefit-item">✓ Reduce customer churn rates</div>
                </div>

                <div class="use-cases">
                    <strong>Use Cases:</strong> E-commerce analytics, website traffic analysis, inventory optimization, customer segmentation, conversion rate optimization
                </div>
            </div>

            <!-- Service 3: AI in Content -->
            <div class="ai-service-pillar">
                <div class="pillar-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                </div>
                <h3>AI-Powered Content Solutions</h3>
                <p class="pillar-description">Create engaging, high-quality content at scale. From blog posts to product images and videos, our AI content solutions maintain your brand voice while dramatically increasing production capacity.</p>

                <h4>Specific Applications:</h4>
                <ul class="pillar-applications">
                    <li><strong>Blog Writing:</strong> SEO-optimized articles, technical documentation, and long-form content</li>
                    <li><strong>Image Generation:</strong> Product visuals, marketing graphics, hero images, and social media assets</li>
                    <li><strong>Video Content:</strong> Automated video editing, subtitle generation, and promotional videos</li>
                    <li><strong>Multilingual Content:</strong> Professional translation and localization across languages</li>
                    <li><strong>Social Media:</strong> Automated post generation and scheduling with brand consistency</li>
                </ul>

                <h4>Key Benefits:</h4>
                <div class="benefits-grid">
                    <div class="benefit-item">✓ Maintain consistent content calendar</div>
                    <div class="benefit-item">✓ Reach multilingual audiences</div>
                    <div class="benefit-item">✓ Cost-effective content production</div>
                    <div class="benefit-item">✓ Built-in SEO optimization</div>
                </div>

                <div class="quality-note">
                    <strong>Quality Assurance:</strong> All AI-generated content undergoes human review and editing to ensure brand voice accuracy and quality standards.
                </div>

                <div class="tech-stack">
                    <strong>Technologies:</strong> Claude AI, ChatGPT, DALL-E
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section class="ai-process">
        <div class="container">
            <h2 class="section-title">Our AI Implementation Process</h2>
            <p class="section-subtitle">A proven 4-step methodology for successful AI integration</p>

            <div class="process-timeline">
                <div class="process-step">
                    <div class="step-number">1</div>
                    <h3>Discovery & Analysis</h3>
                    <p>We start by understanding your current workflows, pain points, and business objectives. Our team conducts a thorough analysis to identify the best AI opportunities for your specific use case.</p>
                    <ul>
                        <li>Current workflow assessment</li>
                        <li>Pain point identification</li>
                        <li>ROI opportunity analysis</li>
                        <li>Technical feasibility review</li>
                    </ul>
                </div>

                <div class="process-step">
                    <div class="step-number">2</div>
                    <h3>Strategy Development</h3>
                    <p>Based on our findings, we develop a comprehensive AI strategy tailored to your needs. This includes tool selection, integration planning, and clear success metrics.</p>
                    <ul>
                        <li>AI tool selection & evaluation</li>
                        <li>Integration roadmap creation</li>
                        <li>Success metrics definition</li>
                        <li>Budget & timeline planning</li>
                    </ul>
                </div>

                <div class="process-step">
                    <div class="step-number">3</div>
                    <h3>Implementation & Integration</h3>
                    <p>Our team deploys AI solutions into your existing systems with minimal disruption. We ensure seamless integration and thorough testing before full rollout.</p>
                    <ul>
                        <li>AI tool deployment</li>
                        <li>System integration</li>
                        <li>Workflow automation setup</li>
                        <li>Testing & validation</li>
                    </ul>
                </div>

                <div class="process-step">
                    <div class="step-number">4</div>
                    <h3>Training & Optimization</h3>
                    <p>We train your team to effectively use AI tools and continuously optimize performance. Ongoing support ensures you get maximum value from your AI investment.</p>
                    <ul>
                        <li>Team training sessions</li>
                        <li>Best practices documentation</li>
                        <li>Performance monitoring</li>
                        <li>Continuous improvement</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Real-World Applications -->
    <section class="ai-applications">
        <div class="container">
            <h2 class="section-title">AI Solutions in Action</h2>
            <p class="section-subtitle">Real-world applications delivering measurable results</p>

            <div class="applications-grid">
                <div class="application-card">
                    <h3>E-commerce Optimization</h3>
                    <p>AI-powered product recommendations, dynamic pricing strategies, and intelligent inventory management that increase conversion rates and revenue.</p>
                    <div class="app-features">
                        <span>Product Recommendations</span>
                        <span>Dynamic Pricing</span>
                        <span>Inventory Optimization</span>
                    </div>
                </div>

                <div class="application-card">
                    <h3>Customer Support Automation</h3>
                    <p>Intelligent chatbots and automated response systems that handle common inquiries 24/7, freeing your team for complex issues.</p>
                    <div class="app-features">
                        <span>24/7 Chatbots</span>
                        <span>Automated Responses</span>
                        <span>Ticket Routing</span>
                    </div>
                </div>

                <div class="application-card">
                    <h3>Marketing Automation</h3>
                    <p>Personalized campaign generation, content creation, and performance optimization powered by AI for better engagement and conversions.</p>
                    <div class="app-features">
                        <span>Personalized Campaigns</span>
                        <span>A/B Testing</span>
                        <span>Content Generation</span>
                    </div>
                </div>

                <div class="application-card">
                    <h3>Accelerated Development</h3>
                    <p>Faster MVP delivery with AI-assisted coding, automated testing, and intelligent code review reducing time-to-market significantly.</p>
                    <div class="app-features">
                        <span>Rapid Prototyping</span>
                        <span>Auto Testing</span>
                        <span>Code Review</span>
                    </div>
                </div>

                <div class="application-card">
                    <h3>Business Intelligence</h3>
                    <p>Automated reports, predictive analytics, and insights dashboards that turn your data into actionable business intelligence.</p>
                    <div class="app-features">
                        <span>Auto Reports</span>
                        <span>Predictive Analytics</span>
                        <span>Live Dashboards</span>
                    </div>
                </div>

                <div class="application-card">
                    <h3>Multilingual Websites</h3>
                    <p>AI-powered translation with human review enabling you to reach global markets with accurate, culturally appropriate content.</p>
                    <div class="app-features">
                        <span>AI Translation</span>
                        <span>Human Review</span>
                        <span>Cultural Adaptation</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Tools & Technologies -->
    <section class="ai-tech-stack">
        <div class="container">
            <h2 class="section-title">Our AI Technology Stack</h2>
            <p class="section-subtitle">Industry-leading tools and platforms we leverage for your success</p>

            <div class="tech-category">
                <h3>Development AI</h3>
                <div class="tech-logos">
                    <span class="tech-badge">GitHub Copilot</span>
                    <span class="tech-badge">Claude Code</span>
                    <span class="tech-badge">ChatGPT</span>
                </div>
            </div>

            <div class="tech-category">
                <h3>Content Creation</h3>
                <div class="tech-logos">
                    <span class="tech-badge">Claude AI</span>
                    <span class="tech-badge">DALL-E</span>
                    <span class="tech-badge">Stable Diffusion</span>
                    <span class="tech-badge">ChatGPT</span>
                </div>
            </div>

            <div class="tech-category">
                <h3>Analytics & Automation</h3>
                <div class="tech-logos">
                    <span class="tech-badge">Custom AI Models</span>
                    <span class="tech-badge">Make.com AI</span>
                    <span class="tech-badge">Zapier AI</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Comparison -->
    <section class="ai-comparison">
        <div class="container">
            <h2 class="section-title">Traditional vs. AI-Enhanced Approach</h2>
            <p class="section-subtitle">See the difference AI implementation makes</p>

            <div class="comparison-table">
                <div class="comparison-row header">
                    <div class="comparison-item">Metric</div>
                    <div class="comparison-item">Traditional Approach</div>
                    <div class="comparison-item highlight">AI-Enhanced Approach</div>
                </div>

                <div class="comparison-row">
                    <div class="comparison-item"><strong>Development Speed</strong></div>
                    <div class="comparison-item">Standard timeline</div>
                    <div class="comparison-item highlight">50-70% faster</div>
                </div>

                <div class="comparison-row">
                    <div class="comparison-item"><strong>Content Production Time</strong></div>
                    <div class="comparison-item">Days per piece</div>
                    <div class="comparison-item highlight">Hours per piece</div>
                </div>

                <div class="comparison-row">
                    <div class="comparison-item"><strong>Cost Efficiency</strong></div>
                    <div class="comparison-item">Baseline costs</div>
                    <div class="comparison-item highlight">30-50% cost reduction</div>
                </div>

                <div class="comparison-row">
                    <div class="comparison-item"><strong>Scalability</strong></div>
                    <div class="comparison-item">Linear with headcount</div>
                    <div class="comparison-item highlight">Exponential scaling</div>
                </div>

                <div class="comparison-row">
                    <div class="comparison-item"><strong>Quality Consistency</strong></div>
                    <div class="comparison-item">Varies by person</div>
                    <div class="comparison-item highlight">Standardized + human review</div>
                </div>

                <div class="comparison-row">
                    <div class="comparison-item"><strong>Resource Requirements</strong></div>
                    <div class="comparison-item">Large teams needed</div>
                    <div class="comparison-item highlight">Smaller teams, higher output</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Case Studies -->
    <section class="ai-case-studies">
        <div class="container">
            <h2 class="section-title">Client Success with AI</h2>
            <p class="section-subtitle">Real results from AI implementation</p>

            <div class="case-studies-grid">
                <div class="case-study-card">
                    <div class="case-badge">E-commerce</div>
                    <h3>Online Retailer Transformation</h3>
                    <p class="challenge"><strong>Challenge:</strong> Slow content creation and poor product recommendations limiting growth and conversion rates.</p>
                    <p class="solution"><strong>AI Solution:</strong> Implemented AI-powered product descriptions, image generation for missing visuals, and intelligent recommendation engine.</p>
                    <p class="results"><strong>Results:</strong></p>
                    <ul class="results-list">
                        <li>65% reduction in content production time</li>
                        <li>28% increase in conversion rate</li>
                        <li>€45K annual cost savings</li>
                        <li>3x faster product onboarding</li>
                    </ul>
                    <p class="testimonial">"The AI tools Webtech implemented have transformed our operations. We're now publishing content 10x faster while maintaining quality."</p>
                    <p class="testimonial-author">- E-commerce Director, Retail Company</p>
                </div>

                <div class="case-study-card">
                    <div class="case-badge">SaaS Development</div>
                    <h3>SaaS Platform Acceleration</h3>
                    <p class="challenge"><strong>Challenge:</strong> Need to accelerate development of new features while reducing bugs and technical debt.</p>
                    <p class="solution"><strong>AI Solution:</strong> Integrated AI-assisted coding, automated testing, and intelligent code review into development workflow.</p>
                    <p class="results"><strong>Results:</strong></p>
                    <ul class="results-list">
                        <li>55% faster feature delivery</li>
                        <li>40% reduction in bugs</li>
                        <li>90% documentation coverage (automated)</li>
                        <li>€80K saved in development costs</li>
                    </ul>
                    <p class="testimonial">"AI-assisted development has been a game-changer. Our team is more productive and code quality has never been better."</p>
                    <p class="testimonial-author">- CTO, SaaS Startup</p>
                </div>

                <div class="case-study-card">
                    <div class="case-badge">Marketing Agency</div>
                    <h3>Agency Content Scaling</h3>
                    <p class="challenge"><strong>Challenge:</strong> Managing multiple client campaigns with limited resources and tight deadlines for content delivery.</p>
                    <p class="solution"><strong>AI Solution:</strong> AI content generation for blogs, social media, and ad copy with human editing and brand voice customization.</p>
                    <p class="results"><strong>Results:</strong></p>
                    <ul class="results-list">
                        <li>200% increase in content output</li>
                        <li>35% more clients serviced</li>
                        <li>60% faster campaign launches</li>
                        <li>€120K revenue increase</li>
                    </ul>
                    <p class="testimonial">"We've doubled our client capacity without hiring more writers. The AI tools handle the heavy lifting while our team adds the creative touch."</p>
                    <p class="testimonial-author">- Creative Director, Marketing Agency</p>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Ethics -->
    <section class="ai-ethics">
        <div class="container">
            <h2 class="section-title">Our AI Principles</h2>
            <p class="section-subtitle">Responsible AI implementation you can trust</p>

            <div class="ethics-grid">
                <div class="ethics-card">
                    <h3>🔍 Transparency</h3>
                    <p>We always disclose when content is AI-generated and maintain clear communication about AI usage in your projects.</p>
                </div>

                <div class="ethics-card">
                    <h3>🧑‍💼 Human Oversight</h3>
                    <p>AI assists, humans decide. All AI outputs undergo human review and final approval. We augment teams, not replace them.</p>
                </div>

                <div class="ethics-card">
                    <h3>🔒 Data Privacy</h3>
                    <p>Your data stays your data. We implement strict data protection and never use client data to train public AI models.</p>
                </div>

                <div class="ethics-card">
                    <h3>✅ Quality Assurance</h3>
                    <p>Rigorous human review of all AI outputs ensures accuracy, brand consistency, and quality standards are maintained.</p>
                </div>

                <div class="ethics-card">
                    <h3>📚 Continuous Learning</h3>
                    <p>We stay updated with the latest AI developments, best practices, and emerging technologies to serve you better.</p>
                </div>

                <div class="ethics-card">
                    <h3>⚖️ Responsible Use</h3>
                    <p>AI is a tool for augmentation, not replacement. We focus on empowering your team to do their best work, faster.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="ai-faq">
        <div class="container">
            <h2 class="section-title">Common Questions About AI Solutions</h2>
            <p class="section-subtitle">Everything you need to know about AI implementation</p>

            <div class="faq-list">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Will AI replace my development team or employees?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Absolutely not. AI is a tool that augments and empowers your team, making them more productive and effective. Think of AI as a very smart assistant that handles repetitive tasks, generates first drafts, and provides suggestions - your team makes the final decisions, adds creativity, and ensures quality. Companies using our AI solutions typically see their teams accomplish more, not fewer people doing the work.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How much can I realistically save with AI implementation?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Savings vary by use case, but our clients typically see 30-50% cost reduction in areas where AI is implemented. For development projects, we see 50-70% time savings. For content production, costs can drop by 60% or more. The ROI typically becomes positive within 3-6 months. We provide detailed ROI projections during our discovery phase based on your specific situation.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Is AI-generated content good for SEO?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, when done correctly. Google doesn't penalize AI content - they penalize low-quality content. Our approach combines AI generation with human review, editing, and optimization. We ensure content is accurate, valuable, and aligned with SEO best practices. Many of our clients have seen improved search rankings thanks to the consistency and volume of high-quality content AI enables.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How do you ensure quality with AI-generated outputs?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Quality assurance is built into every step of our process. All AI outputs undergo human review and editing. We customize AI prompts and fine-tune models to match your brand voice and quality standards. We also implement feedback loops - reviewing results and continuously improving the AI configuration. Our principle is simple: AI creates the first draft, humans perfect it.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>What's the ROI timeline for AI integration?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Most clients see positive ROI within 3-6 months. Quick wins like content generation and development acceleration show results immediately. More complex implementations like predictive analytics may take 6-12 months to show full value. We focus on quick wins first to demonstrate value early, then expand to more complex use cases. Every implementation includes clear metrics to track ROI from day one.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Do I need technical knowledge to use AI solutions?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>No technical expertise required. We handle all the technical implementation and integration. Our solutions are designed to be user-friendly, and we provide comprehensive training for your team. Most users can become productive with AI tools within a few days of training. We also provide ongoing support to ensure your team gets maximum value from the AI tools.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How do you handle data security with AI tools?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Data security is our top priority. We only use enterprise-grade AI tools with strong security guarantees. Your data is never used to train public AI models. We can implement on-premise AI solutions for sensitive data, use encrypted connections, and follow GDPR compliance. We conduct security assessments of all AI tools before recommendation and can provide detailed security documentation for your compliance needs.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Can AI work with my existing systems and workflows?</h3>
                        <span class="faq-toggle">+</span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes. Our AI solutions are designed to integrate seamlessly with your existing tech stack and workflows. Whether you use specific CMS, e-commerce platforms, development tools, or custom systems, we can integrate AI capabilities. We don't require a complete overhaul - we enhance what you already have. During the discovery phase, we map out all integration points and ensure smooth implementation.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="ai-cta" id="contact-ai">
        <div class="container">
            <h2>Ready to Transform Your Business with AI?</h2>
            <p>Join dozens of companies already leveraging AI to work smarter, faster, and more efficiently.</p>
            <div class="cta-buttons">
                <a href="mailto:info@webtech-solutions.hu?subject=AI%20Consultation%20Request" class="btn btn-primary btn-large">Schedule AI Consultation</a>
                <a href="/#contact" class="btn btn-secondary btn-large">Contact Us</a>
            </div>
            <p class="cta-note">Free 30-minute consultation to discuss your AI opportunities</p>
        </div>
    </section>

@endsection
