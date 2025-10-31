<?php 

$date = htmlspecialchars($BirthDate);

$formattedDate = date("F j, Y", strtotime($date));

?>

<div class="profileModal-Overlay">
    <div class="profileModal-Container">
        <div class="profile-bg">
            <div class="profile-img">
                <img src="<?php echo htmlspecialchars($Photo); ?>" alt="">
                <span class="image-indicator">
                    <span class="indicator"></span>
                </span>
            </div>      
        </div>

        <div class="profile-info">
            <div class="profile-main">
                <div class="edit-Profile">
                    <button type="button" class="editProfile"><i class="fa-solid fa-pen"></i> Edit Profile</button>
                </div>
                <h2><?php echo htmlspecialchars($FirstName). ' ' . htmlspecialchars($LastName)?></h2>
                <p><?php echo htmlspecialchars($Email); ?></p>
            </div>

            <div class="profile-detailsContainer">
                <div class="profile-row">
                    <div class="profile-title">Gender</div>
                    <div class="profile-detail"><?php echo htmlspecialchars($Gender); ?></div>
                </div>

                <div class="profile-row">
                    <div class="profile-title">Phone Number</div>
                    <div class="profile-detail"><?php echo htmlspecialchars($PhoneNumber); ?></div>
                </div>

                <div class="profile-row">
                    <div class="profile-title">Birth Date</div>
                    <div class="profile-detail"><?php echo htmlspecialchars($formattedDate); ?></div>
                </div>

                <div class="profile-row">
                    <div class="profile-title">Address</div>
                    <div class="profile-detail"><?php echo htmlspecialchars($Address); ?></div>
                </div>

                <div class="profile-row">
                    <div class="profile-title">Role</div>
                    <div class="profile-detail"><?php echo htmlspecialchars($Account_role); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>