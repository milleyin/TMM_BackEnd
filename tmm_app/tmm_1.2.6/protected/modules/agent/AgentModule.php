<?php

class AgentModule extends CWebModule
{
	public $ipFilters=array('127.0.0.1','::1');
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		Yii::app()->homeUrl=array('/'.$this->getId());
		// import the module-level models and components
		$this->setImport(array(
				'agent.models.*',
				'agent.components.*',
		));
		Yii::app()->setComponents(array(
			'errorHandler'=>array(
				'class'=>'CErrorHandler',
				'errorAction'=>$this->getId().'/agent_error',
			),
			'agent'=>array(
				'class'=>'CWebUser',
				'stateKeyPrefix'=>'agent',//后台session前缀
				'loginUrl'=>array($this->getId().'/agent_login'),
				'loginRequiredAjaxResponse'=>'AGENT_LOGIN_REQUIRED',
				//'allowAutoLogin'=>true,
				'authTimeout'=>1440,
			),
			'session' => array(
				'class'=>'CHttpSession',
				'cookieParams'=>array('httponly'=>true),//防止JS修改Cookie
				'cookieMode'=>'only',//保存SessionID 仅仅用Cookie的方式保存
				//'useTransparentSessionID'=>false,//禁止SessionID通过URL方式明文传输
				'timeout' =>1440,
			),
		),false);
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			$route=$controller->id.'/'.$action->id;
			if(!$this->allowIp(Yii::app()->request->userHostAddress) && $route!=='agent_error/index')
				throw new CHttpException(403,"你没有权限访问系统后台。有问题请联系管理员！！");
			$publicPages=array(
					'agent_login/index',
					'agent_error/index',
					'agent_login/captcha',
					'agent_login/captcha_sms',
			);
			if(Yii::app()->agent->isGuest && !in_array($route,$publicPages))
				Yii::app()->agent->loginRequired();
			else
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
