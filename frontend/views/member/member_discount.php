<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/31
 * Time: 13:51
 */
?>
<script>
    function type_add(){
        var type_name = $("#type_name").val();
        var discount = $("#discount").val();
        if(type_name != ''){
            $.ajax({
                type : 'post',
                url : 'index.php?r=member/type_add',
                data : {'type_name' : type_name, 'discount' : discount},
                success : function(data){
                    if(data == 111){
                        alert("添加成功！");
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试！")
                    }
                }
            });
        }
    }
</script>
<div>
    <div style="padding-bottom: 20px;">
        <p>
            等级名称：<input type="text" id="type_name" />
        </p>
        <p style="padding-top:10px;">
            对应折扣：<input type="text" id="discount" style="width:50px;" /><span style="color:red;font-size: 10px;">*请输入折扣，如8折请输入0.8*</span>
        </p>
        <p style="padding-top:10px;">
            <input type="button" value="增加客户等级" onclick="type_add();"  />
        </p>
    </div>
    <table border="1">
        <tr>
            <th style="width:100px;">会员等级</th>
            <th style="width:100px;">折扣优惠(折)</th>
        </tr>
        <?php foreach($discounts as $discount): ?>
        <tr>
            <th><?php echo $discount['type_name'];?></th>
            <th><?php echo $discount['discount']*10;?>折</th>
        </tr>
        <?php endforeach; ?>
    </table>
</div>
