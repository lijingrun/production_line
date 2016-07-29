<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/10
 * Time: 20:58
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div>
    <?php $form = ActiveForm::begin([
        'options' => ['style' => 'width:250px;'],
    ]); ?>
    <?= $form->field($model,'store_name')->label("店铺名称"); ?>
    <div class="form-group">
        <?= Html::submitButton('添加', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
