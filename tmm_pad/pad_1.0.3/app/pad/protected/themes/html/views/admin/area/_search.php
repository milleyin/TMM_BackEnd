<?php
/* @var $this AreaController */
/* @var $model Area */
/* @var $form CActiveForm */
?>
<p>
你可以输入比较运算符,这是可选的 (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) 开始你的每一个搜索的值来指定应该如何做比较。
</p>
<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
    'action'=>Yii::app()->createUrl($this->route),
    'method'=>'get',
)); ?>
 
    <div class="row">
        <?php echo $form->label($model,'id'); ?>
        <?php echo $form->textField($model, 'id', array('size'=>11, 'maxlength'=>11)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'name'); ?>
        <?php echo $form->textField($model, 'name', array('size'=>32, 'maxlength'=>32)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'spell'); ?>
        <?php echo $form->textField($model, 'spell', array('size'=>60, 'maxlength'=>64)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'pid'); ?>
        <?php echo $form->textField($model, 'pid', array('size'=>11, 'maxlength'=>11)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'sort'); ?>
        <?php echo $form->textField($model, 'sort', array('size'=>11, 'maxlength'=>11)); ?>
    </div>

    <div class="row">
            <?php echo $form->label($model, 'status'); ?>
            <?php echo $form->dropDownList($model, 'status', array(''=>'--请选择--')+$model::$_status); ?>
    </div>
 
    <div class="row buttons">
        <?php echo CHtml::submitButton('搜索'); ?>
    </div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->