<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 21:20
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Service_goods extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%service_goods}}';
    }

    public function getGoods_type(){
        return $this->hasOne(Goods_type::className(),['type_id' => 'goods_type']);
    }


}