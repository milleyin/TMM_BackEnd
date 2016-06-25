<?php
/**
 * 错误控制器
 * @author Changhai Zhan
 *
 */
class ErrorController extends OperatorMainController
{
	/**
	 * 错误页面
	 */
	public function actionIndex()
	{
		if(!! $error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('index', $error);
		}else 
			$this->redirect(Yii::app()->homeUrl);
	}
}