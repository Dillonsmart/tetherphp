export default class TetherToast {

    DEFAULT_DURATION = 3000;

    constructor(options) {
        this.options = options || {};
        this.toastElement = document.createElement('div');
        this.toastElement.className = 'tether-toast';
        this.toastElement.innerText = this.options.message || 'Default Toast Message';
        document.body.appendChild(this.toastElement);

        this.show();
    }

    show() {
        this.toastElement.style.display = 'block';
        this.toastElement.style.opacity = "1";

        setTimeout(() => {
            this.hide();
        }, this.options.duration || this.DEFAULT_DURATION);
    }

    hide() {
        this.toastElement.style.opacity = "0";
        setTimeout(() => {
            if(!document.body.contains(this.toastElement)) {
                return;
            }
            this.toastElement.style.display = 'none';
            document.body.removeChild(this.toastElement);
        }, 300);
    }
}