window.addEventListener("load", function () {
  setTimeout(function () {
    const loader = document.querySelector(".loader-container");
    loader.style.transition = "opacity 0.5s ease";
    loader.style.opacity = "0";

    setTimeout(function () {
      loader.style.display = "none";
    }, 500);
  }, 5000);
});
