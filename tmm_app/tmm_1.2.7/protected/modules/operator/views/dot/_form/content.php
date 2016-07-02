<?php
		$this->renderPartial('/_common/_html',array(
			'form'=>$form,
			'width'=>'100%',
			'height'=>140,
			'model'=>$model->Dot_Shops,
			'filespath'=>Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_items_editor_eat'],
			'filesurl'=>Yii::app ()->baseUrl . Yii::app()->params['uploads_items_editor_eat'],
			'attribute'=>'cost_info',
		));
?>
<?php
		$this->renderPartial('/_common/_html',array(
			'form'=>$form,
			'width'=>'100%',
			'height'=>140,
			'model'=>$model->Dot_Shops,
			'filespath'=>Yii::app ()->basePath .'/..'.Yii::app()->params['uploads_items_editor_eat'],
			'filesurl'=>Yii::app ()->baseUrl . Yii::app()->params['uploads_items_editor_eat'],
			'attribute'=>'book_info',
		));
?>