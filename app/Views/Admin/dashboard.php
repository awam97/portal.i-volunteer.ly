<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<div class="row">
    <div class="col-md-3 col-xs-6">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                <div class="box-title-lite">الاداريين</div>           
            </div>
            <div class="white-box-counter">
                <div class="counter"><?php echo $admins;?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-6">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                <div class="box-title-lite">المدن</div>           
            </div>
            <div class="white-box-counter">
                <div class="counter"><?php echo $cities;?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-xs-6">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                <div class="box-title-lite">النشاطات</div>           
            </div>
            <div class="white-box-counter">
                <div class="counter"><?php echo $activities;?></div>
            </div>
        </div>
    </div>    
    <div class="col-md-3 col-xs-6">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                <div class="box-title-lite">المتطوعين</div>           
            </div>
            <div class="white-box-counter">
                <div class="counter"><?php echo $volunteers;?></div>
            </div>
        </div>
    </div>     
</div>
<br>
<div class="row">
    <div class="col-md-12">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                    <div class="box-title-lite">عدد النشاطات حسب كل مدينة</div>           
            </div>
            <div class="white-box">
                <canvas id="activitiesChart" height="70px"></canvas>
            </div>            
        </div>
    </div>
    <div class="col-md-4">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                <div class="box-title-lite">عدد المتطوعين حسب كل مدينة</div>           
            </div>
            <div class="white-box-counter">
                <canvas id="citiesChart"></canvas>
            </div>        
        </div>
    </div>
    <div class="col-md-8">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                    <div class="box-title-lite">المتطوعين الأكثر نشاطاً</div>           
            </div>
            <div class="white-box">
                <table style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الإسم</th>
                            <th>الساعات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($top_volunteers)): ?>
                            <?php foreach ($top_volunteers as $index => $volunteer): ?>
                                <tr>
                                    <td><?= $index + 1; ?></td>
                                    <td><?= htmlspecialchars($volunteer['name']); ?></td>
                                    <td><?= htmlspecialchars($volunteer['total_hours']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4">No data found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>            
        </div>
    </div>
</div>

<?php $cities = array_map(function ($city) { return $city->name; }, $cities_data);?>

<script>
    const cityLabels = <?php echo json_encode($cities); ?>;
    const activityData = <?php echo json_encode($activitiesPerCity); ?>;
    const volunteerData = <?php echo json_encode($volunteersPerCity); ?>; // New data for volunteers

    const actx = document.getElementById('activitiesChart').getContext('2d');
    const ctsx = document.getElementById('citiesChart').getContext('2d');

    // Activities chart
    new Chart(actx, {
        type: 'bar',
        data: {
            labels: cityLabels,
            datasets: [{                
                data: activityData,
                backgroundColor: 'rgb(135 160 82)',
                borderColor: 'rgb(135 160 82)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Volunteers chart
    new Chart(ctsx, {
    type: 'doughnut',
    data: {
        labels: cityLabels,
        datasets: [{                
            data: volunteerData, // Use volunteer data here
            backgroundColor: [
                'rgb(135 160 82)',   
                'rgb(135 120 10)',
                'rgb(135 190 50)',
            ],
            borderColor: [
                'rgb(135 160 82)',   
                'rgb(135 120 10)',
                'rgb(135 190 50)',
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { 
                display: false // Hides the legend labels
            },
        },
    }
});

</script>

