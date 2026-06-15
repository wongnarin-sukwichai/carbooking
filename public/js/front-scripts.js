// Smooth scroll — offset ตาม navbar height
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', function(e) {
                const t = document.querySelector(this.getAttribute('href'));
                if (!t) return;
                e.preventDefault();
                const navH = document.getElementById('main-nav')?.offsetHeight ?? 66;
                const top  = t.getBoundingClientRect().top + window.scrollY - navH - 12;
                window.scrollTo({ top, behavior: 'smooth' });
            });
        });
        // Nav shadow
        window.addEventListener('scroll', () => {
            document.getElementById('main-nav').classList.toggle('scrolled', window.scrollY > 10);
        });
        // Animate on scroll
        const obs = new IntersectionObserver(entries => {
            entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
        }, { threshold: 0.1 });
        document.querySelectorAll('.step-card').forEach(el => obs.observe(el));