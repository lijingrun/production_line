<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/23
 * Time: 10:12
 */
?>
<script>
    function add_model(){
        var model_name = $("#model_name").val().trim();
        var brand_id = "<?php echo $brand['brand_id']?>";
        var year = $("#year").val();
        if(model_name != '' && brand_id != ''){
            $.ajax({
                type : 'post',
                url : 'index.php?r=model/add_model',
                data : {'model_name' : model_name, 'brand_id' : brand_id, 'year' : year},
                success : function(data){
                    if(data == 111){
                        alert("添加成功！");
                        location.reload();
                    }else if(data == 222){
                        alert("该型号已经存在！");
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
</script>
<div>
    <h3><?php echo $brand['brand_name']; ?></h3>
    <div>
        <p>
        车型：<input type="text" id="model_name" />
        </p>
        <p>
            年份：<input type="text" id="year" />
        </p>
        <input type="button" value="添加车型" onclick="add_model();" />
    </div>


    <div style="padding-top:20px;">
        型号列表
        <ul>
            <?php foreach($models as $model): ?>
            <li>
                <a href="index.php?r=model/style&id=<?php echo $model['id']?>">
                <?php echo $model['model_name']."----(".$model['year'].")"; ?>
                </a>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
