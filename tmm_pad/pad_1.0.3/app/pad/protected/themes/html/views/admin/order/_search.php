<?php
/* @var $this OrderController */
/* @var $model Order */
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
        <?php echo $form->textField($model, 'id', array('size'=>20, 'maxlength'=>20)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'p_id'); ?>
        <?php echo $form->textField($model, 'p_id', array('size'=>20, 'maxlength'=>20)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'type'); ?>
        <?php echo $form->dropDownList($model, 'type', array(''=>'--请选择--')+$model::$_type); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'order_no'); ?>
        <?php echo $form->textField($model, 'order_no', array('size'=>60, 'maxlength'=>256)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'role_id'); ?>
        <?php echo $form->textField($model, 'role_id', array('size'=>20, 'maxlength'=>20)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'money'); ?>
        <?php echo $form->textField($model, 'money', array('size'=>20, 'maxlength'=>20)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'trade_money'); ?>
        <?php echo $form->textField($model, 'trade_money', array('size'=>20, 'maxlength'=>20)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'trade_no'); ?>
        <?php echo $form->textField($model, 'trade_no', array('size'=>60, 'maxlength'=>256)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'trade_id'); ?>
        <?php echo $form->textField($model, 'trade_id', array('size'=>60, 'maxlength'=>256)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'trade_name'); ?>
        <?php echo $form->textField($model, 'trade_name', array('size'=>60, 'maxlength'=>256)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'trade_type'); ?>
        <?php echo $form->dropDownList($model, 'trade_type', array(''=>'--请选择--')+$model::$_trade_type); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'trade_time'); ?>
        <?php echo $model->timeSearchInput($model, 'trade_time'); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'manager_id'); ?>
        <?php echo $form->textField($model, 'manager_id', array('size'=>20, 'maxlength'=>20)); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'up_time'); ?>
        <?php echo $model->timeSearchInput($model, 'up_time'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'add_time'); ?>
        <?php echo $model->timeSearchInput($model, 'add_time'); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'pay_status'); ?>
        <?php echo $form->dropDownList($model, 'pay_status', array(''=>'--请选择--')+$model::$_pay_status); ?>
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