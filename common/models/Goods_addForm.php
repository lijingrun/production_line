<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 12:30
 */
namespace common\models;

use Yii;
use yii\base\Model;

class Goods_addForm extends Model{
    public $goods_name;
    public $goods_type;
    public $images;
    public $price;
    public $introduce;
    public $if_show;
    public $spec;
    public $style;

    public function rules()
    {
        return [
            [['goods_type'],'required','message' => '请选择商品类型'],
            [['goods_name'],'required','message' => '请填写商品名称'],
            [['price'],'required','message' => '请填写商品价格'],
            [['spec'],'required','message' => '请填写商品规格'],
            [['style'],'required','message' => '请填写商品规格'],
//            [['style']],
        ];
    }

}