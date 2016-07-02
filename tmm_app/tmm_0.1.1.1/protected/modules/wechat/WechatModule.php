<?php

class WechatModule extends CWebModule
{
	public $ipFilters=array('127.0.0.1','::1');

	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		Yii::app()->homeUrl=array('/'.$this->getId());
		// import the module-level models and components
		$this->setImport(array(
			'wechat.models.*',
			'wechat.components.*',
		));
		Yii::app()->setComponents(array(
			'errorHandler'=>array(
				'class'=>'CErrorHandler',
				'errorAction'=>$this->getId().'/wx_error',
			),
			'wechat'=>array(
				'class'=>'CWebUser',
				'stateKeyPrefix'=>'wechat',//后台session前缀
				'loginUrl'=>array($this->getId().'/wx_login'),
				'loginRequiredAjaxResponse'=>'WECHAT_LOGIN_REQUIRED',
				'allowAutoLogin'=>true,								//利用Cooike 自动登录
				'autoRenewCookie'=>true,							//自动登录的时候刷新Cooike的时间
				'identityCookie'=>array('httpOnly'=>true),//防止JS修改Cookie
			),
			'cookie'=>array(
				'class'=>'HttpCookie',
				'prefix'=>$this->getId(),
				'options'=>array(
					'httpOnly'=>true,//防止JS修改Cookie
				),
			),
			'session' => array(
				'class'=>'CHttpSession',
				'timeout' =>1440,
				//'useTransparentSessionID'=>false,//禁止SessionID通过URL方式明文传输 默认
				'cookieParams'=>array('httponly'=>true),//防止JS修改Cookie secure
				'cookieMode'=>'only',//保存SessionID 仅仅用Cookie的方式保存
			),
		),false);
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			$route=$controller->id.'/'.$action->id;
			if(!$this->allowIp(Yii::app()->request->userHostAddress) && $route!=='wx_error/index')
				throw new CHttpException(403,"你没有权限访问系统后台。有问题请联系管理员！！");
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
