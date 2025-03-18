<style>
    /* Ensure horizontal scrolling works on smaller screens */
    @media (max-width: 768px) {
        .slider-container {
            display: flex;
            overflow-x: auto;
            padding: 10px 0;
            gap: 10px; /* Optional: Add space between items */
        }

        .slider-container .col-md-3 {
            flex: 0 0 auto; /* Prevent the items from stretching */
            width: 75%; /* Adjust width for smaller screens, you can modify this */
        }

        .slider-container::-webkit-scrollbar {
            display: none; /* Optional: Hide scrollbar */
        }
    }
</style>

<div class="row slider-container">
    <?php foreach ($entities as $entity): ?>
        <?php
            $folderPath = "uploads/{$entityName}_files/";                
            $filePath = glob($folderPath . $entity->id . ".*");                 
            $fileExtension = $filePath ? pathinfo($filePath[0], PATHINFO_EXTENSION) : 'png';
            if(!empty($entity->city_id)){$city = array_filter($cities, function($c) use ($entity) {return $c->id == $entity->city_id;});$cityName = !empty($city) ? reset($city)->name : 'غير معروف';};?>
        <div class="col-md-3 col-xs-12 <?= $entityName; ?>-item" data-id="<?= $entity->id; ?>" <?php if(!empty($entity->city_id)){;?> city-id="<?= $entity->city_id; ?>"<?php ;};?> <?php if(!empty($entity->city_id)){;?> city-name="<?= $cityName; ?>" <?php ;};?>>
            <div class="white-box table-box-items-no-padding">                         
                <div class="table-box-top" style="border-radius: 20px 20px 0px 0px; background: linear-gradient(to bottom, rgba(48, 67, 0, 0.7), rgba(255, 255, 255, 1)), url(https://portal.i-volunteer.ly/<?= $folderPath . $entity->id . '.' . $fileExtension; ?>) no-repeat center center;background-size: cover; position: relative; height: 120px; color: white; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);display: flex;">                    
                    <?php if(!empty($entity->organisation)){;?><span style="margin-left: 10px;margin-right: 10px"><?= $entity->organisation; ?><?php ;};?>                
                    <?php if (!empty($entity->activity_id) && ($entity->activity_id > 0)) {$activity = $db->table('activities')->where('id', $entity->activity_id)->get()->getRow();if ($activity) {echo $activity->name;}}?></span>                   
                </div>
                <div class="table-box-bottom">
                    <?php if(!empty($entity->name)){;?><h4><b><?= $entity->name; ?></b></h4> <?php ;};?>
                    <h5><?php if(!empty($entity->post_date)){;?><?= $entity->post_date;};?><?php if(!empty($entity->admin_id)){;?> | <?= $db->table('admin')->where('id',$entity->admin_id)->get()->getRow()->name;};?></h5>
                    <?php if(!empty($cityName) && !empty($entity->date_from)) {;?><h5><?= $cityName; ?> | <?= $entity->date_from; ?></h5><?php ;};?>
                    <hr>                               
                    <a target="_blank" href="<?= $details; ?>?id=<?= $entity->id; ?>" style="width:100%" class="btn btn-danger">تفاصيل </a>                                            
                </div>
            </div>
        </div>
    <?php endforeach; ?>   
</div>
<br><br>
