document.addEventListener("DOMContentLoaded", () => {
  // Cấu hình các form và endpoint tương ứng
  const authConfigs = [
    {
      formId: "loginForm",
      btnId: "submitBtn",
      alertId: "alertBox",
      endpoint: "/api/auth/login",
    },
    {
      formId: "registerForm",
      btnId: "registerBtn",
      alertId: "registerAlert",
      endpoint: "/api/auth/register",
    },
    {
      formId: "forgotForm",
      btnId: "forgotBtn",
      alertId: "forgotAlert",
      endpoint: "/api/auth/forgot-password",
    },
  ];

  authConfigs.forEach((config) => {
    const form = document.getElementById(config.formId);
    if (!form) return;

    const btn = document.getElementById(config.btnId);
    const alertBox = document.getElementById(config.alertId);

    form.addEventListener("submit", async (e) => {
      e.preventDefault();

      // Validate sơ bộ mật khẩu khớp (chỉ cho trang đăng ký)
      if (config.formId === "registerForm") {
        const pass = form.querySelector('input[name="password"]').value;
        const confirm = form.querySelector(
          'input[name="confirm_password"]',
        ).value;
        if (pass !== confirm) {
          showAlert(alertBox, "Mật khẩu xác nhận không khớp!", false);
          return;
        }
      }

      btn.disabled = true;
      const originalBtnText = btn.textContent;
      btn.textContent = "Đang xử lý...";

      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());

      try {
        const response = await fetch(config.endpoint, {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(data),
        });

        const result = await response.json();

        if (result.success) {
          showAlert(alertBox, result.message, true);
          if (result.data && result.data.redirect) {
            setTimeout(
              () => (window.location.href = result.data.redirect),
              1500,
            );
          }
        } else {
          showAlert(alertBox, result.message, false);
        }
      } catch (error) {
        showAlert(alertBox, "Lỗi kết nối hệ thống.", false);
      } finally {
        btn.disabled = false;
        btn.textContent = originalBtnText;
      }
    });
  });

  function showAlert(box, msg, isSuccess) {
    box.textContent = msg;
    box.className = `mb-4 p-3 rounded text-sm text-center block ${
      isSuccess ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700"
    }`;
    box.classList.remove("hidden");
  }
});
