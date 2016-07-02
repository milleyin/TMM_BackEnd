<?php
/**
 * @author Changhai Zhan
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>

<div class="form wide">

<?php echo "<?php \$form=\$this->beginWidget('CActiveForm', array(
    'id'=>'".$this->class2id($this->modelClass)."-form',
    //'focus'=>array(\$model, 'columnName'),
    'enableAjaxValidation'=>false,
    'enableClientValidation'=>true,
    'clientOptions'=>array(
        'validateOnSubmit'=>true,
    ),
    //'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>\n"; ?>

    <p class="note">这些字段<span class="required">*</span>是必须的。</p>

    <?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>

<?php
foreach($this->tableSchema->columns as $column)
{
    if($column->autoIncrement || strpos($column->name, 'time') !== false || strpos($column->name, 'status') !== false || strpos($column->name, 'ip') !== false || strpos($column->name, 'count') !== false || strpos($column->name, 'id') !== false)
        continue;
?>
    <div class="row">
        <?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass, $column)."; ?>\n"; ?>
        <?php echo "<?php echo ".$this->generateActiveField($this->modelClass, $column)."; ?>\n"; ?>
        <?php echo "<?php echo \$form->error(\$model,'{$column->name}'); ?>\n"; ?>
    </div>

<?php
}
?>
    <div class="row buttons">
        <?php echo "<?php echo CHtml::submitButton(\$model->isNewRecord ? '创建' : '保存'); ?>\n"; ?>
    </div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->