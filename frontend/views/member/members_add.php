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
    <h5>请输入相关会员信息</h5>
    <div >
        <?php $form = ActiveForm::begin([
            'options' => ['style' => 'width:250px;'],
        ]); ?>
            <?= $form->field($model, 'user_name')->label('客户名称') ?>
            <?= $form->field($model,'phone')->label('联系电话') ?>
            <?= $form->field($model,'rec_numbers')->label("推荐码")?>
            <div class="form-group">
                <?= Html::submitButton('保存', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
