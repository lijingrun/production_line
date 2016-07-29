<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/5/10
 * Time: 10:51
 */
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Carema extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%carema}}';
    }
}