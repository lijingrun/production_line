<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/8/24
 * Time: 15:41
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Type extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%type}}';
    }
}