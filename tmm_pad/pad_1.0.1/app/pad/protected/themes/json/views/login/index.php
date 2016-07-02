<?php 
    $form = array(
         'hidden'=>array(Yii::app()->request->csrfTokenName=>Yii::app()->request->csrfToken),
         'attributes' => array(
                CHtml::modelName($model) =>$model->getAttributes($model->safeAttributeNames),
          ),
         'error'=>array(
                CHtml::modelName($model) =>$model->getErrors(),
          ),
    );
	echo json_encode($form);