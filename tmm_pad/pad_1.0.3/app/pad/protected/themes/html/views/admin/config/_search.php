
<?php
/* @var $this ConfigController */
/* @var $model Config */
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
        <?php echo $form->label($model,'store_id'); ?>
        <?php echo $form->textField($model, 'store_id', array('size'=>20, 'maxlength'=>20)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'pad_id'); ?>
        <?php echo $form->textField($model, 'pad_id', array('size'=>20, 'maxlength'=>20)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'manager_id'); ?>
        <?php echo $form->textField($model, 'manager_id', array('size'=>20, 'maxlength'=>20)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'type'); ?>
        <?php echo $form->dropDownList($model, 'type', array(''=>'--请选择--')+$model::$_type); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'chance_number'); ?>
        <?php echo $form->textField($model, 'chance_number', array('size'=>11, 'maxlength'=>11)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'number'); ?>
        <?php echo $form->textField($model, 'number', array('size'=>11, 'maxlength'=>11)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'money'); ?>
        <?php echo $form->textField($model, 'money', array('size'=>20, 'maxlength'=>20)); ?>
    </div>
 
    <div class="row">
        <?php echo $form->label($model,'info'); ?>
        <?php echo $form->textField($model, 'info', array('size'=>60, 'maxlength'=>128)); ?>
    </div>
    <!-- 
    <div class="row">
        <?php echo $form->label($model, 'ad_url'); ?>
        <?php echo $form->textField($model, 'ad_url', array('size'=>60, 'maxlength'=>128)); ?>
    </div>
    -->
    <div class="row">
        <?php echo $form->label($model, 'up_time'); ?>
        <?php echo $model->timeSearchInput($model, 'up_time'); ?>
    </div>

    <div class="row">
        <?php echo $form->label($model, 'add_time'); ?>
        <?php echo $model->timeSearchInput($model, 'add_time'); ?>
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