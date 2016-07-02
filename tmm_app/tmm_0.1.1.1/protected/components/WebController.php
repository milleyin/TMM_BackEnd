<?php
/**
 * web访问继承文件
 * @author Changhai Zhan
 *
 */
class WebController extends SBaseController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
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
	 * 上传图片
	 * @var unknown
	 */
	public $_upload='';
	/**
	 * 系统名字
	 * @var string
	 */
	public $name;
	/**
	 * 显示图片的id 
	 */
	public $imgShowId= 0;
	/**
	 * 页面最后输出的数据
	 * @var unknown
	 */
	public $text = '';
	/**
	 * 初始化
	 * zch
	 * (non-PHPdoc)
	 * @see CController::init()
	 */
	public function  init(){
		parent::init();
	}
	/**
	 * 返回标题
	 * @return string
	 */
	public function getTitle(){
		return $this->pageTitle.'-'.$this->name;
	}
	/**
	 * 表单使用
	 * @param unknown $model
	 * @param unknown $Id
	 */
	public function _Ajax_Verify($model,$Id='')
	{
		if(isset($_POST['ajax']) && ($_POST['ajax'] === $Id))
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * 添加css文件
	 * @param unknown $file
	 * @param string $position
	 * Yii::app()->baseUrl.filename
	 */
	public function addCss($file,$position=''){
		Yii::app()->getClientScript()->registerCssFile($file,$position);
	}

	/**
	 * 添加js文件
	 * @param unknown $file
	 * @param string $position
	 * Yii::app()->baseUrl.filename
	 */
	public function addJs($file){
		Yii::app()->getClientScript()->registerScriptFile($file);
	}

	/**
	 * 添加JQ框架
	 * @param string $jq
	 */
	public function addJq($jq='jquery'){
		Yii::app()->getClientScript()->registerCoreScript($jq);
	}

	/**
	 * 添加meta标签
	 * @param unknown $content
	 * @param string $name
	 * @param string $httpEquiv
	 * @param unknown $options
	 */
	public function addMeta($content,$name=NULL,$httpEquiv=NULL,$options=array()){
		Yii::app()->getClientScript()->registerMetaTag($content,$name,$httpEquiv,$options);
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
	 * 验证图片的错误
	 * @param unknown $model
	 * @param unknown $flies
	 * @param unknown $uploads
	 * @return boolean
	 */
	public function upload_error($model,$files,$uploads)
	{
		if(count($files) != count($uploads))
		{
			foreach ($uploads as  $attribute)
			{
				if(!isset($files[$attribute]))
					$model->addError($attribute, $model->getAttributeLabel($attribute)." 不能空白");
			}
		}else
			return true;
		return false;
	}
	
	/**
	 * 获取新上传的图片属性
	 * @param unknown $model
	 * @param unknown $attributes
	 * @param string $uploadfile
	 * @return multitype:multitype:string Ambigous <CUploadedFile, NULL, multitype:>
	 */
	public function upload($model,$attributes,$uploadfile='')
	{
		if(!is_array($attributes))
			$attributes=array($attributes);
		if($uploadfile=='')
			$uploadfile=$this->_upload;
	
		$uploadfile=$uploadfile.date('Ymd');
		$return =array();
	
		foreach ($attributes as $attribute)
		{
			if(!! $file=CUploadedFile::getInstance($model,$attribute))
			{
				$model->$attribute=$this->getFilePath() . '.' . $file->extensionName;
				$return[$attribute]=array('file'=>$file,'upload'=>$uploadfile,'data'=>$model->$attribute);
			}
		}
		return $return;
	}
	
	/**
	 *获取当前需要创建的文件的地址
	 * @param string $uploadfile
	 * @return string
	 */
	public function getFilePath($uploadfile='')
	{
		if($uploadfile=='')
			$uploadfile=$this->_upload;
		
		return $uploadfile . date('Ymd') . '/' . uniqid(mt_rand(1, 9999),true);
	}
	
	/**
	 * 保存新上传的图片
	 * @param unknown $model
	 * @param unknown $attributes
	 */
	public function upload_save($model,$attributes,$litimg=true,$mode=4,$type=array('pc','app'))
	{
		foreach ($attributes as $attribute=>$v)
		{
			if(isset($v['upload']) && $v['file'])
			{
				if(!is_dir($v['upload']))
					mkdir($v['upload'], 0777, true);
				$v['file']->saveAs($model->$attribute);
				if($litimg)
					$this->upload_litimg($model->$attribute,$mode,$type);
			}
		}
	}
	
	/**
	 * 生成缩略图
	 * @param unknown $path_name
	 */
	public function upload_litimg($path_name,$mode=4,$type=array('pc','app'))
	{
		if(file_exists($path_name))
		{
			$fileinfo=pathinfo(basename($path_name));
			$filename=$fileinfo['filename'];
			$path=dirname($path_name);
			foreach (Yii::app()->params['litimg'] as $key=>$value)
			{
				if( isset(Yii::app()->params['litimg_confing'][$key]) && ($type==''?true:(is_array($type)?in_array($key, $type):$key==$type)) )
				{
					$new_path=$path.'/'.$value;
					if(!is_dir($new_path))
						mkdir($new_path, 0777, true);
					$thumb = Yii::app()->thumb;
					$thumb->image = $path_name;
					$thumb->mode=isset(Yii::app()->params['litimg_confing'][$key]['mode'])?Yii::app()->params['litimg_confing'][$key]['mode']:$mode;
					$thumb->quality =  Yii::app()->params['litimg_confing'][$key]['quality'];
					$thumb->compression =  Yii::app()->params['litimg_confing'][$key]['compression'];
					$thumb->width = Yii::app()->params['litimg_confing'][$key]['with'];
					$thumb->height = Yii::app()->params['litimg_confing'][$key]['height'];
					$thumb->directory = $new_path;
					$thumb->defaultName = $filename;
					$thumb->createThumb();
					$thumb->save();
				}
			}
		}
	}
	
	/**
	 * 返回缩略图 有类型返回类型的 没有类型返回数组所有
	 * @param unknown $path_name
	 * @param string $type
	 * @return string
	 */
	public function litimg_path($path_name,$type=false)
	{
		$filename=basename($path_name);
		$path=dirname($path_name);
		if($type)
			$return=$path.'/'.Yii::app()->params['litimg'][$type].$filename;
		else
		{
			foreach (Yii::app()->params['litimg'] as $key=>$value)
				$return[$key]=$path.'/'.$value.$filename;
		}
		return $return;
	}
	
	/**
	 * 保存原来的值
	 * @param unknown $model
	 * @param unknown $uploads
	 * @return multitype:NULL
	 */
	public function upload_save_data($model,$uploads)
	{
		if(! is_array($uploads))
			$uploads=array($uploads);
		$return=array();
		foreach ($uploads as $attribute)
			$return[$attribute]=$model->$attribute;
		return $return;
	}
	
	/**
	 * 更新上传的值
	 * @param unknown $model
	 * @param unknown $data
	 * @param unknown $files
	 * @return multitype:unknown
	 */
	public function upload_update_data($model,$data,$files)
	{
		$array=array();
		foreach ($data as $attribute=>$path)
		{
			if(!isset($files[$attribute]))
				$model->$attribute=$path;
			else
				$array[]=$path;
		}
		return $array;
	}
	
	/**
	 * 删除原来的图片
	 * @param unknown $paths
	 */
	public function upload_delete($paths)
	{
		if(! is_array($paths))
			$paths=array($paths);
		foreach ($paths as $v)
		{
			if(file_exists($v))
				unlink($v);
			$litimg_paths=$this->litimg_path($v);
			foreach ($litimg_paths as $litimg_path)
			{
				if(file_exists($litimg_path))
					unlink($litimg_path);
			}
		}
	}
	
	/**
	 * 表单ajax验证使用 重写 可以相同model 多个验证
	 * @param unknown $model
	 * @param unknown $Id
	 */
	public function _Ajax_Verify_Same($models,$Id='')
	{
		if(isset($_POST['ajax']) && ($_POST['ajax'] == $Id))
		{
			$result=array();
			if(!is_array($models))
				$models=array($models);
			$modelNames=$new_models=array();
			foreach ($models as $model)
			{
				$name=CHtml::modelName($model);
				if(! in_array($name, $modelNames))
				{
					$modelNames[]=$name;
					$new_models[]=$model;
				}
			}
			foreach($new_models as $key=>$model)
			{
				$modelName=$modelNames[$key];
				if(isset($_POST[$modelName]) && is_array(current($_POST[$modelName])))
				{
					foreach ($_POST[$modelName] as $model_key=>$model_value)
					{
						$new_model=clone $model;
						$new_model->attributes=$model_value;
						$new_model->validate();
						foreach($new_model->getErrors() as $attribute=>$errors)
							$result[CHtml::activeId($new_model,'['.$model_key.']'.$attribute)]=$errors;
					}
				}
				elseif(isset($_POST[$modelName]))
				{
					$model->attributes=$_POST[$modelName];
					$model->validate();
					foreach($model->getErrors() as $attribute=>$errors)
						$result[CHtml::activeId($model,$attribute)]=$errors;
				}
			}
			echo  function_exists('json_encode') ? json_encode($result) : CJSON::encode($result);
			Yii::app()->end();
		}
	}

	/**
	 * 上传图片
	 * @param unknown $model
	 * @param unknown $uploads
	 * @param string $litimg
	 * @param number $mode
	 * @return boolean
	 */
	public function upload_images($model,$uploads=array(),$litimg=false,$mode=4)
	{
		$files = $this->upload($model, $uploads);
		if(! empty($files)){
			if ($model->validate())
				$this->upload_save($model, $files, $litimg,$mode);
			else
				return false;
		}else
			return false;
	
		return true;
	}
	
	/**
	 * 保存上传的图片
	 * @param unknown $model
	 * @param unknown $filename
	 * @param unknown $path
	 * @return boolean
	 */
	public function items_img_save($model,$filename,$litimg=true,$path='',$mode=4,$type=array('pc','app')){
		$name=basename($filename);
		if($path=='')
			$path=$this->_upload;
		$uploadfile=$path.date('Ymd');
		$model_img=new ItemsImg;
		$model_img->items_id=$model->id;
		$model_img->c_id=$model->c_id;
		$model_img->agent_id=$model->agent_id;
		$model_img->store_id=$model->store_id;
		$model_img->img=$uploadfile.'/'.$name;
		if($model_img->save(false))
		{
			if(!is_dir(dirname($model_img->img)))
				mkdir(dirname($model_img->img), 0777, true);
			if(rename($filename,$model_img->img))
			{
				if($litimg)
					$this->upload_litimg($model_img->img,$mode,$type);
				return true;
			}
		}
		return false;
	}
	

	/**
	 * 获取第一个数组 model
	 * @param unknown $models
	 * @return unknown
	 */
	public function model_first($models)
	{
		foreach ($models as $model)
			return $model;
	}
	
	/**
	 * 创建多个数据模型
	 * @param unknown $model
	 * @param unknown $number
	 * @param unknown $scenario
	 */
	public function new_modes($modelName,$scenario,$number=1)
	{
		$models=array();
		for ($i=0;$i<$number;$i++)
			$models[]=new $modelName($scenario);
		return $models;
	}
	
	/**
	* 更新models 返回会新的数组model
	* @param unknown $models
	* @param unknown $number
	* @param unknown $scenario
	* @return multitype:
	*/
	public function update_models($models,$number,$scenario)
	{
		$new_models=array();
		foreach ($models as $model)
		{
			if($number > 0)
			{
				$model->scenario=$scenario;
				$new_models[] = $model;
			}
			$number--;
		}
		return array_merge($new_models,$this->new_modes(CHtml::modelName($model),$scenario,$number));
	}
	
	/**
	* 设置多个场景
	* @param unknown $models
	* @param unknown $scenario
	*/
	public function set_scenarios($models,$scenario)
	{
		if(is_array($models))
		{
			foreach ($models as $model)
				$model->scenario=$scenario;
		}else
			$models->scenario=$scenario;
	}
	
	/**
	* 赋值给对象
	* @param unknown $models
	* @param string $default
	*/
	public function set_attributes($models,$default='',$number='')
	{
		$new_models=array();
		if(is_array($models))
		{
			$modelName=CHtml::modelName($this->model_first($models));
			if($number=='')
				$number=count($models);
			foreach ($models as $key=>$model)
			{
				if($number>0)
				{
					$model->attributes=isset($_POST[$modelName][$key])?$_POST[$modelName][$key]:$default;
					$new_models[]=$model;
				}
				$number--;
			}
		}else
			return $models->attributes=$_POST[CHtml::modelName($models)];
		return $new_models;
	}
	
	/**
	* $models 被赋值的对象 $model_data 数据对象 $array需要赋值的字段 array('id')
	* @param unknown $models
	* @param unknown $model_data
	* @param unknown $array
	* @return multitype:unknown
	*/
	public function models_attributes($models,$model_data,$array)
	{
		$new_models=array();
		foreach ($models as $key=>$model)
		{
			if(isset($model_data[$key]))
			{
				foreach ($array as $value)
					$model->$value = $model_data[$key]->$value;
				$new_models[] = $model;
			}
		}
		return $new_models;
	}
	
	/**
	 * 返回模型的数据
	 * @param unknown $models
	 * @param unknown $textField
	 * @param string $valueField
	 * @return Ambigous <multitype:NULL , multitype:Ambigous <string, mixed, unknown> >|multitype:NULL Ambigous <string, mixed, unknown>
	 */
	public function listData($models, $textField, $valueField='')
	{
		if(! is_array($models))
			return $valueField==''?array(CHtml::value($models,$textField)):array($valueField=>CHtml::value($models,$textField));
		$listData=array();
		if($valueField=='')
		{
			foreach($models as $model)
				$listData[]=CHtml::value($model,$textField);
		}else{
			foreach($models as $model)
				$listData[CHtml::value($model,$valueField)]=CHtml::value($model,$textField);
		}
		return $listData;
	}
	
	/**
	* 取数组的二个或一个值 组成新的数组
	* @param unknown $arrays
	* @param unknown $textField
	* @param string $valueField
	* @return multitype:unknown
	*/
	public function array_listData($arrays, $textField, $valueField='')
	{
		if(! isset($arrays))
			return $valueField==''?array($arrays[$textField]):array($arrays[$valueField]=>$arrays[$textField]);
		$listData=array();
		if($valueField=='')
		{
			foreach($arrays as $array)
			{
				if(isset($array[$textField]))
					$listData[]=$array[$textField];
			}
		}else{
			foreach($arrays as $array)
			{
				if(isset($array[$textField]) && isset($array[$valueField]))
					$listData[$array[$valueField]]=$array[$textField];
			}
		}
		return $listData;
	}
	
	/**
	* 批量验证只返回是否错误 不返回错误原因
	* @param unknown $models
	* @return boolean
	*/
	public function models_validate($models)
	{
		$validate=array();
		$this->set_attributes($models);
		foreach ($models as $model)
		{
			if($model->validate())
				$validate[]=true;
		}
		return count($models)==count($validate);
	}
	
	/**
	 * 设置有多少个模型
	 * @param unknown $modelName
 	 * @param unknown $max
	 * @param string $add
	 * @param string $cut
	 * @return number
	 */
	public function set_number($modelName,$max,$add='add',$cut='cut'){
		$number=count($_POST[$modelName]);
		if(isset($_POST[$add]))
			$number += 1;
		elseif(isset($_POST[$cut]) && count($_POST[$modelName]) > 1)
			$number -=1;
		if($number > Yii::app()->params['items_fare_number'])
				$number=Yii::app()->params['items_fare_number'];
		return $number;
	}
	
	/**
	* 后台显示图片
	* @param unknown $path
	* @param string $type
	* @param string $alt
	* @param unknown $params
	* @return string
	*/
	public function show_img($path,$type='',$alt='',$params=array(),$click=true)
	{
		if($click)
		{
			$this->showOriginal('show_img_'.$this->imgShowId,CHtml::image($path,$path,array(
				'style'=>'min-width:80%;'
			)));			
			if(isset($params['onclick']))
				$params['onclick'] .= '$("#show_img_'.$this->imgShowId++.'").dialog("open"); return false;';
			else
				$params['onclick'] = '$("#show_img_'.$this->imgShowId++.'").dialog("open"); return false;';
			if(isset($params['style']))
				$params['style'] .= 'cursor:pointer;';
			else 
				$params['style'] = 'cursor:pointer;';
		}
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
	 * 显示原来的图片
	 * @param unknown $id
	 * @param unknown $text
	 */
	public function showOriginal($id,$text)
	{
		ob_start();
		$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
				'id'=>$id,
				'htmlOptions'=>array('style'=>'text-align:center;'),
				'options'=>array(
						'title'=>'查看大图',
						'autoOpen'=>false,//是否自动打开
						'width'=>'75%',//宽度
						'height'=>'auto',//高度
						'modal' => true,
				),
		));
		echo $text;
		$this->endWidget();
		$this->text .= ob_get_contents();
		ob_end_clean();
	}
	
	/**
	 * 加载模型数据
	 * @param unknown $id
	 * @param string $condition
	 * @param unknown $params
	 * @param string $model
	 * @throws CHttpException
	 * @return unknown
	 */
	public function loadModel($id,$condition='',$params=array())
	{
		$model=$this->_class_model;
		if($model=='')
			throw new CHttpException(404,'没有设置当前的数据模型！');
		$data=$model::model()->findByPk($id,$condition,$params);
		if($data===null)
			throw new CHttpException(404,'没有找到相关的数据！.');
		return $data;
	}

	/**
	 * 加载模型数据
	 * @param unknown $id
	 * @param string $condition
	 * @param unknown $params
	 * @param string $model
	 * @throws CHttpException
	 * @return unknown
	 */
	public function loadModelAll($condition='',$params=array())
	{
		$model=$this->_class_model;
		if($model=='')
			throw new CHttpException(404,'没有设置当前的数据模型！');
		$data=$model::model()->findAll($condition,$params);
		if($data===null)
			throw new CHttpException(404,'没有找到相关的数据！.');
		return $data;
	}
}