<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 16:50
 */
use yii\widgets\LinkPager;
?>
<style>
    li{
        float: left;
        list-style-type:none;
    }
    a{
        text-decoration:none;
    }
    a:hover{
        text-decoration:none;
    }
</style>
<script>
    function find_car(){
        var car_no = $("#car_no").val().trim();
        location.href='index.php?r=car&car_no='+car_no;
    }
</script>
<div align="center">
    车牌号码：<input type="text" value="<?php echo $car_no; ?>" id="car_no">
    <input type="button" value="查找" onclick="find_car();" />
</div>
<div>
    <ul>
        <?php foreach($cars as $car): ?>
        <li>
            <a href="index.php?r=car/orders&car_id=<?php echo $car['id']; ?>" >
                <div style="background-color: #2a6496;color:white;padding:20px;width:250px;margin:10px;">
                    <p>车牌：<?php echo $car['car_no']?></p>
					<p style="height: 50px;">车主：<?php echo $car['remarks']['user_name']."(".$car['remarks']['phone'].")";?> </p>
                    <p>类型：<?php echo $car['car_type']['car_type']; ?></p>
                    <p style="height: 50px;">车型：<?php echo $car['brand_name'].'-'.$car['model_name'].'-'.$car['style_name'];?></p>
                    <p>购买年份：<?php echo $car['buy_year'];?></p>
                    <p>发动机类型：
                        <?php
                            switch($car['engine_type']){
                                case 1 : echo "自然入气";
                                    break;
                                case 2 : echo "电动发动机";
                                    break;
                                case 3 : echo "涡轮增压";
                                    break;
                                default : echo "未添加";
                            }
                        ?>
                    </p>
                    <p>上次保养时间：<?php if($car['last_maintain'] != 0){ echo date("Y-m-d",$car['last_maintain']); }else{ echo '未有保养记录';}?></p>
                    <p>上次保养里程：<?php if($car['last_mileage'] != 0){ echo $car['last_mileage']."km"; }else{ echo '未有保养记录';}?></p>
                </div>
            </a>
        </li>
        <?php endforeach; ?>
    </ul>
    <div><?= LinkPager::widget(['pagination' => $pages]); ?></div>
</div>
