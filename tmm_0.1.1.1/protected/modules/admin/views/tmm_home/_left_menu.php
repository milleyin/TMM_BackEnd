<?php
	$this->beginWidget('zii.widgets.CPortlet', array());
	if(isset($firse) && $firse){
		$array=array(
			'items'=>$menu,
			'firstItemCssClass'=>'active',
			'htmlOptions'=>array('class'=>'operations'),
		);
	}else{
		$array=array(
				'items'=>$menu,
				'htmlOptions'=>array('class'=>'operations'),
		);
	}
	$this->widget('zii.widgets.CMenu', $array);
	$this->endWidget();
?>