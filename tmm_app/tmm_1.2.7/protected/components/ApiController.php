<?php
/**
 *	系统核心继承控制器
 * @author Changhai Zhan
 *
 */
class ApiController extends AppController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout;
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	/**
	 * 初始化
	 * zch 
	 * (non-PHPdoc)
	 * @see CController::init()
	 */
	public function  init(){
		parent::init();
		if (isset($_SERVER['HTTP_ORIGIN']))
		{
			header("Access-Control-Allow-Credentials: true");
			header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
		}
	}

	/**
	 * 执行方法之前的操作
	 * @see SBaseController::beforeAction()
	 */
	public function beforeAction($action)
	{
		if(parent::beforeAction($action))
		{
			return true;
		}else 
			return false;
	}

	/**
	 * 获取表单的 秘钥 默认 get 
	 * @param unknown $data
	 * @param string $type
	 */
	public function send_csrf($data=array(),$type='GET',$name='csrf')
	{
		if($type=='GET' && isset($_GET[$name]) && $_GET[$name]==$name)
			$data['csrf']=$this->csrf();
		elseif($type=='POST' && isset($_POST[$name]) && $_POST[$name]==$name)
			$data[$name]=$this->csrf();
		else
			$this->send_error(DATA_NOT_SCUSSECS);
		$this->isRefreshToken = false;
		$this->send($data);
	}

	/**
	 * 记录操作日志
	 * @param string $content 内容
	 * @param integer $manage_who 管理员类型
	 * @param integer $manage_type 操作类型
	 */
	public function log($content,$manage_who,$manage_type)
	{
		$modules=ManageLog::$_manage_modules[$manage_who];
		$model=new ManageLog;
		$model->manage_id=Yii::app()->$modules->id;
		$model->manage_who=$manage_who;
		$model->manage_type=$manage_type;
		$model->info=$content;
		$model->url=Yii::app()->request->hostInfo.Yii::app()->request->getUrl();//$this->route;
		$model->ip=Yii::app()->request->userHostAddress;
		$model->save(false);
		return true;
	}
	
	/**
	 * 分页函数
	 * @param unknown $criteria
	 * @param unknown $count
	 * @param unknown $pageSize
	 * @param unknown $domain
	 */
	public function page($criteria,$count,$pageSize,$domain)
	{
		$pages = new CPagination($count);
		$pages->pageVar='page';//设置分页的标志 page=1
		$pages->pageSize=$pageSize;
		$pages->applyLimit($criteria);
		Yii::import('ext.LinkPager.LinkPager');//分页函数
		$widget=$this->createWidget('LinkPager',array(
				'domain'=>$domain,
				'pages'=>$pages
		));
		return $widget->run();
	}
	
	/**
	 * 验证用户是否登录
	 * @param $id
	 */
	public static function verify_login(){
		$model_user = User::model()->find(' status=1 AND  id='.Yii::app()->api->id);
		if(!$model_user)
			self::send_error(DATA_NULL);
		else
			return $model_user;
	}
}