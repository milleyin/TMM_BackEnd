<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-12-09 16:45:12 */
class FarmOuterController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='FarmOuter';

// 	/**
// 	 * 查看详情
// 	 * @param integer $id
// 	 */
// 	public function actionView($id)
// 	{
// 		$this->render('view',array(
// 			'model'=>$this->loadModel($id),
// 		));
// 	}

	/**
	 * 分享链接
	 * @param unknown $url
	 */
 	public function actionShare($url)
 	{
 		// 去除 前面 http:// 或者 https://  去除 ? 后面的
 		list($url) = explode( '?', ltrim(ltrim($url ,'http://'),'https://'));
 		
 		$criteria=new CDbCriteria;
 		$criteria->addColumnCondition(array(
 			'status'=>FarmOuter::status_online,
 		));
 		$criteria->addSearchCondition('link', $url);	
 		$model = FarmOuter::model()->find($criteria);
 		if($model)
 		{
 			$return = array();
 			$return['name']=$model->name;
 			$return['info']=$model->info;
 			$return['link']=$model->link;
 			//获取小图片 如果没有则用手机图片 以上不满足 取原图
 			$share_img=$this->litimg_path($model->img,'litimg_share',$this->litimg_path($model->img));		
 			$return['image']=Yii::app()->params['admin_img_domain'].ltrim($share_img,'.');
 			$this->send($return);
 		}
 		$this->send_error(DATA_NULL);
 	}

	
}
