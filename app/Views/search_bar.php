<form class="login-form form-horizontal" method="POST" target="_blank" id="form_login" action="<?= base_url('activities') ?>">
    <div class="form-container">
        <div class="col-md-12">
            <input type="text" class="form-control" name="activity" placeholder="بحث عن نشاط" style="border: 0px; background-color: #000000a1">
        </div>
        <div class="col-md-12">
            <select class="form-control" name="city" style="border: 0px; background-color: #000000a1">
                <?php foreach ($cities as $city): ?>
                    <option value="<?php echo $city->id; ?>"><?php echo $city->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-12">                    
            <button type="submit" class="register-button btn btn-lg btn-danger" style="margin-right: 0px">بحث </button>
        </div>
    </div>
</form>