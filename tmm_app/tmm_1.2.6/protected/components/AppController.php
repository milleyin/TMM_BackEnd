<?php
/**
 *	系统核心继承控制器
 * @author Changhai Zhan
 *
 */
class AppController extends SBaseController
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
	}

	/**
	 * 返回数据
	 * @param array $data
	 * @param array $jump
	 */
	public function send($data=array(),$jump=array())
	{
		header("Content-type:application/json;");
		$response = array (
			'status' => STATUS_SUCCESS,
			'code' => SUCCESS,
			'msg'  =>constant('SUCCESS_MSG'),
			'data' => $data,
		);
		if(!empty($jump) && is_array($jump))
		{
			$url=Yii::app()->params['app_api_domain'].Yii::app()->createUrl(isset($jump[0])?$jump[0]:'/',array_splice($jump,1));
			$response['jump'] =$url;
		}
		echo json_encode($response);
		self::globalErrorRecord('http_response', json_encode($response),$this->current_model_id());
		Yii::app()->end();
	}

	/**
	 * 表单错误输出
	 * @param $data
	 * @param int $errorCode
	 * @param int $errorStatus
	 */
	public function send_error_form($data,$errorCode=SYSTEM_FROM_ERROR,$errorStatus=STATUS_FAIL){
		header("Content-type:application/json;");
		$response = array (
			'status' => $errorStatus,
			'code' => $errorCode,
			'msg'  =>constant($errorCode.'_MSG'),
			'form' =>$data,
		);

		echo json_encode($response);
		self::globalErrorRecord('response_error_form', json_encode($response),$this->current_model_id());
		Yii::app()->end();
	}

	/**
	 * 系统报错
	 * @param $data
	 * @param int $errorCode
	 * @param int $errorStatus
	 */
	public function send_error_system($data,$errorCode=STATUS_FALL_SYSTEM,$errorStatus=STATUS_FAIL){
		header("Content-type:application/json;");
		$response = array (
			'status' => $errorStatus,
			'code' => $errorCode,
			'msg'  =>constant($errorCode.'_MSG'),
			'system' =>$data,
		);

		echo json_encode($response);
		self::globalErrorRecord('response_error_system', json_encode($response),$this->current_model_id());
		Yii::app()->end();
	}

	/**
	 * 错误输出
	 * @param $errorCode
	 * @param int $errorStatus
	 */
	public function send_error($errorCode, $errorStatus = STATUS_FAIL,$data=array())
	{
		header("Content-type:application/json;");
		$response = array (
			'status' => $errorStatus,
			'code' => $errorCode,
			'msg'  =>constant($errorCode.'_MSG'),
			'data' =>$data,
		);
		echo json_encode($response);
		self::globalErrorRecord('response_error',json_encode($response) ,$this->current_model_id());
		Yii::app()->end();
	}

	/**
	 * 验证表达信息错误
	 * @param $models
	 * @param null $attributes
	 * @return array
	 */
	public function validate_error($models,$attributes=null)
	{
		$result=array();
		if(!is_array($models))
			$models=array($models);
		foreach($models as $model)
		{
			$modelName=CHtml::modelName($model);
			if(isset($_POST[$modelName]))
				$model->attributes=$_POST[$modelName];
			$model->validate($attributes);
			foreach($model->getErrors() as $attribute=>$errors)
				$result[CHtml::activeId($model,$attribute)]=$errors;
		}
		return $result;
	}

	/**
	 * 返回表单错误信息
	 * @param $models
	 * @return array
	 */
	public function form_error($models){
		$result=array();
		if(!is_array($models))
			$models=array($models);

		foreach($models as $model)
		{
			$modelName=CHtml::modelName($model);
			foreach($model->getErrors() as $attribute=>$errors)
				$result[CHtml::activeId($model,$attribute)]=$errors;
		}

		return $result;
	}

	/**
	 * 加载数据
	 * @param $id
	 * @param string $condition
	 * @param array $params
	 * @param string $model
	 * @param string $action
	 * @return mixed
	 * @throws CHttpException
	 */
	public function loadModel($id,$condition='',$params=array(),$action='findByPk')
	{
		$model=$this->_class_model;
		if($model=='')
			$this->send_error(SYSTEM_CLASS_MODEL);
		$data=$model::model()->$action($id,$condition,$params);
		if($data===null)
			$this->send_error(DATA_NULL);
		return $data;
	}

	/**
	 * 返回当前model ID
	 * @return mixed|string
	 */

	public function current_model_id(){
		$model_id = $this->module !== null ? $this->module->id  : "";
		return $model_id;
	}

	/**
	 * 写日志文件
	 * @param $action
	 * @param $error
	 * @return bool
	 */

	public static function globalErrorRecord($action, $error,$model_id='') 
	{
		$date = Date('Y-m-d', time());
		$dir = Yii::getPathOfAlias('application') . '/runtime/'.$model_id.'log/';
		if(!is_dir($dir))
			mkdir($dir, 0777, true);
		
		$path = $dir.$date.'.txt';
		if(!file_exists($path))
		{
			$fh = fopen ($path,"w+");
			fwrite($fh, '******************'.$date.' record' . "\n" . "\n");
			fclose($fh);
		}
		$time = self::microtime_format ( 'Y-m-d  H:i:s:x',self::microtime_float () );
		$str = $time . ":  " . $action . "  " . $error . "\n";
		$handle = fopen ( $path, 'a' );
		fwrite ( $handle, $str );
		fclose ( $handle );
	}
	
	/** 返回时间戳*/
	public static function microtime_float()
	{
		list($usec, $sec) = explode(" ", microtime());
		return ((float)$usec + (float)$sec);
	}
	
	/** 格式化时间戳，精确到毫秒，x代表毫秒 */
	public static function microtime_format($tag, $time)
	{
		$data = explode(".", $time);
		list($usec, $sec) =count($data)==2?$data:array($data[0],0);
		$date = date($tag,$usec);
		return str_replace('x', $sec, $date);
	}
	
	/**
	 * 返回缩略图 有类型返回类型的 没有类型返回数组所有
	 * @param unknown $path_name
	 * @param string $type
	 * @return string
	 */
	public function litimg_path($path_name,$type='',$default='')
	{
		$filename=basename($path_name);
		$path=dirname($path_name);
		if($type=='')
		{
			$type=Yii::app()->params['litimg_app'];
			$return=$path.'/'.Yii::app()->params['litimg'][$type].$filename;
		}
		elseif($type==false)
		{
			foreach (Yii::app()->params['litimg'] as $key=>$value)
				$return[$key]=$path.'/'.$value.$filename;
		}
		else
		{
			$type=Yii::app()->params[$type];
			$return=$path.'/'.Yii::app()->params['litimg'][$type].$filename;
		}
		if(! is_array($return))
			return file_exists($return) ? $return : ($default==''?$path_name:$default);
		else
			return $return;
	}

	/**
	 * 格式化 钱 显示
	 * @param $str
	 * @param int $num
	 * @return string
	 */
	public static function number_format($str,$num=2){
		if(!$str)
			return '0.00';

		return number_format($str,$num);
	}

}