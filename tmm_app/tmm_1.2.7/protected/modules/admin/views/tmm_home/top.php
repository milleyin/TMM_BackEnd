<div id="mainmenu">
		<?php 
			if(isset($this->navbar))
				$this->widget('zii.widgets.CMenu',array(
					'items'=>$this->navbar, //导航栏
				//	'firstItemCssClass'=>'active',
					'activeCssClass'=>'active',
				)); 
		?>
		<div id="out">
			<span>欢迎,</span>
			<span><?php echo CHtml::link(Yii::app()->admin->name,array('/admin/tmm_admin/own'),array('target'=>'admin_right','title'=>'我的信息'))?></span>
			<span><?php echo CHtml::link('退出',array('/admin/tmm_login/out'));?></span>
		</div>
</div>
