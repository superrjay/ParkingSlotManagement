<?php 
session_start();
$Email = $passwordPost = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Email
    if (empty($_POST["Email"])) {
        echo "<script>window.location.href='login.php?email_empty=true';</script>";
        exit; 
    } else {
        $Email = $_POST["Email"];
    }

    // Validate Password
    if (empty($_POST["Password"])) {
        echo "<script>window.location.href='login.php?password_empty=true';</script>";
        exit; 
    } else {
        $passwordPost = $_POST["Password"];
    }

    // Check credentials only if both fields are filled
    if ($Email && $passwordPost) {
        include("php/connections.php");

        // Prepare the SQL statement to prevent SQL injection
        $stmt = $connections->prepare("SELECT Password, Account_type FROM usertbl WHERE Email = ?");
        $stmt->bind_param("s", $Email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $db_password = $row["Password"];
            $db_account_type = $row["Account_type"]; 

            // Verify the password using password_verify
            if (password_verify($passwordPost, $db_password)) {
                // Password is correct, start the session based on account type
                $_SESSION['Email'] = $Email;
                if ($db_account_type == "1") {
                    echo "<script>window.location.href='Admin/Dashboard.php?welcome_admin=true';</script>";
                } elseif ($db_account_type == "2") {
                    echo "<script>window.location.href='Staff/StaffSlotManagement.php?welcome_user=true';</script>";
                } else {
                    echo "<script>window.location.href='staffPage/StaffDashboard.php?welcome_user=true';</script>";
                }
            } else {
                // Password incorrect
                echo "<script>window.location.href='login.php?password_error=true';</script>";
            }
        } else {
            // Email is not registered
            echo "<script>window.location.href='login.php?email_error=true';</script>";
        }

        // Close the statement
        $stmt->close();
    }
}

// Reset error messages when the page is loaded initially
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $EmailErr = $passwordErr = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="icon" href="img/logo.png">
    <!-- Libraries -->
    <link rel="stylesheet" href="lib/css/sweetalert.css">
    <link rel="stylesheet" href="lib/css/toastr.css">
    <link rel="stylesheet" href="lib/icons/css/all.css"/>
    <script src="lib/js/jquery-3.7.1.min.js"></script>
    <script src="lib/js/sweetalert.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/SmoothState.js/0.1.3/jquery.smoothState.min.js"></script>
    <!-- Styling -->
    <link rel="stylesheet" href="auth.css">
</head>
<body>

<main id="main" class="smoothState">
<div class="image-container">
    <?php include 'components/Auth/loginImg.php';?>
</div>

<div class="main-container">
    <div class="logo-container">
        <img src="img/logo.png" alt="Logo">
        <div class="intro">
            <h3>Welcome back!</h3>
            <p>Please enter your details</p>
        </div>
    </div>
    <form id="submitForm" action="login.php" method="POST" novalidate>
    <div class="input-container">
        <div class="input">
            <input type="email" id="Email" placeholder="" autocomplete="off" name="Email" >
            <label for="Email">Email</label>
            <div class="error-message" id="email-invalid"></div>
        </div>
        <div class="input">
            <input type="password" id="Password" placeholder="" name="Password" >
            <label for="Password">Password</label>
            <i id="eye" class="fa-regular fa-eye"></i>
            <div class="error-message" id="password-invalid"></div>
        </div>
        <button type="submit" id="submit">Log in</button>
    </div>
    </form>
    <div class="footer">
        <p>Don't have an Account?</p>
        <a href="register.php" class="link">Sign Up</a>
    </div>
</div>
</main>
    
</body>
</html>

<?php include 'php/alerts.php' ?>

<script>
// Function to validate email format
function isValidEmail(email) {
    const regex = /^[a-zA-Z0-9._%+-]+@(gmail|yahoo|outlook|hotmail|icloud|aol|protonmail)\.(com|org|net|gov|edu|info|co|io|me)$/i;
    return regex.test(email);
}

function displayEmailError(message) {
    const emailErrorDiv = document.getElementById('email-invalid');
    emailErrorDiv.textContent = message;
    emailErrorDiv.classList.remove("show");
    void emailErrorDiv.offsetWidth;
    emailErrorDiv.classList.add("show");
}

function displayPasswordError(message) {
    const passwordErrorDiv = document.getElementById('password-invalid');
    passwordErrorDiv.textContent = message;
    passwordErrorDiv.classList.remove("show");
    void passwordErrorDiv.offsetWidth; 
    passwordErrorDiv.classList.add("show");
}

// Add event listener to the form submit event
document.getElementById('submitForm').addEventListener('submit', function(event) {
    console.log("Form submit event triggered.");

    const emailInput = document.getElementById('Email');
    const passInput = document.getElementById('Password');
    let formIsValid = true;

    // Clear previous error messages
    displayEmailError('');
    displayPasswordError('');

    if (emailInput.value.trim() === '') {
        displayEmailError('Please provide an email address before proceeding!');
        formIsValid = false;
    } else if (!isValidEmail(emailInput.value)) {
        displayEmailError('Please enter a valid email address!');
        formIsValid = false;
    } else {
        // Only check the password if the email is valid
        if (passInput.value.trim() === '') {
            displayPasswordError('Please provide a password before proceeding!');
            formIsValid = false;
        }
    }

    if (!formIsValid) {
        event.preventDefault();
    } 
});
</script>



<script Input Caret>
    document.querySelectorAll('.input-container input').forEach(input => {
    input.addEventListener('focus', function() {
        const label = input.nextElementSibling;

        label.addEventListener('transitionend', function handleTransitionEnd(event) {
            if (event.propertyName === 'top') {
                input.style.caretColor = '#000';
                label.removeEventListener('transitionend', handleTransitionEnd); 
            }
        });
    });

    input.addEventListener('blur', function() {
        if (!input.value) {
            input.style.caretColor = 'transparent';
        }
    });
});
</script>

<script src="js/togglePassword.js"></script>