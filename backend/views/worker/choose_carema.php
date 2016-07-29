<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/10
 * Time: 11:13
 */
?>
<style>
    .div{
        padding:10px;
    }
</style>
<div>
    <form method="post">
        请选择岗位
        <div class="div">
        <select name="carema_id">
            <?php foreach($caremas as $carema): ?>
                <option value="<?php echo $carema['id'];?>"><?php echo $carema['name']?></option>
            <?php endforeach; ?>
        </select>
        </div>
        <div class="div">
            <input type="submit" value="确定" class="btn-success" />
        </div>
    </form>
</div>
