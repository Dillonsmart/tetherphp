export default class TetherToast {

    constructor(options = {}) {
        this.duration = options.duration || 3000;
        this.message = options.message || 'Copied to clipboard';

        this.el = document.createElement('div');
        this.el.setAttribute('role', 'status');
        this.el.setAttribute('aria-live', 'polite');
        this.el.className = 'tether-toast';
        this.el.textContent = this.message;

        Object.assign(this.el.style, {
            position: 'fixed',
            bottom: '2rem',
            left: '50%',
            transform: 'translateX(-50%) translateY(1rem)',
            padding: '0.625rem 1.5rem',
            fontSize: '0.8125rem',
            fontFamily: 'inherit',
            letterSpacing: '0.025em',
            color: '#000',
            backgroundColor: '#fff',
            border: '1px solid #e5e5e5',
            boxShadow: '0 4px 12px rgba(0, 0, 0, 0.08)',
            borderRadius: '0.25rem',
            opacity: '0',
            pointerEvents: 'none',
            transition: 'opacity 0.25s ease, transform 0.25s ease',
            zIndex: '9999',
        });

        document.body.appendChild(this.el);

        requestAnimationFrame(() => {
            this.el.style.opacity = '1';
            this.el.style.transform = 'translateX(-50%) translateY(0)';
        });

        this.timeout = setTimeout(() => this.hide(), this.duration);
    }

    hide() {
        clearTimeout(this.timeout);
        this.el.style.opacity = '0';
        this.el.style.transform = 'translateX(-50%) translateY(1rem)';

        this.el.addEventListener('transitionend', () => {
            this.el.remove();
        }, { once: true });
    }
}
