<?php
/**
 * Created by PhpStorm.
 * User: lijingrun
 * Date: 2016/2/8
 * Time: 17:14
 */
namespace frontend\controllers;
use common\models\Car_type;
use common\models\Goods;
use common\models\Goods_type;
use common\models\Members;
use common\models\Package;
use common\models\Package_goods;
use common\models\Package_log;
use common\models\Package_member;
use common\models\Service;
use common\models\Service_check_type;
use common\models\Service_price;
use common\models\ServiceForm;
use common\models\Service_goods;
use common\models\Weixin;
use common\models\Weixin_template;
use Yii;
use common\models\Service_type;
use yii\data\Pagination;
use yii\web\Controller;

class PackageController extends Controller{

    public function beforeAction($action)
    {
        if(Yii::$app->session['user_role']['orders'] != 'on'){
            Yii::$app->getSession()->setFlash('error','你没有权限访问！');
            return $this->goHome();
        }else{
            return $action;
        }
    }
    public function actionIndex(){
        $packages = Package::find()->asArray()->all();
        foreach($packages as $key=>$package):
            $packages[$key]['goods'] = Package_goods::find()->where(['package_id' => $package['id']])->asArray()->all();
        endforeach;
        return $this->render('package_list',[
            'packages' => $packages,
        ]);
    }

    public $enableCsrfValidation = false;
    //添加
    public function actionAdd(){
        $id = $_GET['id'];
        if(!empty($id)){
            $package = Package::find()->where(['id' => $id])->asArray()->one();
        }
        $services = Service::find()->asArray()->all();
        if(Yii::$app->request->post()){
            if(!empty($id)){
                $package = Package::find()->where(['id' => $id])->one();
            }else{
                $package = new Package();
            }
            $status = $_POST['status'];
            if(empty($status)){
                $status = 0;
            }
            $package->name = $_POST['name'];
            $package->status = $status;
            $package->price = $_POST['price'];
            $package->service_id = $_POST['service_id'];
            if($package->save()){
                Yii::$app->getSession()->setFlash("success","保存成功!");
                return $this->redirect("index.php?r=package/goods_list&package_id=".$package['id']);
            }else{
                Yii::$app->getSession()->setFlash('error','服务器繁忙，请稍后重试！');
                return $this->redirect("index.php?r=package/add&id=".$id);
            }
        }else{

            return $this->render("add",[
                'package' => $package,
                'services' => $services,
            ]);
        }
    }

    //套餐产品列表
    public function actionGoods_list(){
        $id = $_GET['package_id'];
        $package = Package::find()->where(['id' => $id])->asArray()->one();
        $goods_types = Goods_type::find()->asArray()->all();
        $goods = Package_goods::find()->where(['package_id' => $id])->asArray()->all();
        if(Yii::$app->request->post()){
            $goods_id = $_POST['goods_id'];
            $the_goods = Goods::find()->where(['goods_id' => $goods_id])->asArray()->one();
            $nums = $_POST['nums'];
            $p_goods = new Package_goods();
            $p_goods->package_id = $id;
            $p_goods->goods_id = $goods_id;
            $p_goods->goods_name = $the_goods['goods_name'].$the_goods['style']."(".$the_goods['spec'].")";
            $p_goods->nums = $nums;
            if($p_goods->save()){
                Yii::$app->getSession()->setFlash("success",'添加成功！');
            }else{
                Yii::$app->getSession()->setFlash("error",'服务器繁忙，稍后重试！');
            }
            return $this->redirect('index.php?r=package/goods_list&package_id='.$id);
        }else{
            return $this->render('goods_list',[
                'goods_types' => $goods_types,
                'package' => $package,
                'goods' => $goods,
            ]);
        }

    }

    public function actionDel_goods(){
        if(Yii::$app->request->post()){
            $id = $_POST['id'];
            if(Package_goods::deleteAll(['id' => $id])){
                echo 111;
            }else{
                echo 222;
            }
            exit;
        }
    }

    public function actionFind_goods(){
        if(Yii::$app->request->post()){
            $id = $_POST['type_id'];
            $goods = Goods::find()->where(['goods_type' => $id])->asArray()->all();
            if(empty($goods)){
                echo "该分类下面还未有产品";
                exit;
            }
            echo "<select name='goods_id'>";
            foreach($goods as $good):
                echo "<option value='".$good['goods_id']."' >".$good['goods_name'].$good['style']."(".$good['spec'].")"."</option>";
            endforeach;
            echo "</select>";
            echo "数量<input type='text' name='nums' syle='width:50px;'>";
            exit;
        }
    }

    public function actionTo_member(){
        $p_id = $_GET['package_id'];
        $package = Package::find()->where(['id' => $p_id])->asArray()->one();
        $p_member = Package_member::find()->where(['package_id' => $p_id])->andWhere("nums > 0")->asArray()->all();
        if(Yii::$app->request->post()){
            $nums = $_POST['nums'];
            if(empty($nums)){
                $nums = 1;
            }
            $member_id = $_POST['member_id'];
            if(!empty($member_id)) {
                $member = Members::find()->where(['id' => $member_id])->asArray()->one();
                $package_member = new Package_member();
                $package_member->package_id = $p_id;
                $package_member->nums = $nums;
                $package_member->member_id = $member['id'];
                $package_member->member_name = $member['user_name'];
                if($package_member->save()){
                    $package_log = new Package_log();
                    $package_log->create_time = time();
                    $package_log->package_id = $p_id;
                    $package_log->price = $package['price'];
                    $package_log->member_id = $member_id;
                    $package_log->user_id = Yii::$app->session['user_id'];
                    $package_log->package_name = $package['name'];
                    $package_log->store_id  = Yii::$app->session['store_id'];
                    $package_log->save();
                    if(!empty($member['open_id'])){
                        $appid = $member['weixin_id'];
                        $template = Weixin_template::find()->where(['type_id' => 10])->andWhere(['appid' => $appid])->asArray()->one();
                        $weixin = Weixin::find()->where(['appid' => $appid])->asArray()->one();
                        $data = array(
                            'first' => array('value' => '套餐已经成功到你账户',),
                            'keyword1' => array('value' => $member['user_name'],'topcolor'=> '#0F12D'),
                            'keyword2' => array('value' => $package['name'],'topcolor'=> '#0F12D'),
                            'keyword3' => array('value' => "￥".$package['price'],'topcolor'=> '#0F12D'),
                            'keyword4' => array('value' => date("Y-m-d H:i:s",time()),'topcolor'=> '#0F12D'),
                            'remark' => array('value' => '如有疑问，请及时咨询我们','topcolor'=> '#0F12D'),
                        );
                        $error_code = $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$weixin['access_token'],$data);
                        if($error_code != 0){
                            $access_token = $this->get_access_token($appid,$weixin['app_secret']);
                            if($access_token != false){
                                $this->_send_weixin_message($appid,$member['open_id'],$template['template'],$access_token,$data);
                            }
                        }
                    }
                    Yii::$app->getSession()->setFlash('success','发放成功！');
                }else{
                    Yii::$app->getSession()->setFlash('error','发放失败！');
                }
                return $this->redirect('index.php?r=package/to_member&package_id='.$p_id);
            }
        }else{
            return $this->render('to_member',[
                'package' => $package,
                'p_member' => $p_member,
            ]);
        }
    }

    public function actionFind_member(){
        $phone = $_POST['phone'];
        $member = Members::find()->where(['phone' => $phone])->asArray()->one();
        if(empty($member)){
            echo "没有该会员资料";
        }else{
            echo $member['user_name']."<input type='hidden' value='".$member['id']."' name='member_id' />";
            echo "<br />数量：<input type='text' value='1' name='nums' style='width:50px;'/><br />";
            echo "<input type='submit' value='确认'>";
        }
    }

}