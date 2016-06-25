<?php

class AdminModule extends CWebModule
{
	public $ipFilters=array('127.0.0.1','::1');
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		Yii::app()->homeUrl=array('/'.$this->getId());
		// import the module-level models and components
		$this->setImport(array(
			'admin.models.*',
			'admin.components.*',
		));
		Yii::app()->setComponents(array(
				'errorHandler'=>array(
					'class'=>'CErrorHandler',
					'errorAction'=>$this->getId().'/tmm_error',
				),
				'admin'=>array(
					'class'=>'AdminWebUser',
					'stateKeyPrefix'=>'admin',								//后台session前缀
					'loginUrl'=>array($this->getId().'/tmm_login'),
					'loginRequiredAjaxResponse'=>'ADMIN_LOGIN_REQUIRED',
					//'allowAutoLogin'=>true,
					'authTimeout'=>1440,
					//'autoRenewCookie'=>true,								//自动登录的时候刷新Cooike的时间
					//'identityCookie'=>array('httpOnly'=>true),		//防止JS修改Cookie
				),
				'session' => array(
					'class'=>'CHttpSession',
					'timeout' =>1440,
					'cookieParams'=>array('httponly'=>true),	//防止JS修改Cookie
					'cookieMode'=>'only',									//保存SessionID 仅仅用Cookie的方式保存
				),
		),false);
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			$route=$controller->id.'/'.$action->id;
			if(!$this->allowIp(Yii::app()->request->userHostAddress) && $route!=='tmm_error/index')
				throw new CHttpException(403,"你没有权限访问系统后台。有问题请联系管理员！！");
			$publicPages=array(
					'tmm_login/index',
					'tmm_error/index',
					'tmm_login/captcha',
					'tmm_software/query',
					'tmm_software/download',
					'tmm_qrcode/user',
					'tmm_qrcode/store',
					'tmm_qrcode/pad',
					'tmm_hotel/uploads',
					'tmm_eat/uploads',
					'tmm_play/uploads',
			);
			if(Yii::app()->admin->isGuest && !in_array($route,$publicPages))
				Yii::app()->admin->loginRequired();
			elseif( !in_array($route,$publicPages) )
			{
				$model = Admin::model()->findByPk(Yii::app()->admin->id);
				if($model)
				{
					if($model->status != Admin::status_suc)
					{
						Yii::app()->admin->logout(false);
						Yii::app()->admin->setFlash(Admin::login_unique_info,'管理员已被禁用.');
						Yii::app()->admin->loginRequired();
					}
					elseif($model->login_unique != Yii::app()->admin->getState(Admin::login_unique))
					{
						Yii::app()->admin->logout(false);
						Yii::app()->admin->setFlash(Admin::login_unique_info,'已在其他地方登录了，如果不是本人，请修改登录密码.');
						Yii::app()->admin->loginRequired();
					}
					return true;
				}
				else
				{
					Yii::app()->admin->logout(false);
					Yii::app()->admin->setFlash(Admin::login_unique_info,'管理员不存在.');
					Yii::app()->admin->loginRequired();
				}
			}
			return true;
		}
		else
			return false;
	}
	
	protected function allowIp($ip)
	{
		if(empty($this->ipFilters))
			return true;
		foreach($this->ipFilters as $filter)
		{
			if($filter==='*' || $filter===$ip || (($pos=strpos($filter,'*'))!==false && !strncmp($ip,$filter,$pos)))
				return true;
		}
		return false;
	}
}
