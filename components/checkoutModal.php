<!-- Modal -->
<div
    class="modal fade"
    id="checkoutModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="checkoutModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog checkout" role="document">
        <div class="modal-content checkout">
            <div class="modal-body checkout">
                <form action="../php/parkingExecute.php" method="POST">
                    <div class="checkout-data">
                       
                            <div class="qr-code-container">
                                <div class="qr-overlay">
                                <canvas id="qrcode"></canvas>
                                </div>
                            </div>
                     
                            <div class="checkout-slot-data">
                            <input type="text" id="display-floor-checkout" name="floor" readonly>
                            <input type="text" id="display-zone-checkout" name="zone" readonly>
                            <input type="text" id="display-slot-checkout" name="slot_number" readonly>
                            </div>
                        
                            <div class="slot-data-checkout">
                            <div class="slot-identifier">Plate No.</div>
                            <div class="slot-text"><p id="checkout-license-plate"></p></div>
                            </div>

                            <div class="slot-data-checkout">
                            <div class="slot-identifier">Vehicle</div>
                            <div class="slot-text"><p id="checkout-vehicle-type"></p></div>
                            </div>

                            <div class="slot-data-checkout">
                            <div class="slot-identifier">Entry</div>
                            <div class="slot-text"><p id="checkout-time-in"></p></div>
                            </div>

                            <div class="slot-data-checkout">
                            <div class="slot-identifier">Exit</div>
                            <div class="slot-text"><p id="checkout-time-out"></p></div>
                            </div>

                            <div class="slot-data-checkout">
                            <div class="slot-identifier">Duration</div>
                            <div class="slot-text"><p id="checkout-duration"></p></div>
                            </div>
                            
                            <div class="total-header">Total:</div>
                            <div class="slot-total">
                                <div class="total-fee">
                                <i class="fa-solid fa-peso-sign"></i><span id="checkout-fee"></span>
                                </div>
                            </div>

                            <div class="checkout-footer">
                                <button type="submit" name="checkoutSlot"><i class="fa-solid fa-check"></i></button>
                            </div>
                    </div>
                    <input type="hidden" name="current_page" value="<?php echo htmlspecialchars($current_page); ?>">
                    <input type="hidden" id="hidden-license-plate-checkout" name="plate_number">
                    <input type="hidden" id="hidden-user-type" name="user_type">
                    <input type="hidden" id="hidden-vehicle-type" name="vehicle_type">        
                    <input type="hidden" id="hidden-status" name="status">        
                    <input type="hidden" id="form-time-in" name="time_in">             
                    <input type="hidden" id="hidden-time-out" name="time_out">                    
                    <input type="hidden" id="hidden-duration" name="duration">               
                    <input type="hidden" id="hidden-fee" name="fee">               
            </div>
        </div>
        </form>
        <div class="container-footer">
             <img src="../img/triangle-rounded-divider.svg" alt="">
        </div>
    </div>
</div>

<script>
 let currentPrintWindow = null; 

function generateQRCode() {
    // Fetch the values of the populated inputs
    const floor = document.getElementById("display-floor-checkout").value;
    const zone = document.getElementById("display-zone-checkout").value;
    const slot = document.getElementById("display-slot-checkout").value;
    const plateNumber = document.getElementById("hidden-license-plate-checkout").value;
    const userType = document.getElementById("hidden-user-type").value;
    const vehicleType = document.getElementById("hidden-vehicle-type").value;
    const status = document.getElementById("hidden-status").value;
    const timeIn = document.getElementById("form-time-in").value; 
    const timeOut = document.getElementById("hidden-time-out").value; 
    const duration = document.getElementById("hidden-duration").value;
    const fee = document.getElementById("hidden-fee").value;

    console.log("Generating QR Code with the following data:");
    console.log({ floor, zone, slot, plateNumber, userType, vehicleType, status, timeIn, timeOut, duration, fee });

    // Function to format time as MM/DD/YYYY HH:mm AM/PM
    function formatTime(dateString) {
        const date = new Date(dateString);
        const options = {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            hour12: true
        };
        return date.toLocaleString('en-US', options);
    }

    // Prepare data for QR code
    const qrData = {
        floor,
        zone,
        slot,
        plateNumber,
        userType,
        vehicleType,
        status,
        timeIn: formatTime(timeIn),
        timeOut: formatTime(timeOut),
        duration,
        fee
    };

    const qrString = JSON.stringify(qrData);

    // Generate the QR code using QRious
    const qrCodeElement = document.getElementById('qrcode');
    const qr = new QRious({
        element: qrCodeElement,
        value: qrString,
        size: 172,
    });

    // Attach the click event listener to the QR code canvas
    qrCodeElement.addEventListener('click', function() {
        // Close the previous print window if it exists
        if (currentPrintWindow) {
            currentPrintWindow.close();
        }

        // Open a new print window
        currentPrintWindow = window.open('', '', 'width=1200, height=800');
        if (!currentPrintWindow) {
            console.error("Failed to open print window. Check pop-up settings.");
            return;
        }

        // Prepare the print content
        const qrImage = qrCodeElement.toDataURL("image/png");
        currentPrintWindow.document.write('<html><head><title>Print QR Code</title>');
        currentPrintWindow.document.write('<style>body { text-align: center; font-family: Arial, sans-serif; }</style>');
        currentPrintWindow.document.write('</head><body>');
        currentPrintWindow.document.write('<h1>Parking Receipt</h1>');
        currentPrintWindow.document.write('<img src="' + qrImage + '" style="max-width: 100%; height: auto;"/>');

        // Add the details to the print window
        currentPrintWindow.document.write('<h3>Parking Slot Details:</h3>');
        currentPrintWindow.document.write('<p><strong>Floor:</strong> ' + floor + '</p>');
        currentPrintWindow.document.write('<p><strong>Zone:</strong> ' + zone + '</p>');
        currentPrintWindow.document.write('<p><strong>Slot:</strong> ' + slot + '</p>');
        currentPrintWindow.document.write('<p><strong>Plate Number:</strong> ' + plateNumber + '</p>');
        currentPrintWindow.document.write('<p><strong>User Type:</strong> ' + userType + '</p>');
        currentPrintWindow.document.write('<p><strong>Vehicle Type:</strong> ' + vehicleType + '</p>');
        currentPrintWindow.document.write('<p><strong>Status:</strong> ' + status + '</p>');
        currentPrintWindow.document.write('<p><strong>Time In:</strong> ' + qrData.timeIn + '</p>');
        currentPrintWindow.document.write('<p><strong>Time Out:</strong> ' + qrData.timeOut + '</p>');
        currentPrintWindow.document.write('<p><strong>Duration:</strong> ' + duration + ' hours</p>');
        currentPrintWindow.document.write('<p><strong>Fee:</strong> ' + fee + '</p>');
        currentPrintWindow.document.write('</body></html>');
        currentPrintWindow.document.close();

        // Wait for the new window to load before printing
        currentPrintWindow.onload = function() {
            currentPrintWindow.print();
            currentPrintWindow.close(); 
            currentPrintWindow = null; 
        };
    });

    // Attach the click event listener to the wrapper
    const qrWrapper = document.querySelector('.qr-code-container');
    qrWrapper.addEventListener('click', function() {
        // Trigger the click event on the QR code canvas
        qrCodeElement.click();
    });
}

</script>