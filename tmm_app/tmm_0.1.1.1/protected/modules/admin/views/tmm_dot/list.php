<?php 
Yii::app()->clientScript->registerScript('search', "
$('.items_search form').submit(function(){
		 $.fn.yiiListView.update(
			'items_list',{'data':\$(this).serialize()}
		);
	return false;
});
");
?>
<div class="items_list" >
	<div id="search"  class="items_search"  style="width:auto">
		<?php 
			$form=$this->beginWidget('CActiveForm', array(
				'action'=>Yii::app()->createUrl($this->route),
				'method'=>'get',
			)); 
		?>
		<div class="row">
			<span>
				<label>项目名称</label>
				<?php echo $form->textField($model_search,'name'); ?>		
			</span>
			<span>
				<label>供应商名称</label>
				<?php echo $form->textField($model_search->Items_StoreContent,'name'); ?>
			</span>
			<span class="buttons">
				<?php echo CHtml::submitButton('搜索'); ?>
			</span>
		</div>
		<div class="row">
			<label>地址</label>
			<span>
            <?php echo $form->dropDownList($model_search,'area_id_p',Area::data_array_name(),array(
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>Yii::app()->createUrl('/admin/tmm_home/area_name'),
                    'dataType'=>'json',
                    'data'=>array('area_id_p'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                    //	'update'=>'#'.CHtml::activeId($model,'area_id_m'),
                    'success'=>'function(data){
								jQuery("#'.CHtml::activeId($model_search,'area_id_m').'").html(data[0]);
								jQuery("#'.CHtml::activeId($model_search,'area_id_c').'").html(data[1]);
						}',
                ),
            	'style'=>'width:auto;'
            ));
            ?>
            <?php echo $form->dropDownList($model_search,'area_id_m',Area::data_array_name($model_search->area_id_p,true,false),array(
                'ajax'=>array(
                    'type'=>'POST',
                    'url'=>Yii::app()->createUrl('/admin/tmm_home/area_name'),
                    'data'=>array('area_id_m'=>'js:this.value',Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
                    'update'=>'#'.CHtml::activeId($model_search,'area_id_c'),
                ),
				'style'=>'width:auto;'
            )); ?>
       
            <?php echo $form->dropDownList($model_search,'area_id_c',Area::data_array_name($model_search->area_id_m,true,false),array('style'=>'width:auto;')); ?>
		 	 项目
		 	 <?php 
		 	 			echo $form->dropDownList($model_search,'agent_id',array(
										''=>'全部的',
		 	 							1=>'我创建的',
										2=>'别人创建的',
		 	 						),array('style'=>'width:auto;')); 
							?> 		 	
		 	</span>
		</div>
	<?php $this->endWidget(); ?>
	</div>
<?php 
$this->widget('zii.widgets.CListView', array(
	'id'=>'items_list',
	'dataProvider'=>$dataProvider,
	'itemView'=>'_list',
	'sortableAttributes'=>array(
			'id',
			'name',
			'c_id',
			'agent_id',
			'store_id',
			'manager_id',
			'down',
			'push',
			'add_time',
			'up_time',
	),
)); 
?>
</div>
<?php	
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
			'id'=>'view_items',//弹窗ID
			'options'=>array(//传递给JUI插件的参数
					'title'=>'查看项目',
					'autoOpen'=>false,//是否自动打开
					'width'=>'1000px',//宽度
					'height'=>'auto',//高度
					'buttons'=>array(
						//	'关闭'=>'js:function(){$(this).dialog("close");}',//关闭按钮
					),
			),
	));
	$this->endWidget();
?>