<?php
/**
 * 系统管理员继承控制器
 * @author Changhai Zhan
 * @property string $Title
 */
class MainController extends WebController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='/layouts/column_right';
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
	 * 控制页面框架链接
	 * @var unknown
	 */
	public $frame=array();
	/**
	 *	导航链接
	 * @var unknown
	 */
	public $navbar=array();
	/**
	 * 控制器的前缀
	 * @var unknown
	 */
	public $prefix='tmm_';	
	/**
	 * 初始化
	 * zch
	 * (non-PHPdoc)
	 * @see CController::init()
	 */
	public function  init()
	{
		parent::init();
		$this->name='田觅觅-工作人员平台';
	}
	
	/**
	 * 执行方法之前的操作
	 * @see SBaseController::beforeAction()
	 */
	public function beforeAction($action)
	{
		if(parent::beforeAction($action))
		{
			//保存上上次不同的链接
			if(!in_array($this->route, Yii::app()->params['admin_not_back_url']))
			{
				if( !Yii::app()->request->isAjaxRequest && Yii::app()->request->getUrlReferrer() != Yii::app()->request->hostInfo.Yii::app()->request->getUrl())
					Yii::app()->session['admin__update_return_url']=Yii::app()->request->getUrlReferrer();
			}
			return true;
		}else 
			return false;
	}
	
	/**
	 * 面包屑后面添加
	 */
	public function reload(){
		$this->breadcrumbs['刷新页面']=Yii::app()->request->getUrl();
		$this->breadcrumbs['<<-返回']='javascript:history.go(-1);';
	}
	
	/**
	 * 返回上上次链接 排除重复链接
	 */
	public function back($url=false)
	{
		if($url)
			return Yii::app()->session['admin__update_return_url']=='' ?
			Yii::app()->request->getUrlReferrer() : 
			Yii::app()->session['admin__update_return_url'];
		if(Yii::app()->session['admin__update_return_url']=='')
			$this->redirect(Yii::app()->request->getUrlReferrer());
		else
			$this->redirect(Yii::app()->session['admin__update_return_url']);
	}
	
	/**
	 *删除文件夹 及以下的所有文件和文件夹
	 * @param unknown $dir
	 */
	function deletedir($dir)
	{
		if (is_dir($dir))
		{
			$filenames = scandir($dir);
			$filenames=array_slice($filenames,2);
			foreach ($filenames as $filename)
			{
				if (is_dir($dir.'/'.$filename))
					$this->deletedir($dir.'/'.$filename);
				else
					unlink($dir.'/'.$filename);
			}
			rmdir($dir);
		}
	}
	
	/**
	 * 清空项目 缓存图片
	 */
	public function clear_tmp($path)
	{
		if(is_dir($path))
		{
			$date=date('Ymd');
			$filenames=scandir($path);
			$filenames=array_slice($filenames,2);	
			foreach ($filenames as $filename)
			{	
				if($filename !=$date && is_dir($path.'/'.$filename))
					$this->deletedir($path.'/'.$filename);		
			}
		}
	}

	/**
	 * 成功提示
	 * @param string $msg 提示信息
	 * @param string $jumpUrl 跳转url
	 * @param int $wait 等待时间
	 */
	public function success($msg = "", $jumpUrl = "", $wait = 3)
	{
		$this->_jump($msg, $jumpUrl, $wait, 1);
	}

	/**
	 * 错误提示
	 * @param string $msg 提示信息
	 * @param string $jumpUrl 跳转url
	 * @param int $wait 等待时间
	 */
	public function error($msg = "", $jumpUrl = "", $wait = 3)
	{
		$this->_jump($msg, $jumpUrl, $wait, 0);
	}

	/**
	 * 最终跳转处理
	 * @param string $msg 提示信息
	 * @param string $jumpUrl 跳转url
	 * @param int $wait 等待时间
	 * @param int $type 消息类型 0或1
	 * @throws CException
	 */
	private function _jump($msg = "", $jumpUrl = "", $wait = 3, $type = 0)
	{

		$data = array(
			'msg' => $msg,
			'jumpurl' => Yii::app()->createUrl($jumpUrl),
			'wait' => $wait,
			'type' => $type
		);
		$data['title'] = ($type == 1) ? "提示信息" : "错误信息";
		if (empty($jumpUrl)) 
		{
			if ($type == 1) 
				$data['jumpUrl'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : "javascript:window.close();";
			 else 
				$data['jumpUrl'] = "javascript:history.back(-1);";
		} 
		else 
			$data['jumpUrl'] = Yii::app()->createUrl($jumpUrl);
		$this->render("/layouts/show_message", $data);
	}
}