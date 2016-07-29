<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/5
 * Time: 9:18
 */
?>
<script>
    function find_member(){
        var phone = $("#phone").val();
        if(phone != ''){
            $.ajax({
                type : 'post',
                url : 'index.php?r=package/find_member',
                data : {'phone' : phone},
                success : function(data){
                    $("#member").append(data);
                }
            });
        }
    }
</script>
<div>
    套餐：<?php echo $package['name']?>
</div>
<div>
    客户电话：<input type="text" id="phone">
    <input type="button" value="查找" onclick="find_member();" />
</div>
<div >
    <form id="member" method="post">

    </form>
</div>
<div>
    已购买客户：
    <div style="padding:20px;">
        <?php foreach($p_member as $val): ?>
        <p><?php echo $val['member_name']."---X".$val['nums'];?></p>
        <?php endforeach; ?>
    </div>
</div>
