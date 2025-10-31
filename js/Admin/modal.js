const User = document.querySelector(".user-container");
const snippetModal = document.querySelector(".snipModal");
const snippetOverlay = document.querySelector(".snip-overlay");

User.addEventListener("click", function () {
  const isHidden = getComputedStyle(snippetModal).display === "none";

  if (isHidden) {
    snippetOverlay.style.display = "block";
    snippetModal.style.display = "block";
    setTimeout(() => {
      snippetModal.style.height = "300px";
    }, 10);
  } else {
    closeModal();
  }
});

snippetOverlay.addEventListener("click", closeModal);

// Prevent the snippetModal from closing when clicking inside it
snippetModal.addEventListener("click", function (event) {
  event.stopPropagation();
});

// Close modal when scrolling vertically past 110vh or 120vh
window.addEventListener("scroll", function () {
  const scrollThreshold = window.innerHeight * 0.2;
  if (window.scrollY > scrollThreshold) {
    closeModal();
  }
});

// Close modal function
function closeModal() {
  snippetModal.style.height = "0";
  setTimeout(() => {
    snippetOverlay.style.display = "none";
    snippetModal.style.display = "none";
  }, 300);
}
