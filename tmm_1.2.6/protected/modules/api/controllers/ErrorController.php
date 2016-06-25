<?php

class ErrorController extends ApiController
{

	public function actionIndex()
	{
		if(!! $error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->params['api_error_json'])
				$this->send_error_system($error['message']);
			else{
				if(Yii::app()->request->isAjaxRequest)
					echo $error['message'];
				else
					$this->renderPartial('index', $error);
			}
		}

	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}