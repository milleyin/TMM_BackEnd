<?php

class Tmm_errorController extends MainController
{
	public function actionIndex()
	{
		if(!! $error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('index', $error);
		}
	}
}