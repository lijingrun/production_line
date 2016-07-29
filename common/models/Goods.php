<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 0:36
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Goods extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%goods}}';
    }
}