<?php

class SiteController extends Controller
{
	/**
	 * 分享商品类型
	 * @var int
	 */
	private $type_shops = 1;

	/**
	 * 分享项目类型
	 * @var int
	 */
	private $type_items = 2;

	/**
	 * 分享农产品类型
	 * @var int
	 */
	private $type_links = 3;
	/**
	 * 
	 * @var integer
	 */	
	public $error_share = 403;
	/**
	 * 分享域名
	 * @var unknown
	 */
	public $domain = 'https://m.365tmm.com';

 	//public $domain = 'http://test2.365tmm.net';

	/**
	 * 设置 view
	 * @var string
	 */
	public $layout='//layouts/column1';
	
	
	public function beforeAction($action)
	{
		if(parent::beforeAction($action))
		{
			if(! Yii::app()->errorHandler->error)
			{
				$route_arr = array(
					'site/share',
					'site/error',
				);
				if( ! in_array($this->route,$route_arr))
					$this->redirect(array('/admin'));
				return true;
			}else
				return true;
		}else
			return true;
	}

	/**
	 * 分享
	 * $type 默认 商品===1  项目===2  农产品===3
	 * $share ===== 对应ID
	 *  1==69
	 *  2==121
	 *  3==13
	 * http://172.16.1.158/tm/index.php?r=site/share&type=1&share=69
	 * http://172.16.1.158/tm/index.php?r=site/share&type=2&share=121
	 * http://172.16.1.158/tm/index.php?r=site/share&type=3&share=13
	 */
	public function actionShare()
	{
		//接收 GET 传参
		$type  =  isset($_GET['type']) && $_GET['type']  ? $_GET['type'] : $this->type_shops;
		$share = isset($_GET['share']) && $_GET['share'] ? $_GET['share'] :'';

		//根据类型分别调用
		switch($type)
		{
			case $this->type_shops :
				$this->share_shops($share);
				break;
			case $this->type_items :
				$this->share_items($share);
				break;
			case $this->type_links :
				$this->share_links($share);
				break;
			default :
				throw new CHttpException($this->error_share,'分享参数类型 不是有效值');break;
		}
	}


	/**
	 * 分享======商品
	 */
	private function share_shops($share)
	{
		//条件
		$criteria = new CDbCriteria;
		//关系
		$criteria->with = array(
			'Shops_Actives'
		);
		$criteria->addColumnCondition(array(
			'`t`.`status`'=>Shops::status_online,								//商品上线
		));
		$conditions = array(
			'(`t`.`c_id`=:dot AND `t`.`id`=:id)',
			'(`t`.`c_id`=:thrand AND `t`.`id`=:id)',
			'(`t`.`c_id`=:actives AND ( (`Shops_Actives`.`is_open`=:yes AND (`t`.`id`=:id OR `Shops_Actives`.`barcode`=:id) ) OR ( `Shops_Actives`.`is_open`=:no AND `Shops_Actives`.`barcode`=:id ) ))',
		);

		$criteria->params[':dot'] = Shops::shops_dot;					//点
		$criteria->params[':thrand'] = Shops::shops_thrand;			//线
		$criteria->params[':actives'] = Shops::shops_actives;		//活动
		$criteria->params[':yes'] = Actives::is_open_yes;				//开放活动
		$criteria->params[':no'] = Actives::is_open_no;					//私密活动
		$criteria->params[':id'] = $share;
		$criteria->addCondition(implode(' OR ', $conditions));	
		$model = Shops::model()->find($criteria);
		
		if (! $model)
			throw new CHttpException($this->error_share,'分享参数类型 不是有效值');
		//设置分享数量
		Shops::set_shops_share($model->id);
		//活动分享
		if ($model->c_id == Shops::shops_actives && isset($model->Shops_Actives->pay_type) &&  $model->Shops_Actives->pay_type == Actives::pay_type_full)
		{	
			$wx_url = $this->domain . '/full/#/' . $share;
			Yii::app()->request->redirect($wx_url);
		}
		else
			$wx_url = 'http://wx.365tmm.com/#/tuijiandetail_'.($model->c_id-1) . '/' . $share;
		//判断是否 pc  ==== 不是pc跳微信
 		if(!$this->is_pc())
			Yii::app()->request->redirect($wx_url);
 		
		$this->renderPartial('share',array('model'=>$model));
	}

	/**
	 * 分享======项目
	 */
	private function share_items($share){

		$model = Items::model()->findByPk($share,'status=1');

		if(!$model)
			throw new CHttpException($this->error_share,'分享参数类型 不是有效值');

		$c_id = $model->c_id;
		$wx_url = 'http://wx.365tmm.com/#/item/'.$c_id.'/'.$share.'/'.$c_id;
		//判断是否 pc  ==== 不是pc跳微信
		if(! $this->is_pc())
			Yii::app()->request->redirect($wx_url);

		$this->renderPartial('share_items',array('model'=>$model));
	}

	/**
	 * 分享======链接
	 */
	private function share_links($share){

		$model = FarmOuter::model()->findByPk($share,'status=1');

		if(!$model)
			throw new CHttpException($this->error_share,'分享参数类型 不是有效值');

		$wx_url = $model->link;
		Yii::app()->request->redirect($wx_url);
//		$wx_url = 'http://wx.365tmm.com/#/tuijianpointmore/'.$share;
//		//判断是否 pc  ==== 不是pc跳微信
//		if(! $this->is_pc())
//			Yii::app()->request->redirect($wx_url);

		//$this->renderPartial('share_links',array('model'=>$model));
	}

	/**
	 * is_pc
	 */
	public function is_pc()
	{
		if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'])
		{
			$server='tmm_'.strtolower($_SERVER['HTTP_USER_AGENT']);//添加前缀 避免为0
			if(strrpos($server,'windows nt'))
				return true;
		}
		return false;
	}
	
// 	/**
// 	 * Declares class-based actions.
// 	 */
// 	public function actions()
// 	{
// 		return array(
// 			// captcha action renders the CAPTCHA image displayed on the contact page
// 			'captcha'=>array(
// 				'class'=>'CCaptchaAction',
// 				'backColor'=>0xFFFFFF,
// 			),
// 			// page action renders "static" pages stored under 'protected/views/site/pages'
// 			// They can be accessed via: index.php?r=site/page&view=FileName
// 			'page'=>array(
// 				'class'=>'CViewAction',
// 			),
// 		);
// 	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if(!! $error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			elseif($error['code'] == $this->error_share)
				$this->renderPartial('share',$error);
			else
			{
				$this->layout='//layouts/column_error';
				$this->render('error', $error);
			}
		}
	}

// 	/**
// 	 * Displays the contact page
// 	 */
// 	public function actionContact()
// 	{
// 		$model=new ContactForm;
// 		if(isset($_POST['ContactForm']))
// 		{
// 			$model->attributes=$_POST['ContactForm'];
// 			if($model->validate())
// 			{
// 				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
// 				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
// 				$headers="From: $name <{$model->email}>\r\n".
// 					"Reply-To: {$model->email}\r\n".
// 					"MIME-Version: 1.0\r\n".
// 					"Content-Type: text/plain; charset=UTF-8";

// 				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
// 				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
// 				$this->refresh();
// 			}
// 		}
// 		$this->render('contact',array('model'=>$model));
// 	}

// 	/**
// 	 * Displays the login page
// 	 */
// 	public function actionLogin()
// 	{
// 		$model=new LoginForm;

// 		// if it is ajax validation request
// 		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
// 		{
// 			echo CActiveForm::validate($model);
// 			Yii::app()->end();
// 		}

// 		// collect user input data
// 		if(isset($_POST['LoginForm']))
// 		{
// 			$model->attributes=$_POST['LoginForm'];
// 			// validate user input and redirect to the previous page if valid
// 			if($model->validate() && $model->login())
// 				$this->redirect(Yii::app()->user->returnUrl);
// 		}
// 		// display the login form
// 		$this->render('login',array('model'=>$model));
// 	}

// 	/**
// 	 * Logs out the current user and redirect to homepage.
// 	 */
// 	public function actionLogout()
// 	{
// 		Yii::app()->user->logout();
// 		$this->redirect(Yii::app()->homeUrl);
// 	}
	
}