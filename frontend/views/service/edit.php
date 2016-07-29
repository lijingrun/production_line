<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/4/26
 * Time: 14:45
 */
?>


<div>
    <form method="post">
    <p>服务名称：<input type='text' name="name" value="<?php echo $service['name']?>" /></p>
    <p>
        服务类型：
        <select name="type_id">
            <?php foreach($service_types as $type): ?>
            <option value="<?php echo $type['type_id']?>" <?php if($type['type_id'] == $service['type_id']){echo 'selected';}?>><?php echo $type['name']?></option>
            <?php endforeach; ?>
        </select>
    </p>
    <p>标准工时：<input type='text' name="use_time" value="<?php echo $service['use_time']?>" />分钟</p>
    <p>自检里程：<input type='text' name="check_km" value="<?php echo $service['check_km']?>" />km</p>
    <input type='submit' value="提交" />
    </form>
</div>