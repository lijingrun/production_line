<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 17:58
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Service_type extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%service_type}}';
    }
}