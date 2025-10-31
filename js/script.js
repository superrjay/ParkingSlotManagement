document.getElementById('searchButton').addEventListener('click', function() {
    var cardNumber = document.getElementById('searchCardNumber').value;

    // Fetch record from server
    fetch('search_record.php?cardNumber=' + encodeURIComponent(cardNumber))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Populate fields with data from the server
                document.getElementById('cardNumber').value = data.record.card_number;
                document.getElementById('charge').value = data.record.charge;
                document.getElementById('recordDetails').style.display = 'block';
            } else {
                alert(data.message); // Show error message if no record is found
                document.getElementById('recordDetails').style.display = 'none';
            }
        })
        .catch(error => console.error('Error:', error));
});




// Keyboard shortcuts for button clicks
document.addEventListener('keydown', function(event) {
    if (event.key === 'F1') {
        event.preventDefault();
        document.getElementById('paymentButtonSidebar').click();  // Simulate click on Payment button
    }
    if (event.key === 'F2') {
        event.preventDefault();
        document.getElementById('addButtonSidebar').click();  // Simulate click on Add button
    }
    if (event.key === 'F3') {
        event.preventDefault();
        document.getElementById('voidButtonSidebar').click();  // Simulate click on Void button
    }
    if (event.key === 'F4') {
        event.preventDefault();
        document.getElementById('rewardCardButtonSidebar').click();  // Simulate click on Reward Card button
    }
    if (event.key === 'F5') {
        event.preventDefault();
        document.getElementById('concernButtonSidebar').click();  // Simulate click on Concern button
    }
});

    //Cash Button Click Event

    $(document).ready(function () {
        // Hide all nested content sections initially
        $('#nestedContentSidebar').hide();
        $('#paymentSectionSidebar').hide();
        $('#addSectionSidebar').hide();
        $('#voidSectionSidebar').hide();
        $('#rewardCardSectionSidebar').hide();
        $('#concernSectionSidebar').hide();

        // Payment button click event
        $('#paymentButtonSidebar').click(function () {
            $('#mainButtonsSidebar').hide();
            $('#nestedContentSidebar').show();
            $('#paymentSectionSidebar').show();
            $('#addSectionSidebar').hide();
            $('#voidSectionSidebar').hide();
            $('#rewardCardSectionSidebar').hide();
            $('#concernSectionSidebar').hide();
        });

        // Cash payment button click event
        $('#cashPaymentButtonSidebar').click(function () {
            // Show the cash payment modal
            $('#paymentModal').modal('show');
        });

        // Payment Modal functionality
        $('#searchButton').click(function () {
            // Simulate search operation, for example purpose
            let cardNumber = $('#searchCardNumber').val();

            if (cardNumber) {
                // Display record details based on card number
                $('#recordDetails').show();
                $('#cardNumber').val(cardNumber);
                $('#charge').val('100.00'); // Set a static charge for now
            } else {
                alert('Please enter a card number to search.');
            }
        });

        // Back button click events
        $('#paymentBackButtonSidebar').click(function () {
            $('#nestedContentSidebar').hide();
            $('#mainButtonsSidebar').show();
            $('#paymentSectionSidebar').hide();
        });

        $('#addBackButtonSidebar').click(function () {
            $('#nestedContentSidebar').hide();
            $('#mainButtonsSidebar').show();
            $('#addSectionSidebar').hide();
        });

        $('#voidBackButtonSidebar').click(function () {
            $('#nestedContentSidebar').hide();
            $('#mainButtonsSidebar').show();
            $('#voidSectionSidebar').hide();
        });

        $('#rewardBackButtonSidebar').click(function () {
            $('#nestedContentSidebar').hide();
            $('#mainButtonsSidebar').show();
            $('#rewardCardSectionSidebar').hide();
        });

        $('#concernBackButtonSidebar').click(function () {
            $('#nestedContentSidebar').hide();
            $('#mainButtonsSidebar').show();
            $('#concernSectionSidebar').hide();
        });

        // Add button click event
        $('#addButtonSidebar').click(function () {
            $('#mainButtonsSidebar').hide();
            $('#nestedContentSidebar').show();
            $('#paymentSectionSidebar').hide();
            $('#addSectionSidebar').show();  // Ensure add section is shown
            $('#voidSectionSidebar').hide();
            $('#rewardCardSectionSidebar').hide();
            $('#concernSectionSidebar').hide();
        });


        // Nested Payment buttons click events
        $('#debitButtonSidebar').click(function () {
            alert('Payment made by Debit Card.');
        });

        $('#creditButtonSidebar').click(function () {
            alert('Payment made by Credit Card.');
        });

        // Nested Add buttons click events
        $('#existingButtonSidebar').click(function () {
            alert('Adding an existing item.');
        });
    });