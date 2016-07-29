<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
//        'brandLabel' => '生产流水线',
//        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
//        ['label' => '工单', 'url' => ['/orders']],
//        ['label' => '订单', 'url' => ['/goods_orders']],
//        ['label' => '会员', 'url' => ['/member']],
//        ['label' => '车辆', 'url' => ['/car']],
//        ['label' => '产品', 'url' => ['/goods']],
//        ['label' => '套餐', 'url' => ['/package']],
//        ['label' => '服务', 'url' => ['/service']],
//        ['label' => '车型', 'url' => ['/model']],
//        ['label' => '店铺', 'url' => ['/store']],
//        ['label' => '工人', 'url' => ['/worker']],
//        ['label' => '账号', 'url' => ['/user']],
//        ['label' => '统计', 'url' => ['/statistics']],
//        ['label' => '主页', 'url' => ['/interface']]
        ['label' => '主页', 'url' => ['/index']],
        ['label' => '组件', 'url' => ['/step']],
    ];
//    if (Yii::$app->user->isGuest) {
////        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
//        $menuItems[] = ['label' => '登录', 'url' => ['/site/login']];
//    } else {
//        $menuItems[] = [
//            'label' => '登出 (' . Yii::$app->user->identity->username . ')',
//            'url' => ['/site/logout'],
//            'linkOptions' => ['data-method' => 'post']
//        ];
//    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left"></p>

        <p class="pull-right">开发团队@Rium-Lin</p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
