<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 21:20
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Service_price extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%service_price}}';
    }



}