<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 0:44
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Member_discount extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%member_discount}}';
    }
}