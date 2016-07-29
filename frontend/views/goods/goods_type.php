<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 0:42
 */
?>
<style>
    li{padding-top:10px;}
</style>
<script>
    function add_type(){
        var name = $("#type_name").val();
        var top_id = $("#top_id").val();
        var need_style = $("#need_style").val();
        $.ajax({
            type : 'post',
            url : 'index.php?r=goods/add_type',
            data : {'name' : name, 'top_id' : top_id,'need_style' : need_style},
            success : function(data){
                if(data == 111){
                    alert("添加成功！");
                    location.reload();
                }else{
                    alert("服务器繁忙，请稍后重试！");
                }
            }
        });
    }
    function del_type(id){
        if(confirm("是否确定删除该分类？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=goods/del_type',
                data : {'id' : id},
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
    function del_alltype(id){
        if(confirm("是否删除节点以及节点下面所有子节点？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=goods/del_alltype',
                data : {'id' : id},
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
    function change_need(id){
        var need_style = $("#need_style"+id).val();
        $.ajax({
            type : 'post',
            url : 'index.php?r=goods/change_type_need',
            data : {'id' : id, 'need_style' : need_style},
            success : function(data){
                if(data == 111){
                    alert("修改成功！");
                }else{
                    alert("修改失败！");
                }
            }
        });
    }
</script>
<div>
    产品分类
    <?php foreach($top_types as $top_type): ?>
    <li>
        <label >
            <?php echo $top_type['name']?>
            <a href="#" onclick="del_alltype(<?php echo $top_type['type_id']?>);">
                <span title="删除节点">X</span>
            </a>
            <span style="padding-left: 30px;">
                <select id="need_style<?php echo $top_type['type_id']?>" onchange="change_need(<?php echo $top_type['type_id']?>);">
                    <option value="1" <?php if($top_type['need_style']){echo "selected";}?>>匹配车型</option>
                    <option value="0" <?php if(!$top_type['need_style']){echo "selected";}?>>不匹配车型</option>
                </select>
            </span>
        </label>
        <ol>
            <?php if(!empty($top_type['under'])){ ?>
            <?php foreach($top_type['under'] as $under): ?>
            <li>
                <?php echo $under['name']; ?>
                <a href="#" onclick="del_type(<?php echo $under['type_id']?>);">
                    <span title="删除节点">X</span>
                </a>
                <span style="padding-left: 30px;">
                <select id="need_style<?php echo $under['type_id']?>" onchange="change_need(<?php echo $under['type_id']?>);">
                    <option value="1" <?php if($under['need_style']){echo "selected";}?>>匹配车型</option>
                    <option value="0" <?php if(!$under['need_style']){echo "selected";}?>>不匹配车型</option>
                </select>
            </span>
            </li>
            <?php endforeach; }?>
        </ol>
    </li>
    <?php endforeach; ?>
    <div>
        增加分类:
        <div>
            分类名称：<input type="text" id="type_name" />
            上级分类：
            <select id="top_id">
                <option value="0">请选择上级分类</option>
                <?php foreach($top_types as $type): ?>
                <option value="<?php echo $type['type_id']?>"><?php echo $type['name']?></option>
                <?php endforeach; ?>
            </select>
            <span style="color:red;font-size: 8px;">*不选为1级分类*</span>
            <select id="need_style">
                <option value="1">匹配车型</option>
                <option value="0">不匹配车型</option>
            </select>
            <div>
                <input type="button" value="添加" onclick="add_type();" />
            </div>
        </div>
    </div>
</div>


