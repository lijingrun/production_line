<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/12
 * Time: 21:28
 */
?>
<script>
    function check_service(){
        var mileage = $("#mileage").val();
        var car_id = $("#car_id").val();
        if(mileage != ''){
            $("#check_myself").html('系统检测中。。。。');
            $.ajax({
                type : 'post',
                url : 'index.php?r=member/check_myself',
                data : {'mileage' : mileage, 'car_id' : car_id},
                success : function(data){
                    $("#check_myself").html(data);
                }
            });
        }else{
            alert("请输入当前里程数");
        }
    }
    function goto_order(){
        var car_id = $("#car_id").val();
//        alert(car_id);
        location.href="index.php?r=member/add_order&car_id="+car_id;
    }
</script>
<div>
    <?php if($need){ ?>
        <a href="index.php?r=member/my_cars">
    <div style="padding-top:20px;">
        你有还未完善信息的爱车，为了我们能更好为您提供服务，请点击完善
    </div>
        </a>
    <?php } ?>
    <div>
        <?php if(!empty($index_images)){
            foreach($index_images as $images):
            ?>
            <div style="padding:10px;">
                <a href="index.php?r=member/interface&id=<?php echo $images['id']?>">
                <img src="<?php echo '/frontend/web/'.$images['images']?>" style="width:100%;" />
                </a>
            </div>
        <?php
            endforeach;
            } ?>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title">汽车自检</h3>
        </div>
        <div id="check_myself" class="panel-body">
            <select id="car_id" style="margin-top:20px;margin-bottom:20px;font-size: 20px;">
                <?php foreach($cars as $car): ?>
                    <option value="<?php echo $car['id'];?>"><?php echo $car['car_no']?></option>
                <?php endforeach; ?>
            </select>
            <div>
                <div class="form-group">
                    <input type="text" id="mileage" class="form-control" placeholder="当前里程/km" />
                </div>
                <input type="button" value="开始检测" onclick="check_service();" class="btn-warning" style="font-size: 20px;" />
                <input type="button" value="马上下单" onclick="goto_order();" class="btn-info"  style="font-size: 20px;"/>
            </div>
        </div>
    </div>

</div>
