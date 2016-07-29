<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/7
 * Time: 22:03
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<h4>
    <?php echo $member['user_name']; ?>
</h4>
<div>
    <?php $form = ActiveForm::begin([
        'options' => ['style' => 'width:250px;'],
    ]); ?>
    <?= $form->field($model, 'car_no')->label('车牌号码') ?>
    <?= $form->field($model, 'car_type')->dropDownList($car_types,['prompt'=>'请选择商品类型'])->label('汽车类型') ?>
    <div class="form-group">
        <?= Html::submitButton('登记', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<div>
    <?php if(!empty($cars)){?>
    <?php foreach($cars as $car): ?>
        <a href="#">
            <div style="background-color: #2a6496;color:white;padding:20px;width:250px;float:left;margin:10px;">
                <p>车牌：<?php echo $car['car_no']?></p>
                <p>上次保养时间：<?php if($car['last_maintain'] != 0){ echo date("Y-m-d",$car['last_maintain']); }else{ echo '未有保养记录';}?></p>
                <p>上次保养里程：<?php if($car['last_mileage'] != 0){ echo $car['last_mileage']."km"; }else{ echo '未有保养记录';}?></p>
            </div>
        </a>
    <?php endforeach; }else{?>
    <p style="color:red">该客户还未登记任何车辆资料</p>
    <?php } ?>
</div>
