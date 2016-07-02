<?php
/**
 * 广告接口文档
 * @author Changhai Zhan
 *
 */
class AdController extends ApiController
{
	/**
	 * 返回广告专题列表
	 */
	public function actionIndex($type = Ad::type_banner)
	{
		if (isset(Ad::$_type[$type]) && ($type == Ad::type_banner || $type == Ad::type_hot))
		{
			$return = array();
			//条件
			$criteria = new CDbCriteria;
			//标准
			$criteria->addColumnCondition(array(
					'`t`.`p_id`'=>0,												//广告专题
					'`t`.`type`'=>$type,										//广告专题类型
					'`t`.`status`'=>Ad::status_suc,						//广告专题上线
			));
			//排序
			$criteria->order = '`t`.`sort`,`t`.`up_time` desc,`t`.`add_time` desc';
			//统计
			$count = Ad::model()->count($criteria);
			//分页设置
			$return['page'] = $this->page($criteria, $count, Yii::app()->params['api_pageSize']['p_ad'], Yii::app()->params['app_api_domain']);
			//查询
			$models = Ad::model()->findAll($criteria);
			//数据列表
			$return['list_data'] = $this->index_data($models, Yii::app()->params['app_api_domain']);
			//空
			if (empty($return['list_data']))
			{
				$return['list_data'] = array();
				$return['null'] = '小觅已经很努力了！';
			}
			$this->send($return);
		}
		$this->send_error(DATA_NULL);
	}
	
	/**
	 * 广告数据处理函数
	 * @param unknown $models
	 * @param unknown $domain
	 * @return multitype:multitype:string NULL multitype:multitype: NULL  multitype:NULL
	 */
	public function index_data($models, $domain)
	{
		$return = array();
		foreach ($models as $model)
		{
			$img = $this->litimg_path($model->img);
			$return[] = array(
					'name'=>CHtml::encode($model->name),
					'info'=>CHtml::encode($model->info),
					'link'=>$domain . Yii::app()->createUrl('/api/ad/list', array('id'=>$model->id)),
					'value'=>$model->id,
					'sort'=>$model->sort,
					'img'=>Yii::app()->params['admin_img_domain'] . ltrim($img, '.'),
					'type'=>array(
							'name'=>Ad::$_type[$model->type],
							'value'=>$model->type,
					),
			);
		}
		return $return;
	}
	
	/**
	 * 专题下广告列表
	 * @param unknown $id
	 */
	public function actionList($id='', $type=Ad::type_banner)
	{
		//输出数据
		$return = array();
		//条件
		$criteria = new CDbCriteria;
		//关系
		$criteria->with = array(
			'Ad_Type',
			'Ad_Ad',
		);
		//限制类型 横幅 热销
		$criteria->addCondition('`Ad_Ad`.`type`=:type_banner OR `Ad_Ad`.`type`=:type_hot');
		$criteria->params[':type_banner'] = Ad::type_banner;
		$criteria->params[':type_hot'] = Ad::type_hot;
		//条件
		if ($id == '')
		{
			if ($type == Ad::type_banner || $type == Ad::type_hot)	
				$criteria->addColumnCondition(array(
						'`Ad_Ad`.`id`'=>Ad::model()->getFirstPId($type),
				));
			else 
				$this->send_error(DATA_NULL);
		}
		else
		{
			$criteria->addColumnCondition(array(
					'`Ad_Ad`.`id`'=>$id,
			));
		}
		//条件
		$criteria->addColumnCondition(array(
				'`t`.`status`'=>Ad::status_suc,
				'`Ad_Ad`.`status`'=>Ad::status_suc,
				'`Ad_Type`.`status`'=>Type::status_suc,
		));
		//排序
		$criteria->order = '`t`.`sort`,`t`.`up_time` desc,`t`.`add_time` desc';
		//统计
		$count = Ad::model()->count($criteria);
		//分页设置
		$return['page'] = $this->page($criteria, $count, Yii::app()->params['api_pageSize']['ad'], Yii::app()->params['app_api_domain']);
		//查询
		$models = Ad::model()->findAll($criteria);
		//数据列表
		$return['list_data'] = $this->list_data($models, Yii::app()->params['app_api_domain']);
		//空
		if (empty($return['list_data']))
		{
			$return['list_data'] = array();
			$return['null'] = '小觅已经很努力了！';
		}
		$this->send($return);
	}
	
	/**
	 * 列表页数据处理
	 * @param unknown $models
	 * @param unknown $domain
	 * @return multitype:multitype:string NULL multitype:multitype: NULL  multitype:NULL
	 */
	public function list_data($models, $domain)
	{
		$return = array();
		foreach ($models as $model)
		{
			$img = $this->litimg_path($model->img);
			$return[] = array(
					'name'=>CHtml::encode($model->name),
					'info'=>CHtml::encode($model->info),
					'link'=>$model->link,
					'value'=>$model->id,
					'sort'=>$model->sort,
					'img'=>Yii::app()->params['admin_img_domain'] . ltrim($img, '.'),
					'parent'=>array(
							'id'=>$model->Ad_Ad->id,
							'name'=>CHtml::encode($model->Ad_Ad->name),
							'info'=>$model->Ad_Ad->info,
					),
					'link_type'=>array(
							'name'=>CHtml::encode($model->Ad_Type->name),
							'info'=>CHtml::encode($model->Ad_Type->info),
							'value'=>$model->link_type,
					),
					'type'=>array(
							'name'=>Ad::$_type[$model->type],
							'value'=>$model->type,
					),
			);
		}
		return $return;
	}
}