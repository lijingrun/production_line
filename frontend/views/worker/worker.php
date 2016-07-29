<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/10
 * Time: 23:32
 */
?>
<div>
    <a href="index.php?r=worker/add">添加工人</a>
</div>
<div>
    <table>
        <tr>
            <th>工人</th>
            <th>所属店铺</th>
            <th>操作</th>
        </tr>
        <?php foreach($workers as $worker):?>
        <tr>
            <td style="width:80px;"><?php echo $worker['worker_name'];?></td>
            <td style="width:150px;"><?php echo $worker['store']['store_name']?></td>
            <td></td>
        </tr>
        <?php endforeach;?>
    </table>
</div>
