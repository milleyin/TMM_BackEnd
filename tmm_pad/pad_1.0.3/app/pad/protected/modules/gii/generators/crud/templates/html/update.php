<?php
/**
 * @author Changhai Zhan
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs = array(
    '管理页'=>array('admin'),
    \$model->{$nameColumn}=>array('view', 'id'=>\$model->{$this->tableSchema->primaryKey}),
    '更新页',
);\n";
?>
?>

<h1>更新页 <?php echo $this->modelClass."<font color='#eb6100'><?php echo CHtml::encode(\$model->{$this->tableSchema->primaryKey}); ?></font>"; ?></h1>

<?php echo "<?php \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>