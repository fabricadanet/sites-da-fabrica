// resources/js/landing.js
/**
 * Sites da Fábrica - Landing Page JavaScript
 * Interatividade e eventos da landing page
 * @version 1.0
 */

// ============================================================================
// UTILITÁRIOS
// ============================================================================

/**
 * Scroll suave para elemento
 * @param {string} id - ID do elemento
 */
function scrollToSection(id) {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

/**
 * Debounce para funções
 * @param {Function} func - Função a executar
 * @param {number} wait - Tempo de espera em ms
 * @returns {Function}
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Throttle para funções
 * @param {Function} func - Função a executar
 * @param {number} limit - Limite de tempo em ms
 * @returns {Function}
 */
function throttle(func, limit) {
    let inThrottle;
    return function (...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// ============================================================================
// ANIMAÇÕES EM SCROLL
// ============================================================================

/**
 * Observador de Intersection API para animar elementos ao scrollar
 */
function initIntersectionObserver() {
    const options = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Adicionar classe de animação
                entry.target.classList.add('animate-fade-up');

                // Remover da observação após animar
                observer.unobserve(entry.target);
            }
        });
    }, options);

    // Observar todos os elementos com data-animate
    document.querySelectorAll('[data-animate]').forEach(el => {
        observer.observe(el);
    });
}

/**
 * Animar números ao scroll (para estatísticas)
 */
function initNumberAnimation() {
    const numbers = document.querySelectorAll('[data-number]');

    if (numbers.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                animateNumber(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    numbers.forEach(num => observer.observe(num));
}

/**
 * Animar um número de 0 até o valor final
 * @param {Element} element - Elemento a animar
 */
function animateNumber(element) {
    const target = parseInt(element.dataset.number, 10);
    const duration = 2000; // 2 segundos
    const increment = target / (duration / 16); // 60fps
    let current = 0;

    const timer = setInterval(() => {
        current += increment;
        if (current >= target) {
            element.textContent = target;
            clearInterval(timer);
        } else {
            element.textContent = Math.floor(current);
        }
    }, 16);
}

// ============================================================================
// HEADER/NAVEGAÇÃO
// ============================================================================

/**
 * Esconder/mostrar header ao scrollar
 */
function initStickyHeader() {
    const header = document.querySelector('.header, header');
    if (!header) return;

    let lastScrollTop = 0;

    window.addEventListener('scroll', throttle(() => {
        const scrollTop = window.scrollY;

        if (scrollTop > 100) {
            header.classList.add('shadow-md');
        } else {
            header.classList.remove('shadow-md');
        }

        lastScrollTop = scrollTop;
    }, 100));
}

/**
 * Marcar link de navegação ativo ao scrollar
 */
function initActiveNavLinks() {
    const navLinks = document.querySelectorAll('nav a[href^="#"]');
    if (navLinks.length === 0) return;

    window.addEventListener('scroll', throttle(() => {
        let current = '';

        navLinks.forEach(link => {
            const section = document.querySelector(link.getAttribute('href'));
            if (section && section.offsetTop <= window.scrollY + 100) {
                current = link;
            }
        });

        navLinks.forEach(link => link.classList.remove('active'));
        if (current) current.classList.add('active');
    }, 200));
}

/**
 * Mobile menu toggle
 */
function initMobileMenu() {
    const menuButton = document.querySelector('[data-menu-toggle]');
    const menu = document.querySelector('[data-menu]');

    if (!menuButton || !menu) return;

    menuButton.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });

    // Fechar ao clicar em um link
    menu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            menu.classList.add('hidden');
        });
    });
}

// ============================================================================
// FORMULÁRIOS
// ============================================================================

/**
 * Validar formulário simples
 */
function initFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');

    forms.forEach(form => {
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            let isValid = true;
            const inputs = form.querySelectorAll('[required]');

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    isValid = false;
                    input.classList.add('border-red-500');
                } else {
                    input.classList.remove('border-red-500');
                }
            });

            if (isValid) {
                handleFormSubmit(form);
            }
        });
    });
}

/**
 * Lidar com submissão do formulário
 */
async function handleFormSubmit(form) {
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;

    try {
        submitButton.disabled = true;
        submitButton.textContent = 'Enviando...';

        // Simular envio (substituir com endpoint real)
        await new Promise(resolve => setTimeout(resolve, 1000));

        // Sucesso
        submitButton.textContent = 'Enviado com sucesso! ✓';
        form.reset();

        setTimeout(() => {
            submitButton.textContent = originalText;
            submitButton.disabled = false;
        }, 3000);

    } catch (error) {
        console.error('Erro ao enviar formulário:', error);
        submitButton.textContent = 'Erro ao enviar';
        submitButton.disabled = false;
    }
}

// ============================================================================
// MODAIS
// ============================================================================

/**
 * Inicializar modais
 */
function initModals() {
    const modals = document.querySelectorAll('[data-modal]');
    const closeButtons = document.querySelectorAll('[data-close-modal]');

    // Abrir modal
    document.querySelectorAll('[data-open-modal]').forEach(button => {
        button.addEventListener('click', () => {
            const modalId = button.dataset.openModal;
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        });
    });

    // Fechar modal
    closeButtons.forEach(button => {
        button.addEventListener('click', () => {
            const modal = button.closest('[data-modal]');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });

    // Fechar ao clicar fora do modal
    modals.forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });
}

// ============================================================================
// ACORDEÃO
// ============================================================================

/**
 * Inicializar acordeões (FAQ)
 */
function initAccordions() {
    const accordions = document.querySelectorAll('[data-accordion] summary');

    accordions.forEach(summary => {
        summary.addEventListener('click', (e) => {
            const details = summary.parentElement;
            const isOpen = details.open;

            // Fechar todos os outros acordeões
            document.querySelectorAll('[data-accordion]').forEach(acc => {
                if (acc !== details && acc.open) {
                    acc.open = false;
                }
            });

            // Adicionar animação
            if (isOpen) {
                details.classList.remove('animate-pulse');
            } else {
                details.classList.add('animate-pulse');
            }
        });
    });
}

// ============================================================================
// TOOLTIP
// ============================================================================

/**
 * Inicializar tooltips
 */
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');

    tooltips.forEach(element => {
        element.addEventListener('mouseenter', () => {
            const text = element.dataset.tooltip;
            const tooltip = document.createElement('div');

            tooltip.className = 'absolute bg-gray-900 text-white px-2 py-1 rounded text-sm whitespace-nowrap pointer-events-none z-50';
            tooltip.textContent = text;

            document.body.appendChild(tooltip);

            const rect = element.getBoundingClientRect();
            tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + 'px';
            tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';

            element.addEventListener('mouseleave', () => {
                tooltip.remove();
            }, { once: true });
        });
    });
}

// ============================================================================
// LAZY LOAD DE IMAGENS
// ============================================================================

/**
 * Lazy load de imagens
 */
function initLazyLoad() {
    if ('IntersectionObserver' in window) {
        const images = document.querySelectorAll('img[data-src]');

        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    img.classList.add('animate-fade-up');
                    imageObserver.unobserve(img);
                }
            });
        });

        images.forEach(img => imageObserver.observe(img));
    }
}

// ============================================================================
// CONTAR-REGRESSIVA
// ============================================================================

/**
 * Inicializar contagem regressiva
 */
function initCountdown() {
    const countdowns = document.querySelectorAll('[data-countdown]');

    countdowns.forEach(element => {
        const endDate = new Date(element.dataset.countdown).getTime();

        const timer = setInterval(() => {
            const now = new Date().getTime();
            const distance = endDate - now;

            if (distance < 0) {
                clearInterval(timer);
                element.textContent = 'Expirado!';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            element.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }, 1000);
    });
}

// ============================================================================
// NOTIFICAÇÕES/TOAST
// ============================================================================

/**
 * Mostrar notificação toast
 * @param {string} message - Mensagem a exibir
 * @param {string} type - Tipo (success, error, warning, info)
 * @param {number} duration - Duração em ms
 */
function showToast(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `fixed bottom-4 right-4 p-4 rounded-lg text-white animate-slide-left z-50`;

    const colors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        warning: 'bg-yellow-500',
        info: 'bg-blue-500'
    };

    toast.classList.add(colors[type] || colors.info);
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('animate-slide-left');
        toast.classList.add('animate-slide-right');

        setTimeout(() => {
            toast.remove();
        }, 300);
    }, duration);
}

// ============================================================================
// EVENT TRACKING (Google Analytics)
// ============================================================================

/**
 * Rastrear clique em CTA
 */
function initClickTracking() {
    const ctaButtons = document.querySelectorAll('[data-track-cta]');

    ctaButtons.forEach(button => {
        button.addEventListener('click', () => {
            const label = button.dataset.trackCta;

            // Google Analytics
            if (typeof gtag !== 'undefined') {
                gtag('event', 'cta_click', {
                    'button_label': label
                });
            }

            // Facebook Pixel
            if (typeof fbq !== 'undefined') {
                fbq('track', 'ViewContent');
            }
        });
    });
}

// ============================================================================
// INICIALIZAÇÃO GERAL
// ============================================================================

/**
 * Inicializar todos os scripts
 */
function initLandingPage() {
    console.log('🚀 Inicializando Landing Page...');

    // Esperar DOM estar carregado
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

    function init() {
        // Animações
        initIntersectionObserver();
        initNumberAnimation();

        // Header/Nav
        initStickyHeader();
        initActiveNavLinks();
        initMobileMenu();

        // Formulários
        initFormValidation();

        // Modais
        initModals();

        // Acordeão
        initAccordions();

        // Tooltips
        initTooltips();

        // Lazy Load
        initLazyLoad();

        // Contagem regressiva
        initCountdown();

        // Tracking
        initClickTracking();

        // Scroll suave para links âncora
        document.querySelectorAll('a[href^="#"]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const href = link.getAttribute('href');
                scrollToSection(href.substring(1));
            });
        });

        console.log('✅ Landing Page inicializada com sucesso!');
    }
}

// Iniciar ao carregar script
initLandingPage();

// ============================================================================
// EXPORT PARA USO EXTERNO
// ============================================================================

if (typeof window !== 'undefined') {
    window.LandingPage = {
        scrollToSection,
        showToast,
        debounce,
        throttle
    };
}