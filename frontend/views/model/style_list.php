<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/14
 * Time: 15:51
 */
?>
<script>
    function add_style(){
        var style_name = $("#style_name").val().trim();
        var model_id = $("#model_id").val();
        if(style_name != ''){
            $.ajax({
                type : 'post',
                url : 'index.php?r=model/style_add',
                data : {'style_name' : style_name, 'model_id' : model_id},
                success : function(data){
                    if(data == 111){
                        alert("添加成功！");
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试！")
                    }
                }
            });
        }
    }
</script>
<div>
    <h3>
    系列：<?php echo $model['model_name'];?>
        <input type="hidden" value="<?php echo $model['id']?>" id="model_id" >
    </h3>
    车型：
    <ul>
        <?php foreach($styles as $style): ?>
        <li>
            <?php echo $style['style_name']?>
        </li>
        <?php endforeach; ?>
    </ul>
    <div>
        <input type="text" id="style_name" /><input type="button" value="添加" onclick="add_style();" />
    </div>
</div>
