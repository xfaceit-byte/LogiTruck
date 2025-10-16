document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.querySelector('.hamburger');
    const navLinks = document.querySelector('.nav-links');

    if (hamburger && navLinks) {
        hamburger.addEventListener('click', (e) => {
            e.stopPropagation();
            navLinks.classList.toggle('active');
            hamburger.classList.toggle('active');

            // Toggle visibility and animation
            if (navLinks.classList.contains('active')) {
                navLinks.style.opacity = '1';
                navLinks.style.visibility = 'visible';
                navLinks.style.transform = 'translateY(0)';
            } else {
                navLinks.style.opacity = '0';
                navLinks.style.visibility = 'invisible';
                navLinks.style.transform = 'translateY(-8px)';
            }
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!hamburger.contains(e.target) && !navLinks.contains(e.target)) {
                navLinks.classList.remove('active');
                hamburger.classList.remove('active');
                navLinks.style.opacity = '0';
                navLinks.style.visibility = 'invisible';
                navLinks.style.transform = 'translateY(-8px)';
            }
        });

        // Close menu when clicking on navigation links
        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                hamburger.classList.remove('active');
                navLinks.style.opacity = '0';
                navLinks.style.visibility = 'invisible';
                navLinks.style.transform = 'translateY(-8px)';
            });
        });
    }

    // Smooth scroll for in-page anchors (only if targets exist)
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.style.backgroundColor = 'rgba(255, 255, 255, 0.95)';
                navbar.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
            } else {
                navbar.style.backgroundColor = '#fff';
                navbar.style.boxShadow = 'none';
            }
        });
    }

    const elements = document.querySelectorAll('.feature-card, .about-content, .about-image');
    if (elements.length) {
        elements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(20px)';
            element.style.transition = 'all 0.5s ease-out';
        });

        const animateOnScroll = () => {
            elements.forEach(element => {
                const elementPosition = element.getBoundingClientRect().top;
                const screenPosition = window.innerHeight;
                if (elementPosition < screenPosition) {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }
            });
        };

        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll();
    }
});


const validateForm = (form) => {
    const name = form.querySelector('input[name="name"]');
    const email = form.querySelector('input[name="email"]');
    const message = form.querySelector('textarea[name="message"]');
    let isValid = true;

    if (name.value.trim() === '') {
        showError(name, 'Name is required');
        isValid = false;
    } else {
        removeError(name);
    }

    if (email.value.trim() === '') {
        showError(email, 'Email is required');
        isValid = false;
    } else if (!isValidEmail(email.value)) {
        showError(email, 'Please enter a valid email');
        isValid = false;
    } else {
        removeError(email);
    }

    if (message.value.trim() === '') {
        showError(message, 'Message is required');
        isValid = false;
    } else {
        removeError(message);
    }

    return isValid;
};

const showError = (input, message) => {
    const formControl = input.parentElement;
    const errorMessage = formControl.querySelector('.error-message') || document.createElement('small');
    errorMessage.className = 'error-message';
    errorMessage.textContent = message;
    if (!formControl.querySelector('.error-message')) {
        formControl.appendChild(errorMessage);
    }
    input.classList.add('error');
};

const removeError = (input) => {
    const formControl = input.parentElement;
    const errorMessage = formControl.querySelector('.error-message');
    if (errorMessage) {
        formControl.removeChild(errorMessage);
    }
    input.classList.remove('error');
};

const isValidEmail = (email) => {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}; 