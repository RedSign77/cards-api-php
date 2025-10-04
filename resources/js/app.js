import './bootstrap.js';
document.addEventListener('DOMContentLoaded', () => {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    });

    // Add loading states for buttons
    document.querySelectorAll('a[href^="/admin"], a[href^="/api"], a[href^="/register"]').forEach(link => {
        link.addEventListener('click', function() {
            const originalText = this.innerHTML;
            this.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Loading...';
            // Simulate loading for demo; remove in production
            setTimeout(() => {
                this.innerHTML = originalText;
            }, 2000);
        });
    });

    // Parallax effect for floating cards
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelectorAll('.card-flip');
        const speed = 0.5;

        parallax.forEach(element => {
            const yPos = -(scrolled * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    });

    // Card flip interaction
    document.querySelectorAll('.card-flip').forEach(card => {
        card.addEventListener('mouseenter', () => {
            const inner = card.querySelector('.card-flip-inner');
            inner.style.transform = 'rotateY(180deg)';
        });
        card.addEventListener('mouseleave', () => {
            const inner = card.querySelector('.card-flip-inner');
            inner.style.transform = 'rotateY(0deg)';
        });
    });
});
