<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 12:38
 */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>
<script>
    function find_model(){
        var brand_id = $("#brand").val();
        $("#model_list").html('');
        if(brand_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=goods/find_models',
                data : {'brand_id' : brand_id},
                success : function(data){
                    $("#model_list").append(data);
//                    alert(data);
                }
            });
        }
    }
    function find_style(){
        var model_id = $("#model_id").val();
        $("#style_list").remove();
        $.ajax({
            type : 'post',
            url : 'index.php?r=goods/find_style',
            data : {'model_id' : model_id},
            success : function(data){
                $("#model_id").after(data);
            }
        });
    }
    function add_style(){
        var style_id = $("#style").val();
        var style_name = $("#style"+style_id).text();
        $("#t_style").append(style_name);
        $("#style_ids").append("<input type='hidden' name='style_ids[]' value='"+style_id+"'>");
    }
</script>
<div>
    <?php $form = ActiveForm::begin([
        'options' => ['style' => 'width:250px;'],
    ]); ?>
    <?= $form->field($model,'goods_name')->label('商品名称'); ?>
    <?= $form->field($model,'style')->label('产品型号'); ?>
    <?= $form->field($model,'spec')->label('产品规格'); ?>
    <?= $form->field($model,'goods_type')->dropDownList($goods_types,['prompt'=>'请选择商品类型'])->label('商品类型') ?>
    <?= $form->field($model,'price')->label('商品价格(元)'); ?>
    <div>
        <label class="control-label" >适合车型（全部车型请留空）</label>
        <div id="style_ids">
            <textarea cols="33" rows="5" id="t_style"></textarea>
        </div>

    </div>
    <div class="form-group field-goods_addform-price ">
    <label class="control-label" >选择车型</label>
    <select id="brand" class="form-control" onchange="find_model();">
        <option value="0">全部车型</option>
        <?php foreach($brands as $brand):?>
            <option value="<?php echo $brand['brand_id']?>"><?php echo $brand['brand_name']?></option>
        <?php endforeach;?>
    </select>
    </div>
    <div class="form-group field-goods_addform-price " id="model_list"></div>
    <div class="form-group">
        <?= Html::submitButton('保存并继续添加', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>

