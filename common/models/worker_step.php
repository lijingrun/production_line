<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/7/29
 * Time: 21:32
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Worker_step extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%worker_step}}';
    }
}