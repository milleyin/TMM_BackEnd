<!DOCTYPE html>
<html lang="en">
<head>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
    <title>错误提示</title>
    <link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets() . '/css/mailingSuc.css'; ?>">
</head>
</head>
<body>
<div class="mailingSuc">
    <img style="width: 64px;height: 64px;" " src="<?php echo $this->getAssets() . '/images/error.png';?>" class="sucImg">
    <!-- 
        <p class="title">错误编码：<span style="color: red;"><?php echo $error['code']; ?></span></p>
     -->
    <p class="txt" style="margin: 0;"><?php echo CHtml::encode($error['message']); ?></p>
</div>
</body>
</html>