<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/24
 * Time: 15:20
 */
?>
<script>
    function input_nums(id){
        var htm = "需要数量：<input style='width:50px;' type='text' value='1' name='nums[]' />";
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
                url : 'index.php?r=member/del_goods',
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
    function to_goods_list(){
        $("#to_goods_list").hide();
        $("#goods_list").show();
    }
</script>
<div style="font-size: 20px;">
    <?php if(!empty($goods)){ ?>
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">已选择商品</h3>
            </div>
            <div class="panel-body">

                <?php if(!empty($has_add_goods)){foreach($has_add_goods as $val):
                    echo "<p>".$val['goods_name'].$val['goods_id']['style']."(".$val['goods_id']['spec'].")"."--".$val['nums']."&nbsp;&nbsp;<a><span onclick='del_goods(".$val['id'].");'>X</span></a> </p>";
                endforeach; }else{ echo "您未选择任何商品！";}?>
            </div>
        </div>
        <input type="button" value="进行商品选择" id="to_goods_list" class="btn-warning" onclick="to_goods_list();" />
        <div class="panel panel-danger" id="goods_list" style="display: none;">
            <div class="panel-heading">
        <h4>请选择需要的产品以及填写对应数量</h4>
                </div>
            <div class="panel-body">
        <form method="post">
            <?php foreach($goods as $good): ?>
                <div>
                    <input type="checkbox" name="goods_ids[]" value="<?php echo $good['goods_id']?>" id="check_box<?php echo $good['goods_id']?>" onclick="input_nums(<?php echo $good['goods_id']?>);" />
                    <span><?php echo $good['goods_name'].$good['style']."(".$good['spec'].")"."--￥".$good['price'];?></span>
                    <p id="input<?php echo $good['goods_id']?>">
                    </p>
                </div>
            <?php endforeach; ?>
            <div>
                <input type="checkbox" /><span>自带</span>
            </div>
            <input type="submit" class="btn-info" value="确定提交" />
        </form>
                </div>
            </div>
    <?php }else{ ?>
        该服务无需选择商品
    <?php } ?>
</div>
</div>
