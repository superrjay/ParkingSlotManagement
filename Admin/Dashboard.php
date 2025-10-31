<?php include '../php/connections.php';
      include '../php/adminLoginData.php';    
      include '../php/parkingFunction.php';
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3"></script>
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
            <img class="logo-img" src="../img/logo.png" alt="">
        </div>

        <div class="links-container">
            <ul class="list">
                <li>
                    <a class="links active" href="Dashboard.php">
                        <i class='bx bx-command' ></i>
                        <span class="circle"></span>
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
                <a class="links" href="UserManagement.php">
                        <i class='bx bx-user' ></i>
                        <span class="link-text">User Management</span>
                </a>
                </li>
            </ul>
        </div>

    </div>

    <section class="content">
        <?php include '../components/Admin/Navigation.php'; ?>
        <?php 
        $parkingData = fetchParking();

        $totalSlots = 0;
        $availableSlots = 0;
        $occupiedSlots = 0;
        $reservedSlots = 0;

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

        $availablePercentage = ($availableSlots / 300) * 100;
        $occupiedPercentage = ($occupiedSlots / 300) * 100;
        $reservedPercentage = ($reservedSlots / 300) * 100;

        // Fetch and decode revenue data
        $revenueData = json_decode(fetchRevenue(), true);
        $monthlyRevenue = [];

        // Loop through the revenue data and sum the fees for each month
        foreach ($revenueData as $entry) {
            $timeOut = new DateTime($entry['time_out']);
            $monthYear = $timeOut->format('Y-m'); 
        
            if (!isset($monthlyRevenue[$monthYear])) {
                $monthlyRevenue[$monthYear] = 0;
            }
            $monthlyRevenue[$monthYear] += (float) $entry['fee'];
        }

        // Get the current and last month's revenue
        $currentMonth = date('Y-m'); 
        $lastMonth = date('Y-m', strtotime('-1 month')); 

        $currentMonthRevenue = isset($monthlyRevenue[$currentMonth]) ? $monthlyRevenue[$currentMonth] : 0;
        $lastMonthRevenue = isset($monthlyRevenue[$lastMonth]) ? $monthlyRevenue[$lastMonth] : 0;

        // Calculate the percentage change
        if ($lastMonthRevenue > 0) {
            $percentageChange = (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100;
        } else {
            $percentageChange = $currentMonthRevenue > 0 ? 100 : 0;
        }

        $percentageChangeFormatted = number_format($percentageChange, 2);

        // Display the total Fee 
        $revenueData = json_decode(fetchRevenue(), true); 
        $totalRevenue = array_sum(array_column($revenueData, 'fee'));
        $totalRevenueFormatted = number_format($totalRevenue);
        ?>
        <!-- Cards -->
        <div class="card-container">
            <div class="card card1">
                <div class="card-title">Total Income</div>
                <div class="card-data">₱<?php echo $totalRevenueFormatted?><span class="current-Revenue">/ <?php echo "₱" . number_format($currentMonthRevenue); ?><span class="month-text"> (This Month)</span></span></div>
                <div class="card-desc">
                    <?php
                        if ($percentageChange > 0) {
                            echo '<span class="revenue-badge increase">';
                            echo '<i class="fa-solid fa-arrow-up"></i> ';
                            echo '+' . $percentageChangeFormatted . "%"; 
                        } elseif ($percentageChange < 0) {
                            echo '<span class="revenue-badge decrease">';
                            echo '<i class="fa-solid fa-arrow-down"></i> '; 
                            echo $percentageChangeFormatted . "%"; 
                        } else {
                            echo '<span class="revenue-badge">'; 
                            echo $percentageChangeFormatted . "%"; 
                        }
                        echo '</span>';
                    ?>
                    <span class="comparison">Compared to last month</span>
                </div>
            </div>
            <div class="card card2">
                <div class="card-title">Slot Occupation Rate</div>
                <div class="card-data"><?php echo round($occupiedPercentage, 2)?>%</div>
                <div class="card-desc">
                    <span class="comparison"><?php echo $occupiedSlots?> Slots out of 300</span>
                    <div class="progress-bar">
                        <span class="progress"></span>
                    </div>
                </div>
            </div>
            <div class="card card3">
                <div class="card-title">Slot Availability Rate</div>
                <div class="card-data"><?php echo round($availablePercentage, 2)?>%</div>
                <div class="card-desc">
                    <span class="comparison"><?php echo $availableSlots ?> Slots out of 300</span>
                    <div class="progress-bar">
                        <span class="progress"></span>
                    </div>
                </div>
            </div>
            <div class="card card4">
                <div class="card-title">Reserved Rate</div>
                <div class="card-data"><?php echo round($reservedPercentage, 2)?>%</div>
                <div class="card-desc">
                    <span class="comparison"><?php echo $reservedSlots ?> Slots out of 100</span>
                    <div class="progress-bar">
                        <span class="progress"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphs -->
        <div class="dashboard-container">
            <div class="graph graph1">
              <div class="graph-header">
                <div class="graph-title">Revenue Over Time</div>

                <select id="timeframe" onchange="updateChart()">
                  <option value="daily">Daily</option>
                  <option value="monthly">Monthly</option>
                  <option value="yearly">Yearly</option>
                </select>
              </div>

              <div id="custom-legend"></div>
              <canvas class="graph-data" id="revenueGraph" width="100%" height="30px"></canvas>
            </div>

            <div class="graph graph2">
                <div class="graph-header">
                    <div class="graph-title">User Type Distribution</div>
                </div>
                <canvas id="usertypeDistribution" width="520" height="410"></canvas>
                </canvas>
            </div>
            
            <div class="graph graph3">
                <div class="graph-header">
                    <div class="graph-title">Revenue per Floor</div>
                </div>
                <canvas id="ocuppationRate" width="500" height="380">
                </canvas>
            </div>
            <div class="graph graph4">
                <div class="graph-header">
                    <div class="graph-title">Occupancy Rate per Floor</div>
                </div>
                
                <canvas id="occupancyRateperFloor" width="500" height="400"></canvas>
            </div>
            <div class="graph graph5">
                <div class="graph-header">
                    <div class="graph-title">Vehicle Type Distribution</div>
                </div>
                <canvas id="vehicleTypeDistribution" width="500" height="400"></canvas>
            </div>
        </div>
    </section>

    <?php include '../components/Admin/SnipModal.php'; ?>

    <script src="../js/Admin/modal.js"></script>
    <script src="../js/toggleSidebar.js"></script>

    <script Progress Bar>
        document.addEventListener("DOMContentLoaded", function () {
            var progressBars = document.querySelectorAll('.progress-bar');

            var occupiedPercentage = <?php echo $occupiedPercentage; ?>;
            var availablePercentage = <?php echo $availablePercentage; ?>;
            var reservedPercentage = <?php echo $reservedPercentage; ?>;
            
            progressBars[0].style.width = occupiedPercentage + '%';
            progressBars[1].style.width = availablePercentage + '%';
            progressBars[2].style.width = reservedPercentage + '%';
        });
    </script>

    <!-- Revenue Over Time Data -->
    <script Revenue Graph>
       const archiveData = <?php echo fetchRevenue(); ?>;
       let chart;
       const revenuegraph = document.getElementById('revenueGraph').getContext('2d');

        // Function to initialize the chart
        function initializeChart() {
            const timeLabels = archiveData.map(entry => entry.time_out);
            const revenueData = archiveData.map(entry => parseFloat(entry.fee));

            if (!chart) {
                chart = new Chart(revenuegraph, {
                    type: 'line',
                    data: {
                        labels: timeLabels,
                        datasets: [{
                            label: 'Revenue',
                            data: revenueData,
                            fill: "#795d9f",  
                            borderColor: '#795d9f', 
                            tension: 0.1,
                            pointRadius: 6,  
                            pointHoverRadius: 10, 
                            pointBackgroundColor: '#795d9f',  
                            pointBorderColor: '#fff',
                            pointHoverBackgroundColor: '#fff', 
                            pointHoverBorderColor: '#795d9f', 
                            pointStyle: 'circle',
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                type: 'time',
                                time: {
                                    unit: 'day',
                                    displayFormats: {
                                        day: 'MMM d, yyyy',
                                        month: 'MMM yyyy',
                                        year: 'yyyy'
                                    },
                                    tooltipFormat: 'PP'
                                },
                                title: {
                                    display: true,
                                    text: 'Time'
                                }
                            },
                            y: {
                                title: {
                                    display: true,
                                    text: 'Revenue ($)'
                                }
                            }
                        },
                        animation: {
                            duration: 1500,
                            easing: 'easeOutQuart', 
                            onComplete: function() {
                                console.log('Chart animation complete!');
                            }
                        }
                    }
                });
                generateCustomLegend();
            }
        }

        // Function to generate the custom legend function 
        function generateCustomLegend() {
            const legendContainer = document.getElementById('custom-legend');
            legendContainer.innerHTML = ''; 

            // Loop through each dataset in the chart
            chart.data.datasets.forEach((dataset, index) => {
                const legendItem = document.createElement('div');
                legendItem.classList.add('legend-item');
                legendItem.style.display = 'flex';
                legendItem.style.alignItems = 'center';
                legendItem.style.cursor = 'pointer';

                // Create the circle element for the dataset's color
                const circle = document.createElement('span');
                circle.style.display = 'inline-block';
                circle.style.width = '12px';
                circle.style.height = '12px';
                circle.style.backgroundColor = dataset.borderColor;
                circle.style.borderRadius = '50%';
                circle.style.marginRight = '8px';

                // Create the label for the dataset
                const label = document.createElement('span');
                label.textContent = dataset.label;
                label.style.fontSize = '14px';
                label.style.fontWeight = 'bold';
                label.style.color = '#333';

                // Append the circle and label to the legend item
                legendItem.appendChild(circle);
                legendItem.appendChild(label);

                // Add click event to toggle the visibility of the dataset
                legendItem.addEventListener('click', () => {
                    const meta = chart.getDatasetMeta(index);
                    meta.hidden = !meta.hidden; 
                    chart.update(); 
                });

                // Append the legend item to the legend container
                legendContainer.appendChild(legendItem);
            });
        }
        initializeChart();

            // Function to update the chart based on the selected timeframe
            function updateChart() {
                if (!chart) {
                    console.error('Chart is not initialized yet.');
                    return; 
                }

                const timeframe = document.getElementById('timeframe').value;
                let filteredData = archiveData.map(entry => ({
                    time_out: new Date(entry.time_out),
                    fee: parseFloat(entry.fee)
                }));

                filteredData.sort((a, b) => a.time_out - b.time_out);

                const now = new Date();

                if (timeframe === 'daily') {
                    const dailyRevenue = {};

                    filteredData.forEach(entry => {
                        const dateKey = entry.time_out.toISOString().split('T')[0]; 
                        dailyRevenue[dateKey] = (dailyRevenue[dateKey] || 0) + entry.fee;
                    });

                    chart.options.scales.x.time.unit = 'day';
                    chart.data.labels = Object.keys(dailyRevenue); 
                    chart.data.datasets[0].data = Object.values(dailyRevenue);

                } else if (timeframe === 'monthly') {
                    const monthlyRevenue = {};
                    filteredData.forEach(entry => {
                        const month = entry.time_out.getFullYear() + '-' + (entry.time_out.getMonth() + 1).toString().padStart(2, '0');
                        monthlyRevenue[month] = (monthlyRevenue[month] || 0) + entry.fee;
                    });

                    chart.options.scales.x.time.unit = 'month';
                    chart.data.labels = Object.keys(monthlyRevenue);
                    chart.data.datasets[0].data = Object.values(monthlyRevenue);

                } else if (timeframe === 'yearly') {
                    const yearlyRevenue = {};
                    filteredData.forEach(entry => {
                        const year = entry.time_out.getFullYear().toString();
                        yearlyRevenue[year] = (yearlyRevenue[year] || 0) + entry.fee;
                    });

                    chart.options.scales.x.time.unit = 'year';
                    chart.data.labels = Object.keys(yearlyRevenue);
                    chart.data.datasets[0].data = Object.values(yearlyRevenue);
                }

                chart.options.scales.x.grid.display = false;
                chart.options.scales.y.grid.display = false;
                chart.update();
            }

            initializeChart();
            document.getElementById('timeframe').addEventListener('change', updateChart);
            updateChart();
        </script>

        <!-- User Type Distribution Data Fetch -->
        <?php
        $fetchParking = fetchParking();

        $guestCountPerFloor = [0, 0, 0, 0, 0]; 
        $reserveeCountPerFloor = [0, 0, 0, 0, 0]; 
    
        foreach ($fetchParking as $slot) {
            $floorIndex = (int)$slot['floor'] - 1; 
            if ($slot['user_type'] === 'Guest') {
                $guestCountPerFloor[$floorIndex]++;
            } elseif ($slot['user_type'] === 'Reservee') {
                $reserveeCountPerFloor[$floorIndex]++;
            } 
        }
        ?>

        <!-- User Type Distribution Chart Script -->
        <script>
            const userTypeDistribution = document.getElementById('usertypeDistribution').getContext('2d');
            const guestCountPerFloor = <?php echo json_encode($guestCountPerFloor); ?>;
            const reserveeCountPerFloor = <?php echo json_encode($reserveeCountPerFloor); ?>;
            const floors = ['Floor 1', 'Floor 2', 'Floor 3', 'Floor 4', 'Floor 5'];

            const userTypeBarChart = new Chart(userTypeDistribution, {
                type: 'bar',
                data: {
                    labels: floors,
                    datasets: [
                        {
                            label: 'Guest', 
                            data: guestCountPerFloor, 
                            backgroundColor: 'rgba(159, 93, 139, 0.8)', 
                            borderColor: 'rgba(159, 93, 139, 1)', 
                            borderWidth: 2
                        },
                        {
                            label: 'Reservee',
                            data: reserveeCountPerFloor,
                            backgroundColor: 'rgba(121, 93, 159, 0.8)', 
                            borderColor: 'rgba(121, 93, 159, 1)',
                            borderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: false
                            },
                            stacked: true 
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Count'
                            },
                            stacked: true 
                        }
                    }
                }
            });
        </script>

        <!-- Occupation Rate per Floor Data Fetch -->
        <?php
        // Call Out Universal Fetch Function for Archive_tbl
        $archiveFetch = fetchArchive();

        $feesByFloor = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0
        ];

        foreach ($archiveFetch as $slot) {
            $floor = $slot['floor']; 
            $fee = $slot['fee']; 
        
            if (isset($feesByFloor[$floor])) {
                $feesByFloor[$floor] += $fee;
            }
        }
        
        $feesJson = json_encode($feesByFloor);
        ?>

        <!-- Occupation Rate per Floor Chart Script -->
        <script>
            const ocuppationRate = document.getElementById('ocuppationRate').getContext('2d');
            const feesData = <?php echo $feesJson; ?>;
            const floorLabels = ['Floor 1', 'Floor 2', 'Floor 3', 'Floor 4', 'Floor 5'];

            const labelColors = [
                '#9f7fbe',  
                '#6a8d89',  
                '#d99b6d',  
                '#a4bdb0', 
                '#e3c085'  
            ];

            const labelBorders = [
                '#7f5f9d',
                '#517c75',
                '#b77953',
                '#8a9c8f',
                '#c19a64'
            ]


            const feesDataset = {
                label: 'Total Fee',
                data: Object.values(feesData), 
                backgroundColor: labelColors,
                borderColor: labelBorders,
                borderWidth: 3,
                borderRadius: 8
            };

            const ocuppationBarChart = new Chart(ocuppationRate, {
                type: 'bar',
                data: {
                    labels: floorLabels,  
                    datasets: [feesDataset] 
                },
                options: {
                    scales: {
                        x: {
                            ticks: {
                                callback: function(value, index) {
                                    return floorLabels[index];  
                                },
                                color: (context) => {
                                    return labelColors[context.index];  
                                }
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>

        <!-- Occupancy Rate per FLoor Data Fetch -->
        <?php
        $fetchParking = fetchParking(); 
        $occupiedSlotbyFloor = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0
        ];

        foreach ($fetchParking as $slotData) {
            $floor = $slotData['floor']; 
            $status = $slotData['status']; 
            
            if (isset($occupiedSlotbyFloor[$floor]) && strtolower($status) === "occupied") {
                $occupiedSlotbyFloor[$floor] += 1;
            }
        }

        $occupiedSlotbyFloorJSON = json_encode($occupiedSlotbyFloor);
        ?>

        <!-- Occupancy Rate per Floor Chart Script -->
        <script>
            const occupancyRate = document.getElementById('occupancyRateperFloor').getContext('2d');
            const floorData = <?php echo $occupiedSlotbyFloorJSON; ?>;
            const floorLabel = ['Floor 1', 'Floor 2', 'Floor 3', 'Floor 4', 'Floor 5'];
            const labelColor = [
                        '#9f7fbe',  
                        '#6a8d89',  
                        '#d99b6d',  
                        '#a4bdb0', 
                        '#e3c085'  
                    ];

                    const labelBorder = [
                        '#7f5f9d',
                        '#517c75',
                        '#b77953',
                        '#8a9c8f',
                        '#c19a64'
                    ]

            const occupiedDatasets = {
                label: 'Occupied Slots',
                data: Object.values(floorData), 
                backgroundColor: labelColor,
                borderColor: labelBorder,
                borderWidth: 2,
            };

            const occupancyRateChart = new Chart(occupancyRate, {
                type: 'polarArea',  
                data: {
                    labels: floorLabel,  
                    datasets: [occupiedDatasets] 
                },
                options: {
                    responsive: false,
                    scales: {
                        r: {
                            grid: {
                                display: true
                            },
                            ticks: {
                                display: false  
                            }
                        }
                    }
                }
            });
        </script>


        <!-- Vehicle Type Distribution Data Fetch -->
        <?php
        $bicycleCount = 0;
        $motorcycleCount = 0;
        $carCount = 0;

        foreach ($parkingData as $slot) {
            if ($slot['vehicle_type'] === 'Bicycle') {
                $bicycleCount++;
            } elseif ($slot['vehicle_type'] === 'Motorcycle') {
                $motorcycleCount++;
            } elseif ($slot['vehicle_type'] === 'Car') {
                $carCount++;
            }
        }
        ?>

        <!-- Vehicle Type Distribution Chart Script -->
        <script>
            const vehicleDistribution = document.getElementById('vehicleTypeDistribution').getContext('2d');

            const bicycleCount = <?php echo $bicycleCount; ?>;
            const motorcycleCount = <?php echo $motorcycleCount; ?>;
            const carCount = <?php echo $carCount; ?>;

            const vehiclePieChart = new Chart(vehicleDistribution, {
                type: 'pie',
                data: {
                    labels: ['Bicycle', 'Motorcycle', 'Car'],
                    datasets: [{
                        label: 'Vehicle Count',
                        data: [bicycleCount, motorcycleCount, carCount],
                        backgroundColor: [
                            'rgba(121, 93, 159, 0.8)',
                            'rgba(93, 159, 135, 0.8)', 
                            'rgba(159, 93, 139, 0.8)',  
                        ],
                        borderColor: [
                            'rgba(121, 93, 159, 1)',     
                            'rgba(93, 159, 135, 1)',    
                            'rgba(159, 93, 139, 1)', 
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: false,
                    maintainAspectRatio: false
                }
            });
        </script>
</body>
</html>
