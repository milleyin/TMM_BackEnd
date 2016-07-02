<?php
/**
 *	系统核心继承控制器
 * @author Changhai Zhan
 *
 */
class Controller extends CController
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
		/**
		 * 安全验证时用的防止有错误
		 * zch
		 */
		if(Yii::app()->request->enableCsrfValidation){
			if(isset($_POST[Yii::app()->request->csrfTokenName]))
				unset($_POST[Yii::app()->request->csrfTokenName]);
		}
		if(!is_dir(Yii::app()->basePath.'/runtime/error'))
			mkdir(Yii::app()->basePath.'/runtime/error', 0777, true);
		if(YII_DEBUG && !is_dir(Yii::app()->basePath.'/runtime/error/test'))
			mkdir(Yii::app()->basePath.'/runtime/error/test', 0777, true);
	}

    /**
     * 添加错误日志
     * @param  string  $content  操作详情
     * @param  integer $manage_who 管理员类型
     * @param  integer $manage_type 操作类型
     * @param  integer $error_type  错误类型
     * @param  string  $method     	 操作方法
     * 					$this->error_log($e->getMessage(), ErrorLog::admin, ErrorLog::create,ErrorLog::rollback,__METHOD__);
     */
    public function error_log($content,$manage_who,$manage_type,$error_type='-1',$method=''){
        $modules=ErrorLog::$_manage_modules[$manage_who];
        $model=new ErrorLog;
        $model->error_id=Yii::app()->$modules->id;
        $model->error_type=$error_type;
        $model->manage_who=$manage_who;
        $model->manage_type=$manage_type;
        $model->manage_method=$method;
        $model->info=$content;
        $model->url=Yii::app()->request->hostInfo.Yii::app()->request->getUrl();//$this->route;
        $model->ip=Yii::app()->request->userHostAddress;
        $model->save(false);
        return true;
    }

	/**
	 * post 密钥验证
	 * @return array
	 */
	public function csrf(){
		if(Yii::app()->request->enableCsrfValidation) {
			$csrfTokenName = Yii::app()->request->csrfTokenName;
			$csrfToken = Yii::app()->request->csrfToken;
			return array($csrfTokenName=>$csrfToken,'name'=>$csrfTokenName,'value'=>$csrfToken);
		}else
			return array();
	}

	/**
	 * 短信验证码
	 */
	public function verify_sms($mobile='13144828679',$type=1,$code=1234){

		$token = Yii::app()->params['sms_key'];
		$content = array('mobile' =>$mobile,'message' => self::sms_templates($type,$code));

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://sms-api.luosimao.com/v1/send.json");

		curl_setopt($ch, CURLOPT_HTTP_VERSION  , CURL_HTTP_VERSION_1_0 );
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);

		curl_setopt($ch, CURLOPT_HTTPAUTH , CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD  , 'api:'.$token);

		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$content );

		$res = curl_exec( $ch );
		curl_close( $ch );
		$val = json_decode($res);
		if($val->error==0)
			$status = 1; // 成功
		else {
			if($val->error== -20)
				$status=2;  // 短信账号欠费，发邮件通知财物
			$status = 2;  //失败
		}

		var_dump($res);
		Yii::log('key','error',$res);

		//写短信发送日志  数据库

		return $status;

	}

	/**
	 * 短信发送模版
	 */
	public function sms_templates($type,$code){
		switch($type){
			case 1:
				$content = "工作人员验证码：".$code.'【田秘密】';break;
			case 2:
				$content = "代理商验证码：".$code.'【田秘密】';break;
			case 3:
				$content = "商家验证码：".$code.'【田秘密】';break;
			case 4:
				$content = "用户验证码：".$code.'【田秘密】';break;
			default:
				$content = "自定义：".$code.'【田秘密】';break;
		}
		return $content;
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
	 * 格式化输出
	 */
	public function p_r($val){
		echo "<pre>";
		print_r($val);
	}

	/**
	 * 替换图片链接
	 */
	public function admin_img_replace($content)
	{
		$replace =  str_replace('<img alt="" src="/', '<img alt="" src="'.Yii::app()->params['admin_img_domain'].'/', $content);
		return $replace;
	}
	
	/**
	 * 格式化距离
	 * @param unknown $distance
	 * @return string
	 */
	public function FormatDistance($distance)
	{
		if((int)$distance >= 1000)
			$distance=round($distance/1000,2).'km';
		else
			$distance = $distance.'m';
		return $distance;
	}
}