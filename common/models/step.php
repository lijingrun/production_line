<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/7/28
 * Time: 17:24
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Step extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%step}}';
    }
}