<?php
/* @var $this Tmm_dotController */
/* @var $model Dot */
/* @var $form CActiveForm */
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/subjoin/subjoin.css" >
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/subjoin/subjoin.js"></script>

<div style="min-width:1400px;">
	<!--项目 描述切换-->
	<div class="info_nav_float">
		<div class="info_nav_float_left">添加项目</div>
		<div class="info_nav_float_right">添加描述</div>
	</div>
	<div class="info_nav_float_clear"></div>
	<div class="info_nav_thrand"></div>

	<!--添加项目-->
	<div class="create_items">
		<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'dot-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<?php 
		if(! isset($model->Dot_Pro[0]))	
			$model->Dot_Pro=$this->new_modes('Pro', 'create_dot');
	?>
	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model->Dot_Shops); ?>

	<div class="row">
		<?php echo $form->labelEx($model->Dot_Shops,'name'); ?>
		<?php echo $form->textField($model->Dot_Shops,'name',array('size'=>24,'maxlength'=>24)); ?>
		<?php echo $form->error($model->Dot_Shops,'name'); ?>	
	</div>

	<div id="group_data">
	<?php 
		if(isset($model->Dot_Pro) && is_array($model->Dot_Pro) && isset($model->Dot_Pro[0]->items_id)){
			foreach ($model->Dot_Pro as $Dot_Pro){
	?>
			<div class="group">
			<?php echo $form->HiddenField($Dot_Pro, "[]items_id")?>
				<hr class="hr"  />
				<div class="group_left" >
					<div class="group_title">
						<span class="group_name"><?php echo CHtml::encode($Dot_Pro->Pro_Items->name); ?></span>
						<span class="group_classliy"  title="<?php echo CHtml::encode($Dot_Pro->Pro_ItemsClassliy->info); ?>"><?php echo CHtml::encode($Dot_Pro->Pro_ItemsClassliy->name); ?></span>
					</div>
					<div class="group_address">
						<span>地址:</span>
							<?php 
							 echo CHtml::encode(
											$Dot_Pro->Pro_Items->Items_area_id_p_Area_id->name.
											$Dot_Pro->Pro_Items->Items_area_id_m_Area_id->name.
											$Dot_Pro->Pro_Items->Items_area_id_c_Area_id->name.
											$Dot_Pro->Pro_Items->address
								);
							?>
					</div>
				</div>
				<div class="group_img" >
				<?php 
						$img='';
						if(isset($Dot_Pro->Pro_Items->Items_ItemsImg[0]))
							$img=$Dot_Pro->Pro_Items->Items_ItemsImg[0]->img;
						echo $this->show_img($img);
				?>
				</div>
				<div style="clear: both;"></div>
				<div class="group_button">
						<?php echo CHtml::button('上移',array('class'=>'group_up'));?>
						<?php echo CHtml::button('下移',array('class'=>'group_down'));?>
						<?php echo CHtml::button('移除',array('class'=>'group_remove'));?>
				</div>
			</div>
		
	<?php 
			}
		}	
	?>
	</div>
	<div style="clear: both;"></div>
	<div class="row">
		<?php echo $form->hiddenField($model,'select_items'); ?>
		<?php echo $form->error($model,'select_items'); ?>	
		<?php echo $form->error($model->Dot_Pro[0],'items_id');  ?>
	</div>
	<div class="row">
		<?php echo $form->hiddenField($model->Dot_Shops,'cost_info'); ?>
		<?php echo $form->error($model->Dot_Shops,'cost_info'); ?>
	</div>
	<div class="row">
		<?php echo $form->hiddenField($model->Dot_Shops,'book_info'); ?>
		<?php echo $form->error($model->Dot_Shops,'book_info'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>
<?php $this->endWidget(); ?>
</div><!-- form -->

<?php 
$html=<<<"END"
'<div class="group"><input type="hidden" value="'+id+'" name="Pro[][items_id]" ><hr class="hr"><div class="group_left"><div class="group_title"><span class="group_name">'+group_name+'</span><span class="group_classliy" title="'+group_classliy_title+'">'+group_classliy+'</span></div><div class="group_address">'+group_address+'</div></div><div class="group_img">'+group_img_html+'</div><div style="clear: both;"></div><div class="group_button"><input class="group_up" name="" type="button" value="上移"><input class="group_down" type="button" value="下移"><input class="group_remove" name="" type="button" value="移除"></div></div>'
END;
Yii::app()->clientScript->registerScript('search', "
$('.items_search form').submit(function(){
		 $.fn.yiiListView.update(
			'items_list',{'data':\$(this).serialize()}
		);
	return false;
});
jQuery('body').on('click','.select_items',function(){
	var id= jQuery(this).parent().parent().parent().find('.list_id input').val();
	var data=new Array();
	jQuery('.form input[name=\'Pro[][items_id]\']').each(function (){
			data.push($(this).val());
	});
	if(jQuery.inArray(id, data) != -1){
		alert('该项目已添加！');
		jQuery(this).attr('disabled',true);
		$(this).css('cursor','not-allowed');
		return;
	}	
	var eq = jQuery(this).parent().parent().find('.list_eq').html();
	var this_view=jQuery('#items_list .items').find('.view').eq(eq);
	var group_name=this_view.find('.list_name').html();
	var group_classliy_title=this_view.find('.list_classliy').attr('title');
	var group_classliy=this_view.find('.list_classliy').html();
	var group_address=this_view.find('.list_address').html();
	var group_img_html=this_view.find('.list_img').html();
	jQuery('#group_data').append($html);	
	jQuery(this).attr('disabled',true);
	$(this).css('cursor','not-allowed');
	return false;
});
function group_eq(this_eq){
	return this_eq.parent().parent().index();
}
function group_remove(eq){
	jQuery('#group_data .group').eq(eq).remove();
}
function group_html(eq){
	return jQuery('#group_data .group').eq(eq).html();
}
function swap_html(eq_f,html_f,eq_t,html_t){		
	jQuery('#group_data .group').eq(eq_t).html(html_f);
	jQuery('#group_data .group').eq(eq_f).html(html_t);
}
jQuery('body').on('click','.group_up',function(){
	var eq =group_eq(jQuery(this));	
	if(eq==0){
		alert('已经是第一个了！');
		return;	
	}
	swap_html(eq,group_html(eq),eq-1,group_html(eq-1));
});
jQuery('body').on('click','.group_down',function(){
	var eq =group_eq(jQuery(this));	
	if(eq==(jQuery('#group_data').children().length-1))
	{
		alert('已经是最后一个了！');
		return;	
	}
	swap_html(eq,group_html(eq),eq+1,group_html(eq+1));
});
jQuery('body').on('click','.group_remove',function(){
		var eq =group_eq(jQuery(this));		
		var id=jQuery(this).parent().parent().find('input[name=\'Pro[][items_id]\']').val();
		var data=new Array();
		jQuery('.items input[name=\'Pro[][items_id]\']').each(function (){
				data.push($(this).val());
		});
		if(jQuery.inArray(id, data) != -1){
			jQuery('.items .view').eq(jQuery.inArray(id, data)).find('.list_button_add input').removeAttr('disabled').removeAttr('style');
		}
		jQuery(this).parent().parent().remove();
});
");
?>
		<div class="items_list" >
	<div class="items_search"  style="width:auto">
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
	</div>
	<!--添加描述-->
	<div class="create_descirbe">
		<?php

		$this->renderPartial('/_common/_html',array(
			'form'=>$form,
			'width'=>'98%',
			'height'=>280,
			'model'=>$model->Dot_Shops,
			'filespath'=>Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_items_editor_eat'],
			'filesurl'=>Yii::app ()->baseUrl . Yii::app()->params['uploads_items_editor_eat'],
			'attribute'=>'cost_info',
		));
		?>
		<?php
		$this->renderPartial('/_common/_html',array(
			'form'=>$form,
			'width'=>'98%',
			'height'=>280,
			'model'=>$model->Dot_Shops,
			'filespath'=>Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_items_editor_eat'],
			'filesurl'=>Yii::app ()->baseUrl . Yii::app()->params['uploads_items_editor_eat'],
			'attribute'=>'book_info',
		));
		?>
	</div>

</div>
<?php	
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
			'id'=>'view_items',//弹窗ID
			'options'=>array(//传递给JUI插件的参数
					'title'=>'查看项目',
					'autoOpen'=>false,//是否自动打开
					'width'=>'1300px',//宽度
					'height'=>'auto',//高度
					'modal' => true,
					'buttons'=>array(
						//	'关闭'=>'js:function(){$(this).dialog("close");}',//关闭按钮
					),
			),
	));
	$this->endWidget();
?>