<?php $volunteer_city = $db->table('volunteers')->where('id', $volunteer_id)->get()->getRow()->city_id;?>

<div class="row">
    <div class="col-md-3 col-xs-6">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                <div class="box-title-lite">النشاطات المتاحة</div>           
            </div>
            <div class="white-box-counter">
                <div class="counter"><?php echo $db->table('activities')->select('activities.*')->join('volunteer_activities', 'volunteer_activities.activity_id = activities.id AND volunteer_activities.volunteer_id = ' . $volunteer_id, 'left')->where('activities.date_from>',date("Y/m/d"))->where('volunteer_activities.activity_id IS NULL')->countAllResults();?></div>
            </div>
        </div>
    </div>          
    <div class="col-md-3 col-xs-6">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                <div class="box-title-lite">نشاطاتك</div>           
            </div>
            <div class="white-box-counter">
                <div class="counter"><?php echo $db->table('volunteer_activities')->where('volunteer_id', $volunteer_id)->countAllResults();?></div>
            </div>
        </div>
    </div>   
    <div class="col-md-3 col-xs-6">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                <div class="box-title-lite">ساعاتك التطوعية</div>           
            </div>
            <div class="white-box-counter">
                <div class="counter"><?php $total_hours = $db->table('activities')->selectSum('activities.hours', 'total_hours')->join('volunteer_activities', 'volunteer_activities.activity_id = activities.id')->where('volunteer_activities.volunteer_id', $volunteer_id)->where('volunteer_activities.status', '2')->get()->getRow();
                    if ($total_hours && $total_hours->total_hours !== null) {echo $total_hours->total_hours;} else {echo "0";};?>
                </div>
            </div>
        </div>
    </div> 
    <div class="col-md-3 col-xs-6">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                <div class="box-title-lite">شهاداتك</div>           
            </div>
            <div class="white-box-counter">
                <div class="counter"><?php echo $certificates = $db->table('volunteer_activities')->where('volunteer_id', $volunteer_id)->where('status', '2')->countAllResults();?></div>
            </div>
        </div>
    </div> 
</div>
<br>
<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="counter-panel">
            <div class="page-title-box-lite">
                <div class="box-title-lite">الإعلانات و الأخبار</div>           
            </div>
            <div class="white-box-counter">      
                <div class="row">  
                
                    <?php $news = $db->table('news')->select('news.id  as news_id, news.name, news.post_date, volunteer_activities.activity_id')->join('volunteer_activities', 'volunteer_activities.activity_id = news.activity_id')->where('volunteer_activities.volunteer_id', $volunteer_id)->orderBy('news.post_date', 'DESC')->limit(4)->get()->getResult();if (empty($news)) : ?>
                    <h3>لا يوجد اخبار</h3>
                    <?php else : ?>
                        <br>
                    <?php foreach ($news as $item) : ?>
                        <?php
                        $folderPath = "uploads/news_files/";                
                        $filePath = glob($folderPath . $item->news_id  . ".*");                 
                        $fileExtension = $filePath ? pathinfo($filePath[0], PATHINFO_EXTENSION) : 'png';
                        ?>
                        <div class="col-md-4 col-xs-6 news-item" data-id="<?= $item->news_id ; ?>">
                            <div class="white-box table-box-items-no-padding">                         
                                <div class="table-box-top" style="border-radius: 20px 20px 0px 0px; 
                                    background: linear-gradient(to bottom, rgba(48, 67, 0, 0.7), rgba(255, 255, 255, 1)), 
                                    url(https://portal.i-volunteer.ly/<?= $folderPath . $item->news_id  . '.' . $fileExtension; ?>) no-repeat center center;
                                    background-size: cover; position: relative; height: 120px; color: white; text-shadow: 0 1px 3px rgba(0, 0, 0, 0.6);
                                    display: flex;">
                                    <label class="select-checkbox" style="margin: 0;">
                                        <input class="entity-checkbox" type="checkbox" name="entity" data-id="<?= $item->news_id ; ?>" value="<?= $item->news_id ; ?>">                            
                                        <span></span>
                                    </label>
                                    <span style="margin-left: 10px;margin-right: 10px"><?= $db->table('activities')->where('id',$item->activity_id)->get()->getRow()->name ?></span>
                                </div>
                                <div class="table-box-bottom">
                                    <center>                                                                 
                                        <h4><b><a><?= $item->name; ?></a></b></h4> 
                                    </center>
                                </div>
                            </div>
                        </div>
                    <?php endforeach;endif; ?>
                </div>
            </div>
        </div>
    </div>                  
</div>
