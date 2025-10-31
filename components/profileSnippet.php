    <div class="snippet-overlay">
    <form action="../php/Logout.php" method="POST">
        <div class="snippet-container">
            <div class="snippet-bg"></div>
            <div class="snippet-profile">
                <img class="profileButton" src="<?php echo htmlspecialchars($Photo); ?>" alt="">
                <span class="circle <?php echo $status == 'online' ? 'online' : 'offline'; ?>"></span>
            </div>
            <div class="snippet-content">
                <div class="snippet-info">
                    <div class="snippet-name">
                        <?php echo htmlspecialchars($FirstName);?>
                    </div>
                    <div class="snippet-email">
                        <?php echo htmlspecialchars($Email); ?>
                    </div>
                </div>
                <div class="snippet-actions">
                    <button type="button" class="profileButton"> <i class="fa-solid fa-user"></i> View Profile</button>
                    <div class="divide"></div>
                    <button type="button" class="editProfile"> <i class="fa-solid fa-pencil"></i> Edit Profile</button>
                </div>
                
                <div class="snippet-logout">
                    <button type="submit"> <i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</button>
                </div>
            </div>
        </div>
        </form>
    </div>
