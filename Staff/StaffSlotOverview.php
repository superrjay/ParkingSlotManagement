<?php 
include '../php/connections.php';
include '../php/fetchLoginData.php';
$current_page = 'StaffSlotOverview'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slot Management</title>
    <link rel="icon" href="../img/logo.png">
    <!-- Libraries -->
    <link rel="stylesheet" href="../lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../lib/css/sweetalert.css">
    <link rel="stylesheet" href="../lib/css/toastr.css">
    <link rel="stylesheet" href="../lib/css/flatpickr.min.css">
    <link rel="stylesheet" href="../lib/icons/css/all.css"/>
    <script src="../lib/js/jquery-3.7.1.min.js"></script>
    <script src="../lib/js/bootstrap.bundle.js"></script>
    <script src="../lib/js/JsBarcode.all.min.js"></script>
    <script src="../lib/js/qrious.min.js"></script>
    <script src="../lib/js/sweetalert.js"></script>
    <script src="../lib/js/toastr.js"></script>
    <script src="../lib/js/flatpickr.min.js"></script>
    <!-- Styling -->
    <link rel="stylesheet" href="../style.css">
</head>
<body>

    <div class="loader-container" id="loader-container">
    <div class="loader">
  <div class="box" style="--i: 1; --inset:44%">
    <div class="logo">
        <img src="../img/logo.png" alt="">
    </div>
  </div>
  <div class="box" style="--i: 2; --inset:40%"></div>
  <div class="box" style="--i: 3; --inset:36%"></div>
  <div class="box" style="--i: 4; --inset:32%"></div>
  <div class="box" style="--i: 5; --inset:28%"></div>
  <div class="box" style="--i: 6; --inset:24%"></div>
  <div class="box" style="--i: 7; --inset:20%"></div>
  <div class="box" style="--i: 8; --inset:16%"></div>
</div>
    </div>


    <header>
        <div class="navbar">
        <div class="header-logo"><a href="#"><img src="../img/logo.png" alt=""></a></div>
        </div>
    </header>         

<section>
<?php include '../components/sidebarLeft.php'; ?>
            <div class="parking-overview">
              <div class="slot-overview">
              <?php include '../php/parkingFunction.php';
              $fetchParking = fetchParking();?>
              <?php include '../components/floorsLayout.php';?>
              </div>
              <footer>
                <div class="footer">
                    <button id="staffSlotManagement"><i class="fa-solid fa-house"></i></button>
                    <button id="staffSlotOverview"><i class="fa-solid fa-car"></i></button>
                    <button><i class="fa-solid fa-circle-info"></i></button>
                    <button id="snippetButton"><i class="fa-solid fa-user"></i></button>
                </div>
                </footer>
            </div>
<?php include '../components/sidebarRight.php'; ?>
</section>

<?php include '../components/profileSnippet.php'; ?>
<?php include '../components/profileModal.php'; ?>
<?php include '../components/editProfileModal.php'; ?>
<?php include '../components/passwordModal.php'; ?>
<?php include '../components/viewSlotModal.php'; ?>
<?php include '../components/editSlotModal.php'; ?>
<?php include '../components/checkoutModal.php'; ?>
<?php include '../components/addSlotModal.php'; ?>

    
    <!-- Functions -->
    <script>
    const zones = ['A', 'B', 'C', 'D', 'E', 'F'];
    const slotsPerZone = 10;
    const floors = [1, 2, 3, 4, 5];
    const parkingData = <?php echo json_encode($fetchParking); ?>; 

    // Loop through each floor
    floors.forEach(floor => {
        // Loop through each zone
        zones.forEach(zone => {
            const container = document.getElementById(`floor${floor}-zone${zone}-slots`);
            let slotNumber = 1; 

            // Loop through parking data to find slots for the current floor and zone
            parkingData.forEach(slot => {
                if (slot.floor == floor && slot.zone == zone) {
                    const button = document.createElement('button');
                    button.className = 'slot';
                    button.setAttribute('data-zone', zone);
                    button.setAttribute('data-slot', slotNumber); 
                    button.setAttribute('data-floor', floor);
                    button.setAttribute('data-slot-id', slot.slot_id);
                    button.setAttribute('data-license-plate', slot.plate_number);
                    button.setAttribute('data-user-type', slot.user_type);
                    button.setAttribute('data-vehicle-type', slot.vehicle_type);
                    button.setAttribute('data-status', slot.status);
                    button.setAttribute('data-time-in', slot.time_in);
                    
                    // Set the button's inner text to the slot number
                    button.textContent = slotNumber;

                    // Check if the status is "Occupied" and add the occupied class if truesoptoihgjd and if not, disable viewing the slot 
                    if (slot.status === 'Occupied') {
                        button.classList.add('occupied');
                    } else {
                        button.setAttribute('disabled', 'disabled');
                        button.style.pointerEvents = 'none';
                    }

                    button.addEventListener('click', function () {
                        const selectedZone = this.getAttribute('data-zone');
                        const selectedSlot = this.getAttribute('data-slot');
                        const selectedFloor = this.getAttribute('data-floor');
                        const licensePlate = this.getAttribute('data-license-plate');
                        const userType = this.getAttribute('data-user-type');
                        const vehicleType = this.getAttribute('data-vehicle-type');
                        const status = this.getAttribute('data-status');
                        const timeIn = this.getAttribute('data-time-in');

                        // Update the Bootstrap modal with the slot's information
                        document.getElementById('modal-floor').textContent = `${selectedFloor}`;
                        document.getElementById('modal-zone').textContent = `${selectedZone}`;
                        document.getElementById('modal-slot').textContent = `${selectedSlot}`;
                        document.getElementById('modal-license-plate').textContent = `${licensePlate}`;
                        document.getElementById('modal-user-type').textContent = `${userType}`;
                        document.getElementById('modal-vehicle-type').textContent = `${vehicleType}`;
                        document.getElementById('modal-status').textContent = `${status}`;

                        document.getElementById('hidden-time-in').value = timeIn;

                        // Handle time_in: if it's 'null', empty, or invalid, hide the field
                        const modalTimeIn = document.getElementById('modal-time-in');
                        const modalTimeInField = document.getElementById('modal-time-in-field');

                        if (timeIn && timeIn !== 'null' && timeIn.trim() !== '') {
                            const date = new Date(timeIn);
                            const formattedDate = date.toLocaleString('en-US', { 
                                year: 'numeric', 
                                month: '2-digit', 
                                day: '2-digit', 
                                hour: '2-digit', 
                                minute: '2-digit', 
                                hour12: true 
                            });
                            modalTimeIn.textContent = formattedDate;
                            modalTimeInField.style.display = 'block'; 
                        } else {
                            modalTimeInField.style.display = 'none'; 
                        }

                        // Display vehicle type Image
                        const vehicleImageContainer = document.querySelector('.view-vehicle-type');
                        vehicleImageContainer.innerHTML = ''; 

                        let imgSrc = '';

                        // Match vehicle type and set corresponding image
                        if (vehicleType === 'Car') {
                            imgSrc = '../img/Cars.svg';
                        } else if (vehicleType === 'Motorcycle') {
                            imgSrc = '../img/Moto.svg';
                        } else if (vehicleType === 'Bicycle') {
                            imgSrc = '../img/Bikes.svg';
                        } else {
                            imgSrc = '../img/Parking.svg'; 
                        }

                        const vehicleImg = document.createElement('img');
                        vehicleImg.src = imgSrc;
                        vehicleImg.alt = vehicleType;
                        vehicleImageContainer.appendChild(vehicleImg);

                        const modal = new bootstrap.Modal(document.getElementById('slotModal'));
                        modal.show();
                    });

                    container.appendChild(button);
                    slotNumber++; 
                }
            });
        });
    });
    </script>

    <script>
      document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("staffSlotManagement").onclick = function () {
            location.href = "staffSlotManagement.php";
        }
        document.getElementById("staffSlotOverview").onclick = function () {
          location.href = "StaffSlotOverview.php";
        }
      });
     </script>

    <script>
        const urlParams = new URLSearchParams(window.location.search);
if (
  urlParams.has("add_slot") ||
  urlParams.has("edit_slot") ||
  urlParams.has("checkout_slot") ||
  urlParams.has("user_edit") ||
  urlParams.has("password_changed")
) {
  document.getElementById("loader-container").style.display = "none";

  // Disable the header immediately
  document.querySelector("header").classList.add("disabled");

  // Disable other specified elements
  document.querySelector(".reserved-list-container").classList.add("disabled");
  document.querySelector(".sidebar").classList.add("disabled");
  document.querySelector(".slot-overview").classList.add("disabled");
}

    </script>
     <script src="../js/modal.js"></script>
     <script src="../js/loading.js"></script>
     <script src="../js/floorPagination.js"></script>
     <script src="../js/section.js"></script>


     <?php include '../php/alerts.php'; ?>
</body>
</html>
