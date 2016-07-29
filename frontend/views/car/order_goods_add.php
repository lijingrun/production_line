<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/23
 * Time: 9:18
 */
?>
<script>
        function input_nums(id){
            var htm = "数量：<input style='width:50px;' type='text' value='1' name='nums[]' />";
            if($("#check_box"+id).is(":checked")){
                $("#input"+id).html(htm);
            }else{
                $("#input"+id).html('');
            }
        }
    function del_goods(id){
        if(confirm("是否确定删除该商品？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=car/del_goods',
                data : {'id' : id},
                success : function(data){
                    if(data == 111){
                        alert("操作成功！");
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
    function find_by_key(){
        var key_word = $("#key_word").val().trim();
        var id = <?php echo $id;?>;
        location.href='index.php?r=car/order_goods_add&id='+id+"&key_word="+key_word;
    }
</script>
<div>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">已选择商品</h3>
        </div>
        <div class="panel-body">
            <?php foreach($has_add_goods as $val):
                echo "<p>".$val['goods_name']."--".$val['nums']."&nbsp;&nbsp;<a><span onclick='del_goods(".$val['id'].");'>X</span></a> </p>";
            endforeach; ?>
        </div>
    </div>

    <h3>请选择需要的产品以及填写对应数量</h3>
    <div style="padding:10px;">
        产品型号：<input type="text" value="<?php echo $key_word;?>" id="key_word" />
        <input type="button" value="搜索" onclick="find_by_key();" class="btn-success" />
    </div>
    <?php if(!empty($goods)){ ?>

    <form method="post">
    <?php foreach($goods as $good): ?>
        <div>
            <input type="checkbox" name="goods_ids[]" value="<?php echo $good['goods_id']?>" id="check_box<?php echo $good['goods_id']?>" onclick="input_nums(<?php echo $good['goods_id']?>);" />
            <span><?php echo $good['goods_name'].$good['style']."(￥".$good['price'].")"?></span>
            <p id="input<?php echo $good['goods_id']?>">
            </p>
        </div>
    <?php endforeach; ?>
        <input type="submit" value="提交" class="btn-primary" />
    </form>
    <?php } ?>
</div>


