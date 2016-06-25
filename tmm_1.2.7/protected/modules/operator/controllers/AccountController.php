<?php
/**
 * 我的钱包
 * Class AccountController
 *
 * @author Moore Mo
 */
class AccountController extends OperatorMainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model = 'Account';

	/**
	 * 我的钱包--查看详情
	 */
	public function actionView()
	{
		$this->addJs(Yii::app()->baseUrl . '/css/operator/main/right/account/probarBox.js');
		$this->render('view', array(
			'model' => Account::getAccount(Yii::app()->operator->id, Account::agent, Account::money_type_rmb),
		));
	}
}
