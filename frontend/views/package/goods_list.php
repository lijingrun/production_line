<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/4
 * Time: 9:51
 */
?>
<script>
    function get_goods(){
        var type_id = $("#goods_type").val();
        $("#goods_list").html("");
        if(type_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=package/find_goods',
                data : {'type_id' : type_id},
                success : function(data){
                    $("#goods_list").append(data);
//                    alert(data);
                }
            });
        }
    }
    function del_goods(id){
        if(confirm("是否删除该商品？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=package/del_goods',
                data : {'id' : id},
                success : function(data){
                    if(data == 111){
                        alert("删除成功！");
                        location.reload();
                    }else{
                        alert("删除失败");
                    }
                }
            });
        }
    }
</script>
<div>
    <div>
        已选产品：
        <div>
            <?php foreach($goods as $good):  ?>
            <p>
                <?php echo $good['goods_name']."X".$good['nums']; ?>
                <input type="button" value="删除" onclick="del_goods(<?php echo $good['id']?>);" />
            </p>
            <?php endforeach; ?>
        </div>
    </div>

    <div>
        <form method="post">
            <div>
                <select id="goods_type" onchange="get_goods();">
                    <option value="0">商品类型</option>
                    <?php foreach($goods_types as $type): ?>
                    <option value="<?php echo $type['type_id']?>" ><?php echo $type['name']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="goods_list"style="padding-top:20px;padding-bottom: 20px;"></div>
            <input type="submit" value="添加" class="btn-success" />
        </form>
    </div>
</div>
