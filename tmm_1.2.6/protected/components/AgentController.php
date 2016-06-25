<?php
/**
 * 代理商继承控制器
 * @author Changhai Zhan
 * @property string $Title
 */
class AgentController extends WebController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout = '/layouts/column_right';
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
	 * 系统名字
	 * @var string
	 */
	public $name;
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
	 * 当前操作的数据模型
	 * @var unknown
	 */
	public $_class_model;
	/**
	 * 初始化
	 * zch
	 * (non-PHPdoc)
	 * @see CController::init()
	 */
	public function  init()
	{
		parent::init();
		$this->name='代理商管理平台';
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
			if(!in_array($this->route, Yii::app()->params['agent_not_back_url']))
			{
				if( !Yii::app()->request->isAjaxRequest && Yii::app()->request->getUrlReferrer() != Yii::app()->request->hostInfo.Yii::app()->request->getUrl())
					Yii::app()->session['agent__update_return_url']=Yii::app()->request->getUrlReferrer();
			}
			return true;
		}else
			return false;
	}
	
	/**
	 * 面包屑后面添加
	 */
	public function reload(){
// 		$this->breadcrumbs['刷新页面']=Yii::app()->request->getUrl();
// 		$this->breadcrumbs['<<-返回']='javascript:history.go(-1);';
		
	}
	
	/**
	 * 显示面包屑
	 * @param unknown $breadcrumbs
	 */
	public function breadcrumbs($breadcrumbs=array(),$homeLink=false)
	{
		if(empty($breadcrumbs))
			$breadcrumbs=$this->breadcrumbs;
		if(!empty($breadcrumbs))
		{
			$this->reload();
			return $this->widget('zii.widgets.CBreadcrumbs', array(
					'homeLink'=>$homeLink,
					'separator'=>' / ',
					'links'=>$breadcrumbs,
			),true);
		}
	}

	/**
	 * 返回上上次链接 排除重复链接
	 */
	public function back($url=false){
		if($url)
			return Yii::app()->session['agent__update_return_url']=='' ?
				Yii::app()->request->getUrlReferrer() :
				Yii::app()->session['agent__update_return_url'];
		if(Yii::app()->session['agent__update_return_url']=='')
			$this->redirect(Yii::app()->request->getUrlReferrer());
		else
			$this->redirect(Yii::app()->session['agent__update_return_url']);
	}
	
	/**
	 * 点的列表页显示 （选择点 创建线）
	 * @param unknown $path
	 * @param string $type
	 * @param string $alt
	 * @param unknown $params
	 * @return string
	 */
	public function dot_list_show_img($path,$type='',$alt='',$params=array())
	{
		if($type=='')
			$type=Yii::app()->params['litimg_pc'];
		$litimg_path=$this->litimg_path($path,$type);
		if(!file_exists($litimg_path))
		{
			$litimg_path=$path;
			$params['with']=Yii::app()->params['litimg_confing'][$type]['with'];
			$params['height']=Yii::app()->params['litimg_confing'][$type]['height'];
		}
		if($alt=='')
			$alt=$litimg_path;
		return CHtml::image($litimg_path,$litimg_path,$params);
	}

	/**
	 * 钱的算法
	 * @param $money
	 * @param int $int
	 * @param string $type
	 * @return string
	 */
	public function money_floor($money,$int=2,$type='floor')
	{
		$array=array('ceil','floor','round');
		return sprintf("%0.2f", floor((string)($money * 100)) / 100);
	}
	
}