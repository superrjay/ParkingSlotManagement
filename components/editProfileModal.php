<div class="editProfile-Overlay">
    <div class="editProfile-Container">
        <form id="editProfileForm" action="../php/Staffs/editProfile.php" spellcheck="false">
            <input type="hidden" name="current_page" value="<?php echo htmlspecialchars($current_page); ?>">
            <div class="editProfile-bg">
            <div class="editProfile-img">
                <input type="file" id="imageInput" name="Photo" accept="image/*" style="display: none;" />
                <label for="imageInput" class="custom-file-upload">
                    <img id="profileImage" src="<?php echo htmlspecialchars($currentUser['Photo']); ?>" alt="Profile Image">
                    <span class="image-indicator">
                        <i class="fa-solid fa-camera"></i>
                    </span>
                </label>
            </div>
            </div>
            
            <div class="editProfile-displayInfo">
                <div class="profile-display">
                    <div class="view-profile">
                        <button type="button" class="profileButton"><i class="fa-solid fa-link"></i> View Profile</button>
                    </div>
                    <h2><?php echo htmlspecialchars($FirstName). ' ' .htmlspecialchars($LastName); ?></h2>
                    <p><?php echo htmlspecialchars($Email);?></p>
                </div>

                <div class="editProfile-displayInputs">
                    <div class="input-row">
                        <div class="input-title name">Name</div>
                        <div class="input-set">
                            <div class="input-form">
                                <input type="text" class="set-input" id="FirstName" name="FirstName" autocomplete="off" value="<?php echo htmlspecialchars($currentUser['FirstName']) ?>"  placeholder=" ">
                            </div>
                            <div class="input-form">
                                <input type="text" class="set-input" id="LastName" name="LastName" autocomplete="off" value="<?php echo htmlspecialchars($currentUser['LastName']) ?>"  placeholder=" ">  
                            </div>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="input-title">Email</div>
                        <div class="input-set">
                            <div class="input-form">
                                <input name="Email" class="set-input" id="Email" type="text" placeholder=" " autocomplete="off" value="<?php echo htmlspecialchars($currentUser['Email']) ?>"  />
                            </div>
                            <div class="input-form">
                            <button class="input-button" id="newPass" type="button">Change password</button>
                            </div>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="input-title indiv">Gender</div>
                        <div class="input-form full">
                            <select name="Gender" id="Gender">
                                <option value="" disabled <?php if (empty($Gender)) echo 'selected'; ?>></option>
                                <option value="Male" <?php echo ($currentUser['Gender'] === 'Male') ? 'selected' : ''; ?>>Male</option>
                                <option value="Female" <?php echo ($currentUser['Gender'] === 'Female') ? 'selected' : ''; ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="input-title indiv">Phone Number</div>
                        <div class="input-form full">
                            <input type="tel" id="PhoneNumber" placeholder="" maxlength="11"  value="<?php echo htmlspecialchars($currentUser['PhoneNumber']); ?>" autocomplete="off" name="PhoneNumber" required>
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="input-title indiv">Birth Date</div>
                        <div class="input-form full">
                            <input type="date" name="BirthDate" id="BirthDate" value="<?php echo htmlspecialchars($currentUser['BirthDate']); ?>"  placeholder=" ">
                        </div>
                    </div>
                    <div class="input-row">
                        <div class="input-title indiv">Address</div>
                        <div class="input-form full">
                            <input type="text" name="Address" autocomplete="off" value="<?php echo htmlspecialchars($currentUser['Address']); ?>"  placeholder=" ">
                        </div>
                    </div>
                </div>

                <div class="editProfile-Footer">
                    <button type="button" class="close-btn" id="closeEdit">Close</button>
                    <button type="submit" id="submitEdit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script Photo Handling>
    document.addEventListener("DOMContentLoaded", function () {
    const imageInput = document.getElementById("imageInput");
    const profileImage = document.getElementById("profileImage");

    // Event listener for image input
    imageInput.addEventListener("change", function (event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                profileImage.src = e.target.result; 
            };
            reader.readAsDataURL(file);
            }
        });
    });
</script>

<script>
$(document).ready(function() {
    $('#submitEdit').on('click', function(e) {
        e.preventDefault();

        // Collect the form data, including file uploads
        var formData = new FormData(document.getElementById("editProfileForm"));

        $.ajax({
            url: '../php/Staffs/editProfile.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Display SweetAlert success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Profile Updated',
                        text: response.message,
                        confirmButtonColor: '#3085d6'
                    }).then(() => {
                        // Redirect to the new page
                        window.location.href = response.redirect;
                    });
                } else if (response.status === 'no_change') {
                    // Display SweetAlert message indicating no changes
                    Swal.fire({
                        icon: 'info',
                        title: 'No Changes Detected',
                        text: response.message,
                        confirmButtonColor: '#3085d6'
                    });
                } else {
                    // Display SweetAlert error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message,
                        confirmButtonColor: '#d33'
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    icon: 'error',
                    title: 'AJAX Error',
                    text: 'There was an error updating the profile. Please try again.',
                    confirmButtonColor: '#d33'
                });
            }
        });
    });
});


</script>

<script BirthDate>
    document.addEventListener("DOMContentLoaded", function() {
        flatpickr("#BirthDate", {
            dateFormat: "Y-m-d",
            maxDate: "today" 
        });
    });
</script>

