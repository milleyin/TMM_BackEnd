<?php
namespace app\controllers;

use FrontController;

/**
 * 
 * @author Changhai Zhan
 *
 */
class ErrorController extends FrontController
{
	public function actions()
	{
		return array(
				'index'=>array(
						'class'=>'ext.actions.ErrorAction',
						'view'=>'index',
						'layout'=>'//layouts/jsonerror',
				),
		);
	}
}