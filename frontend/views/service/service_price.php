<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/18
 * Time: 14:29
 */
?>
<script>
    function del_price(id){
        if(confirm("是否确定删除对应工时？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=service/del_price',
                data : {'id' : id},
                success : function(data){
                    if(data == 111){
                        alert("已经成功删除！");
                        location.reload();
                    }else{
                        alert("服务器繁忙，请稍后重试！")
                    }
                }
            });
        }
    }
</script>
<h4>服务：<?php echo $service['name']?></h4>

<?php foreach($service_prices as $price):  ?>
<p>
    <?php
        if($price['car_type'] != 0) {
            echo $price['car_type']['car_type'] . "---￥" . $price['price'];
        }else{
            echo "全部车型---￥" . $price['price'];
        }
    ?>&nbsp;&nbsp;
    <a href="#" title="删除">
        <span onclick="del_price(<?php echo $price['id']?>);">X</span>
    </a>
</p>
<?php endforeach; ?>

<form method="post">
    对应车型：
    <select name="car_type">
        <option>全部车型</option>
        <?php foreach($car_types as $type):?>
        <option value="<?php echo $type['type_id']?>" ><?php echo $type['car_type']?></option>
        <?php endforeach; ?>
    </select>
    对应工时费：
    <input type="text" name="price">
    <input type="submit" value="保存" />
</form>
