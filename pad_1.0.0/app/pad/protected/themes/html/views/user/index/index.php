<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <title>验证成功</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets() . '/css/mailingSuc.css'; ?>">
</head>
</head>

<body>
<div class="mailingSuc">
    <img src="<?php echo $this->getAssets() . '/images/receive.png';?>" class="sucImg">
    <p class="title">验证成功！</p>
    <p class="txt">今日剩余<?php echo CHtml::encode($model->number); ?>次抽奖机会</p>
</div>
</body>

</html>
