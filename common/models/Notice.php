<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/8/31
 * Time: 15:27
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Notice extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%notice}}';
    }
}