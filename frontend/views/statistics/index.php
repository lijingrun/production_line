<script>
    function find_data(){
        var start = $("#start").val();
        var end = $("#end").val();
        if(end == ''){
            alert("请选择结束时间");
        }else{
            location.href="index.php?r=statistics&start="+start+"&end="+end;
        }
    }
    function e_data(){
//        alert(111);
        var start = $("#start").val();
        var end = $("#end").val();
        location.href="index.php?r=statistics/export_data&start="+start+"&end="+end;
    }
</script>
<style>
    .tab_div{
        padding:5px;
        float:left;
    }
    .worker_no{
        padding: 10px;
        font-size: 18px;
    }
    th{
        text-align:center;
    }
    td{
        text-align:center;
    }
</style>
<div>
    <div align="center">
        请选择日期：
        <input type="date" id="start" value="<?php echo $start;?>" />
        到
        <input type="date" id="end" value="<?php echo $end;?>" />
        <input type="button" value="查询" onclick="find_data();" />
        <input type="button" value="导出" onclick="e_data();" />
    </div>
    <div style="width:100%;padding-top:20px;" align="center">

        <?php foreach($workers as $worker): ?>
        <div class="tab_div">
        <table border="1">
            <tr>
                <td colspan="5">
                    <strong class="worker_no">
                    工号：<?php echo $worker['worker_no'];?>
                    </strong>
                    <span class="worker_no">
                        (<?php echo $start; ?>到<?php echo $end;?>生产报表)
                    </span>
                </td>
            </tr>
            <tr>
                <th>序号</th>
                <th>组件</th>
                <th>数量</th>
                <th>单价</th>
                <th>总价</th>
            </tr>
            <?php $i=1; foreach($worker['step_data'] as $val):?>
            <tr>
                <td><?php echo $i;$i++;?></td>
                <td><?php echo $val['step_name'];?></td>
                <td><?php echo $val['nums'];?></td>
                <td><?php echo $val['step_price'];?></td>
                <td><?php echo $val['total_price']?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td></td>
                <td colspan="3">共计：</td>
                <td><?php echo $worker['total_price'];?></td>
            </tr>
        </table>
        </div>
        <?php endforeach; ?>

    </div>
</div>