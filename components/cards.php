<?php 

$parkingData = fetchParking();

$totalSlots = 0;
$availableSlots = 0;
$occupiedSlots = 0;
$reservedSlots = 0;

// Loop through the fetched parking data to count slots by status
foreach ($parkingData as $slot) {
    $totalSlots++;
    
    if ($slot['status'] === 'Available') {
        $availableSlots++;
    } elseif ($slot['status'] === 'Occupied') {
        $occupiedSlots++;
    } elseif ($slot['status'] === 'Reserved') {
        $reservedSlots++;
    }
}
?>

<div class="cards-container">
        <div class="cards total">
            <div class="card-info">
                <div class="card-data"><?php echo $totalSlots; ?></div>
                <div class="card-title">Total Slots</div>
            </div>
            <div class="card-icon">
                <i class="fas fa-th"></i>
            </div>
        </div>
        <div class="cards available">
            <div class="card-info">
                <div class="card-data"><?php echo $availableSlots; ?></div>
                <div class="card-title">Available Slots</div>
            </div>
            <div class="card-icon">
                <i class="fas fa-parking"></i>
            </div>
        </div>
        <div class="cards occupied">
            <div class="card-info">
                <div class="card-data"><?php echo $occupiedSlots; ?></div>
                <div class="card-title">Occupied Slots</div>
            </div>
            <div class="card-icon">
                <i class="fas fa-car"></i>
            </div>
        </div>
        <div class="cards reserved">
            <div class="card-info">
                <div class="card-data"><?php echo $reservedSlots; ?></div>
                <div class="card-title">Reserved Slots</div>
            </div>
            <div class="card-icon">
                <i class="fas fa-user-lock"></i>
            </div>
        </div>
    </div>