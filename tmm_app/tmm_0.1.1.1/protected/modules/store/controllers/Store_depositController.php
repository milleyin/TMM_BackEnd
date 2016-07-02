<?php
/**
 * 商家保证金控制器
 * @author Moore Mo
 * Class Store_depositController
 */
class Store_depositController extends StoreMainController
{
	/**
	 * 设置当前操作数据模型
	 * @var string
	 */
	public $_class_model = 'DepositLog';

	/**
	 * 保证金记录列表
	 */
	public function actionIndex()
	{
		$this->_class_model = 'StoreUser';
		$store_model = $this->loadModel(Yii::app()->store->id, array(
			'with'=> array(
				'Store_Content' => array('select'=>'deposit'),
			),
			'condition'=>'`t`.`status`=1 AND `t`.`p_id`=0',
		));

		$this->_class_model = 'DepositLog';
		$criteria = new CDbCriteria;
		$criteria->addCondition('`status`=1 AND `deposit_who`=:deposit_who AND `deposit_id`=:deposit_id');
		$criteria->params[':deposit_who'] = DepositLog::deposit_store;
		$criteria->params[':deposit_id'] = Yii::app()->store->id;
		$criteria->order = 'add_time desc';
		$count = DepositLog::model()->count($criteria);

		$return = array();

		//分页设置
		$return['page'] = $this->page($criteria, $count, Yii::app()->params['api_pageSize']['deposit_log'], Yii::app()->params['app_api_domain']);
		//根据条件查询
		$model = DepositLog::model()->findAll($criteria);

		$return['deposit'] = $store_model->Store_Content->deposit;
		foreach($model as $log) 
		{
			$return['list_data'][] = array(
				'deposit' => $log->deposit,
				'deposit_old'=>$log->deposit_old,
				'format_deposit'=>($log->deposit_status == DepositLog::deposit_status_add ? '+' : '-').number_format($log->deposit,2),
				'deposit_status'=> array(
					'value' => $log->deposit_status,
					'name' => DepositLog::$_deposit_status[$log->deposit_status],
				),
				'reason' => CHtml::encode($log->reason),
				'add_time' => date('Y/m/d',$log->add_time),
			);
		}

		if(empty($return['list_data']))
		{
			$return['list_data']=array();
			$return['null']='暂无数据！';
		}

		$this->send($return);
	}
}