<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/14
 * Time: 10:31
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Deposit_log extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%deposit_log}}';
    }
}
