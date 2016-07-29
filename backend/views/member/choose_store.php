<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/29
 * Time: 10:12
 */
?>


<div style="padding:20px;font-size: 20px;">
    <form method="post">
        <select name="store_id">
            <option >请选择预约的店铺</option>
            <?php foreach($stores as $store): ?>
            <option value="<?php echo $store['store_id'];?>"><?php echo $store['store_name']?></option>
            <?php endforeach; ?>
        </select>
        <p style="padding-top:20px;">
        <input type="submit" value="确认提交" class="btn-success" />
        </p>
    </form>
</div>
