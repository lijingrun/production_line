<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/25
 * Time: 10:03
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Package_log extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%package_log}}';
    }
}