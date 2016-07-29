<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 18:25
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div>
    <?php $form = ActiveForm::begin([
        'options' => ['style' => 'width:250px;'],
    ]); ?>
    <?= $form->field($model , 'name')->label('服务名称'); ?>
    <?= $form->field($model , 'type_id')->dropDownList($types , ['prompt'=>'请选择商品类型'])->label('所属类型'); ?>
    <?= $form->field($model, 'use_time')->label("标准工时（/分钟）"); ?>
    <?= $form->field($model,'check_km')->label('自检里程/km'); ?>
    <div class="form-group">
        <?= Html::submitButton('保存并继续添加', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
