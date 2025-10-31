document.addEventListener("DOMContentLoaded", function () {
  const snippetOverlay = document.querySelector(".snippet-overlay");
  const snippetContainer = document.querySelector(".snippet-container");
  const snippetButton = document.getElementById("snippetButton");

  const editProfileOverlay = document.querySelector(".editProfile-Overlay");
  const editProfileContainer = document.querySelector(".editProfile-Container");
  const editProfileButtons = document.querySelectorAll(".editProfile");
  const closeBtn = editProfileContainer.querySelector(".close-btn");

  const profileModalOverlay = document.querySelector(".profileModal-Overlay");
  const profileModal = document.querySelector(".profileModal-Container");
  const profileButtons = document.querySelectorAll(".profileButton");

  const newPasswordButton = document.getElementById("newPass");
  const newPasswordOverlay = document.querySelector(".new-password-overlay");
  const newPasswordContainer = document.querySelector(
    ".new-password-container"
  );
  const closeNewPassButton = document.querySelector(".close-newpass-btn");

  function closeModal(overlay, container) {
    overlay.style.opacity = "0";
    container.style.opacity = "0";

    setTimeout(() => {
      overlay.classList.remove("display");
      container.classList.remove("display");
    }, 300);
  }

  async function openModal(overlay, container) {
    if (
      snippetOverlay.classList.contains("display") &&
      overlay !== snippetOverlay
    ) {
      await closeModal(snippetOverlay, snippetContainer);
    }
    if (
      editProfileOverlay.classList.contains("display") &&
      overlay !== editProfileOverlay
    ) {
      await closeModal(editProfileOverlay, editProfileContainer);
    }
    if (
      profileModalOverlay.classList.contains("display") &&
      overlay !== profileModalOverlay
    ) {
      await closeModal(profileModalOverlay, profileModal);
    }
    if (
      newPasswordOverlay.classList.contains("display") &&
      overlay !== newPasswordOverlay
    ) {
      await closeModal(newPasswordOverlay, newPasswordContainer);
    }

    overlay.classList.add("display");
    container.classList.add("display");
    overlay.style.opacity = "0";
    container.style.opacity = "0";

    overlay.offsetHeight;
    container.offsetHeight;

    setTimeout(() => {
      overlay.style.opacity = "1";
      container.style.opacity = "1";
    }, 10);
  }

  if (snippetButton && snippetContainer && snippetOverlay) {
    snippetButton.addEventListener("click", function () {
      openModal(snippetOverlay, snippetContainer);
    });

    snippetOverlay.addEventListener("click", function () {
      closeModal(snippetOverlay, snippetContainer);
    });

    snippetContainer.addEventListener("click", function (event) {
      event.stopPropagation();
    });
  }

  if (
    editProfileButtons.length > 0 &&
    editProfileContainer &&
    editProfileOverlay
  ) {
    editProfileButtons.forEach((button) => {
      button.addEventListener("click", function () {
        openModal(editProfileOverlay, editProfileContainer);
      });
    });

    editProfileOverlay.addEventListener("click", function () {
      closeModal(editProfileOverlay, editProfileContainer);
    });

    closeBtn.addEventListener("click", function () {
      closeModal(editProfileOverlay, editProfileContainer);
    });

    editProfileContainer.addEventListener("click", function (event) {
      event.stopPropagation();
    });
  }

  if (profileButtons.length > 0 && profileModal && profileModalOverlay) {
    profileButtons.forEach((button) => {
      button.addEventListener("click", function () {
        openModal(profileModalOverlay, profileModal);
      });
    });

    profileModalOverlay.addEventListener("click", function () {
      closeModal(profileModalOverlay, profileModal);
    });

    profileModal.addEventListener("click", function (event) {
      event.stopPropagation();
    });
  }

  if (newPasswordButton && newPasswordContainer && newPasswordOverlay) {
    newPasswordButton.addEventListener("click", function () {
      openModal(newPasswordOverlay, newPasswordContainer);
    });

    newPasswordOverlay.addEventListener("click", function () {
      closeModal(newPasswordOverlay, newPasswordContainer);
    });

    closeNewPassButton.addEventListener("click", function () {
      closeModal(newPasswordOverlay, newPasswordContainer);
    });

    newPasswordContainer.addEventListener("click", function (event) {
      event.stopPropagation();
    });
  }
});
