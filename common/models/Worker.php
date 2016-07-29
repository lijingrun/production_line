<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/10
 * Time: 23:30
 */

namespace common\models;

use Yii;
use yii\base\Model;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Worker extends ActiveRecord{
    public static function tableName()
    {
        return '{{%worker}}';
    }
}