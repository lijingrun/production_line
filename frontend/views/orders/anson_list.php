<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/26
 * Time: 20:50
 */
?>
<script>
    function change_reason(id){
        var html = "<input type='text' id='change_r' onblur='to_change_reason("+id+");'>";
        $("#reason"+id).html(html);
    }
    function to_change_reason(id){
        var reason = $("#change_r").val();
        $.ajax({
            type : 'post',
            url : 'index.php?r=orders/change_reason',
            data : {'id' : id, 'reason' : reason},
            success : function(data){
                if(data == 111){
                    alert("修改完毕！");
                    location.reload();
                }else{
                    alert("扑街了，修改失败！");
                }
            }
        });
    }
</script>
<div>
    <?php foreach($reasons as $reason): ?>
        <div>
            <p>车牌号码：<?php echo $reason['car_no'];?></p>
            <p>加单原因：
                <span ondblclick="change_reason(<?php echo $reason['id']?>);" id="reason<?php echo $reason['id']?>">
                    <?php echo $reason['reason'];?>
                </span>
                <span style="font-size: 8px;color:red;padding-left:10px;">(双击修改)</span>
            </p>
            <p>加单服务：<?php echo $reason['service_name'];?></p>
            <p>填写工人：<?php echo $reason['worker']['username'];?></p>
            <p>
                <a href="index.php?r=orders/to_additional&reason_id=<?php echo $reason['id'];?>">
                    <input type="button" value="去加单" class="btn-success" />
                </a>
            </p>
        </div>
    <?php endforeach; ?>
    <div>

    </div>
</div>
