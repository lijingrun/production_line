<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/8/24
 * Time: 15:34
 */
?>
<script>
    function add_type(){
        var type_name = $("#type_name").val().trim();
        if(type_name != ''){
            $.ajax({
                type : 'post',
                url : 'index.php?r=step/add_type_ajax',
                data : {'type_name' : type_name},
                success : function(data){
                    location.reload();
                }
            });
        }else{
            alert("请先输入类型名")
        }
    }
</script>
<div style="padding:20px;">
    <div style="width:300px;padding-bottom:20px;">
        <div class="input-group">
                <input type="text" class="form-control" placeholder="组件类型" id="type_name">
          <span class="input-group-btn">
            <button class="btn btn-default" type="button" onclick="add_type();">添加</button>
          </span>
        </div>
    </div>
    <div class="alert alert-success" role="alert">
        <ul class="nav nav-pills">
            <?php foreach($types as $type): ?>
            <li role="presentation" class="disabled"><a href="#"><?php echo $type['name']?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
