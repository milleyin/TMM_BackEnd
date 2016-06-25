<?php
$this->breadcrumbs=array(
		'供应商账号管理页'=>array('admin'),
		$model->phone=>array('view','id'=>$model->id),
		'更新供应商账号属性标签信息',
);
?>
<h1>更新供应商账号属性标签信息<font color='#eb6100'><?php echo CHtml::encode($model->phone); ?></font></h1>
<div id="tree">
	<div ><span><a id="js_buttons" href="#">展开全部</a></span></div>
		<?php 
			echo CHtml::beginForm(Yii::app()->createUrl('/admin/tmm_user/sort',array('id'=>$model->id)),'post');
		?>
		<ul class="tree">		
		<?php 
			echo $html;
		?>
		</ul>
	<div class="row">
		<span class="buttons">
		<?php 
			echo CHtml::submitButton('更新排序');
		?>
		</span>
	</div>
	<?php 
		echo CHtml::endForm();
	?>
</div>
<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($){          
	$tree=$('.tree').checkTree({});
	$('#js_buttons').click(function(){
		$tree.find("li").find("ul").toggle('slow');
	//	$(this).html('');
	});
	$('li .name').change(function(){
 		if(!$(this).attr('name').match(/^namename\[[\d]\]$/))
			$(this).attr('name','name'+$(this).attr('name'));
	});
});
/*]]>*/
</script>
