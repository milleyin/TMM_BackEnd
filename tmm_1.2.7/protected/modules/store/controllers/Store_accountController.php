<?php
/**
 * 商家收入控制器
 * @author Moore Mo
 * Class Store_accountController
 */
class Store_accountController extends StoreMainController
{
	/**
	 * 设置当前操作数据模型
	 * @var string
	 */
	public $_class_model = 'Account';

	/**
	 * 商家账务情况
	 */
	public function actionView()
	{
		$this->_class_model = 'StoreUser';
		$store_model = $this->loadModel(Yii::app()->store->id, array(
			'with'=> array(
				'Store_Content' => array('select'=>'deposit'),
			),
			'condition'=>'`t`.`status`=1 AND `t`.`p_id`=0',
		));
//
//		$this->_class_model = 'Account';
//		$criteria = new CDbCriteria;
//		$criteria->addCondition('`status`=1 AND `account_type`=:account_type AND `account_id`=:account_id');
//		$criteria->params[':account_type'] = Account::store;
//		$criteria->params[':account_id'] = Yii::app()->store->id;
//
//		$account_model = Account::model()->find($criteria);
//		if (empty($account_model)) {
//			$this->send_error(DATA_NULL);
//		}
		$account_model = Account::getAccount(Yii::app()->store->id,Account::store,Account::money_type_rmb);
		// 收入总额
		$return['total']				= $account_model->total;
		$return['total_format'] 		= $this->number_format($account_model->total);
		// 可提现金额
		$return['money'] 				= $account_model->money;
		$return['money_format'] 		= $this->number_format($account_model->money);
		// 已提现金额
		$return['cash_count'] 		= $account_model->cash_count;
		$return['cash_count_format'] = $this->number_format($account_model->cash_count);
		// 剩余保证金
		$return['deposit']				= $store_model->Store_Content->deposit;
		$return['deposit_format'] 	= $this->number_format($store_model->Store_Content->deposit);

		$this->send($return);
	}
}