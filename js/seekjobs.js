        document.addEventListener('DOMContentLoaded', () => {
            // Hamburger Menu Toggle
            const hamMenu = document.querySelector('.ham-menu');
            const navBar = document.querySelector('nav .nav');
            if (hamMenu && navBar) {
                hamMenu.addEventListener('click', () => {
                    navBar.classList.toggle('active');
                });
            }

            // Select Function 
            function changeBg(select) {
                select.style.backgroundColor = select.value;
            }
            // Smooth Scrolling for Navigation
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('href');
                    if (
                        targetId &&
                        targetId.length > 1 &&
                        document.querySelector(targetId)
                    ) {
                        e.preventDefault();
                        const targetElement = document.querySelector(targetId);
                        targetElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        // Close mobile nav if open
                        if (navBar && navBar.classList.contains('active')) {
                            navBar.classList.remove('active');
                        }
                    }
                });
            });
        });

        // Animation on Scroll (fadeIn effect)
            function animateOnScroll() {
              const sections = document.querySelectorAll('section');
              sections.forEach(section => {
              const sectionTop = section.getBoundingClientRect().top;
              const windowHeight = window.innerHeight;
              if (sectionTop < windowHeight * 0.85) {
                section.classList.add('fadeIn');
              } else {
                section.classList.remove('fadeIn');
              }
              });
            }
            window.addEventListener('scroll', animateOnScroll);
            window.addEventListener('load', animateOnScroll);

            // Contact Form Submission (alert and reset)
            const contactForm = document.querySelector('.contact-form-fields');
            if (contactForm) {
              contactForm.addEventListener('submit', function(e) {
              // Only show alert if not using mailto
              if (contactForm.action.indexOf('mailto:') === -1) {
                e.preventDefault();
                alert('Thank you for your message! We will contact you shortly.');
                contactForm.reset();
              }
              });
            }

            // Handle Connect button
            document.querySelectorAll('.btn01').forEach(button => {
                button.addEventListener('click', () => {
                    button.textContent = "Connected";
                    button.classList.add('connected');
                });
            });

            // Contact Form Submission (alert and reset)
            const formContact = document.querySelector('.contact');
            if (formContact) {
              formContact.addEventListener('submit', function(e) {
              // Only show alert if not using mailto
              if (formContact.action.indexOf('mailto:') === -1) {
                e.preventDefault();
                alert('Thank you for your message! We will contact you shortly.');
                formContact.reset();
              }
              });
            }