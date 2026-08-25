(function () {
  const dialog = document.querySelector('[data-ui-dialog]');
  if (!dialog || typeof dialog.showModal !== 'function') {
    return;
  }

  const titleEl = dialog.querySelector('[data-ui-dialog-title]');
  const messageEl = dialog.querySelector('[data-ui-dialog-message]');
  const cancelBtn = dialog.querySelector('[data-ui-dialog-cancel]');
  const confirmBtn = dialog.querySelector('[data-ui-dialog-confirm]');

  let resolvePromise = null;

  const finish = (value) => {
    dialog.close();
    const resolve = resolvePromise;
    resolvePromise = null;
    if (typeof resolve === 'function') {
      resolve(value);
    }
  };

  cancelBtn?.addEventListener('click', () => finish(false));
  confirmBtn?.addEventListener('click', () => finish(true));

  dialog.addEventListener('click', (event) => {
    if (event.target === dialog) {
      finish(false);
    }
  });

  dialog.addEventListener('cancel', (event) => {
    event.preventDefault();
    finish(false);
  });

  window.uiDialog = {
    confirm(options) {
      const opts = options || {};
      titleEl.textContent = opts.title || '';
      messageEl.textContent = opts.message || '';
      confirmBtn.textContent = opts.confirmText || 'Tamam';
      cancelBtn.textContent = opts.cancelText || 'Vazgeç';
      cancelBtn.classList.remove('hidden');

      return new Promise((resolve) => {
        resolvePromise = resolve;
        dialog.showModal();
      });
    },

    alert(options) {
      const opts = options || {};
      titleEl.textContent = opts.title || '';
      messageEl.textContent = opts.message || '';
      confirmBtn.textContent = opts.confirmText || 'Tamam';
      cancelBtn.classList.add('hidden');

      return new Promise((resolve) => {
        resolvePromise = () => resolve(true);
        dialog.showModal();
      });
    },
  };
})();
