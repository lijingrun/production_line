<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 18:19
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Service_check_type extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%service_check_type}}';
    }
}