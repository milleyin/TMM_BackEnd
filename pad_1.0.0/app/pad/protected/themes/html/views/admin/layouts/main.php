<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content = "IE=edge,chrome=1" />
	<meta name="robots"  content="none">
	<meta http-equiv="Content-Type" content="text/html; charset=<?php echo Yii::app()->charset?>">
	<meta name="language" content="<?php echo Yii::app()->language?>">	
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets(); ?>/css/screen.css" media="screen, projection">
	<link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets(); ?>/css/print.css" media="print">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets(); ?>/css/ie.css" media="screen, projection">
	<![endif]-->
	<link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets(); ?>/css/main.css">
	<link rel="stylesheet" type="text/css" href="<?php echo $this->getAssets(); ?>/css/form.css">
	<title><?php echo CHtml::encode($this->title); ?></title>
</head>
<body>
	<div class="container" id="page">
		<div id="header">
			<div id="logo">
				<?php
					echo CHtml::link(CHtml::image($this->getAssets() . '/images/logo.png', '加载中……', array('title'=>$this->name)), array('/admin'));
				?>
				<span>
					<?php echo CHtml::encode($this->name); ?>
				</span>
			</div>
		</div>
		<!-- header -->		
		<?php echo $content; ?>
		<!-- content -->
		<div class="clear"></div>
		<div id="footer">
			Copyright &copy; <?php echo date('Y'); ?> by My Company. All Rights Reserved. Powered by 
			<strong>
				<?php echo CHtml::link(Helper::getPowered(), 'http://www.365tmm.com/', array('target'=>'_blank')) ?>
			</strong>
		</div>
		<!-- footer -->
	</div>
	<!-- page -->
</body>
</html>
