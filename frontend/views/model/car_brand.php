<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/22
 * Time: 16:35
 */

?>
<script>
    function brand_add(){
        var brand_name = $("#brand_name").val().trim();
        if(brand_name == ''){
            alert("请输入品牌名称！");
        }else{
            $.ajax({
                type : 'post',
                url : 'index.php?r=model/brand_add',
                data : {'brand_name' : brand_name},
                success : function(data){
                    if(data == 111){
                        alert('添加成功！');
                        location.reload();
                    }else if(data == 222){
                        alert('该品牌已经存在！');
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
    function find_brand(){
        var brand_name = $("#find_name").val().trim();
        if(brand_name != ''){
            location.href='index.php?r=model&brand_name='+brand_name;
        }
    }
</script>
<div>
    <div>
        品牌名：
        <input type="text" id="brand_name"  />
        <input type="button" value="添加" onclick="brand_add();" />
    </div>
    <div style="padding:20px;">
        <input type="text" id="find_name" value="<?php echo $brend_name?>" /><input type="button" value="查找" onclick="find_brand();" />
    </div>
    <div>
        车辆品牌：
        <ul>
            <?php foreach($brands as $brand): ?>
            <li>
                <a href="index.php?r=model/model_list&brand_id=<?php echo $brand['brand_id'];?>">
                    <div>
                        <?php echo $brand['brand_name']; ?>
                    </div>
                </a>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
</div>
