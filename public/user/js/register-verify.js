document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('register-verify-form');
  const codeInput = document.getElementById('verify-code');
  const submitBtn = document.getElementById('verify-submit');
  const resendBtn = document.getElementById('verify-resend');
  const alertBox = document.getElementById('verify-alert');

  if (!form || !codeInput || !submitBtn || !resendBtn || !alertBox) return;

  const csrf = form.querySelector('input[name="_token"]')?.value || '';
  const sendUrl = form.dataset.sendUrl;
  const verifyUrl = form.dataset.verifyUrl;
  const loginUrl = form.dataset.loginUrl;

  let resendCooldown = 0;
  let resendTimer = null;

  const showAlert = (message, type = 'error') => {
    alertBox.textContent = message;
    alertBox.classList.remove('hidden', 'bg-bg', 'text-ink', 'text-announce', 'bg-[rgba(182,29,15,0.06)]');
    if (type === 'success') {
      alertBox.classList.add('bg-bg', 'text-ink');
    } else {
      alertBox.classList.add('text-announce', 'bg-[rgba(182,29,15,0.06)]');
    }
  };

  const setLoading = (loading) => {
    submitBtn.disabled = loading;
    submitBtn.textContent = loading ? 'Doğrulanıyor...' : 'Doğrula ve Kaydı Tamamla';
  };

  const startResendCooldown = (seconds = 60) => {
    resendCooldown = seconds;
    resendBtn.disabled = true;
    resendBtn.textContent = `Kodu Tekrar Gönder (${resendCooldown}s)`;

    if (resendTimer) window.clearInterval(resendTimer);

    resendTimer = window.setInterval(() => {
      resendCooldown -= 1;
      if (resendCooldown <= 0) {
        window.clearInterval(resendTimer);
        resendBtn.disabled = false;
        resendBtn.textContent = 'Kodu Tekrar Gönder';
        return;
      }
      resendBtn.textContent = `Kodu Tekrar Gönder (${resendCooldown}s)`;
    }, 1000);
  };

  const sendCode = async () => {
    const response = await fetch(sendUrl, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrf,
        Accept: 'application/json',
      },
    });

    const payload = await response.json();

    if (!response.ok) {
      showAlert(payload.message || 'Kod gönderilemedi.');
      if (response.status === 429) {
        const match = String(payload.message || '').match(/(\d+)\s+saniye/);
        if (match) startResendCooldown(Number(match[1]));
      }
      return false;
    }

    showAlert(payload.message || 'Doğrulama kodu gönderildi.', 'success');
    startResendCooldown(60);
    codeInput.focus();
    return true;
  };

  resendBtn.addEventListener('click', () => {
    if (resendCooldown > 0) return;
    sendCode();
  });

  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const code = codeInput.value.trim();
    if (!/^\d{6}$/.test(code)) {
      showAlert('Lütfen 6 haneli doğrulama kodunu girin.');
      return;
    }

    setLoading(true);

    try {
      const response = await fetch(verifyUrl, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': csrf,
          Accept: 'application/json',
        },
        body: JSON.stringify({ code }),
      });

      const payload = await response.json();

      if (!response.ok || !payload.status) {
        showAlert(payload.message || 'Doğrulama başarısız.');
        setLoading(false);
        return;
      }

      showAlert(payload.message || 'Kayıt tamamlandı.', 'success');
      window.setTimeout(() => {
        window.location.href = payload.redirect || loginUrl;
      }, 900);
    } catch (error) {
      showAlert('Bir hata oluştu. Lütfen tekrar deneyin.');
      setLoading(false);
    }
  });

  codeInput.addEventListener('input', () => {
    codeInput.value = codeInput.value.replace(/\D/g, '').slice(0, 6);
  });

  sendCode();
});
