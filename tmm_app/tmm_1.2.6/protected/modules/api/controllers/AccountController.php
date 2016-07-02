<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-29 14:09:17 */
class AccountController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Account';

	/**
	 * 我的钱包
	 */
	public function actionMy_burse(){

		$model_user = $this->verify_login();

//		$criteria = new CDbCriteria;
//		$criteria->addCondition('`status`=1 AND `account_type`=:account_type AND `account_id`=:account_id');
		//是否是组织者   2015-11-19  组织者和用户类型是一样
//		if($model_user->is_organizer == 1)
//			$criteria->params[':account_type'] = Account::organizer;
//		else
//			$criteria->params[':account_type'] = Account::user;
//
//
//		$criteria->params[':account_id'] = Yii::app()->api->id;
//
//		$account_model = Account::model()->find($criteria);
//		if (empty($account_model)) {
//			$this->send_error(DATA_NULL);
//		}

		$account_model = Account::getAccount(Yii::app()->api->id,Account::user,Account::money_type_rmb);

		// 收入总额
		$return['total'] 				= $account_model->total;
		$return['total_format'] 		= $this->number_format($account_model->total);
		// 可提现金额
		$return['money'] 				= $account_model->money;
		$return['money_format'] 		= $this->number_format($account_model->money);
		// 已提现金额
		$return['cash_count'] 		= $account_model->cash_count;
		$return['cash_count_format']	= $this->number_format($account_model->cash_count);
		// 冻结金额
		$return['no_money'] 			= $account_model->no_money;
		$return['no_money_format'] 	= $this->number_format($account_model->no_money);

		$this->send($return);

	}

}
