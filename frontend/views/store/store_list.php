<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/10
 * Time: 20:50
 */
?>
<div>
    <a href="index.php?r=store/add" >添加店铺</a>
</div>
<div>
    <table>
        <tr>
            <th style="width:100px;">店铺名</th>
            <th style="width:100px;">创建时间</th>
        </tr>
        <?php foreach($stores as $store): ?>
        <tr>
            <td><?php echo $store['store_name']; ?></td>
            <td><?php echo date('Y-m-d',$store['create_time']); ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

</div>
