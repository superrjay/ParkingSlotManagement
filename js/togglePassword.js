const passwordInput = document.getElementById("Password");
const eyeIcon = document.getElementById("eye");

eyeIcon.addEventListener("click", () => {
  eyeIcon.classList.add("hide");
  passwordInput.classList.add("visible");

  setTimeout(() => {
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      passwordInput.style.letterSpacing = "1px";
      eyeIcon.className = "fa-regular fa-eye-slash";
    } else {
      passwordInput.type = "password";
      eyeIcon.className = "fa-regular fa-eye";
      passwordInput.style.letterSpacing = "5px";
    }

    passwordInput.classList.remove("visible");
  }, 300);
});
