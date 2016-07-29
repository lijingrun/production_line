<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/7
 * Time: 11:29
 * 会员
 */

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $openid
 * @property string $phone
 */
class Member_cons_point extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%member_cons_point}}';
    }
}