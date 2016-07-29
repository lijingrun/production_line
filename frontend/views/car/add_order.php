<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/13
 * Time: 10:26
 */
?>

<div>
    <?php if($my_cars->count() == 1){ ?>
    <p><?php echo $car['car_no']; ?></p>
        <input type="hidden" value="<?php echo $car['id'];?>" name="car_id">
    <?php }else{ ?>
    <select name="car_id">
        <option></option>
    </select>
    <?php }?>
</div>
