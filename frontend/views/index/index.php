<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/7/28
 * Time: 21:21
 */
?>
<script>
    function get_step(){
        var step_id = $("#step_list").val();
        $("#content").html('');
        if(step_id != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=index/get_content',
                data : {'step_id' : step_id},
                success : function(data){
                    var step = JSON.parse(data);
                    $("#content").html(step['content']);
                    $("#plan").val(step['plan']);
                }
            });
        }else{
            $("#plan").val(0);
            $("#content").html("<h1 align=‘center’>操作规范</h1>");
        }
    }
    function count_total(){
        var actual_num = $("#actual_num").val();
        var unhealthy_num = $("#unhealthy_num").val();
        var total = parseInt(actual_num)+parseInt(unhealthy_num);
        $("#total").val(total);
    }
    function submit_data(){
        var actual_num = $("#actual_num").val();
        var unhealthy_num = $("#unhealthy_num").val();
        var worker_no = $("#worker_no").val().trim();
        var step_id = $("#step_list").val();
        if(worker_no == ''){
            alert("请输入工号");
            return false;
        }else if(step_id == 0){
            alert("请选择工序");
            return false;
        }else{
            $.ajax({
                type : 'post',
                url : 'index.php?r=index/save_data',
                data : {'actual_num' : actual_num, 'unhealthy_num' : unhealthy_num , 'worker_no' : worker_no , 'step_id' : step_id},
                success : function(data){
//                    alert(data);
                    if(data == 111){
                        alert("保存成功！");
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }
    }
    function get_type(){
        var type = $("#type_list").val();
        $("step_list").html("");
        if(type != 0){
            $.ajax({
                type : 'post',
                url : 'index.php?r=index/get_type',
                data : {'type' : type},
                success : function(data){
                    $("#step_list").html(data);
//                    alert(data);
                }
            });
        }
    }
</script>
<style>
    .div1{
        border : solid 1px #A1A1A1;
        /*padding:10px;*/
        overflow:hidden;
        height:600px;
    }
    .div2{
        border : solid 1px #A1A1A1;
        padding:10px ;
        height:600px;
    }
    .p1{
        padding:10px 10px 10px 30px;
        font-size: 20px;
        background-color: #c67605;
    }
    .p1 input{
        width: 100px;
    }
</style>
<div style="padding-top:20px;">
    <div class="row">
        <div class="col-xs-9">
            <div class="div1">
                <div style="width: 100%;height:410px;" id="content">
                    <h1 align="center">操作规范</h1>
                </div>
            </div>
        </div>
        <div class="col-xs-3">
<!--            <form method="post">-->
            <div class="div2">
                <p class="p1">
                    计&nbsp;划：
                    <input type="text" value="0" id="plan" name="plan" readonly="readonly" />
                </p>
                <p class="p1">
                    实&nbsp;际：
                    <input type="text" value="0" id="actual_num" name="actual_num" onblur="count_total();"/>
                </p>
                <p class="p1">
                    不&nbsp;良：
                    <input type="text" value="0" style="color:red;" id="unhealthy_num" name="unhealthy_num" onblur="count_total();" />
                </p>
                <p class="p1">
                    累&nbsp;计：
                    <input type="text" id="total" value="0" readonly="readonly" />
                </p>
                <p class="p1">
                    类&nbsp;型：
                    <select onchange="get_type();" id="type_list" >
                        <option value="0">组件类型</option>
                        <?php foreach($types as $type): ?>
                        <option value="<?php echo $type['id']?>"><?php echo $type['name']?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p class="p1">
                    组&nbsp;件：
                    <select onchange="get_step();" id="step_list" name="step_id">
                        <option value="0">选择组件</option>
                    </select>
                </p>
                <p class="p1">
                    工&nbsp;号：
                    <input type="text" id="worker_no" name="worker_no" />
                </p>
                <div align="right" style="font-size: 18px;">
                    <input type="button" value="提交" onclick="submit_data();"  class="btn-success" style="padding-left: 20px;padding-right: 20px;" />
                </div>
            </div>
<!--            </form>-->
        </div>
    </div>
</div>
<div style="color:red;">
    <marquee direction="left" ><?php echo $notice['notice'];?></marquee>
</div>
