<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/9/30
 * Time: 11:05
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Worker extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%worker}}';
    }
}