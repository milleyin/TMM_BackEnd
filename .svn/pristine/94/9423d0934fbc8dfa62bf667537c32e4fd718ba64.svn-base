<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <title>觅镜抽奖</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets() . '/css/lottery-pay.css'; ?>">
</head>
</head>

<body>
<div class="payment-img"><img src="<?php echo $this->getAssets() . '/images/logo-pay.png';?>"/></div>
<div class="payment-amount">￥<?php echo $modelOrder->viewMoney($modelOrder->money); ?></div>
<div class="payment-info-list">
    <div class="info-item">
        <div class="item-title">收款方</div>
        <div class="item-con">田觅觅</div>
    </div>
    <div class="info-item">
        <div class="item-title">收款理由</div>
        <div class="item-con">支付完成后，即可获得抽奖机会</div>
    </div>
</div>

<div class="payment-info-button">
    <a class="message-sub" href="<?php echo Yii::app()->createUrl('/user/index/pay', array('id'=>$modelOrder->id));?>">立即支付</a>
</div>
</body>

</html>
