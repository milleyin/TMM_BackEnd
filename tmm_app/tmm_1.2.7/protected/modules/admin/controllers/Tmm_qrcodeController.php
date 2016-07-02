<?php
/**
 * 二维码
 * @author Changhai Zhan
 *
 */
class Tmm_qrcodeController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Software';
	/**
	 * 安卓类型
	 * @var unknown
	 */
	public $_android='Android';
	/**
	 * ios 类型
	 * @var unknown
	 */
	public $_ios='IOS';
	/**
	 * WeiXin 类型
	 * @var unknown
	 */
 	public $_weixin='WeiXin';
	/**
	 * ios 类型
	 * @var unknown
	 */
	public $_windows='Windows NT';
	/**
	 * 链接
	 * @var unknown
	 */
	public $_weixin_link='http://wx.365tmm.com';
	/**
	 * 用户 ios
	 * @var unknown
	 */
	public $_user_ios_link='https://itunes.apple.com/us/app/tian-mi-mi/id1042164581?mt=8';
	/**
	 * 供应商 ios
	 * @var unknown
	 */
	public $_store_ios_link='https://itunes.apple.com/us/app/tian-mi-mi-shang-jia-duan/id1043412617?mt=8';
	/**
	 * 用户 安卓&IOS
	 * @var unknown
	 */
	public $_weixin_user_link='http://a.app.qq.com/o/simple.jsp?pkgname=com.qljl.tmm&g_f=991653';
	/**
	 * 供应商 安卓&IOS
	 * @var unknown
	 */
	public $_weixin_store_link='http://a.app.qq.com/o/simple.jsp?pkgname=com.qljl.tmm_business&g_f=991653';
	
	/**
	 * 用户二维码
	 */
	public function actionUser()
	{		
		$type=$this->type();		
		if($type==$this->_ios)
		{
			$this->redirect($this->_user_ios_link);
		}
		elseif($type==$this->_android)
		{
			$criteria= new CDbCriteria;
			$criteria->addColumnCondition(array(
					'type'=>Software::type_user,
					'`use`'=>Software::use_apk,
			));
			$criteria->order='`version` desc';
			$model=Software::model()->find($criteria);
			if($model)
				$this->download($model->id, 'TMM_USER');		
		}
		elseif($type==$this->_weixin)
			$this->redirect($this->_weixin_user_link);
			//$this->weixin_user();
		elseif($type==$this->_windows)
			$this->redirect($this->_weixin_user_link);
		//	$this->weixin_user();
	}

	/**
	 * 供应商二维码
	 */
	public function actionStore()
	{
		$type=$this->type();		
		if($type==$this->_ios)
		{
			$this->redirect($this->_store_ios_link);	
		}
		elseif($type==$this->_android)
		{
			$criteria= new CDbCriteria;
			$criteria->addColumnCondition(array(
					'type'=>Software::type_store,
					'`use`'=>Software::use_apk,
			));
			$criteria->order='`version` desc';
			$model=Software::model()->find($criteria);
			if($model)
				$this->download($model->id, 'TMM_STORE');				
		}
		elseif($type==$this->_weixin)
			$this->redirect($this->_weixin_store_link);
		elseif($type==$this->_windows)
			$this->redirect($this->_weixin_store_link);
	}
	
	/**
	 * PAD展示屏(Android)
	 */
	public function actionPad()
	{
		$criteria= new CDbCriteria;
		$criteria->addColumnCondition(array(
				'type'=>Software::type_pad,
				'`use`'=>Software::use_apk,
		));
		$criteria->order='`version` desc';
		$model=Software::model()->find($criteria);
		if($model)
 			$this->download($model->id, 'TMM_PAD');
		else 
			echo '没有上传';
	}
	
	/**
	 * 微信下载页
	 */
	public function weixin_user()
	{
		$criteria= new CDbCriteria;
		$criteria->addColumnCondition(array(
				'type'=>Software::type_user,
				'`use`'=>Software::use_apk,
		));
		$criteria->order='`version` desc';
		$model=Software::model()->find($criteria);
		if($model)
			$this->renderPartial('download',array(
					'type'=>'田觅觅',
					'ios'=>$this->_user_ios_link,
					'android'=>array('/admin/tmm_software/download','id'=>$model->id),
			));
	}
	
	/**
	 * 微信下载页
	 */
	public function weixin_store()
	{
		$criteria= new CDbCriteria;
		$criteria->addColumnCondition(array(
				'type'=>Software::type_store,
				'`use`'=>Software::use_apk,
		));
		$criteria->order='`version` desc';
		$model=Software::model()->find($criteria);
		if($model)
			$this->renderPartial('download',array(
					'type'=>'田觅觅供应商端',
					'ios'=>$this->_store_ios_link,
					'android'=>array('/admin/tmm_software/download','id'=>$model->id),
			));
	}
	
	/**
	 * 下载
	 * @param unknown $id
	 */
	public function download($id,$name)
	{
		$model=$this->loadModel($id);
		if($this->file_exists_uploads($model->file_path))
		{
			$return=Yii::app()->request->sendFile($name .$model->version_name. $model->dow_url,$model->file_path,null,false,true);
			if($return)
				Software::model()->updateByPk($id, array(
					'dow_count'=>new CDbExpression('`dow_count`+1'),
				));
		}
	}

	/**
	 * 获取 安卓 IOS 微信
	 * @return unknown|boolean
	 */
	public function type()
	{
		if(isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'])
		{
			$server='tmm_'.strtolower($_SERVER['HTTP_USER_AGENT']);//添加前缀 避免为0
			if(strrpos($server,'micromessenger'))
				return $this->_weixin;
			elseif(strrpos($server,'iphone') || strrpos($server, 'ipad') || strrpos($server, 'ipod'))
				return $this->_ios;
			elseif(strrpos($server,'android'))
				return $this->_android;
			elseif(strrpos($server,'windows nt'))
				return $this->_windows;
		}
		return false;
	}
}