<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/7
 * Time: 8:47
 */
?>

<div>
    <p>服务：
        <?php
        switch($evaluate['service']){
            case 3 : echo "好评";
                break;
            case 2 : echo "中评";
                break;
            case 1 : echo "差评";
        }
        ?>
    </p>
    <p>手艺：
        <?php
        switch($evaluate['craft']){
            case 3 : echo "好评";
                break;
            case 2 : echo "中评";
                break;
            case 1 : echo "差评";
        }
        ?>
    </p>
    <p>时间：
        <?php
        switch($evaluate['use_time']){
            case 3 : echo "好评";
                break;
            case 2 : echo "中评";
                break;
            case 1 : echo "差评";
        }
        ?>
    </p>
    <p>沟通：
        <?php
        switch($evaluate['com']){
            case 3 : echo "好评";
                break;
            case 2 : echo "中评";
                break;
            case 1 : echo "差评";
        }
        ?>
    </p>
    <p>评价:<?php echo $evaluate['content'];?></p>
    <p>评价时间：<?php echo date("Y-m-d",$evaluate['created_time']);?></p>
</div>
