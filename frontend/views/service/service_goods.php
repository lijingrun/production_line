<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 21:23
 */
?>
<script>
    function add_goods(){
        var service_id = <?php echo $service['id']?>;
        var goods_type = $("#goods_type").val();
        var num = $("#num").val().trim();
        var is_must = $('input[name="is_must"]:checked').val();
        if(goods_type == 0 || num == ''){
            alert("商品分类和所需数量都不能为空！");
        }else{
            $.ajax({
                type : 'post',
                url : 'index.php?r=service/add_goods',
                data : {'service_id' : service_id, 'goods_type' : goods_type, 'num' : num, 'is_must' : is_must},
                success : function(data){
                    if(data == 111){
                        location.reload();
                    }
                }
            });
        }
    }
    function del_goods(id){
        if(confirm("是否确定删除该内容？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=service/del_goods',
                data : {'service_id' : id},
                success : function(data){
                    if(data == 111){
                        alert("删除成功！");
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
    function change_must(id){
        var is_must = $("#must_type").val();
        $.ajax({
            type : 'post',
            url : 'index.php?r=service/change_must',
            data : {'is_must' : is_must, 'id' : id},
            success : function(data){
                if(data == 111){
                    alert("修改成功！");
                }else{
                    alert("修改失败!");
                    location.reload();
                }
            }
        });
    }
</script>
<div>
    <h4>服务：<?php echo $service['name']?></h4>
    <table>
        <tr>
            <th style="width:100px;">需要产品</th>
            <th style="width:100px;">所需数量</th>
            <th style="width:100px;">是否必须</th>
            <th style="width:80px;">操作</th>
        </tr>

        <?php foreach($service_goods as $service_good): ?>
        <tr>
            <td>
                <?php echo $service_good['goods_type']['name']; ?>
            </td>
            <td><?php echo $service_good['nums'];?></td>
            <td>
                <select onchange="change_must(<?php echo $service_good['id']?>);" id="must_type">
                    <option <?php if($service_good['is_must']){ echo "selected='selected'";} ?> value="1">必须</option>
                    <option <?php if(!$service_good['is_must']){ echo "selected='selected'";} ?> value="0">非必须</option>
                </select>
            </td>
            <td>
                <input type="button" value="删除" onclick="del_goods(<?php echo $service_good['id']?>);" />
            </td>
        </tr>
        <?php endforeach;?>
    </table>
    <div style="padding-top: 20px;background-color: #2e6da4;color:white;margin-top: 20px;">
        <h4 style="padding-left: 10px;">添加新内容：</h4>
        <div style="padding:10px;">
            商品类型：
            <select id="goods_type" style="width:100px;color:black;">
                <option value="0">商品类型</option>
                <?php foreach($goods_types as $goods_type): ?>
                    <option value="<?php echo $goods_type['type_id']?>">
                        <?php echo $goods_type['name']; ?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>

        <div style="padding:10px;">
            所需数量：<input type="text" id="num" style="width:50px;color:black" >
        </div>

        <div style="padding:10px;">是否必须:
            <input type="radio"  value="1" name="is_must" checked="checked" >是
            <input type="radio"  value="0" name="is_must">否
        </div>
        <div style="padding:10px;color:black">
            <input type="button" value="增加" onclick="add_goods();"  />
        </div>
    </div>
</div>
