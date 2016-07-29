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
                    $("#content").html(data);
                }
            });
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
        var step_list = $("#step_list").val();
        if(worker_no == ''){
            alert("请输入工号");
            return false;
        }else if(step_list == 0){
            alert("请选择工序");
            return false;
        }else{
            $.ajax({
                type : 'post',
                url : 'index.php?r=index/save_data',
                data : {'actual_num' : actual_num, 'unhealthy_num' : unhealthy_num , 'worker_no' : worker_no , 'step_list' : step_list},
                success : function(data){
                    alert("保存成功！");
                }
            });
        }
    }
</script>
<style>
    .div1{
        /*border : solid 1px #A1A1A1;*/
        padding:10px;
    }
    .div2{
        border : solid 1px #A1A1A1;
        padding:10px ;
    }
    .p1{
        padding:10px 10px 10px 30px;
        font-size: 20px;
        background-color: #c67605;
    }
    .p1 input{
        width: 160px;
    }
</style>
<div style="padding-top:20px;">
    <div class="row">
        <div class="col-xs-6">
            <div class="div1">
                <div style="width: 100%;" id="content">

                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="div2">
                <p class="p1">
                    计划数：
                    <input type="text" value="0" id="plan" />
                </p>
                <p class="p1">
                    实际数：
                    <input type="text" value="0" id="actual_num" onblur="count_total();"/>
                </p>
                <p class="p1">
                    不良数：
                    <input type="text" value="0" id="unhealthy_num" onblur="count_total();" />
                </p>
                <p class="p1">
                    累计数：
                    <input type="text" id="total" value="0" />
                </p>
                <p class="p1">
                    组&nbsp;&nbsp;&nbsp;件：
                    <select style="width:160px;" onchange="get_step();" id="step_list">
                        <option value="0">请选择组件</option>
                        <?php foreach($steps as $step): ?>
                        <option value="<?php echo $step['step_id'];?>"><?php echo $step['title'];?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p class="p1">
                    工&nbsp;&nbsp;&nbsp;号：
                    <input type="text" id="worker_no" />
                </p>
                <div align="right" style="font-size: 18px;">
                    <input type="button" value="提交" onclick="submit_data();" class="btn-success" style="padding-left: 20px;padding-right: 20px;" />
                </div>
            </div>
        </div>
    </div>
</div>
