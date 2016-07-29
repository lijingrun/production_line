<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/11
 * Time: 15:30
 */
?>

<div>
    <p>
        评价时间：<?php echo date("Y-m-d H:i:s",$evas['created_time']); ?>
    </p>
    <p>
        服务：
        <?php
        switch($evas['service']){
            case 1 : echo "差评";
                break;
            case 2 : echo "中评";
                break;
            case 3 : echo "好评";
                break;
        }
        ?>
    </p>
    <p>
        手艺：
        <?php
        switch($evas['craft']){
            case 1 : echo "差评";
                break;
            case 2 : echo "中评";
                break;
            case 3 : echo "好评";
                break;
        }
        ?>
    </p>
    <p>
        时间：
        <?php
        switch($evas['use_time']){
            case 1 : echo "差评";
                break;
            case 2 : echo "中评";
                break;
            case 3 : echo "好评";
                break;
        }
        ?>
    </p>
    <p>
        沟通：
        <?php
        switch($evas['com']){
            case 1 : echo "差评";
                break;
            case 2 : echo "中评";
                break;
            case 3 : echo "好评";
                break;
        }
        ?>
    </p>
    <p>
        评价：
        <div><?php echo $evas['content'];?></div>
    </p>
</div>
