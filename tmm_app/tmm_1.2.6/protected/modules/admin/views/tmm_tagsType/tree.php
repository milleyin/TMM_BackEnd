<?php
$this->breadcrumbs=array(
		'树形管理页',
		'管理页'=>array('admin'),
);
?>
<h1 style="width: 800px">分类管理页</h1>
<div id="tree">
	<div ><span><a id="js_buttons" href="#">展开全部</a></span> <span><?php echo CHtml::link('创建标签分类',array('create')); ?>	</span></div>
		<?php 
			echo CHtml::beginForm(Yii::app()->createUrl('/admin/tmm_tagsType/sort'),'post');
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
