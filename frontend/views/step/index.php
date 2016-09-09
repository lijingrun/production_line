<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/7/28
 * Time: 16:53
 */
?>
<script>
    function find(){
        var type = $("#type").val();
        if(type != 0){
            location.href="index.php?r=step&type_id="+type;
        }else{
            location.href="index.php?r=step"
        }
    }
</script>
<div>
    <div style="padding-left:20px;">
        <a href="index.php?r=step/add">
            <input type="button" value="添加新组件" class="btn-success" />
        </a>
        <a href="index.php?r=step/add_type">
            <button class="btn-info">组件类型</button>
        </a>
        <a href="index.php?r=step/add_notice">
            <button class="btn-danger">添加公告</button>
        </a>
    </div>
    <div style="padding:10px;" align="center">
        组件类型：
        <select id="type" onchange="find();">
            <option value="0">全部类型</option>
            <?php foreach($types as $type): ?>
            <option value="<?php echo $type['id']?>" <?php if($type_id == $type['id']){ echo "selected";}?>><?php echo $type['name'];?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div style="padding:20px;" align="center">
                <?php foreach($steps as $step): ?>
            <a href="index.php?r=step/edit&step_id=<?echo $step['step_id'];?>">
                <p class="bg-success" style="font-size: 18px;padding:5px;">
                    <?php echo $step['title'];?>
                </p>
            </a>
                <?php $i++; endforeach; ?>
    </div>
</div>
