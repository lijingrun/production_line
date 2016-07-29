<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/4
 * Time: 8:57
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Package_member extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%package_member}}';
    }
}