<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/4/14
 * Time: 10:47
 */
?>
<script>
    function to_change_order(id){
        var html = "<input type='text' style='width:80px;' id='order"+id+"' onblur='change_order("+id+");'>";
        $("#to_order"+id).removeAttr("onclick");
        $("#to_order"+id).html(html);
    }
    function change_order(id){
        var order = $("#order"+id).val();
        if(order != ''){
            $.ajax({
                type : 'post',
                url : 'index.php?r=interface/change_order',
                data : {'id' : id, "order" : order},
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
    function del_interface(id){
        if(confirm("是否确定删除？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=interface/del',
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
</script>
<div>
    <div>
        <table border="1">
            <tr>
                <th>活动</th>
                <th>排序(按数字升序排列)</th>
                <th style="width:100px;">操作</th>
            </tr>
            <?php foreach($index_images as $images): ?>
            <tr>
                <td>
                    <a href="index.php?r=interface/add_model&id=<?php echo $images['id']?>">
                    <img src="<?php echo $images['images'];?>" style="width:350px;margin:20px;" />
                    </a>
                </td>
                <td align="center" onclick="to_change_order(<?php echo $images['id']?>);" id="to_order<?php echo $images['id']?>">
                    <span >
                        <?php echo $images['order']?>
                    </span>
                </td>
                <td align="center">
                    <input type="button" value="删除" class="btn-danger" onclick="del_interface(<?php echo $images['id']?>)" />
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <a href="index.php?r=interface/add_model">
        <input type="button" class="btn-info" value="增加模块"
    </a>
</div>
