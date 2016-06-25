<?php
/* @var $this Tmm_organizerController */
/* @var $model Organizer */

$this->breadcrumbs=array(
    '运营商管理页'=>array('admin'),
    $model->phone=>array('view','id'=>$model->id),
    '设置区域权限',
);
?>
<h1>设置运营商的区域权限<font color='#eb6100'><?php echo $model->phone; ?></font></h1>
<div class="form wide">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'agent-form',
        'enableAjaxValidation'=>true,
        'enableClientValidation'=>true,
        'clientOptions'=>array(
            'validateOnSubmit'=>true,
        ),
    )); ?>
    <p class="note">请选择你需要的区域</p>

    <div class="row">
        <div id="tree" style="height: 600px;overflow: auto;"></div>
    </div>
    <div class="row">
        <?php echo $form->hiddenField($model,'area_select',array()); ?>
        <?php echo $form->error($model,'area_select'); ?>
    </div>
    <div class="row buttons">
        <?php echo CHtml::submitButton('保存'); ?>
    </div>

    <?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">
    /*<![CDATA[*/
    jQuery(function($){
        var treeData = <?php echo $html; ?>;

        $("#tree").dynatree({
            checkbox: true,
            selectMode: 3,
            children: treeData,
            fx: { height: "toggle", duration: 200 },
            onSelect: function(select, node) {

                var selKeys = $.map(node.tree.getSelectedNodes(), function(node){

                    if (node.data.children === null) {
                        return node.data.key;
                    }

                });
                $('#Agent_area_select').val(selKeys);
            },
        });


    });
    /*]]>*/
</script>
