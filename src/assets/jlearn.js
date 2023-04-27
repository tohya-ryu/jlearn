/* initiate form handling */
document.querySelectorAll(".framework-form-submit").forEach((btn) => {
  btn.addEventListener("click", framework.form.submit);
});
