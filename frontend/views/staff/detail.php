<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/9/30
 * Time: 14:29
 */

?>
<script>
    function to_change(id){
        var htm = "<input type='text' id='new_num' style='width:50px;' onblur='change_num("+id+")'>";
        $("#change"+id).html(htm);
    }
    function to_change_un(id){
        var htm = "<input type='text' id='new_num' style='width:50px;' onblur='change_num_un("+id+")'>";
        $("#change_un"+id).html(htm);
    }
    function change_num(id){
        var new_num = $("#new_num").val().trim();
        if(new_num >= 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=staff/change_num',
                data : {'id' : id, 'new_num' : new_num},
                success : function(data){
                    if(data == 111){
                        alert("操作成功！");
                        location.reload();
                    }
                }
            });
        }
    }
    function change_num_un(id){
        var new_num = $("#new_num").val().trim();
        if(new_num > 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=staff/change_num_un',
                data : {'id' : id, 'new_num' : new_num},
                success : function(data){
                    if(data == 111){
                        alert("操作成功！");
                        location.reload();
                    }
                }
            });
        }
    }

</script>
<div>
    <table class="table">
        <tr>
            <th>工号</th>
            <th>日期</th>
            <th>组件</th>
            <th>实际数</th>
            <th>不良数</th>
        </tr>
        <?php foreach($worker_step as $val): ?>
        <tr>
            <td><?php echo $val['worker_no'];?></td>
            <td><?php echo date("Y-m-d",$val['date'])?></td>
            <td><?php echo $val['step_id']['title'];?></td>
            <td id="change<?php echo $val['id']?>" title="双击修改"><span ondblclick="to_change(<?php echo $val['id']?>);"><?php echo $val['actual_num'];?><span></td>
            <td id="change_un<?php echo $val['id']?>" title="双击修改"><span ondblclick="to_change_un(<?php echo $val['id']?>);"><?php echo $val['unhealthy_num'];?></span></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
