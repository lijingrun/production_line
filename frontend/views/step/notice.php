<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/8/31
 * Time: 15:21
 */
?>

<form method="post">
    <div>
        公告内容：
        <textarea class="form-control" rows="3" name="notice"><?php echo $notice['notice'];?></textarea>
        <br />
        <input type="submit" class="btn-info" value="提交" />
    </div>
</form>
