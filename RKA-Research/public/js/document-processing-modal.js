(function () {
    'use strict';

    const modal = document.getElementById('documentProcessingModal');

    if (!modal) {
        return;
    }

    const icon = document.getElementById('documentProcessingIcon');
    const title = document.getElementById('documentProcessingTitle');
    const message = document.getElementById('documentProcessingMessage');
    const details = document.getElementById('documentProcessingDetails');
    const footer = document.getElementById('documentProcessingFooter');
    const button = document.getElementById('documentProcessingButton');

    let state = 'loading';
    let closeHandler = null;

    function renderIcon(nextState) {
        icon.className = `document-processing-icon ${nextState}`;
        icon.innerHTML = '';

        if (nextState === 'loading') {
            const spinner = document.createElement('span');
            spinner.className = 'document-processing-spinner';
            icon.appendChild(spinner);
            return;
        }

        const iconElement = document.createElement('i');
        iconElement.className = nextState === 'success'
            ? 'bi bi-check-lg'
            : 'bi bi-x-lg';
        icon.appendChild(iconElement);
    }

    function setDetails(value) {
        const text = Array.isArray(value)
            ? value.filter(Boolean).join('\n')
            : (value || '');

        details.textContent = text;
        details.classList.toggle('show', !!text);
    }

    function show(nextState, options) {
        const config = options || {};
        state = nextState;
        closeHandler = typeof config.onClose === 'function' ? config.onClose : null;

        modal.dataset.state = nextState;
        modal.classList.add('show');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('document-processing-modal-open');

        renderIcon(nextState);
        title.textContent = config.title || 'Memproses Dokumen';
        message.textContent = config.message || '';
        setDetails(config.details || '');

        const canClose = nextState !== 'loading';
        footer.classList.toggle('show', canClose);
        button.textContent = config.buttonText || (nextState === 'error' ? 'TUTUP' : 'OKE');

        if (canClose) {
            window.setTimeout(() => button.focus(), 50);
        }
    }

    function close() {
        if (state === 'loading') {
            return;
        }

        modal.classList.remove('show');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('document-processing-modal-open');

        const handler = closeHandler;
        closeHandler = null;

        if (typeof handler === 'function') {
            handler();
        }
    }

    button.addEventListener('click', close);

    // Modal sengaja tidak ditutup ketika backdrop diklik.
    modal.addEventListener('click', event => {
        if (event.target === modal) {
            event.preventDefault();
        }
    });

    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && modal.classList.contains('show')) {
            event.preventDefault();
            if (state !== 'loading') {
                close();
            }
        }
    });

    window.DocumentProcessingModal = {
        showLoading(options) {
            show('loading', options);
        },
        showSuccess(options) {
            show('success', options);
        },
        showError(options) {
            show('error', options);
        },
        close,
        isOpen() {
            return modal.classList.contains('show');
        },
        getState() {
            return state;
        }
    };
})();
