<?php include "php/connections.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Email = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
    $Password = htmlspecialchars($_POST['Password']);
    $FirstName = htmlspecialchars(trim($_POST['FirstName']));
    $LastName = htmlspecialchars(trim($_POST['LastName']));
    $Gender = htmlspecialchars(trim($_POST['Gender']));
    $BirthDate = htmlspecialchars($_POST['BirthDate']);
    $Address = htmlspecialchars($_POST['Address']);
    $PhoneNumber = htmlspecialchars($_POST['PhoneNumber']);
    $Account_type = '2';
    
    $photoFilePath = ''; // Initialize variable for photo file path

    if (isset($_FILES['Photo'])) {
        if ($_FILES['Photo']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['Photo']['tmp_name'];
            $fileName = $_FILES['Photo']['name'];
            $fileSize = $_FILES['Photo']['size'];
            $fileType = $_FILES['Photo']['type'];

            // Validate the image file type
            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!in_array($fileType, $allowedFileTypes)) {
                echo "<script>alert('Invalid image format. Please upload JPEG, PNG, or GIF.'); window.location.href='register.php?register_error=true';</script>";
                exit;
            }

            // Validate file size 
            if ($fileSize > 2 * 1024 * 1024) {
                echo "<script>alert('File size exceeds 2MB limit.'); window.location.href='register.php?register_error=true';</script>";
                exit;
            }

            // Move the uploaded file to the uploads directory
            $uploadFileDir = 'uploads/';
            $newFileName = uniqid() . '_' . basename($fileName);
            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $photoFilePath = $newFileName; 
            } else {
                echo "<script>alert('There was an error moving the uploaded file.'); window.location.href='register.php?register_error=true';</script>";
                exit;
            }
        } else {
            echo "<script>alert('No image uploaded or there was an upload error.'); window.location.href='register.php?register_error=true';</script>";
            exit;
        }
    } else {
        echo "<script>alert('File input not set.'); window.location.href='register.php?register_error=true';</script>";
        exit;
    }

    // Validate email
    if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format'); window.location.href='register.php?register_error=true';</script>";
        exit;
    }

    // Validate password length
    if (strlen($Password) < 8) {
        echo "<script>alert('Password must be at least 8 characters long'); window.location.href='register.php?register_error=true';</script>";
        exit;
    }

    // Check if email already exists
    $checkEmailStmt = $connections->prepare("SELECT * FROM usertbl WHERE Email = ?");
    $checkEmailStmt->bind_param("s", $Email);
    $checkEmailStmt->execute();
    $result = $checkEmailStmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>window.location.href='register.php?email_exists=true'</script>";
        $checkEmailStmt->close();
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);

    // Prepare to insert data into the database
    $stmt = $connections->prepare("INSERT INTO usertbl (Email, Password, FirstName, LastName, Gender, BirthDate, Address, PhoneNumber, Account_type, Photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssis", $Email, $hashedPassword, $FirstName, $LastName, $Gender, $BirthDate, $Address, $PhoneNumber, $Account_type, $photoFilePath);

    if ($stmt->execute()) {
        $_SESSION['Email'] = $Email;
        echo "<script>window.location.href='Staff/StaffSlotManagement.php?register_success=true';</script>";
    } else {
        echo "<script>alert('Error registering user: " . $stmt->error . "'); window.location.href='register.php?register_error=true';</script>";
    }

    $stmt->close();
    $connections->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="icon" href="img/logo.png">
    <!-- Libraries -->
    <link rel="stylesheet" href="lib/css/sweetalert.css">
    <link rel="stylesheet" href="lib/css/toastr.css">
    <link rel="stylesheet" href="lib/css/flatpickr.min.css">
    <link rel="stylesheet" href="lib/icons/css/all.css"/>
    <script src="lib/js/jquery-3.7.1.min.js"></script>
    <script src="lib/js/sweetalert.js"></script>
    <script src="lib/js/flatpickr.min.js"></script>
    <!-- Styling -->
    <link rel="stylesheet" href="auth.css">
</head>
<body>

<main>
<div class="image-container">
    <?php include 'components/Auth/signupImg.php';?>
</div>

<div class="main-container">
    <div class="logo-container">
        <img src="img/logo.png" alt="Logo">
        <div class="intro">
            <h3>Create an Account</h3>
            <p>Please enter your details</p>
        </div>
    </div>
    <form action="register.php" id="registerForm" method="POST" enctype="multipart/form-data">

    <div class="register-container" id="register" data-step="1">
        <div class="input">
            <input type="email" id="Email" placeholder="" autocomplete="off" name="Email" required>
            <label for="Email">Email</label>
            <div class="error-message" id="email-invalid"></div>
        </div>
        <div class="input">
            <input type="password" id="Password" placeholder="" name="Password" required>
            <label for="Password">Password</label>
            <i id="eye" class="fa-regular fa-eye"></i>
            <div class="password-validation"></div>
            <div class="error-message" id="password-invalid"></div>
        </div>
        <button type="button" class="next-button">Sign Up</button>
    </div>
    
    <div class="register-container" data-step="2">
        <div class="input">
            <input type="text" id="FirstName" placeholder="" autocomplete="off" name="FirstName" required>
            <label for="FirstName">First Name</label>
            <div class="error-message" id="firstname-invalid"></div>
        </div>
        <div class="input">
            <input type="text" id="LastName" placeholder="" autocomplete="off" name="LastName" required>
            <label for="LastName">Last Name</label>
            <div class="error-message" id="lastname-invalid"></div>
        </div>
        <div class="input">
            <select id="Gender" placeholder=" " onchange="moveLabel('Gender')" autocomplete="off" name="Gender" required>
                <option value=" " disabled selected></option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>
            <label for="Gender">Gender</label>
            <div class="error-message" id="gender-invalid"></div>
            
        </div>
        <div class="buttons">
            <button type="button" class="prev-button">Back</button>
            <button type="button" class="next-button">Next</button>
        </div>
    </div>
    
    <div class="register-container" data-step="3">
        <div class="input">
            <input type="date" id="BirthDate" placeholder="" autocomplete="off" name="BirthDate" required>
            <label for="BirthDate">Birth Date</label>
            <div class="error-message" id="birthdate-invalid"></div>
        </div>
        <div class="input">
            <input type="text" id="Address" placeholder="" autocomplete="off" name="Address" required>
            <label for="Address">Address</label>
            <div class="error-message" id="address-invalid"></div>
        </div>
        <div class="input">
            <input type="tel" id="PhoneNumber" placeholder="" maxlength="11" autocomplete="off" name="PhoneNumber" required>
            <label for="PhoneNumber">Phone Number</label>
            <div class="error-message" id="phone-invalid"></div>
        </div>
        <div class="buttons">
            <button type="button" class="prev-button">Back</button>
            <button type="button" class="next-button">Next</button>
        </div>
    </div>

    <div class="register-container" data-step="4">
        <div class="input-photo">
            <div class="drop-area" id="drop-area">
                <p>Drag & Drop your image here or <strong>click to select image</strong></p>
                <input type="file" name="Photo" id="fileElem" accept="image/*" style="display:none;">
                <img id="preview" class="preview-img" alt="Image Preview">
                <div class="file-info" id="file-info"></div>
            </div>
            <div class="error-message" id="photo-invalid"></div>
        </div>
        <div class="buttons">
            <button type="button" class="prev-button">Back</button>
            <button type="submit" id="submit">Submit</button>
        </div>
    </div>

    </form>
    <div class="footer">
        <p>Already have an account?</p>
        <a href="login.php" class="link">Log in</a>
    </div>
</div>
</main>

</body>
</html>

<?php include 'php/alerts.php' ?>

<script>
    //* Function to Validate Formats
// Function to validate email format
function isValidEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Function to validate password format
function isValidPassword(password) {
    const regex = /^(?=.{8,})/;
    return regex.test(password);
}

function isValidFirstName(firstname) {
    const regex = /^[a-zA-Z]+$/;
    return regex.test(firstname);
}

function isValidLastName(lastname) {
    const regex = /^[a-zA-Z]+$/;
    return regex.test(lastname);
}

// Function to validate phone number format
function isValidPhoneNumber(phonenumber) {
    const regex = /^09\d{9}$/;
    return regex.test(phonenumber);
}

    //* Function to Display Errors
// Function to display email format validation error
function displayEmailFormatError(message) {
    const emailErrorDiv = document.getElementById('email-invalid');
    emailErrorDiv.textContent = message;
    emailErrorDiv.classList.remove("show");
    void emailErrorDiv.offsetWidth;
    emailErrorDiv.classList.add("show"); 
}

// Function to display password format validation error
function displayPasswordFormatError(message) {
    const passwordErrorDiv = document.getElementById('password-invalid');
    passwordErrorDiv.textContent = message;
    passwordErrorDiv.classList.remove("show"); 
    void passwordErrorDiv.offsetWidth; 
    passwordErrorDiv.classList.add("show");
}

// Function to display first name format validation error
function displayFirstNameFormatError(message) {
    const firstNameErrorDiv = document.getElementById('firstname-invalid');
    firstNameErrorDiv.textContent = message;
    firstNameErrorDiv.classList.remove("show");
    void firstNameErrorDiv.offsetWidth;
    firstNameErrorDiv.classList.add("show"); 
}

// Function to display last name format validation error
function displayLastNameFormatError(message) {
    const lastNameErrorDiv = document.getElementById('lastname-invalid');
    lastNameErrorDiv.textContent = message;
    lastNameErrorDiv.classList.remove("show");
    void lastNameErrorDiv.offsetWidth;
    lastNameErrorDiv.classList.add("show"); 
}


// Function to display gender error
function displayGenderSelectError(message) {
    const genderErrorDiv = document.getElementById('gender-invalid');
    genderErrorDiv.textContent = message;
    genderErrorDiv.classList.remove("show");
    void genderErrorDiv.offsetWidth;
    genderErrorDiv.classList.add("show"); 
}

// Function to display birthdate error
function displayBirthDateError(message) {
    const birthdateErrorDiv = document.getElementById('birthdate-invalid');
    birthdateErrorDiv.textContent = message;
    birthdateErrorDiv.classList.remove("show");
    void birthdateErrorDiv.offsetWidth;
    birthdateErrorDiv.classList.add("show"); 
}

// Function to display address error
function displayAddressError(message) {
    const addressErrorDiv = document.getElementById('address-invalid');
    addressErrorDiv.textContent = message;
    addressErrorDiv.classList.remove("show");
    void addressErrorDiv.offsetWidth;
    addressErrorDiv.classList.add("show");
}

// Function to display phone number format validation error
function displayPhoneNumberFormatError(message) {
    const phoneNumberErrorDiv = document.getElementById('phone-invalid');
    phoneNumberErrorDiv.textContent = message;
    phoneNumberErrorDiv.classList.remove("show");
    void phoneNumberErrorDiv.offsetWidth;
    phoneNumberErrorDiv.classList.add("show"); 
}

function displayPhotoError(message) {
    const photoErrorDiv = document.getElementById('photo-invalid');
    photoErrorDiv.textContent = message;
    photoErrorDiv.classList.remove("show");
    void photoErrorDiv.offsetWidth;
    photoErrorDiv.classList.add("show");
}


// Handle Next Button Click
document.querySelectorAll('.next-button').forEach(button => {
    button.addEventListener('click', function () {
        const emailInput = document.querySelector('#register input[type="email"]');
        const passInput = document.getElementById('Password');
        const firstnameInput = document.getElementById('FirstName');
        const lastnameInput = document.getElementById('LastName');
        const genderSelect = document.getElementById('Gender');
        const birthInput = document.getElementById('BirthDate');
        const addressInput = document.getElementById('Address');
        const phoneInput = document.getElementById ('PhoneNumber');
        const currentContainer = this.closest('.register-container');

        // Clear previous email format error message
        displayEmailFormatError('');
        displayPasswordFormatError('');
        displayFirstNameFormatError('');
        displayLastNameFormatError('');
        displayGenderSelectError('');
        displayBirthDateError('');
        displayAddressError('');
        displayPhoneNumberFormatError('');

        // Check if the email input exists
        if (emailInput) {
    
            // First Page (Main)
            if (currentContainer.dataset.step === '1') {
                if (emailInput.value.trim() === '') {
                    displayEmailFormatError('Please provide an email address before proceeding!');
                    return; 
                }

                if (!isValidEmail(emailInput.value)) {
                    displayEmailFormatError('Please enter a valid email address!');
                    return; 
                }

                if (passInput.value.trim() === '') {
                    displayPasswordFormatError('Please provide a password before proceeding!');
                    return;
                }

                if (!isValidPassword(passInput.value)) {
                    displayPasswordFormatError('Password must be at least 8 characters long');
                    return;
                }
            }

            // Second Page 
            if (currentContainer.dataset.step === '2') {
                if (firstnameInput.value.trim() === '') {
                    displayFirstNameFormatError('Please enter your first name before Ppoceeding!');
                    return;
                }

                if (!isValidFirstName(firstnameInput.value)) {
                    displayFirstNameFormatError('Please enter only letters. Numbers and symbols are not allowed.');
                    return;
                }

                if (lastnameInput.value.trim() === '') {
                    displayLastNameFormatError('Please enter your last name before proceeding!');
                    return;
                }

                if (!isValidLastName(lastnameInput.value)) {
                    displayLastNameFormatError('Please enter only letters. Numbers and symbols are not allowed.');
                    return;
                }

                if (genderSelect.value.trim() === '') {
                    displayGenderSelectError('Please select your gender');
                    return; 
                }
            }

            // Third Page
            if (currentContainer.dataset.step === '3') {
                if (birthInput.value.trim() === '') {
                    displayBirthDateError('Please enter your birthdate before proceeding!');
                    return;
                }

                if (addressInput.value.trim() === '') {
                    displayAddressError('Please enter your address before proceeding!');
                    return;
                }

                if (phoneInput.value.trim() === '') {
                    displayPhoneNumberFormatError('Please enter your phone number before proceeding!');
                    return;
                }

                if (!isValidPhoneNumber(phoneInput.value)) {
                    displayPhoneNumberFormatError('Invalid Phone Number! Must start with "09" and be 11 digits long.');
                    return;
                }
            }

            document.getElementById('registerForm').addEventListener('submit', function(event) {
                const previewImage = document.getElementById('preview');

                displayPhotoError('');

                if (!previewImage.src) {
                    displayPhotoError('Please choose a photo to proceed!');
                    event.preventDefault();
                }
             });

            // AJAX to check if email exists
            fetch('php/checkEmail.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ Email: emailInput.value }) 
            })
            .then(response => {
                // Check if the response is OK
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json(); 
            })
            .then(data => {
                // Check if the email exists in the database
                if (data.exists) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Email Already Exists',
                        text: 'This email is already registered. Please use another email.'
                    });
                } else {
                    // Proceed to the next step if email does not exist
                    const inputs = currentContainer.querySelectorAll('input');
                    let allFilled = true;

                    inputs.forEach(input => {
                        if (input.value.trim() === '') {
                            allFilled = false;
                        }
                    });

                    // Proceed to the next container if all inputs are filled
                    if (allFilled) {
                        // Start the fade-out transition
                        currentContainer.style.opacity = '0'; 
                        setTimeout(() => {
                            currentContainer.classList.remove('active'); 
                            const nextContainer = currentContainer.nextElementSibling;
                            if (nextContainer) {
                                nextContainer.classList.add('active'); 
                                nextContainer.style.opacity = '0'; 
                                nextContainer.offsetHeight; 
                                nextContainer.style.transition = 'opacity 0.5s ease';
                                nextContainer.style.opacity = '1'; 
                            }
                        }, 300); 
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Please fill in all fields before proceeding!'
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Server Error',
                    text: 'An error occurred while checking the email. Please try again later.'
                });
            });
        } else {
            // Show error if email input is not found
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Email input is missing!'
            });
        }
    });
});

// Handle Previous Button Click
document.querySelectorAll('.prev-button').forEach(button => {
    button.addEventListener('click', function () {
        const currentContainer = this.closest('.register-container');
        const prevContainer = currentContainer.previousElementSibling;

        displayPhotoError('');

        if (prevContainer) {
            // Start the fade-out transition
            currentContainer.style.opacity = '0'; 
            setTimeout(() => {
                currentContainer.classList.remove('active'); 
                prevContainer.classList.add('active');
                prevContainer.style.opacity = '0';
                prevContainer.offsetHeight;
                prevContainer.style.transition = 'opacity 0.5s ease';
                prevContainer.style.opacity = '1'; 
            }, 300); 
        }
    });
});

// Set the first container to active on load
document.getElementById('register').classList.add('active');
document.querySelector('#register').style.opacity = '1'; 
</script>

<script Password Validation>

</script>

<script Input Caret>
    document.querySelectorAll('.register-container input').forEach(input => {
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

<script Gender Label>
function moveLabel(inputId) {
  var inputElement = document.getElementById(inputId);
  var label = document.querySelector('label[for="' + inputId + '"]');

  if (inputElement.value) {
    label.style.top = "-10px";
    label.style.fontSize = "0.8em";
  } else {
    label.style.top = "0";
  }
}
</script>

</script>

<script BirthDate>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#BirthDate", {
            dateFormat: "Y-m-d",
            maxDate: "today" 
        });
    });
</script>

<script PhotoHandling>
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('fileElem');
    const previewImg = document.getElementById('preview');
    const fileInfo = document.getElementById('file-info');
    const dropText = dropArea.querySelector('p');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    // Remove highlight when dragging leaves
    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false);
    dropArea.addEventListener('click', () => fileInput.click(), false);

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    function highlight() {
        dropArea.classList.add('highlight');
    }

    function unhighlight() {
        dropArea.classList.remove('highlight');
    }

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    fileInput.addEventListener('change', (e) => {
        const files = e.target.files;
        handleFiles(files);
    });

    function handleFiles(files) {
    fileInfo.innerHTML = '';
    if (files.length > 0) {
        const file = files[0]; 
        if (file && file.type.startsWith('image/')) {
            // Update the input's files property
            fileInput.files = files; 

            const reader = new FileReader();
                reader.onload = function (event) {
                    previewImg.src = event.target.result; 
                    previewImg.style.display = 'block'; 
                    previewImg.classList.add('grow'); 
                    dropArea.classList.add('grow');
                    dropArea.classList.add('valid');
                    dropText.style.opacity = '0';
                    fileInfo.style.opacity = '0';
                    displayPhotoError('');

                    // Remove the class after the animation duration
                    setTimeout(() => {
                        previewImg.classList.remove('grow'); 
                        dropArea.classList.remove('grow'); 
                    }, 500); 
                };
            reader.readAsDataURL(file);
            fileInfo.innerHTML = `<p>${file.name} (${(file.size / 1024).toFixed(2)} KB)</p>`;
        } else {
                Swal.fire({
            icon: 'error',
            title: 'Invalid File Type',
            text: 'Please upload a valid image file (JPEG, PNG, or GIF).',
            timer: 2000,
            showConfirmButton: false
        });
        fileInput.value = ''; 
        previewImg.style.display = 'none'; 
        fileInfo.innerHTML = '';
        dropText.style.opacity = '1'; 
        fileInfo.style.opacity = '1';
        }
    }
}
</script>

<script src="js/togglePassword.js"></script>
