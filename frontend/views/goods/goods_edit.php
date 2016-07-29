<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/23
 * Time: 11:20
 */
?>
<style>
    .div-inside{
        padding-top:10px;
    }
</style>
<script>
    function change_brand(){
        $("#brand").html('');
        $("#button1").remove();
        $("#brand").html("<textarea id='style_name' cols='33' rows=5></textarea><br />");
        $.ajax({
            type : 'post',
            url : 'index.php?r=goods/get_car_brand',
            success : function(data){
                $("#brand").append(data);
            }
        });
    }
    function get_models(){
        $("#model").remove();
        var brand_id = $("#brand_id").val();
        if(brand_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=goods/get_models',
                data : {'brand_id' : brand_id},
                success : function(data){
                    $("#brand_id").after(data);
                }
            });
        }
    }
    function get_style(){
        var model_id = $("#model").val();
        $("#style_list").remove();
        if(model_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=goods/get_style',
                data : {'model_id' : model_id},
                success : function(data){
                    $("#model").after(data);
                }
            });
        }
    }
    function add_style(){
        var style_id = $("#style").val();
        var style_name = $("#style"+style_id).text();
        $("#style_name").append(style_name);
        $("#style_name").after("<input type='hidden' name='style_ids[]' value='"+style_id+"'>");
    }
</script>
<div>
    <form method="post">
        <div class="div-inside">
            产品名称:<input type="text" name="goods_name" value="<?php echo $goods['goods_name']; ?>" />
        </div>
        <div class="div-inside">
            统一售价:<input type="text" name="price" value="<?php echo $goods['price']?>" />
        </div>
        <div class="div-inside">
            产品规格:<input type="text" name="spec" value="<?php echo $goods['spec']?>" />
        </div>
        <div class="div-inside">
            产品型号:<input type="text" name="style" value="<?php echo $goods['style']?>" />
        </div>
        <div class="div-inside">
            产品品牌:<input type="text" name="f_style" value="<?php echo $goods['f_style']?>" />
        </div>
        <div class="div-inside">
            原厂编码:<input type="text" name="f_no" value="<?php echo $goods['f_no']?>" />
        </div>
        <div class="div-inside">
            参考车型:<input type="text" name="cars_list" value="<?php echo $goods['cars_list']?>" />
        </div>
        <div>
            <select name="need_style">
                <option value="0" <?php if($goods['need_car_style'] == 0){echo "selected";}?>>不匹配车型</option>
                <option value="1" <?php if($goods['need_car_style'] == 1){echo "selected";}?>>匹配车型</option>
            </select>
        </div>
        <div class="div-inside">
            适合车型:
            <div id="brand">
            <?php
                if($goods['style_ids'] == 'all'){
                    echo '所有车型';
                }else{
                    echo "<ul>";
                    foreach($goods['brand'] as $style):
                        echo "<li>".$style['style_name']."</li>";
                    endforeach;
                    echo "</ul>";
                }
            ?>
            </div>
            <input type="button" value="增加适合车型" onclick="change_brand();" id="button1"/>
        </div>
        <div class="div-inside" >
            所属分类:
            <select name="goods_type">
                <?php foreach($goods_types as $type): ?>
                    <option value="<?php echo $type['type_id']; ?>" <?php if($type['type_id'] == $goods['goods_type']){echo "selected=selected";} ?> >
                        <?php echo $type['name'];?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="div-inside">
            <input type="submit" value="确定修改" />
        </div>

    </form>

</div>
