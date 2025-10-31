<div class="sidebar">
    <div class="sidebar-contents">
        <div class="user-profile">
            <img class="profileButton" src="<?php echo htmlspecialchars($Photo)?>" alt="">
         <div class="user-info">
            <h2><?php echo htmlspecialchars($FirstName)?></h2>
            <p><?php echo htmlspecialchars($Account_role)?></p>
         </div>
        </div>
        <div class="sidebar-button">
            <button class="action-button"
            data-bs-toggle="modal"
            data-bs-target="#addSlotModal">
            <span class="corner-text">F6</span>
            <span class="center-text">ADD</span>
            </button>
        </div>
    </div>
</div>


