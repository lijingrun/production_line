<?php


/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/7
 * Time: 11:27
 * 会员模块控制器
 */

namespace frontend\controllers;

use common\models\Car_type;
use common\models\Coupon;
use common\models\Member_cons_point;
use common\models\Member_coupon;
use common\models\Member_discount;
use common\models\Package;
use common\models\Package_goods;
use common\models\Package_member;
use common\models\Point_log;
use common\models\Store;
use common\models\User;
use common\models\Weixin;
use common\models\Weixin_template;
use Yii;
use common\models\Members;
use common\models\Member_addForm;
use common\models\Deposit_log;
use common\models\Deposit_plan;
use common\models\Deposit_addForm;
use common\models\Cars;
use common\models\Orders;
use common\models\Car_addForm;
use common\models\Rec_log;
use yii\web\Controller;
use yii\data\Pagination;

class MemberController extends Controller
{

    public function beforeAction($action)
    {
        if(Yii::$app->session['user_role']['member'] != 'on'){
            Yii::$app->getSession()->setFlash('error','你没有权限访问！');
            return $this->goHome();
        }else{
            return $action;
        }
    }

    //会员列表
    public function actionIndex(){
        $conditions = array();
        $phone = $_GET['phone'];
		$car_no = $_GET['car_no'];
		if(!empty($car_no)){
			$car = Cars::find()->where("car_no like '%".$car_no."%'")->asArray()->one();
			$conditions['id'] = $car['member_id'];
		}
        if(!empty($phone)){
            $conditions['phone'] = $phone;
        }
        if(!empty($conditions)){
            $all_members = Members::find()->where($conditions);
        }else {
            $all_members = Members::find();
        }
        $pages = new Pagination([
            'totalCount' => $all_members->count(),
            'pageSize' => 20,
        ]);
        $members = $all_members->offset($pages->offset)->limit($pages->limit)->all();
		foreach($members as $key=>$member):
			$members[$key]['rec_numbers'] = Cars::find()->where(['member_id' => $member['id']])->asArray()->all();
		endforeach;
        $member_types = Member_discount::find()->asArray()->all();
        return $this->render('members_index',[
            'members' => $members,
            'pages' => $pages,
            'member_types' => $member_types,
            'conditions' => $conditions,
			'car_no' => $car_no,
        ]);
    }

    //修改会员的等级
    public function actionChange_type(){
        if(Yii::$app->request->post()){
            $type = $_POST["type"];
            $member_id = $_POST['member_id'];
            $member = Members::find()->where(['id' => $member_id])->one();
            $member->type = $type;
            if($member->save()){
                echo 111;
            }else{
                echo 222;
            }
        }
    }

    //会员详细情况
    public function actionDetail(){
        $member_id = $_GET['member_id'];
        $member = Members::find()->where(['id' => $member_id])->asArray()->one();
        //消费积分
        $member_cons_point = Member_cons_point::find()->where(['member_id' => $member_id])->andWhere(['>','surplus',0])->asArray()->all();
        $point_log = Point_log::find()->where(['member_id' => $member_id])->asArray()->all();
        $total_cons_point = 0;
        foreach($member_cons_point as $key=>$val):
            $total_cons_point += $val['surplus'];
			$member_cons_point[$key]['order'] = Orders::find()->where(['order_no' => $val['order_no']])->asArray()->one();
        endforeach;
		//车辆信息
		$cars = Cars::find()->where(['member_id' => $member_id])->asArray()->all();
        return $this->render('detail',[
            'member' => $member,
            'total_cons_point' => $total_cons_point,
            'member_cons_point' => $member_cons_point,
            'point_log' => $point_log,
			'cars' => $cars,
        ]);
    }

    //发送微信模板信息
    public function actionSend_message(){
        $member_id = $_GET['id'];
        $member = Members::find()->where(['id' => $member_id])->asArray()->one();
        if(Yii::$app->request->post()){
            $content = $_POST['content'];
            if(empty($member['open_id'])){
                Yii::$app->getSession()->setFlash('error','客户还未绑定微信号');
                return $this->render('send_message');
            }else{
                $appid = $member['weixin_id'];
                $template = Weixin_template::find()->where(['type_id' => 9])->andWhere(['appid' => $appid])->asArray()->one();
                $weixin = Weixin::find()->where(['appid' => $appid])->asArray()->one();
                $user = User::find()->where(['id' => Yii::$app->session['user_id']])->asArray()->one();
                $store = Store::find()->where(['store_id' => $user['store_id']])->asArray()->one();
                $data = array(
                    'first' => array('value' => '您需要的配件已回到门店',),
                    'keyword1' => array('value' => $store['store_name'],'topcolor'=> '#0F12D'),
                    'keyword2' => array('value' => date("Y-m-d H:i:s",time()),'topcolor'=> '#0F12D'),
                    'keyword3' => array('value' => $user['username'],'topcolor'=> '#0F12D'),
                    'keyword4' => array('value' => $content,'topcolor'=> '#0F12D'),
                    'remark' => array('value' => '请您及时到店进行配件更换，谢谢','topcolor'=> '#0F12D'),
                );
                $error_code = $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$weixin['access_token'],$data);
                if($error_code != 0){
                    $access_token = $this->get_access_token($appid,$weixin['app_secret']);
                    if($access_token != false){
                        $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$access_token,$data);
                    }
                }
                Yii::$app->getSession()->setFlash('success','发送完毕！');
                return $this->redirect("index.php?r=member");
            }
        }else{
            return $this->render('send_message');
        }
    }

    //会员卡卷页面
    public function actionCoupon(){
        $member_id = $_GET['member_id'];
        $member_coupons = Member_coupon::find()->where(['member_id' => $member_id])->asArray()->all();
        if(!empty($member_coupons)) {
            foreach ($member_coupons as $key => $couppon) {
                $member_coupons[$key]['coupon_id'] = Coupon::find()->where(['coupon_id' => $couppon['coupon_id']])->asArray()->one();
            }
        }
        return $this->render('coupons',[
            'member_coupons' => $member_coupons,
        ]);
    }

    //添加会员
    public function actionAdd(){
        $model = new Member_addForm();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $from_rec = $_POST['Member_addForm']['rec_numbers'];
            $from_member = Members::find()->where(['rec_numbers' => $from_rec])->asArray()->one();
            $member = new Members();
            $member->user_name = $model['user_name'];
            $member->phone = $model['phone'];
            $member->password = md5('123456');
            $member->create_time = time();
            $member->from_member = $from_member['id'];
            $member->rec_numbers = $this->getRandChar(6); //自动生成6位不重复推荐码
            if ($member->save()) {
                //插入推荐信息
                $rec_log = new Rec_log();
                $rec_log->rec_member = $from_member['id'];
                $rec_log->member_id = $member->id;
                $rec_log->create_time = time();
                $rec_log->save();
                Yii::$app->getSession()->addFlash('success', '保存成功');
            } else {
                Yii::$app->getSession()->addFlash('error', '保存失败');
            }
            $this->redirect('index.php?r=member');
            return;
        }else{
            return $this->render("members_add",[
                'model' => $model,
            ]);
        }


    }

    //会员类型管理
    public function actionDiscount(){
        $discouns = Member_discount::find()->asArray()->all();
        return $this->render('member_discount',[
            'discounts' => $discouns,
        ]);
    }

    //增加会员类型
    public function actionType_add(){
        if(Yii::$app->request->post()){
            $type_name = $_POST['type_name'];
            $discount = $_POST['discount'];
            if(empty($discount)){
                $discount = 1;
            }
            $new_type = new Member_discount();
            $new_type->type_name = $type_name;
            $new_type->discount = $discount;
            if($new_type->save()){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    //会员登记汽车列表
    public function actionCars(){
        $member_id = $_GET['id'];
        $member = Members::find()->where('id ='.$member_id)->asArray()->one();
        $car_type = Car_type::find()->asArray()->all();
        $car_types = array();
        foreach($car_type as $type):
            $car_types[$type['type_id']] = $type['car_type'];
        endforeach;
        $model = new Car_addForm();
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $car = new Cars();
            $car->car_no = $model['car_no'];
            $car->member_id = $member_id;
            $car->car_type = $model['car_type'];
            if($car->save()){
                Yii::$app->getSession()->addFlash('success','添加成功！');
            }else{
                Yii::$app->getSession()->addFlash('error','保存失败！');
            }
            return $this->redirect('index.php?r=member/cars&id='.$member_id);
        }else {
            $conditions = array();
            $conditions['member_id'] = $member_id;
            $cars = Cars::find()->where($conditions)->asArray()->all();
//            print_r($cars);exit;
            return $this->render('cart_list', [
                'member' => $member,
                'model' => $model,
                'cars' => $cars,
                'car_types' => $car_types,
            ]);
        }
    }

	public function actionDeposit() {
		$member_id = $_GET['id'];
        $member = Members::find()->where('id ='.$member_id)->asArray()->one();
        $member_m = Members::find()->where('id ='.$member_id)->one();
		$model = new Deposit_addForm();

		$deposit_plan = Deposit_plan::find()->asArray()->all();
        $deposit_plans = array();
        foreach($deposit_plan as $plan):
			$deposit_plans[$plan['id']] = 
				sprintf( '充值 ￥%.2f 得 ￥%.2f', $plan['cash_amount'], $plan['deposit_amount'] );
        endforeach;

		if($model->load(Yii::$app->request->post()) && $model->validate()) {
			$deposit = new Deposit_log();
			$deposit->member_id = $member_id;
			$deposit->user_id = Yii::$app->user->getId();
			$deposit->store_id = Yii::$app->session['store_id'];
			$plan = Deposit_plan::find()->where('id ='.$model['plan_id'])->asArray()->one();
			$deposit->plan_id = $model['plan_id'];
			$deposit->cash_amount = $plan['cash_amount'];
			$deposit->deposit_amount = $plan['deposit_amount'];
			$deposit->description = $model['description'];
            $deposit->create_time = time();
			$member_m->balance = $member_m->balance + $plan['deposit_amount'];
			
			if($deposit->save() && $member_m->save()) {
                Yii::$app->getSession()->addFlash('success','充值成功！');
            }else{
                Yii::$app->getSession()->addFlash('error','充值失败！');
            }
            return $this->redirect('index.php?r=member');
		}
		else {
			return $this->render('deposit_add', [
					'member' => $member,
					'model' => $model,
					'deposit_plans' => $deposit_plans,
				]);
		}
	}

	public function actionDeposit_details() {
		$deposits = Deposit_log::find()->where('member_id ='.$_GET['id'])->asArray()->all();
		$member = Members::find()->where('id ='.$_GET['id'])->asArray()->one();
		return $this->render('deposit_list', [
					'member' => $member,
					'deposits' => $deposits,
				]);
	}
    //会员套餐
    public function actionPackage(){
        $member_id = $_GET['member_id'];
        $packages = Package_member::find()->where(['member_id' => $member_id])->asArray()->all();
        foreach($packages as $key=>$package):
            $packages[$key]['package'] = Package::find()->where(['id' => $package['package_id']])->asArray()->one();
            $packages[$key]['goods'] = Package_goods::find()->where(['package_id' => $package['package_id']])->asArray()->all();
        endforeach;
        return $this->render('packages',[
            'packages' => $packages,
        ]);
    }
    //会员随机推荐码
    function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;

        for($i=0;$i<$length;$i++){
            $str.=$strPol[rand(0,$max)];//rand($min,$max)生成介于min和max两个数之间的一个随机整数
        }
        $member = Members::find()->where(['rec_numbers' => $str])->count();
        if($member != 0){
            $this->getRandChar($length);
        }else{
            return $str;
        }

    }

}
