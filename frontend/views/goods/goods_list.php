<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 0:40
 */
use yii\widgets\LinkPager;
?>
<style>
    td{
        padding-top:10px;
    }
</style>
<script>
    function find_goods(){
        var goods_name = $("#goods_name").val();
        var type_id = $("#goods_type").val();
        location.href="index.php?r=goods&goods_name="+goods_name+"&type="+type_id;
    }
    function del_goods(id){
        if(confirm("是否确定删除该商品？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=goods/del_goods',
                data : {'id' : id},
                success : function(data){
                    if(data == 111){
                        alert("删除成功！");
                        location.reload();
                    }
                }
            });
        }
    }
    function change_price(id){
        var html = "<input type='text' style='width:50px;' onblur='ajax_chang_price("+id+")' id='the_price"+id+"' >";
        $("#price"+id).html(html);
    }

    function ajax_chang_price(id){
        var price = $("#the_price"+id).val();
        if( price == ''){
            alert("请输入价格！");
        }else{
            $.ajax({
                type : 'post',
                url : 'index.php?r=goods/change_price',
                data : {'id' : id, 'price' : price},
                success : function(data){
                    if(data == 111){
                        alert("修改成功");
                        location.reload();
                    }else{
                        alert("修改失败！");
                    }
                }
            });
        }
    }
    function to_edit_goods(id){
        var goods_name = $("#goods_name"+id).html();
        var price = $("#price"+id).html();
        var spec = $("#spec"+id).val();
        $("#goods_name"+id).html("<input type='text' style='width:70px;' value='"+goods_name+"' id='edit_goods_name' >");
        $("#price"+id).html("<input type='text' style='width:70px;' value='"+price+"' id='edit_price' >");
        $("#goods_type"+id).html(
            "<select id='edit_goods_type'>"+
                <?php foreach($goods_types as $type):?>
            "<option value='<?php echo $type['type_id']?>'><?php echo $type['name']?></option>"+
                <?php endforeach; ?>
            "</select>"
        );
        $("#spec"+id).html("<input type='text' style='width:70px;' value='"+spec+"' id='edit_spec' >");
        $("#opera"+id).html("<a href='#'><span onclick='change_goods("+id+");'>确定修改</span></a>");
    }
    function change_goods(id){
        var edit_goods_name = $("#edit_goods_name").val();
        var edit_price = $("#edit_price").val();
        var edit_type = $("#edit_goods_type").val();
        var edit_spec = $("#edit_spec").val();
        $.ajax({
            type : 'post',
            url : 'index.php?r=goods/edit_goods',
            data : {'id' : id , 'goods_name' : edit_goods_name, 'price' : edit_price, 'type' : edit_type, 'edit_spec' : edit_spec, 'spec' : edit_spec},
            success : function(data){
                if(data == 111){
                    alert("修改成功！");
                    location.reload();
                }else{
                    alert("修改失败！");
                }
            }
        });
    }
</script>
<a href="index.php?r=goods/goods_type">
    产品分类
</a>
<a href="index.php?r=goods/goods_edit">
    添加产品
</a>
<a href="index.php?r=goods/coupon_list">
    优惠卷
</a>
<div align="center" style="padding:10px;">
    商品名称:<input type="text" id="goods_name" value="<?php echo $goods_name; ?>" />
    商品类型:
    <select id="goods_type">
        <option value="0">全部</option>
        <?php foreach($goods_types as $type): ?>
        <option value="<?php echo $type['type_id']; ?>" <?php if($type['type_id'] == $type_id){ echo 'selected'; }?>>
            <?php echo $type['name']?>
        </option>
        <?php endforeach; ?>
    </select>
    <input type="button" value="查找" onclick="find_goods()" />
</div>
<div>
    <?php if(empty($goods)){
        echo '查不到该商品！';
    }else{ ?>
    <table>
        <tr>
            <th style="width:300px;">产品</th>
            <th style="width:100px;">价钱</th>
            <th style="width:80px;" >规格</th>
            <th style="width:150px;">所属分类</th>
            <th style="width:100px;">匹配车型</th>
            <th style="width:200px;">操作</th>
        </tr>
        <?php foreach($goods as $good): ?>
        <tr>
            <td id="goods_name<?php echo $good['goods_id']?>"><?php echo $good['goods_name'];if(!empty($good['style'])){ echo "(".$good['style'].")";} ?></td>
            <td id="price<?php echo $good['goods_id']?>" ondblclick="change_price(<?php echo $good['goods_id'];?>)">

                    <?php echo $good['price'] ?>
            </td>
            <td id="spec<?php echo $good['goods_id']?>"><?php echo $good['spec'] ?></td>
            <td id="goods_type<?php echo $good['goods_id']?>"><?php echo $good['goods_type']['name'];?></td>
            <td>
                <?php
                if($good['need_car_style'] == 0){
                    echo "不匹配";
                }else{
                    echo "匹配";
                }
                ?>
            </td>
<!--            <td>-->
<!--                --><?php
//                    if($good['style_ids'] != 'all' && $good['style_ids'] != ','){
//                        foreach($good['brand'] as $style):
//                            echo $style['style_name']."<br />";
//                        endforeach;
//                    }else{
//                        echo "所有车型";
//                    }
//                ?>
<!--            </td>-->
            <td id="opera<?php echo $good['goods_id']?>" >
                <a href="#">
                    <span onclick="del_goods('<?php echo $good['goods_id'];?>');">删除</span>
                </a>
                <a href="index.php?r=goods/goods_edit&goods_id=<?php echo $good['goods_id']?>" id="edit_goods">
                    <span>修改</span>
                </a>
                <a href="index.php?r=goods/add_coupon&goods_id=<?php echo $good['goods_id']?>"><span>新建优惠卷</span></a>
            </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td colspan="2">
                <?= LinkPager::widget(['pagination' => $pages]); ?>
            </td>
        </tr>
    </table>
    <?php } ?>
</div>