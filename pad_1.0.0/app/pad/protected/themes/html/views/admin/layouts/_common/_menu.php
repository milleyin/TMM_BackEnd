<div class="logo" style="float:left;">
	<?php 
		echo CHtml::link(CHtml::image($this->getAssets() . '/images/logo.png', '加载中……', array('title'=>$this->name)), Yii::app()->homeUrl) 
	?>
</div>

<div id="mainmenu">
		<?php
// 				$this->widget('zii.widgets.CMenu', array(
// 					'items'=>array(
// 							array(
// 									'label'=>'首页',
// 									'url'=>array('/admin'),
// 									'linkOptions'=>array(
// 											'title'=>'首页',
// 									)
// 							),
// 					),
// 					'firstItemCssClass'=>'active',
// 					'activeCssClass'=>'active',
// 				));
		?>
</div>

<div id="out">
	<span>欢迎,</span>
	<span><?php echo CHtml::link(Yii::app()->user->name, Yii::app()->homeUrl, array('title'=>'我的信息'))?></span>
	<span><?php echo CHtml::link('退出系统', CHtml::normalizeUrl(array('login/out')), array('注销账户'));?></span>
</div>
