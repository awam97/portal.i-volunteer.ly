<?php include(APPPATH . 'Views/generalheader.php'); ?> 
<br>
<div class=row>
    <?php include(APPPATH . 'Views/search_bar.php'); ?>  
    <div class="col-md-4 col-xs-4">
        <select id="sortBy" class="form-control">
                <option value="name">ترتيب أبجدي</option>
                <option value="date">ترتيب زمني</option>
            </select>
        </div>
        <div class="col-md-4 col-xs-4">
            <select id="sortOrder" class="form-control">
                <option value="asc">تصاعدي</option>
                <option value="desc">تنازلي</option>
            </select>
        </div>        
        <div class="col-md-4 col-xs-4">
            <input type="text" id="<?= $entityName; ?>Search"  class="form-control"  placeholder="اكتب للبحث ...">
        </div>
</div>
<hr>
<h2><b><?= $page_title;?> بعنوان ( <?= $searchKey;?> ) بمدينة <?= $cityName;?></b></h2>
<br>
<div id="tab1" class="row">
    <div class="row">
        <?php foreach ($entities as $entity): ?>
            <?php
                $folderPath = "uploads/{$entityName}_files/";                
                $filePath = glob($folderPath . $entity->id . ".*");                 
                $fileExtension = $filePath ? pathinfo($filePath[0], PATHINFO_EXTENSION) : 'png';
                if(!empty($entity->city_id)){$city = array_filter($cities, function($c) use ($entity) {return $c->id == $entity->city_id;});$cityName = !empty($city) ? reset($city)->name : 'غير معروف';};?>
            <div class="col-md-4 col-xs-6 <?= $entityName; ?>-item" data-id="<?= $entity->id; ?>">
                <div class="white-box table-box-items-no-padding">                         
                    <div class="table-box-top" style="border-radius: 20px 20px 0px 0px; ; background: linear-gradient(to bottom, rgba(48, 67, 0, 0.7), rgba(255, 255, 255, 1)), 
                            url(https://portal.i-volunteer.ly/<?= $folderPath . $entity->id . '.' . $fileExtension; ?>) no-repeat center center;
                        background-size: cover; position: relative; height: 120px; color: white; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
                        display: flex;">                    
                        <span style="margin-left: 10px;margin-right: 10px"><?php if(!empty($entity->organisation)){echo $entity->organisation;};?></span>
                    </div>
                    <div class="table-box-bottom">
                        <center>                                                                 
                            <?php if(!empty($entity->name)){;?><h4 id="name"><b><?= $entity->name; ?></b></h4> <?php ;};?>
                            <?php if(!empty($entity->date_from)){;?><h5 id="date"><?= $entity->date_from; ?></h5><?php ;};?>
                            <?php if(!empty($entity->post_date)){;?><h5 id="date"><?= $entity->post_date; ?></h5><?php ;};?>
                            <?php if(!empty($cityName)){;?><h3 id="city"><?= $cityName; ?><b></b></h3><?php ;};?>
                            <hr>                               
                            <a href="<?= $details; ?>?id=<?= $entity->id; ?>" class="btn btn-info">تفاصيل </a>                        
                        </center>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>   
    </div>
</div>
<br><br>
<script>
    $(document).ready(function () 
    {
        const entityName = '<?= $entityName; ?>';                    

        //Search
        $(`#${entityName}Search`).on('input', function () 
        {                        
            const query = $(this).val().toLowerCase();
            $(`.${entityName}-item`).each(function () {
                const itemName = $(this).find('h4').text().toLowerCase();
                $(this).toggle(itemName.includes(query));
            });
        });        
        
        //Sorting
        $('#sortBy, #sortOrder').on('change', function () 
        {
            const sortBy = $('#sortBy').val();
            const sortOrder = $('#sortOrder').val();
            let items = $(`.${entityName}-item`).toArray();
            items.sort(function (a, b) {
                let aValue, bValue;

                if (sortBy === 'name') {
                    // Sort by Name
                    aValue = $(a).find('h4').text().toLowerCase();
                    bValue = $(b).find('h4').text().toLowerCase();
                } else if (sortBy === 'date') {
                    // Sort by Date
                    aValue = new Date($(a).find('#date').text());
                    bValue = new Date($(b).find('#date').text());
                }

                if (aValue < bValue) return (sortOrder === 'asc' ? -1 : 1);
                if (aValue > bValue) return (sortOrder === 'asc' ? 1 : -1);
                return 0;
            });            
            $(`#tab1 .row`).html(items);
        });        
        $('#sortBy').trigger('change');
        
    });        
</script>
