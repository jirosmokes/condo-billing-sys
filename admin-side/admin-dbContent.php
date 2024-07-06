<?php
// Example PHP to simulate total tenants count (replace with your actual logic)
$totalTenants = 100;
?>

<section id="totalTenants" style="display: inline-block; width: 300px; padding: 15px; border-radius: 10px; background-color: #333; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);">
    <h2 style="color: white; font-size: 20px; margin-bottom: 8px;">Total Registered Tenants</h2>
    <p style="color: #ccc; font-size: 14px; line-height: 1.4; margin: 0;">Total: <?php echo $totalTenants; ?></p>
</section>

<section id="totalRevenue" style="display: inline-block; width: 300px; padding: 15px; border-radius: 10px; background-color: #333; box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); margin-top: 20px;">
    <h2 style="color: white; font-size: 20px; margin-bottom: 8px;">Total Revenue</h2>
    <p style="color: #ccc; font-size: 14px; line-height: 1.4; margin: 0;">Total Revenue: Pesos</p> <!-- Display total revenue -->

    <!-- Canvas for Chart.js -->
    <canvas id="revenueChart" style="margin-top: 10px; max-height: 200px;"></canvas>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Example data for Chart.js
        var ctx = document.getElementById('revenueChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June'],
                datasets: [{
                    label: 'Revenue',
                    data: [500, 300, 600, 300, 900, 1500], // Example static revenue data
                    borderColor: '#4CAF50', // Green color for the line
                    backgroundColor: 'rgba(76, 175, 80, 0.2)', // Green color with transparency for the fill
                    borderWidth: 2,
                    pointRadius: 5,
                    pointBackgroundColor: '#4CAF50', // Green color for points
                    pointBorderColor: '#4CAF50',
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#4CAF50',
                    pointHoverBorderColor: '#4CAF50'
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        });
    </script>
</section>
