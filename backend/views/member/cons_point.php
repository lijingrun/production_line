<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/6
 * Time: 17:40
 */

?>
<script>
    function transfer_cons_point(id){
        $("#trans"+id).html('');
        $("#trans"+id).html("请输入被转让会员电话号码：<br/><input type='text' id='phone"+id+"' ><br/><input style='margin-top:10px;' type='button' value='转让' onclick='transfer_to("+id+");'>");
    }
    function transfer_to(id){
        var phone = $("#phone"+id).val().trim();
        if(confirm("是否确认将该积分卷转让？") && phone != ''){
            $.ajax({
                type : 'post',
                url : 'index.php?r=member/transfer',
                data : {'id' : id, 'phone' : phone},
                success : function(data){
                    if(data == 111){
                        alert("转让成功！");
                        location.reload();
                    }else if(data == 333){
                        alert("该会员不存在！");
                    }else{
//                        alert("服务器繁忙，请稍后重试！");
                        alert(data);
                    }
                }
            });
        }
    }
</script>
<div>
    <h3>我的消费积分卷</h3>
    <?php foreach($cons_point as $point): ?>
        <div class="panel panel-warning" style="font-size: 20px;">
            <div class="panel-heading">
                <h3 class="panel-title">

                </h3>
            </div>
            <div class="panel-body">
                <p>可用积分：<?php echo $point['surplus'];?></p>
                <p>有效期： <?php echo date("Y-m-d",$point['ev_time']); ?></p>
                <p id="trans<?php echo $point['id'];?>">
                    <input type="button" value="转让积分卷" onclick="transfer_cons_point(<?php echo $point['id']?>);" class="btn-info" />
                </p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
