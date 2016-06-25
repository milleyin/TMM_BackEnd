<?php

class ApiModule extends CWebModule
{
	public $ipFilters=array('127.0.0.1','::1');

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		Yii::app()->homeUrl=array('/'.$this->getId());
		// import the module-level models and components
		$this->setImport(array(
			'api.models.*',
			'api.components.*',
		));
		Yii::app()->setComponents(array(
			'errorHandler'=>array(
				'class'=>'CErrorHandler',
				'errorAction'=>$this->getId().'/error',
			),
			'api'=>array(
				'class'=>'ApiWebUser',
				'stateKeyPrefix'=>'member',								//后台session前缀
				'loginUrl'=>array($this->getId().'/login'),
				'loginRequiredAjaxResponse'=>'API_LOGIN_REQUIRED',
				'allowAutoLogin'=>true,									//利用Cooike 自动登录
				'autoRenewCookie'=>true,								//自动登录的时候刷新Cooike的时间
				'identityCookie'=>array('httpOnly'=>true),		//防止JS修改Cookie
			),
			'cookie'=>array(
					'class'=>'HttpCookie',
					'prefix'=>$this->getId(),
					'options'=>array(
						'httpOnly'=>true,										//防止JS修改Cookie
					),
			),
			'session' => array(
				'class'=>'CHttpSession',
				'timeout' =>1440,
				'cookieParams'=>array('httponly'=>true),		//防止JS修改Cookie secure
				'cookieMode'=>'only',										//保存SessionID 仅仅用Cookie的方式保存
			),
		),false);
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			$route=$controller->id.'/'.$action->id;
			if(!$this->allowIp(Yii::app()->request->userHostAddress) && $route!=='error/index')
				throw new CHttpException(403,"你没有权限访问系统后台。有问题请联系管理员！！");		
			$allow=array(
				'error/index',
				'shops/index',
				'shops/selected',
				'actives/view',
				'area/index',
				'area/counties',
				'area/set',
				'area/get',
				'area/gps',
				'dot/view',
				'eat/view',
				'farmOuter/share',
				'home/index',
				'hotel/view',
				'login/captcha',
				'login/index',
				'login/login_sms',
				'login/captcha_sms',
				'login/out',
				'play/view',
				'thrand/view',
				'callback/alipay',
				//报名短信
				'attend/captcha_sms',
				//创建报名
				'attend/create',
				//广告
				'ad/index'
			);
			if(!in_array($route,$allow))
			{
				if(Yii::app()->api->isGuest)
					Yii::app()->controller->send_error(API_LOGIN_REQUIRED,STATUS_FAIL,array('login'=>0,'route'=>$route));
				else
				{
					$model=User::model()->findByPk(Yii::app()->api->id);
					if($model)
					{
						if($model->status != User::status_suc)
						{
							Yii::app()->api->logout(false);
							Yii::app()->controller->send_error(API_LOGIN_REQUIRED,STATUS_FAIL,array(
								'login'=>0,
								'info' => '该用户已禁用.',
							));
						}
						elseif($model->login_unique != Yii::app()->api->getState(User::login_unique))
						{
							Yii::app()->api->logout(false);
							Yii::app()->controller->send_error(API_LOGIN_REQUIRED,STATUS_FAIL,array(
								'login'=>0,
								'info' => '该用户已在其他地方登录了，如果不是本人，请修改登录密码.',
							));
						}
						return true;
					}
					else 
					{
						Yii::app()->api->logout(false);
						Yii::app()->controller->send_error(API_LOGIN_REQUIRED,STATUS_FAIL,array(
							'login'=>0,
							'info' => '用户账号不存在.',
						));
					}
				}
			}
			else
				return true;

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
