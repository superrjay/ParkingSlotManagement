<?php include '../php/connections.php';
      include '../php/adminLoginData.php';    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="view-transition" content="same-origin" />
    <title>Slot Management</title>
    <link rel="icon" href="../img/logo.png">
    <!-- Libraries -->
    <link rel="stylesheet" href="../lib/css/bootstrap.min.css" />
    <link rel="stylesheet" href="../lib/css/sweetalert.css">
    <link rel="stylesheet" href="../lib/css/toastr.css">
    <link rel="stylesheet" href="../lib/css/flatpickr.min.css">
    <link rel="stylesheet" href="../lib/icons/css/all.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <script src="../lib/js/jquery-3.7.1.min.js"></script>
    <script src="../lib/js/bootstrap.bundle.js"></script>
    <script src="../lib/js/qrious.min.js"></script>
    <script src="../lib/js/sweetalert.js"></script>
    <script src="../lib/js/toastr.js"></script>
    <script src="../lib/js/flatpickr.min.js"></script>
    <!-- Styling -->
    <link rel="stylesheet" href="../admin.css">
</head>
<body>
    
    <div class="sidebar shrink">
        <div class="logo">
            <img src="../img/logo.png" alt="">
        </div>

        <div class="links-container">
            <ul class="list">
                <li>
                    <a class="links" href="Dashboard.php">
                        <i class='bx bx-command' ></i>
                        <span class="link-text">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="links" href="SlotManagement.php">
                        <i class='bx bx-car' ></i>
                        <span class="link-text">Slot Management </span>
                    </a>
                </li>
                <li>
                <a class="links active" href="UserManagement.php">
                        <i class='bx bx-user' ></i>
                        <span class="circle"></span>
                        <span class="link-text">User Management</span>
                </a>
                </li>
            </ul>
        </div>

        <div class="side-footer">
            
        </div>
    </div>

    <section class="content">
        <?php include '../components/Admin/Navigation.php'; ?>
    </section>

    <script src="../js/toggleSidebar.js"></script>
</body>
</html>