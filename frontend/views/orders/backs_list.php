<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/3/7
 * Time: 14:10
 */
?>
<script>
    function find(){
        var car_no = $("#car_no").val();
        location.href="index.php?r=orders/order_back&car_no="+car_no;
    }
    function no_accept(id){
        var html = "请输入不受理原因<input type='text' id='why_no_accept' /><input type='button' value='确定' onclick='to_no_accept("+id+");'>";
        $("#accept"+id).html(html);
    }
    function to_no_accept(id){
        var why = $("#why_no_accept").val().trim();
        if(why == ''){
            alert("请输入不受理的原因");
        }else{
            $.ajax({
                type : 'post',
                url : 'index.php?r=orders/no_accept_back',
                data : {'why' : why , 'id' : id},
                success : function(data){
                    if(data == 111){
                        alert("操作成功！");
                        location.reload();
                    }else{
                        alert("操作失败！");
                    }
                }
            });
        }
    }
</script>
<div>
    车牌号码：<input type="text" id="car_no" value="<?php echo $car_no; ?>"> <input type="button" value="查找" onclick="find();"/>
    <?php foreach($backs as $back):  ?>
    <div style="padding:20px;">
        <p>原工单：<?php echo $back['order_no'];?></p>
        <p>申请时间：<?php echo date("Y-m-d",$back['created_time']);?></p>
        <p>申请原因：<?php echo $back['why'];?></p>
        <p>返工车辆：<?php echo $back['car_no'];?></p>
        <?php if(!empty($back['back_order'])){ ?>
        <a href="index.php?r=orders/detail&order_id=<?php echo $back['back_order'];?>">返工单号：<?php echo $back['back_order'];?></a>
        <?php }else{ ?>
            <?php if($back['status'] == 1){ ?>
            <div id="accept<?php echo $back['id']?>">
                <a href="index.php?r=car/order_add&car_id=<?php echo $back['car_id']?>&back_id=<?php echo $back['id']?>">
                <input type="button" value="建返工单" />
                </a>
                <input type="button" value="不受理" onclick="no_accept(<?php echo $back['id']?>);" />
            </div>
                <?php }else{ ?>
                不受理(原因：<?php echo $back['del_reason']?>)
                <?php } ?>
        <?php }?>
    </div>
    <?php endforeach; ?>
</div>
