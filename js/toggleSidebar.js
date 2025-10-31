const ToggleButton = document.querySelector(".sidebarToggle");
const Sidebar = document.querySelector(".sidebar");
const Section = document.querySelector(".content");

const contents = [Sidebar, Section];

setTimeout(function () {
  ToggleButton.addEventListener("click", function () {
    const allExpanded = contents.every((element) =>
      element.classList.contains("expand")
    );

    if (allExpanded) {
      contents.forEach((element) => {
        element.classList.remove("expand");
        element.classList.add("shrink");
      });
    } else {
      contents.forEach((element) => {
        element.classList.remove("shrink");
        element.classList.add("expand");
      });
    }
  });
}, 300);
