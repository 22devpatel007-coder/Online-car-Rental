document.addEventListener('DOMContentLoaded', () => {
  // Button ripple effect
  document.querySelectorAll('.button, .nav .btn').forEach(btn => {
    btn.addEventListener('click', e => {
      btn.classList.add('clicked');
      setTimeout(() => btn.classList.remove('clicked'), 200);
    });
  });

  // Simple toast utility
  window.toast = function (msg) {
    const t = document.createElement('div');
    t.textContent = msg;
    t.style.cssText = 'position:fixed;bottom:20px;left:50%;transform:translateX(-50%);background:#0b1224;border:1px solid #334155;color:#e5e7eb;padding:8px 12px;border-radius:10px;z-index:9999';
    document.body.appendChild(t);
    setTimeout(() => t.remove(), 1800);
  }
});

