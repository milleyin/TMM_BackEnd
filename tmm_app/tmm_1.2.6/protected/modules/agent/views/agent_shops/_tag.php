<?php 
	$select=TagsElement::checked_element($data->id,$select_id,constant('TagsElement::tags_shops_'.$admin));
?>
<div class="tag_img <?php echo $select?'selected':'optional';?>" style="cursor:pointer;" <?php echo $select?'title="已选择"':'title="点击添加"'?>>
	<?php 
		if($select){
	?>
    	<img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/tag_bg_already.png">
    <?php }else{?>
        <img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/tag_bg.png">
    <?php }?>
    <span class="name"><?php echo CHtml::encode($data->name);?></span>
    <span class="tags_id" style="display:none"><?php echo CHtml::encode($data->id);?></span>
</div>