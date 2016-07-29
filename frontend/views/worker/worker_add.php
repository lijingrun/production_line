<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/10
 * Time: 23:38
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div>
    <?php $form = ActiveForm::begin([
        'options' => ['style' => 'width:250px;'],
    ]); ?>
    <?= $form->field($model,'worker_name')->label('工人姓名'); ?>
    <?= $form->field($model,'password')->label('登录密码'); ?>
    <?= $form->field($model,'store_id')->dropDownList($stores,['prompt'=>'请选择所属店铺'])->label('所属店铺') ?>
    <div class="form-group">
        <?= Html::submitButton('添加', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

