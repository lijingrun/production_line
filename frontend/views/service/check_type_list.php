<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/17
 * Time: 8:59
 */
?>
<script>
    function del_style(id){
        if(confirm("是否确定删除该检测项目？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=service/del_type',
                data : {'id' : id},
                success : function(data){
                    if(data == 111){
                        alert("删除成功！");
                        location.reload();
                    } else{
                        alert("服务器繁忙，请稍后重试");
                    }
                }
            });
        }
    }
</script>
<div>
    <h4>快速检测检查项：</h4>
    <ul>
        <?php foreach($types as $type): ?>
            <li><?php echo $type['check_type'];?>&nbsp;&nbsp;&nbsp;&nbsp;<span onclick="del_style(<?php echo $type['id']?>)"><a href="#">X</a></span></li>
        <?php endforeach;  ?>
    </ul>
    <form method="post">
    <div>
        检查项：<input type="text" name="check_type" /><input type="submit" value="增加" />
    </div>
    </form>
</div>
