<?php
/* @var $this Tmm_dotController */
/* @var $model Dot */
/* @var $form CActiveForm */
?>
<?php
/* @var $this Tmm_dotController */
/* @var $model Dot */

$this->breadcrumbs=array(
	'线路管理页'=>array('/admin/tmm_shops/admin'),
	'线路(点)管理页'=>array('admin'),
	$model->Dot_Shops->name=>array('view','id'=>$model->id),
	'线路(点)包装页',
);

?>
<h1>线路(点) 包装<font color='#eb6100'><?php echo CHtml::encode($model->Dot_Shops->name); ?></font></h1>
<div class="form wide">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'dot-pack-form',
	'enableAjaxValidation'=>true,
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">这些字段 <span class="required">*</span>是必须的.</p>

	<?php echo $form->errorSummary($model->Dot_Shops); ?>

	<div class="row value">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $model->id; ?>
	</div>
	<div class="row value">
		<?php echo $form->label($model->Dot_Shops,'name'); ?>
		<?php echo CHtml::encode($model->Dot_Shops->name); ?>
	</div>
	<div class="row value">
		<?php echo $form->label($model->Dot_Shops,'brow'); ?>
		<?php echo Chtml::encode($model->Dot_Shops->brow);?>
	</div>
	<div class="row value">
		<?php echo $form->label($model->Dot_Shops,'share'); ?>
		<?php echo Chtml::encode($model->Dot_Shops->share);?>
	</div>
	<div class="row value">
		<?php echo $form->label($model->Dot_Shops,'praise'); ?>
		<?php echo Chtml::encode($model->Dot_Shops->praise);?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model->Dot_Shops,'list_img'); ?>
		<?php echo $form->fileField($model->Dot_Shops,'list_img'); ?>
		<?php echo $form->error($model->Dot_Shops,'list_img'); ?>
	</div>
	<?php
		if($this->file_exists_uploads($model->Dot_Shops->list_img)){
			echo '<div class="row"><label>'.$model->Dot_Shops->getAttributeLabel('list_img').'</label>';
			echo 	$this->show_img($model->Dot_Shops->list_img);
			echo '</div>';
		}
	?>
	<div class="row">
		<?php echo $form->labelEx($model->Dot_Shops,'list_info'); ?>
		<?php echo $form->textArea($model->Dot_Shops,'list_info',array('style'=>'width:300px;height:80px;')); ?>
		<?php echo $form->error($model->Dot_Shops,'list_info'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model->Dot_Shops,'page_img'); ?>
		<?php echo $form->fileField($model->Dot_Shops,'page_img'); ?>
		<?php echo $form->error($model->Dot_Shops,'page_img'); ?>
	</div>
	<?php
		if($this->file_exists_uploads($model->Dot_Shops->page_img)){
			echo '<div class="row"><label>'.$model->Dot_Shops->getAttributeLabel('page_img').'</label>';
			echo 	$this->show_img($model->Dot_Shops->page_img);
			echo '</div>';
		}
	?>
	<div class="row">
		<?php echo $form->labelEx($model->Dot_Shops,'page_info'); ?>
		<?php echo $form->textArea($model->Dot_Shops,'page_info',array('style'=>'width:300px;height:80px;')); ?>
		<?php echo $form->error($model->Dot_Shops,'page_info'); ?>
	</div>

	<?php 
		if(isset($model->Dot_Pro))
			$this->renderPartial('_items', array(
						'model'=>$model,
						'form'=>$form,
			)); 
	?>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? '创建' : '保存'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
Yii::app()->clientScript->registerScript('pack_items', "

function group_eq(this_eq){
	return this_eq.parent().parent().index();
}
function group_html(eq){
	return jQuery('.items_container .view').eq(eq).html();
}
function swap_html(eq_f,html_f,eq_t,html_t){
	jQuery('.items_container .view').eq(eq_t).html(html_f);
	jQuery('.items_container .view').eq(eq_f).html(html_t);
}
function set_eq(eq_f,eq_t){
	jQuery('.items_container .view').eq(eq_t).find('.list_id').children().attr('name','Pro['+eq_f+'][id]');
	jQuery('.items_container .view').eq(eq_f).find('.list_id').children().attr('name','Pro['+eq_t+'][id]');	
	jQuery('.items_container .view').eq(eq_t).find('.list_right').children('textarea').attr('name','Pro['+eq_f+'][info]').attr('id','Pro_'+eq_f+'_info');
	jQuery('.items_container .view').eq(eq_f).find('.list_right').children('textarea').attr('name','Pro['+eq_t+'][info]').attr('id','Pro_'+eq_t+'_info');	
	jQuery('.items_container .view').eq(eq_t).find('.list_right').children('div').attr('id','Pro_'+eq_f+'_info_em_');
	jQuery('.items_container .view').eq(eq_f).find('.list_right').children('div').attr('id','Pro_'+eq_t+'_info_em_');		
}
function get_info(eq){	
	return jQuery('.items_container .view').eq(eq).find('.list_right').children('textarea').val();
}
function set_val(eq,eq_t){
	return jQuery('.items_container .view').eq(eq).find('.list_right').children('textarea').val(get_info(eq));
	return jQuery('.items_container .view').eq(eq_t).find('.list_right').children('textarea').val(get_info(eq_t));		
}
jQuery('body').on('click','.group_up',function(){
	var eq =group_eq(jQuery(this));
		
	if(eq==0){
		alert('已经是第一个了！');
		return;
	}
	set_val(eq,eq-1);	
	set_eq(eq,eq-1);
	swap_html(eq,group_html(eq),eq-1,group_html(eq-1));	
});
jQuery('body').on('click','.group_down',function(){
	var eq =group_eq(jQuery(this));
	if(eq==(jQuery('.items_container').children().length-1))
	{
		alert('已经是最后一个了！');
		return;
	}
	set_val(eq,eq+1);	
	set_eq(eq,eq+1);
	swap_html(eq,group_html(eq),eq+1,group_html(eq+1));
});
jQuery('body').on('click','.group_top',function(){
	var eq =group_eq(jQuery(this));
	if(eq==0){
		alert('已经是第一个了！');
		return;
	}
	set_val(eq,0);
	set_eq(eq,0);
	swap_html(eq,group_html(eq),0,group_html(0));	
});
");
?>
