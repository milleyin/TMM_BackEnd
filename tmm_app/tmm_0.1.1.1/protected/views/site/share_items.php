

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>田觅觅</title>
    <style type="text/css">
        * {
            padding: 0;
            margin: 0;
        }
        body {
            font-family: "Microsoft YaHei";
        }

        .bg {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url(<?php echo Yii::app()->request->baseUrl.'/images/share/bh.png' ?>) no-repeat;
            background-size: 100% 100%;
            z-index: -1;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            text-align: center;
            width: 100%;
        }

        .wrap {
            width: 960px;
            text-align: center;
            margin: 100px auto;
        }

        .media {
            width: 612px;
            float: left;
        }
        .media img {
            width: 612px;
            height: 367px;
        }
        .media div {
            font-size: 24px;
            line-height: 48px;
            text-align: left;
        }

        .media .txt {
            font-size: 16px;
            line-height: 24px;
            color: #333333;
        }

        .weixing {
            float: right;
        }
        .weixing .title {
            line-height: 48px;
            font-size: 18px;
        }
        .weixing .sub-title {
            line-height: 36px;
            font-size: 16px;
            margin-bottom: 24px;
        }
        .weixing .txt {
            font-size: 14px;
            color: #666666;
        }
        .weixing img {
            width: 200px;
            margin-bottom: 30px;
        }

        .footer p {
            font-size: 12px;
            color: #666666;
            line-height: 24px;
        }
    </style>
</head>

<body>
<div class="wrap">
<?php
    if(isset($model) && $model){
 ?>
    <div class="media">
        <img src="<?php echo  Yii::app()->params['app_api_domain'].'/'.$model->map; ?>">
        <div class="title"><?php echo $model->name; ?></div>
        <div class="txt"><?php echo $model->address; ?></div>
    </div>
<?php
    }else
    {
 ?>
        <div class="media">
            <img src="<?php echo Yii::app()->request->baseUrl.'/images/share/404.jpg' ?>">
            <div class="title">404</div>
            <div class="txt">Sorry，您所查看的项目不存在，可能已下架或者被转移</div>
        </div>
<?php
    }
?>
    <div class="weixing">
        <div class="title">田觅觅打造靠谱生态旅游</div>
        <div class="sub-title">实现人与自然的互联</div>
        <img src="<?php echo Yii::app()->request->baseUrl.'/images/share/app.png' ?>">
        <div class="txt">想要了解更多，请扫码下载APP</div>
    </div>
</div>

<div class="footer">
    <p>深圳市田觅觅信息科技有限公司</p>
    <p>电话：400-019-7090&nbsp;&nbsp;&nbsp;&nbsp;地址：深圳市南山区科丰路2号特发信息港B栋301室</p>
</div>
<div class="bg"></div>
</body>

</html>
