<script type="text/javascript">
$(function(){
		$(".operations li").click(function(){
			jQuery(this).addClass("active").siblings().removeClass("active");
		});	
})
</script>
<?php 
	$array=array();
	if(!empty($this->menu)){
		$i=0;
		foreach ($this->menu as $k=>$v){
			if($i==0)
				$array[$k]=$this->renderPartial('_left_menu',array(
					'menu'=>$v,
					'firse'=>true
				),true);
			else 
				$array[$k]=$this->renderPartial('_left_menu',array(
						'menu'=>$v,
				),true);			
			$i++;
		}
	}	
$this->widget('zii.widgets.jui.CJuiAccordion', array(
		'panels'=>$array,
		'htmlOptions'=>array(
				'style'=>'width:187px;height:auto;'
		),
		'options'=>array(
				'style'=>'height:auto;',
				'animated'=>'bounceslide',
				'collapsible'=>true,
				'active'=>0,
		),
));
?>


