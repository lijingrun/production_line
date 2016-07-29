<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/7/28
 * Time: 16:53
 */
?>

<div>
    <div style="padding-left:20px;">
        <a href="index.php?r=step/add">
            <input type="button" value="添加新组件" class="btn-success" />
        </a>
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
