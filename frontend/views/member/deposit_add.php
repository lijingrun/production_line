<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/7
 * Time: 20:42
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div>
    <h5>请输入相关充值信息</h5>
    <div >
        <?php $form = ActiveForm::begin([
            'options' => ['style' => 'width:250px;'],
        ]); ?>
			<?= $form->field($model,'plan_id')->dropDownList($deposit_plans,['prompt'=>'请选择充值套餐'])->label('充值套餐') ?>	
			<?= $form->field($model,'description')->textarea()->label('附加信息') ?>	
            <div class="form-group">
                <?= Html::submitButton('保存', ['class' => 'btn btn-primary', 'name' => 'confirm-button']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
