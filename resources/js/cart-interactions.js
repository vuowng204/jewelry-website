export function initializeCart() {
    const cart = document.querySelector('[data-cart]');

    if (!cart) {
        return;
    }

    const networkError = cart.querySelector('[data-cart-network-error]');
    const announcement = cart.querySelector('[data-cart-announcement]');
    let pending = false;

    cart.addEventListener('click', (event) => {
        if (pending && event.target.closest('[data-cart-checkout]')) {
            event.preventDefault();
        }
    });

    cart.addEventListener('submit', async (event) => {
        const form = event.target;
        const control = event.submitter;

        if (!form.matches('[data-cart-form]') || !control) {
            return;
        }

        event.preventDefault();

        if (pending) {
            return;
        }

        const content = cart.querySelector('[data-cart-content]');
        const controls = [...content.querySelectorAll('[data-cart-action]:not(:disabled)')];
        const checkout = content.querySelector('a[data-cart-checkout]');
        const formData = new FormData(form);

        if (control.name) {
            formData.append(control.name, control.value);
        }

        pending = true;
        networkError.hidden = true;
        announcement.textContent = '';
        content.setAttribute('aria-busy', 'true');
        checkout?.setAttribute('aria-disabled', 'true');
        controls.forEach((button) => { button.disabled = true; });

        try {
            const response = await fetch(form.action, {
                method: form.method,
                body: formData,
                headers: { Accept: 'text/html' },
                cache: 'no-store',
            });

            if (response.redirected && response.url === cart.dataset.loginUrl) {
                window.location.assign(response.url);
                return;
            }

            const page = new DOMParser().parseFromString(await response.text(), 'text/html');
            const updatedContent = page.querySelector('[data-cart-content]');

            if (!response.ok || !updatedContent) {
                throw new Error('Could not refresh cart');
            }

            const restoreFocus = document.activeElement === control || document.activeElement === document.body;
            content.replaceWith(updatedContent);
            announcement.textContent = updatedContent.querySelector('[data-cart-subtotal]')?.textContent ?? '';

            if (restoreFocus) {
                const updatedForm = [...updatedContent.querySelectorAll('[data-cart-form]')]
                    .find((candidate) => candidate.action === form.action && candidate.dataset.cartForm === form.dataset.cartForm);
                const availableControls = [...(updatedForm?.querySelectorAll('[data-cart-action]:not(:disabled)') ?? [])];
                const updatedControl = availableControls.find((button) => button.dataset.cartAction === control.dataset.cartAction);
                (updatedControl ?? availableControls[0] ?? updatedContent.querySelector('[data-cart-continue]'))
                    ?.focus({ preventScroll: true });
            }
        } catch {
            networkError.hidden = false;
        } finally {
            pending = false;
            content.removeAttribute('aria-busy');
            checkout?.removeAttribute('aria-disabled');
            controls.forEach((button) => { button.disabled = false; });
        }
    });
}
