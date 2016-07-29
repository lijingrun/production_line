<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/4
 * Time: 9:24
 */
?>

<div>
    <form method="post">
        <p>套餐名称：<input type="text" name="name" value="<?php echo $package['name']?>" ></p>
        <p>套餐价钱：<input type="text" name="price" value="<?php echo $package['price']?>" ></p>
        <p>
            服务类型
            <select name="service_id">
                <?php foreach($services as $service): ?>
                <option value="<?php echo $service['id']?>" <?php if($service['id'] == $package['service_id']){ echo "selected";} ?>><?php echo $service['name'];?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p><input type="checkbox" value="1" name="status" <?php if($package['status'] == 1){ echo "checked=\"checked\""; }?> />激活</p>
        <input type="submit" value="保存并添加商品" />
    </form>
</div>
