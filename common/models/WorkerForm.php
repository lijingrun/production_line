<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/10
 * Time: 23:34
 */
namespace common\models;

use Yii;
use yii\base\Model;

class WorkerForm extends Model{
    public $worker_name;
    public $store_id;
    public $password;

    public function rules()
    {
        return [
            [['worker_name','store_id','password'],'required','message' => '内容不能为空'],
            [['worker_name'], 'unique', 'targetClass' => 'common\models\Worker', 'message' => '工人已经存在！']
        ];
    }
}