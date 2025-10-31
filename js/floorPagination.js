let currentFloor = 1;
const totalFloors = 5;

const updateFloor = () => {
  // Hide all floors with opacity transition
  document.querySelectorAll(".floor").forEach((floor) => {
    floor.classList.remove("active");
    floor.style.opacity = 0;
  });

  // Delay display to allow opacity transition to finish
  setTimeout(() => {
    document.querySelectorAll(".floor").forEach((floor) => {
      floor.style.display = "none";
    });

    // Show the current floor
    const activeFloor = document.getElementById(`floor-${currentFloor}`);
    activeFloor.style.display = "flex";
    setTimeout(() => {
      activeFloor.style.opacity = 1;
    }, 50);
  }, 100);

  // Update floor indicator
  document.querySelector(
    ".floor-indicator"
  ).textContent = `Floor ${currentFloor}`;
};

// Handle previous floor button
document.querySelector(".prev-floor").addEventListener("click", () => {
  if (currentFloor > 1) {
    currentFloor--;
    updateFloor();
  }
});

// Handle next floor button
document.querySelector(".next-floor").addEventListener("click", () => {
  if (currentFloor < totalFloors) {
    currentFloor++;
    updateFloor();
  }
});

updateFloor();
