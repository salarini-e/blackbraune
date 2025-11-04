class BlackBrauneApp {
    constructor() {
        this.init();
    }

    init() {
        this.setupNavigation();
        this.setupMobileMenu();
        this.setupSmoothScrolling();
        this.setupProgramTabs();
        this.setupFormValidation();
        this.setupScrollAnimations();
        this.setupLoadingAnimations();
        this.setupContactForm();
    }

    // Configuração da navegação
    setupNavigation() {
        const header = document.querySelector('.header');
        let lastScrollY = window.scrollY;

        window.addEventListener('scroll', () => {
            const currentScrollY = window.scrollY;
            
            // Mostrar/esconder header baseado no scroll com efeito glass
            if (currentScrollY > 200) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }

            lastScrollY = currentScrollY;
        });

        // Highlight do menu ativo
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.nav-link');

        window.addEventListener('scroll', () => {
            let currentSection = '';
            
            sections.forEach(section => {
                const sectionTop = section.offsetTop - 100;
                const sectionHeight = section.offsetHeight;
                
                if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                    currentSection = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${currentSection}`) {
                    link.classList.add('active');
                }
            });
        });
    }

    // Menu mobile
    setupMobileMenu() {
        const hamburger = document.querySelector('.hamburger');
        const navMenu = document.querySelector('.nav-menu');
        const navLinks = document.querySelectorAll('.nav-link');

        hamburger?.addEventListener('click', () => {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });

        // Fechar menu ao clicar em um link
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                hamburger?.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.classList.remove('menu-open');
            });
        });

        // Fechar menu ao clicar fora
        document.addEventListener('click', (e) => {
            if (!hamburger?.contains(e.target) && !navMenu.contains(e.target)) {
                hamburger?.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });
    }

    // Scroll suave
    setupSmoothScrolling() {
        const navLinks = document.querySelectorAll('.nav-link, .btn[href^="#"]');
        
        navLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = link.getAttribute('href');
                const targetSection = document.querySelector(targetId);
                
                if (targetSection) {
                    const headerHeight = document.querySelector('.header').offsetHeight;
                    const targetPosition = targetSection.offsetTop - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // Tabs da programação
    setupProgramTabs() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const programDays = document.querySelectorAll('.program-day');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const targetDay = button.getAttribute('data-day');
                
                // Remove active class from all buttons and days
                tabButtons.forEach(btn => btn.classList.remove('active'));
                programDays.forEach(day => day.classList.remove('active'));
                
                // Add active class to clicked button and corresponding day
                button.classList.add('active');
                const targetDayElement = document.getElementById(targetDay);
                if (targetDayElement) {
                    targetDayElement.classList.add('active');
                }
            });
        });
    }

    // Validação do formulário
    setupFormValidation() {
        const form = document.getElementById('cadastroForm');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            
            if (this.validateForm(form)) {
                this.submitForm(form);
            }
        });

        // Validação em tempo real
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.addEventListener('blur', () => {
                this.validateField(input);
            });
            
            input.addEventListener('input', () => {
                this.clearFieldError(input);
            });
        });
    }

    validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        // Validação específica do email
        const emailField = form.querySelector('#email');
        if (emailField && !this.isValidEmail(emailField.value)) {
            this.showFieldError(emailField, 'Por favor, insira um e-mail válido');
            isValid = false;
        }

        // Validação dos termos
        const termosField = form.querySelector('#termos');
        if (termosField && !termosField.checked) {
            this.showFieldError(termosField, 'Você deve aceitar os termos de uso');
            isValid = false;
        }

        return isValid;
    }

    validateField(field) {
        const value = field.value.trim();
        
        if (field.hasAttribute('required') && !value) {
            this.showFieldError(field, 'Este campo é obrigatório');
            return false;
        }

        if (field.type === 'email' && value && !this.isValidEmail(value)) {
            this.showFieldError(field, 'Por favor, insira um e-mail válido');
            return false;
        }

        if (field.type === 'checkbox' && field.hasAttribute('required') && !field.checked) {
            this.showFieldError(field, 'Este campo é obrigatório');
            return false;
        }

        this.clearFieldError(field);
        return true;
    }

    showFieldError(field, message) {
        this.clearFieldError(field);
        
        const errorElement = document.createElement('span');
        errorElement.className = 'field-error';
        errorElement.textContent = message;
        errorElement.style.color = '#e74c3c';
        errorElement.style.fontSize = '0.875rem';
        errorElement.style.marginTop = '0.25rem';
        errorElement.style.display = 'block';
        
        field.style.borderColor = '#e74c3c';
        field.parentNode.appendChild(errorElement);
    }

    clearFieldError(field) {
        const existingError = field.parentNode.querySelector('.field-error');
        if (existingError) {
            existingError.remove();
        }
        field.style.borderColor = '#ddd';
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    async submitForm(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        
        // Mostrar loading
        submitButton.disabled = true;
        submitButton.textContent = 'Cadastrando...';
        submitButton.style.opacity = '0.7';

        try {
            // Simular envio (em produção, conectar com backend)
            await this.simulateFormSubmission(new FormData(form));
            
            this.showSuccessMessage('Cadastro realizado com sucesso! Você receberá novidades em seu e-mail.');
            form.reset();
            
        } catch (error) {
            this.showErrorMessage('Erro ao realizar cadastro. Tente novamente.');
        } finally {
            // Restaurar botão
            submitButton.disabled = false;
            submitButton.textContent = originalText;
            submitButton.style.opacity = '1';
        }
    }

    simulateFormSubmission(formData) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // Simular sucesso (90% das vezes)
                if (Math.random() > 0.1) {
                    console.log('Dados do formulário:', Object.fromEntries(formData));
                    resolve();
                } else {
                    reject(new Error('Erro simulado'));
                }
            }, 2000);
        });
    }

    showSuccessMessage(message) {
        this.showNotification(message, 'success');
    }

    showErrorMessage(message) {
        this.showNotification(message, 'error');
    }

    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                <span>${message}</span>
                <button class="notification-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        // Estilos da notificação
        Object.assign(notification.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            maxWidth: '400px',
            background: type === 'success' ? '#27ae60' : type === 'error' ? '#e74c3c' : '#3498db',
            color: 'white',
            padding: '1rem',
            borderRadius: '8px',
            boxShadow: '0 4px 12px rgba(0, 0, 0, 0.15)',
            zIndex: '10000',
            transform: 'translateX(100%)',
            transition: 'transform 0.3s ease'
        });

        notification.querySelector('.notification-content').style.display = 'flex';
        notification.querySelector('.notification-content').style.alignItems = 'center';
        notification.querySelector('.notification-content').style.gap = '0.75rem';

        const closeButton = notification.querySelector('.notification-close');
        closeButton.style.background = 'none';
        closeButton.style.border = 'none';
        closeButton.style.color = 'white';
        closeButton.style.cursor = 'pointer';
        closeButton.style.padding = '0';
        closeButton.style.marginLeft = 'auto';

        document.body.appendChild(notification);

        // Animar entrada
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Remover notificação
        const removeNotification = () => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        };

        closeButton.addEventListener('click', removeNotification);
        setTimeout(removeNotification, 5000);
    }

    // Animações de scroll
    setupScrollAnimations() {
        const animatedElements = document.querySelectorAll('.loja-card, .partner-card, .program-item, .about-text, .cadastro-info');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        });

        animatedElements.forEach(element => {
            element.style.opacity = '0';
            element.style.transform = 'translateY(30px)';
            element.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(element);
        });
    }

    // Animações de carregamento
    setupLoadingAnimations() {
        // Animação de fade-in para o hero
        const heroContent = document.querySelector('.hero-content');
        if (heroContent) {
            setTimeout(() => {
                heroContent.style.opacity = '1';
                heroContent.style.transform = 'translateY(0)';
            }, 500);
        }

        // Contador animado para datas
        this.animateCounters();
    }

    animateCounters() {
        const eventDates = document.querySelector('.event-dates h2');
        if (eventDates) {
            let startDate = new Date('2025-11-27');
            let currentDate = new Date();
            let timeDiff = startDate.getTime() - currentDate.getTime();
            let daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if (daysDiff > 0) {
                const countdownElement = document.createElement('p');
                countdownElement.style.fontSize = '1rem';
                countdownElement.style.opacity = '0.8';
                countdownElement.style.marginTop = '0.5rem';
                countdownElement.textContent = `Faltam ${daysDiff} dias!`;
                eventDates.parentNode.appendChild(countdownElement);
            }
        }
    }

    // Formulário de contato adicional
    setupContactForm() {
        // Máscara para telefone
        const telefoneInput = document.getElementById('telefone');
        if (telefoneInput) {
            telefoneInput.addEventListener('input', (e) => {
                let value = e.target.value.replace(/\D/g, '');
                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
                e.target.value = value;
            });
        }

        // Efeito de focus nos inputs
        const formInputs = document.querySelectorAll('.form-group input, .form-group select');
        formInputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentNode.classList.add('focused');
            });
            
            input.addEventListener('blur', () => {
                if (!input.value) {
                    input.parentNode.classList.remove('focused');
                }
            });
        });
    }
}

// Utilitários
class Utils {
    static debounce(func, wait, immediate) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    }

    static throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
}

// Inicializar aplicação quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    new BlackBrauneApp();
});

// Adicionar estilos dinâmicos para animações
const style = document.createElement('style');
style.textContent = `
    .hero-content {
        opacity: 0;
        transform: translateY(50px);
        transition: opacity 1s ease, transform 1s ease;
    }

    .form-group.focused label {
        color: #E6A201;
        transform: translateY(-2px);
    }

    .notification {
        font-family: 'Poppins', sans-serif;
    }

    .nav-link.active {
        color: #FFC53A !important;
    }

    .nav-link.active::after {
        width: 100% !important;
    }

    .hamburger.active span:nth-child(1) {
        transform: rotate(-45deg) translate(-5px, 6px);
    }

    .hamburger.active span:nth-child(2) {
        opacity: 0;
    }

    .hamburger.active span:nth-child(3) {
        transform: rotate(45deg) translate(-5px, -6px);
    }

    body.menu-open {
        overflow: hidden;
    }

    @media (max-width: 768px) {
        .nav-menu.active {
            left: 0;
        }
    }

    /* Animação de loading para botões */
    .btn:disabled {
        cursor: not-allowed;
        position: relative;
    }

    .btn:disabled::after {
        content: '';
        position: absolute;
        top: 50%;
        right: 1rem;
        width: 12px;
        height: 12px;
        margin-top: -6px;
        border: 2px solid transparent;
        border-top-color: currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Melhorias visuais para o select múltiplo */
    select[multiple] option:checked {
        background: #e67e22;
        color: white;
    }

    /* Scroll suave para navegadores que não suportam scroll-behavior */
    html {
        scroll-behavior: smooth;
    }

    /* Efeito de hover melhorado para cards */
    .loja-card:hover .loja-icon {
        transform: scale(1.1);
    }

`;

document.head.appendChild(style);