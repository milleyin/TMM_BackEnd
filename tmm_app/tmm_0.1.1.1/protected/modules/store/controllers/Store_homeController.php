<?php

class Store_homeController extends StoreMainController
{
	/**
	 * 设置当前操作数据模型
	 * @var string
	 */
	public $_class_model='StoreUser';

	public function actionIndex()
	{
		if(!Yii::app()->store->isGuest)
		{
			$return = array();
			$datas = array(
				'phone',
				'count',
				'last_ip',
			);

			$model = $this->loadModel(Yii::app()->store->id,array(
				'with'=>array('Store_Content'),
				'condition'=>'t.status=1'
			));
			$return['value'] = $model->id;
			if (!empty($model->Store_Content)) {
				$return['name'] = $model->Store_Content->name;
			} else {
				if($model->p_id !=0 )
				{
					$model = $this->loadModel(Yii::app()->store->id, array(
						'with' => array(
							'Store_Store' => array('with' => 'Store_Content', 'condition' => 'Store_Store.status=1'),
						),
						'condition' => 't.status=1'
					));
				}
				$return['name'] = $model->Store_Store->Store_Content->name;
			}

			foreach($datas as $data)
				$return[$data] = $model->$data;

			if ($model->p_id) {
				$return['is_main'] = false;
				$return['link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/store/store_store/son_view',array('id'=>$model->id));
			} else {
				$return['is_main'] = true;
				$return['link'] = Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/store/store_store/view');
			}
			$return['add_time'] = Yii::app()->format->datetime($model->add_time);
			$return['last_time'] = Yii::app()->format->datetime($model->last_time);

			$this->send($return, array('store/store_home/index'));
		}else
			$this->send_error(Yii::app()->params['Store_LOGIN_REQUIRED']);
	}

	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}