<?php
/**
 * 运营商 入口
 * @author Changhai Zhan
 *
 */
class OperatorModule extends CWebModule
{
	public $ipFilters = array('127.0.0.1','::1');
	
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application
		Yii::app()->homeUrl = array('/'.$this->getId().'/home/index');
		// import the module-level models and components
		$this->setImport(array(
			'operator.models.*',
			'operator.components.*',
		));
		Yii::app()->setComponents(array(
				'errorHandler'=>array(
					'class'=>'CErrorHandler',
					'errorAction'=>$this->getId().'/error',
				),
				'operator'=>array(
					'class'=>'OperatorWebUser',
					'stateKeyPrefix'=>'operator',								//后台session前缀
					'loginUrl'=>array($this->getId().'/login'),
					'loginRequiredAjaxResponse'=>'OPERATOR_LOGIN_REQUIRED',
					//'allowAutoLogin'=>true,
					'authTimeout'=>1440,
					//'autoRenewCookie'=>true,								//自动登录的时候刷新Cooike的时间
					//'identityCookie'=>array('httpOnly'=>true),	//防止JS修改Cookie
				),
				'session' => array(
					'class'=>'CHttpSession',
					'timeout' =>1440,
					'cookieParams'=>array('httponly'=>true),	//防止JS修改Cookie
					'cookieMode'=>'only',									//保存SessionID 仅仅用Cookie的方式保存
				),
		), false);
	}

	public function beforeControllerAction($controller, $action)
	{
		if (parent::beforeControllerAction($controller, $action))
		{
			$route = $controller->id.'/'.$action->id;
			if (!$this->allowIp(Yii::app()->request->userHostAddress) && $route !== 'error/index')
				throw new CHttpException(403, '你没有权限访问运营商系统后台。有问题请联系管理员！！');
			$publicPages = array(
					'login/index',
					'error/index',
					'login/captcha',
					'hotel/uploads',
					'eat/uploads',
					'play/uploads',
					'login/apply',
					'login/pwd',
			);
			if (Yii::app()->operator->isGuest && !in_array($route, $publicPages))
				Yii::app()->operator->loginRequired();
			else if(!in_array($route, $publicPages))
			{
				$model = Agent::model()->findByPk(Yii::app()->operator->id);
				if ($model)
				{
					if ($model->status != Agent::status_suc)
					{
						Yii::app()->operator->logout(false);
						Yii::app()->operator->setFlash(Agent::login_unique_info, '运营商账号已被禁用');
						Yii::app()->operator->loginRequired();
					}
					elseif($model->login_unique != Yii::app()->operator->getState(Agent::login_unique))
					{
						Yii::app()->operator->logout(false);
						Yii::app()->operator->setFlash(Agent::login_unique_info,'已在其他地方登录了，如果不是本人，请修改登录密码.');
						Yii::app()->operator->loginRequired();
					}
					return true;
				}
				else
				{
					Yii::app()->operator->logout(false);
					Yii::app()->operator->setFlash(Agent::login_unique_info,'运营商账号不存在.');
					Yii::app()->operator->loginRequired();
				}
			}
			return true;
		}
		else
			return false;
	}
	
	/**
	 * IP 限制
	 * @param unknown $ip
	 * @return boolean
	 */
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
