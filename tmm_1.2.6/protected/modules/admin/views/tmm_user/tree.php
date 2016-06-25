<?php
$this->breadcrumbs=array(
		'用户管理页'=>array('admin'),
		$model->phone=>array('view','id'=>$model->id),
		'更新用户属性标签',
);
?>
<h1>更新用户属性标签<font color='#eb6100'><?php echo $model->phone,'[',CHtml::encode($model->nickname),']'; ?></font></h1>
<div class="form wide">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'user-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    <p class="note">请选择标签分类下的属性标签</p>

    <div class="row">
        <div id="tree" ></div>
    </div>
    <div class="row">
        <?php echo $form->hiddenField($model->User_TagsElement,'user_select_tags_type',array()); ?>
        <?php echo $form->error($model->User_TagsElement,'user_select_tags_type'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('保存'); ?>
    </div>
    <?php $this->endWidget(); ?>
 </div>
<script type="text/javascript">
/*<![CDATA[*/
jQuery(function($){
    $("#tree").dynatree({
        checkbox: true,
        selectMode: 3,
        children: <?php echo $json; ?>,
        fx: { height: "toggle", duration: 200 },
        onSelect: function(select, node) {
            var selKeys = $.map(node.tree.getSelectedNodes(), function(node){
                return node.data.key;
            });
            $('#<?php echo CHtml::activeId($model->User_TagsElement,'user_select_tags_type')?>').val(selKeys);
        },
    });
});
/*]]>*/
</script>
