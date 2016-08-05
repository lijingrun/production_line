<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/7/29
 * Time: 22:24
 */

namespace frontend\controllers;

use common\models\Step;
use common\models\Worker_step;
use Yii;
use yii\web\Controller;


class StatisticsController extends Controller{

    public function actionIndex(){
        $start = $_GET['start'];
        $end = $_GET['end'];
        if(empty($start)){
            $start = strtotime(date("Y-m-01",time()));
        }else{
            $start = strtotime($start);
        }
        if(empty($end)){
            $end = strtotime(date("Y-m-d",time()));
        }else{
            $end = strtotime($end);
        }
        $steps = Step::find()->asArray()->all();
        $workers = Worker_step::find()->where("date >=".$start)->andWhere("date <=".$end)->groupBy('worker_no')->orderBy('worker_no')->asArray()->all();
        foreach($workers as $key=>$worker){
            $workers[$key]['step_data'] = array();
            $workers[$key]['total_price'] = 0;
            foreach($steps as $step):
                //数量
                $nums = Worker_step::find()->where("worker_no ='".$worker['worker_no']."'")->andWhere("step_id =".$step['step_id'])->andWhere("date >=".$start)->andWhere("date <=".$end)->sum(actual_num);
                if(empty($nums)){
                    $nums = 0;
                }
                $total_price = $nums*$step['price'];
                $workers[$key]['total_price'] += $total_price;
                $worker_data = array(
                    'step_name' => $step['title'],
                    'step_price' => $step['price'],
                    'nums' => $nums,
                    'total_price' => $total_price,
                );
                $workers[$key]['step_data'][] = $worker_data;
            endforeach;
        }
        return $this->render('index',[
            'start' => date("Y-m-d",$start),
            'end' => date("Y-m-d",$end),
            'workers' => $workers,
            'step' => $steps,
        ]);
    }

    public function actionExport_data(){
        $start = $_GET['start'];
        $end = $_GET['end'];
        if(empty($start)){
            $start = strtotime(date("Y-m-01",time()));
        }else{
            $start = strtotime($start);
        }
        if(empty($end)){
            $end = strtotime(date("Y-m-d",time()));
        }else{
            $end = strtotime($end);
        }
        $steps = Step::find()->asArray()->all();
        $workers = Worker_step::find()->where("date >=".$start)->andWhere("date <=".$end)->groupBy('worker_no')->orderBy('worker_no')->asArray()->all();
        foreach($workers as $key=>$worker){
            $workers[$key]['step_data'] = array();
            $workers[$key]['total_price'] = 0;
            foreach($steps as $step):
                //数量
                $nums = Worker_step::find()->where("worker_no ='".$worker['worker_no']."'")->andWhere("step_id =".$step['step_id'])->andWhere("date >=".$start)->andWhere("date <=".$end)->sum(actual_num);
                if(empty($nums)){
                    $nums = 0;
                }
                $total_price = $nums*$step['price'];
                $workers[$key]['total_price'] += $total_price;
                $worker_data = array(
                    'step_name' => $step['title'],
                    'step_price' => $step['price'],
                    'nums' => $nums,
                    'total_price' => $total_price,
                );
                $workers[$key]['step_data'][] = $worker_data;
            endforeach;
        }
        $filename = $start."到".$end."工人统计".".csv";
        foreach($workers as $worker):
            $str .= "工号, ".$worker['worker_no']."\n";
            $str .= "序号,组件,数量,单价,总价\n";
            $i = 1;
            foreach($worker['step_data'] as $val):
                if(is_array($val)) {
                    $str .= $i . "," . $val['step_name'] . "," . $val['nums'] . "," . $val['step_price'] . "," . $val['total_price'] . "\n";
                    $i++;
                }
            endforeach;
            $str .= " , , ,总计,".$worker['total_price']."\n";
            $str .= "\n";
        endforeach;
        $str = iconv('utf-8','gb2312',$str);
//        $str = "姓名,性别,年龄\n";
//        $str .= "zhangyandas,nv,23\n";
        $this->export_csv($filename,$str);

    }

    //导出
    function export_csv($filename,$data)
    {
        header("Content-type:text/csv");
        header("Content-Disposition:attachment;filename=".$filename);
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $data;
    }

}