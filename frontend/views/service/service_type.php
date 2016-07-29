<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 18:01
 */

?>
<script>
    function add_type(){
        var name = $("#name").val().trim();
        var top_id = $("#top_id").val();
        if(name == ''){
            alert("请输入分类名称");
        }else{
            $.ajax({
                type : 'post',
                url : 'index.php?r=service/type_add',
                data : {'name' : name, 'top_id' : top_id},
                success : function(data){
                    if(data == 111){
                        location.reload();
                    }else{

                    }
                }
            });
        }
    }
    function del_type(id){
        if(confirm("是否确定删除该分类？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=service/del_service_type',
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
</script>
<div>
    类型名：<input type="text" id="name">
    上级分类：
    <select id="top_id">
        <option value="0">请选择上级分类</option>
        <?php foreach($types as $type): ?>
        <option value="<?php echo $type['type_id']; ?>">
            <?php echo $type['name']; ?>
        </option>
        <?php endforeach; ?>
    </select>
    <input type="button" value="增加" onclick="add_type();" />
</div>
<div style="padding-top:20px;">
    服务分类：
    <?php foreach($types as $top_type): ?>
        <li>
            <label ><?php echo $top_type['name']."--<span onclick='del_type(".$top_type['type_id'].");'>X</span>"?></label>
            <ol>
                <?php if(!empty($top_type['under'])){ ?>
                    <?php foreach($top_type['under'] as $under): ?>
                        <li><?php echo $under['name']."--<span>X</span>"; ?></li>
                    <?php endforeach; }?>
            </ol>
        </li>
    <?php endforeach; ?>
</div>
