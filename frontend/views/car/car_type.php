<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/18
 * Time: 13:54
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div>
    <?php $form = ActiveForm::begin([
        'options' => ['style' => 'width:250px;'],
    ]); ?>
    <?= $form->field($model, 'car_type')->label('类型') ?>
    <div class="form-group">
        <?= Html::submitButton('登记', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
类型：
<ul>
<?php foreach($car_types as $type):  ?>
<li>
    <?php echo $type['car_type']; ?>
</li>
<?php endforeach;?>
</ul>