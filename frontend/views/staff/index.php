<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/9/30
 * Time: 11:00
 */
?>
<script>
    function add(){
        var worker_no = $("#worker_no").val().trim();
        if(worker_no != ''){
            $.ajax({
                type : 'post',
                url : 'index.php?r=staff/add',
                data : {'worker_no'  : worker_no},
                success : function(data){
                    if(data == 111){
                        alert("添加成功！");
                        location.reload();
                    }else if( data == 222){
                        alert("请输入内容");
                    }else if(data == 333){
                        alert("编号已经存在！");
                    }else{
                        alert("服务器繁忙，请稍后重试！");
                    }
                }
            });
        }else{
            alert("请输入内容");
        }
    }
    function del(id){
        if(confirm("删除会删除账号下面所有记录，是否确定操作？")){
            $.ajax({
                type : 'post',
                url : 'index.php?r=staff/del',
                data : {"id" : id},
                success : function(data){
                    if(data == 111){
                        alert("操作成功！");
                        location.reload();
                    }else{
                        alert(data);
                    }
                }
            });
        }
    }
</script>
<div >
    <div class="input-group" style="width:30%">
        <input type="text" class="form-control" id="worker_no">
      <span class="input-group-btn">
        <button class="btn btn-default" type="button" onclick="add();">添加</button>
      </span>
    </div>

    <div style="padding-top:30px;">
        账号列表：
        <ul class="">
            <?php foreach($workers as $work): ?>
            <li>
                    <a href="index.php?r=staff/detail&worker_no=<?php echo $work['worker_no'];?>"><?php echo $work['worker_no']?></a>---<a href="#" onclick="del(<?php echo $work['id']?>);">X</a>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
