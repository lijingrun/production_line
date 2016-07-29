<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/13
 * Time: 9:26
 */
?>

<div style="padding-bottom: 20px;">
    <h3>我的爱车：</h3>
    <div>
        <?php foreach($cars as $car): ?>

                <div class="alert alert-success" role="alert" style="font-size: 20px;">
                    <p>车牌号：<?php echo $car['car_no']; if(empty($car['style_id']) || empty($car['car_code'])){ echo "<span style='color:red;'>(需要完善资料)</span>";}?></p>
                    <p>车型：<?php echo $car['brand_name'].'-'.$car['model_name'].'-'.$car['style_name']?></p>
                    <?php if(!empty($car['type']['car_type'])){ ?>
                    <p>发动机缸数：<?php echo $car['type']['car_type']?></p>
                    <?php } ?>
                    <p>购买年份：<?php echo $car['buy_year']?></p>
                    <p>
                        <?php if(!empty($car['last_mileage'])){?>
                        上次保养里程：<?php echo $car['last_mileage'] ?>
                        <?php }else{?>
                        暂时未有保养记录!
                        <?php }?>
                    </p>
                    <p><?php if(!empty($car['last_maintain'])){?>
                            上次保养时间：<?php echo date("Y-m-d" ,$car['last_maintain']); ?>
                        <?php }else{?>
                            暂时未有保养记录!
                        <?php }?></p>
                    <a href="index.php?r=member/car_detail&car_id=<?php echo $car['id']; ?>" >
                        <span class="label label-info">修改车辆信息</span>
                    </a>
                    <a href="index.php?r=member/car_orders&car_id=<?php echo $car['id'];?>">
                        <span class="label label-warning">
                            查看保养记录
                        </span>
                    </a>
                </div>
        <?php endforeach;?>
    </div>
    <a href="index.php?r=member/car_detail">
    <input type="button" value="注册新车辆" class="btn-success" style="font-size: 18px;" />
    </a>
</div>
