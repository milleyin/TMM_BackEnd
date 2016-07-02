<?php

class ErrorController extends ApiController
{

	public function actionIndex()
	{
		if(!! $error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->params['api_error_json'])
				$this->send_error_system($error['message']);
			else
			{
				if(Yii::app()->request->isAjaxRequest)
					echo $error['message'];
				else
					$this->renderPartial('index', $error);
			}
		}
	}
}