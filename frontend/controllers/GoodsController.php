<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 0:28
 */

namespace frontend\controllers;

use common\models\Car_brand;
use common\models\Car_model;
use common\models\Car_style;
use common\models\Coupon;
use common\models\Goods_addForm;
use common\models\Member_coupon;
use common\models\Members;
use common\models\Weixin;
use common\models\Weixin_template;
use Yii;
use common\models\Goods;
use common\models\Goods_type;
use common\models\Goods_typeForm;
use common\models\Upload_imageForm;
use yii\data\Pagination;
use yii\web\Controller;

class GoodsController extends Controller{

    public function beforeAction($action)
    {
        if(Yii::$app->session['user_role']['goods'] != 'on'){
            Yii::$app->getSession()->setFlash('error','你没有权限访问！');
            return $this->goHome();
        }else{
            return $action;
        }
    }

    //产品列表
    public function actionIndex(){
        $goods_types = Goods_type::find()->asArray()->all();
        $goods_name = $_GET['goods_name'];
        $type_id = $_GET['type'];
        if(!empty($goods_name)){
            $conditions = ['like','goods_name',$goods_name];
        }
        if($type_id != 0){
            $conditions2 = ['goods_type' => $type_id];
        }
        $all_goods = Goods::find();
        if(!empty($conditions)) {
            $all_goods->andWhere($conditions);
        }
        if(!empty($conditions2)){
            $all_goods->andWhere($conditions2);
        }
        $pages = new Pagination([
            'totalCount' => $all_goods->count(),
            'pageSize' => 20,
        ]);
        $goods = $all_goods->offset($pages->offset)->limit($pages->limit)->all();
        foreach($goods as $key=>$good):
            $goods[$key]['goods_type'] = Goods_type::find()->where(['type_id' => $good['goods_type']])->asArray()->one();
            //获取车型列表
            if($good['style_ids'] != 'all' && $good['style_ids'] != ','){
                $ids = explode(',',$good['style_ids']);
                $ids = array_filter($ids);
                $ids = implode(',',$ids);
                if(!empty($ids)) {
                    $styles = Car_style::find()->where('id in (' . $ids . ')')->asArray()->all();
                }else{
                    $styles = '';
                }
                $goods[$key]['brand'] = $styles;
            }
        endforeach;
        return $this->render('goods_list',[
            'goods_types' => $goods_types,
            'goods_name' => $goods_name,
            'type_id' => $type_id,
            'goods' => $goods,
            'pages' => $pages,
        ]);

    }

    //产品分类页面
    public function actionGoods_type(){
        $top_types = Goods_type::find()->where(['top_id' => 0])->asArray()->all();
        foreach($top_types as $key=>$top_type):
            $under_type = Goods_type::find()->where(['top_id' => $top_type['type_id']])->asArray()->all();
            $top_types[$key]['under'] = $under_type;
        endforeach;
            return $this->render('goods_type', [
                'top_types' => $top_types,
            ]);
    }

    //修改产品分类是否 匹配车型
    public function actionChange_type_need(){
        $id = $_POST['id'];
        $need = $_POST['need_style'];
        $goods_type = Goods_type::find()->where(['type_id' => $id])->one();
        $goods_type->need_style = $need;
        if($goods_type->save()){
            echo 111;
        }else{
            echo 222;
        }
        exit;
    }

    //添加产品分类
    public function actionAdd_type(){
        $goods_type = new Goods_type();
        $goods_type->name = $_POST['name'];
        $goods_type->top_id = $_POST['top_id'];
        if($_POST['need_style']){
            $goods_type->need_style = $_POST['need_style'];
        }else{
            $goods_type->need_style = 0;
        }
        if($goods_type->save()){
            echo 111;
        }else{
            echo 222;
        }
    }

    //修改产品价格
    public function actionChange_price(){
        if(Yii::$app->request->post()){
            $goods_id = $_POST['id'];
            $price = $_POST['price'];
            $goods = Goods::find()->where(['goods_id' => $goods_id])->one();
            $goods->price = $price;
            if($goods->save()){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //增加产品
    public function actionGoods_add(){
        $model = new Goods_addForm();
//        $upload_img = new Upload_imageForm();
        $goods_type = Goods_type::find()->asArray()->all();
        $goods_types = array();
        foreach($goods_type as $type):
            $goods_types[$type['type_id']] = $type['name'];
        endforeach;
        //查所有品牌
        $brands = Car_brand::find()->asArray()->all();
        if($model->load(Yii::$app->request->post()) && $model->validate() ){
            //适合类型
            $style_ids = $_POST['style_ids'];
            if(empty($style_ids)){
                $style = 'all';
            }else{
                $style = ','.implode(',',$style_ids).',';

            }
            $goods = new Goods();
            $goods->goods_name = $model['goods_name'];
            $goods->price = $model['price'];
            $goods->goods_type = $model['goods_type'];
            $goods->if_show = 1;
            $goods->style = $model['style'];
            $goods->spec = $model['spec'];
            $goods->style_ids = $style;
//            $upload_img->file = UploadedFile::getInstance($upload_img, 'file');
//            if(!file_exists('upload/goods')){
//                mkdir('upload/goods');
//            }
//            print_r($upload_img);
//            echo $upload_img->file->baseName;exit;
//            $img = 'upload/goods/'. $upload_img->file->baseName . time().'.' . $upload_img->file->extension;
//            echo $img;exit;
//            $upload_img->file->saveAs($img);
//            echo $img;exit;
            if($goods->save()){
                Yii::$app->getSession()->setFlash('success','保存成功！');
            }else{
                Yii::$app->getSession()->setFlash('error','保存失败！');
            }
            return $this->redirect('index.php?r=goods/goods_add');
        }else{
            return $this->render('goods_add',[
                'model' => $model,
//                'upload_img' => $upload_img,
                'goods_types' => $goods_types,
                'brands' => $brands,
            ]);
        }
    }

    //根据品牌id获取系列
    public function actionFind_models(){
        if(Yii::$app->request->post()){
            $brand_id = $_POST['brand_id'];
            $models = Car_model::find()->where(['brand_id' => $brand_id])->asArray()->all();
            echo "<label class='control-label' >选择系列</label>";
            echo "<select id='model_id' class='form-control' onchange='find_style();'>";
            echo "<option value='0'>请选择系列</option>";
            foreach($models as $model):
                echo "<option value='".$model['id']."'>".$model['model_name']."</option>";
            endforeach;
            echo "</select>";
            exit;
        }
    }

    //根据系列获取车型
    public function actionFind_style(){
        if(Yii::$app->request->post()){
            $model_id = $_POST['model_id'];
            $styles = Car_style::find()->where(['model_id' => $model_id])->asArray()->all();
            echo "<div id='style_list'><label class='control-label' >选择车型</label>";
            echo "<select id='style' class='form-control'>";
            foreach($styles as $style):
                echo "<option value='".$style['id']."' id='style".$style['id']."'>".$style['style_name']."</option>";
            endforeach;
            echo "</select><input type='button' value='添加' onclick='add_style();'></div>";
            exit;
        }
    }

    //删除商品
    public function actionDel_goods(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            $goods = new Goods();
            if($goods->deleteAll(['goods_id' => $id])){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //修改产品
    public function actionEdit_goods(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            $goods_name = $_POST['goods_name'];
            $price = $_POST['price'];
            $goods_type = $_POST['type'];
            $spec = $_POST['spec'];
            $goods = Goods::find()->where(['goods_id' => $id])->one();
            $goods->goods_name = $goods_name;
            $goods->goods_type = $goods_type;
            $goods->price = $price;
            $goods->spec = $spec;
            if($goods->save()){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //删除节点
    public function actionDel_type(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            $goods_type = new Goods_type();
            if($goods_type->deleteAll(['type_id' => $id])){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //删除节点以及下面所有子节点
    public function actionDel_alltype(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            $goods_type = new Goods_type();
            $under_count = Goods_type::find()->where(['top_id' => $id])->count();
            if($under_count >= 1){
                if($goods_type->deleteAll(['top_id' => $id]) && $goods_type->deleteAll(['type_id' => $id])) {
                    echo 111;
                }else{
                    echo 222;
                }
            }else{
                if($goods_type->deleteAll(['type_id' => $id])) {
                    echo 111;
                }else{
                    echo 222;
                }
            }

            exit;
        }
    }

    //修改产品
    public function actionGoods_edit(){
        $goods_id = $_GET['goods_id'];
        $goods = Goods::find()->where(['goods_id' => $goods_id])->asArray()->one();

        if($goods['style_ids'] != 'all'){
            $goods['brand'] = Car_style::find()->where("id in (0".$goods['style_ids']."0)")->asArray()->all();
        }
        $goods_types = Goods_type::find()->asArray()->all();
        if(Yii::$app->request->post()){
            $goods =  Goods::find()->where(['goods_id' => $goods_id])->one();
            if(empty($goods)){
                $goods = new Goods();
            }
            $model = $_POST['model'];
            if(empty($model)){
                $model = 0;
            }
            $style_ids = $_POST['style_ids'];
            if(empty($style_ids)){
                //如果无选任何车型，又是不匹配车型的话，直接all，匹配的话，判断是否原来为all，是就修改成, 不是就不做处理
                if($_POST['need_style'] == 0) {
                    $goods->style_ids = 'all';
                }else{
                    if($goods->style_ids == 'all'){
                        $goods->style_ids = ',';
                    }
                }
            }else{
                //如果有选择车型，判断如果原来是all，就替代，不是，就增加
                if($goods->style_ids == "all") {
                    $style = ','.implode(',',$style_ids).',';
                    $goods->style_ids = $style;
                }else{
                    $style = implode(',',$style_ids).',';
                    $goods->style_ids .= $style;
                }

            }
            $goods->goods_name = $_POST['goods_name'];
            $goods->goods_type = $_POST['goods_type'];
            $goods->price = $_POST['price'];
            $goods->spec = $_POST['spec'];
            $goods->style = $_POST['style'];
            $goods->f_style = $_POST['f_style'];
            $goods->f_no = $_POST['f_no'];
            $goods->cars_list = $_POST['cars_list'];
            $goods->need_car_style = $_POST['need_style'];
//            $goods->style_ids = $style;

            if($goods->save()){
                Yii::$app->getSession()->setFlash('success','修改成功！');
                return $this->redirect('index.php?r=goods');
            }else{
                Yii::$app->getSession()->setFlash('error','服务器繁忙，请稍后重试！');
            }
        }else{
            return $this->render('goods_edit',[
                'goods' => $goods,
                'goods_types' => $goods_types,
            ]);
        }
    }

    //获取车辆品牌
    public function actionGet_car_brand(){
        $car_brands = Car_brand::find()->asArray()->all();
        echo "<select onchange='get_models();' id='brand_id' name='brand_id'>";
        echo "<option value='0'>所有车型</option>";
        foreach($car_brands as $brand):
            echo "<option value='".$brand['brand_id']."' >".$brand['brand_name']."</option>";
        endforeach;
        echo "</selece>";
        exit;
    }

    //获取车辆系列
    public function actionGet_models(){
        $brand_id = $_POST['brand_id'];
        $models = Car_model::find()->where(['brand_id' => $brand_id])->asArray()->all();
        echo "<select id='model' onchange='get_style();' name='model_id'>";
        echo "<option value='0'>请选择系列</option>";
        foreach($models as $model):
            echo "<option value='".$model['id']."'>".$model['model_name']."(".$model['year'].")"."</option>";
        endforeach;
        echo "<select>";
        exit;
    }

    //获取车辆型号
    public function actionGet_car_style(){
        if(Yii::$app->request->post()){
            $model_id = $_POST['model_id'];
            $styles = Car_style::find()->where(['model_id' => $model_id])->asArray()->all();
            echo "<select name='style_id' id='style' name='style_name'>";
            foreach($styles as $style):
                echo "<option value='".$style['id']."'>".$style['style_name']."</option>";
            endforeach;
            echo "</select>";
            exit;
        }
    }

    //新建产品优惠卷
    public function actionAdd_coupon(){
        $goods_id = $_GET['goods_id'];
        $goods = Goods::find()->where(['goods_id' => $goods_id])->asArray()->one();
        if(Yii::$app->request->post()){
            $coupon = new Coupon();
            $coupon->explain = $_POST['explain'];
            $coupon->coupon_name = $_POST['coupon_name'];
            $coupon->validity_period = $_POST['validity_period'];
            $coupon->goods_id = $_POST['goods_id'];
            $coupon->price = $_POST['price'];
            $coupon->min_price = $_POST['min_price'];
            if($coupon->save()){
                Yii::$app->getSession()->setFlash('success','新增成功！');
            }else{
                Yii::$app->getSession()->setFlash('error','服务器繁忙，请稍后重试！');
            }
            return $this->redirect('index.php?r=goods/coupon_list');
        }else{
            return $this->render('add_coupon',[
                'goods' => $goods,
            ]);
        }
    }

    //优惠卷列表
    public function actionCoupon_list(){
        $goods = Goods::find()->asArray()->all();
        if(Yii::$app->request->post()){
            $coupon = new Coupon();
            if(empty($_POST['min_price'])){
                $min_price = 0;
            }else{
                $min_price = $_POST['min_price'];
            }
            $coupon->coupon_name = $_POST['coupon_name'];
            $coupon->explain = $_POST['explain'];
            $coupon->validity_period = $_POST['validity_period'];
            $coupon->price = $_POST['price'];
            $coupon->min_price = $min_price;
            $coupon->goods_id = $_POST['goods_id'];
            if($coupon->save()){
                Yii::$app->getSession()->setFlash('success','添加成功');
            }else{
                Yii::$app->getSession()->setFlash('error','服务器繁忙，请稍后重试！');
            }
            return $this->redirect('index.php?r=goods/coupon_list');
        }else {
            $coupons = Coupon::find()->orderBy('price')->asArray()->all();
            foreach ($coupons as $key => $coupon):
                $coupons[$key]['goods_id'] = Goods::find()->where(['goods_id' => $coupon['goods_id']])->asArray()->one();
            endforeach;
            return $this->render('coupon_list', [
                'coupons' => $coupons,
                'goods' => $goods,
            ]);
        }
    }

    //根据会员电话发放优惠卷给客户
    public function actionGrant_coupon(){
        $coupon_id = $_GET['coupon_id'];
        if(Yii::$app->request->post()){
            $member_phones = $_POST['member_phones'];
            $phones = explode(',',$member_phones);
            //循环发放，如果发放失败，就记录下失败的号码
            $error_phone = '';
            foreach($phones as $phone):
                $member = Members::find()->where(['phone' => $phone])->asArray()->one();
                if(!empty($member['id'])){
                    $coupon = $this->coupon_to_member($coupon_id,$member['id']);
                    if(!$coupon){
                        $error_phone .= $phone.',';
                    }
                }else{
                    $error_phone .= $phone.',';
                }
            endforeach;
            Yii::$app->getSession()->setFlash('success','发放完毕');
            if(!empty($error_phone)){
                Yii::$app->getSession()->setFlash('error','下列号码发放不成功，请确认('.$error_phone.')');
            }
            return $this->redirect('index.php?r=goods/grant_coupon&coupon_id='.$coupon_id);
        }else{
            return $this->render('grant_coupon');
        }
    }

    function coupon_to_member($coupon_id,$member_id){
        $coupon = Coupon::find()->where(['coupon_id' => $coupon_id])->asArray()->one();
        $member_coupon = new Member_coupon();
        $member_coupon->member_id = $member_id;
        $member_coupon->coupon_id = $coupon_id;
        $coupon_sn = '000'.mt_rand(1000000, 9999999);
        $member_coupon->coupon_sn = $coupon_sn;
        $member_coupon->created_time = time();
        $member_coupon->status = 2;
        $member_coupon->created_id = Yii::$app->session['user_id'];
        $end_time = time()+($coupon['validity_period']*86400);
        $member_coupon->end_time = $end_time;
        //如果客户有绑定微信，就发现金卷到账信息给客户
        $member = Members::find()->where(['id' => $member_id])->asArray()->one();
        if(!empty($member['open_id'])){
            $data = array(
                'first' => array('value' => '优惠卷已经成功发放到您账号上面',),
                'keyword1' => array('value' => $coupon['coupon_name'],'topcolor'=> '#0F12D'),
                'keyword2' => array('value' => $coupon_sn,'topcolor'=> '#0F12D'),
                'keyword3' => array('value' => date("Y-m-d",$end_time),'topcolor'=> '#0F12D'),
                'remark' => array('value' => '您可以到个人中心查看所有的卡卷','topcolor'=> '#0F12D'),
            );
            $weixin = Weixin::find()->where(['appid' => $member['weixin_id']])->asArray()->one();  //公众号
            $template = Weixin_template::find()->where(['appid' => $weixin['appid']])->andWhere(['type_id' => 4])->asArray()->one();  //优惠卷发放模板
            $appid = $weixin['appid'];
            $secret = $weixin ['app_secret'];
            $errcode = $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$weixin['access_token'],$data);
//            echo $errcode;exit;

            //access_token无效的话，重新获取
            if($errcode != 0){
                $access_token = $this->get_access_token($appid,$secret);
                if($access_token != false){
                    $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$access_token,$data);
                }
            }
        }
        return $member_coupon->save();
    }

    //修改优惠卷
    public function actionEdit_coupon(){
        $coupon_id = $_GET['coupon_id'];
        $goods = Goods::find()->asArray()->all();
        $coupon = Coupon::find()->where(['coupon_id' => $coupon_id])->asArray()->one();
        if(Yii::$app->request->post()){
            $coupon = Coupon::find()->where(['coupon_id' => $coupon_id])->one();
            if(empty($_POST['min_price'])){
                $min_price = 0;
            }else{
                $min_price = $_POST['min_price'];
            }
            $coupon->coupon_name = $_POST['coupon_name'];
            $coupon->explain = $_POST['explain'];
            $coupon->validity_period = $_POST['validity_period'];
            $coupon->price = $_POST['price'];
            $coupon->min_price = $min_price;
            $coupon->goods_id = $_POST['goods_id'];
            if($coupon->save()){
                Yii::$app->getSession()->setFlash('success','添加成功');
            }else{
                Yii::$app->getSession()->setFlash('error','服务器繁忙，请稍后重试！');
            }
            return $this->redirect('index.php?r=goods/coupon_list');
        }else{
            return $this->render('edit_coupon',[
                'coupon' => $coupon,
                'goods' => $goods,
            ]);
        }
    }

    //工单获取车辆型号
    public function actionGet_style(){
        if(Yii::$app->request->post()){
            $model_id = $_POST['model_id'];
            $styles = Car_style::find()->where(['model_id' => $model_id])->asArray()->all();
            echo "<span id='style_list'><select  id='style' name='style_name'>";
            foreach($styles as $style):
                echo "<option value='".$style['id']."' id='style".$style['id']."'>".$style['style_name']."</option>";
            endforeach;
            echo "</select>";
            echo "<input type='button' value='增加' onclick='add_style();'></span>";
            exit;
        }
    }

}