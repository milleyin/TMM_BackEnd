<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <title>奖品</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets() . '/css/prize.css'?>">
</head>

<body>
<div class="prize">
    <p class="title"><?php echo CHtml::encode($model->prize_name); ?></p>
    <img src="<?php echo $model->Record_Upload->getUrlPath();  ?>" class="codeImg">
    <div class="code">
        兑奖码：<?php echo $model->code; ?>
    </div>
    <?php
        if ( $model->exchange_status == \Record::RECORD_EXCHANGE_STATUS_NO)
        {
    ?>
    <a href="<?php echo $this->createAbsoluteUrl('express/create', array('id'=>$model->id)); ?>" class="addr">填写邮寄信息</a>
    <?php 
        }
        else if ($model->exchange_status == \Record::RECORD_EXCHANGE_STATUS_ING) 
        {
    ?>
        <span class="addred">已填写邮寄信息</span>
    <?php 
        }
    ?>
    <p class="prize-title">奖品概述</p>
    <p class="txt">
        <?php echo str_replace(array("\r\n","\n","\r"), '<br/>', $model->prize_info); ?>
    </p>
</div>
</body>

</html>