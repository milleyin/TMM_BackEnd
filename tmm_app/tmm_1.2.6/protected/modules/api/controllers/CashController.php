<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-10-29 14:09:40 */
class CashController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Cash';

	/**
	 * 提现申请记录列表
	 */
	public function actionIndex()
	{
		$model_user = $this->verify_login();

		$return = Cash::cash_index(Yii::app()->api->id,Cash::cash_type_user);
		$this->send($return);
	}

	/**
	 * 添加(申请)提现
	 * 2015-12-22 改逻辑
	 * 1. 短信验证不管对错都不可以在用
	 * 2. 先验证账号是否可以申请的现
	 */
	public function actionCreate(){

		$model_user = $this->verify_login();

		$model = new Cash;

		//  验证帐是否可以申请
		$cash_result = $this->validate_cash($model);
		if (!$cash_result)
			$this->send_error_form($this->form_error($model));


		if (isset($_POST['Cash']) && is_numeric($_POST['Cash']['price']) && $_POST['Cash']['sms']) {

			$model = new Cash;
			$model->scenario = 'user_create';
			$model->attributes=array('phone'=>$model_user->phone,'sms'=>$_POST['Cash']['sms'],'price'=>$_POST['Cash']['price']);

			if ($model->verifycode_cash_user($model_user->id) && !$model->hasErrors() && $model->validate()) {
					//开启事物
					$transaction = Yii::app()->db->beginTransaction();
					try
					{
						//$model->unsetAttributes();
						// 1. 验证是否已绑定银行帐号
						$bank_info = $this->validate_bank($model);
						if (!$bank_info)
							throw new Exception("用户提现-错误-未绑定银行帐号");

						// 2. 验证帐是否可以申请
						$cash_result = $this->validate_cash($model);
						if ($cash_result == false) {
							throw new Exception("用户提现-错误-还有没处理完的提现申请");
						}
						// 3. 验证帐单金额
						$bills_result = $this->validate_account($model,$_POST['Cash']['price']);

						if ($bills_result == false) {
							throw new Exception("用户提现-错误-提取金额不足");
						}

						// 验证成功成功后
						$model->cash_type	 	= Cash::cash_type_user;	    //用户
						$model->cash_id 		= Yii::app()->api->id;			//用户ID
						$model->cash_status 	= Cash::cash_status_cashing;	//提现状态 待提现
						$model->audit_status 	= Cash::audit_status_first;	//审核状态 待初审
						$model->money 			= $_POST['Cash']['price'];	//可提现金额
						$model->price 			= $_POST['Cash']['price'];	//提现金额
						$model->bank_card_id 	= $bank_info->id;				//银行卡管理ID
						$model->bank_id 		= $bank_info->bank_id;			//银行类型ID
						$model->bank_name 		= $bank_info->bank_name;		//开户姓名
						$model->bank_branch 	= $bank_info->bank_branch;		//开户支行
						$model->bank_code 		= $bank_info->bank_code;		//开户银行账号
						$model->bank_type 		= $bank_info->bank_type;		//用户银行类型
						$model->bank_identity = $bank_info->bank_identity;	//开户身份证

						if (! $model->save(false)) {
							throw new Exception("商家提现-错误-添加提现记录失败");
						}

						if (!$this->update_cash_account($model)) {
							throw new Exception("商家提现-错误-冻结提现金额失败");
						}

						$return = $this->log('商家提现', ManageLog::user, ManageLog::create);


						$transaction->commit();
					}
					catch (Exception $e)
					{
						$transaction->rollBack();
						$this->error_log($e->getMessage(), ErrorLog::user, ErrorLog::create, ErrorLog::rollback, __METHOD__);
					}

				} else {
					$this->send_error_form($this->form_error($model));
				}


			if (isset($return)) {
				//成功
				$return=array(
					'status'=>STATUS_SUCCESS,
				);
				$this->send($return);
			} else {
				$this->send_error_form($this->form_error($model));
			}
		}

		$this->send_csrf();

	}

	/**
	 * 插一步，验证金额
	 */
	public function actionCash_verify_price(){

		$model_user = $this->verify_login();

		$model = new Cash;

		//  验证帐是否可以申请
		$cash_result = $this->validate_cash($model);
		if (!$cash_result)
			$this->send_error_form($this->form_error($model));


		if (isset($_POST['Cash']) && is_numeric($_POST['Cash']['price'])) {

			$model = new Cash;
			$model->scenario = 'cash_price';
			$model->attributes=array('price'=>$_POST['Cash']['price']);

			if ( !$model->hasErrors() && $model->validate()) {

				try
				{
					//$model->unsetAttributes();
					// 1. 验证是否已绑定银行帐号
					$bank_info = $this->validate_bank($model);
					if (!$bank_info)
						throw new Exception("用户提现-错误-未绑定银行帐号");

					// 2. 验证帐是否可以申请
					$cash_result = $this->validate_cash($model);

					if ($cash_result == false) {
						throw new Exception("用户提现-错误-还有没处理完的提现申请");
					}
					// 3. 验证帐单金额
					$bills_result = $this->validate_account($model,$_POST['Cash']['price']);
					if ($bills_result == false) {
						throw new Exception("用户提现-错误-提取金额不足");
					}

					$return = true;

				}
				catch (Exception $e)
				{
					$this->error_log($e->getMessage(), ErrorLog::user, ErrorLog::create, ErrorLog::rollback, __METHOD__);
				}

			} else {
				$this->send_error_form($this->form_error($model));
			}


			if (isset($return)) {
				//成功
				$return=array(
					'status'=>STATUS_SUCCESS,
				);
				$this->send($return);
			} else {
				$this->send_error_form($this->form_error($model));
			}
		}

		$this->send_csrf();

	}

	/**
	 * 发送短信=====提取
	 */
	public function actionCaptcha_cash()
	{
		$model_user = $this->verify_login();

		$model_cash = new Cash;
		$bank_info = $this->validate_bank($model_cash);

		if(! $bank_info)
			$this->send_error(DATA_NOT_SCUSSECS);

		if ($bank_info->bank_id && $bank_info->bank_name && $bank_info->bank_branch && $bank_info->bank_code)
		{
			if($model_user->phone)
			{
				$model = new SmsApiLoginForm;
				$model->scenario = 'cash_phone';
				$model->attributes = array('phone'=>$model_user->phone);
				if($model->validate())
				{
					//验证
					if($model->user_cash_phone_sms(Yii::app()->api->id))
					{
						//成功
						$return=array(
							'status'=>STATUS_SUCCESS,
						);
						$this->send($return);

					}else
						$this->send_error_form($model->get_error());
				}else
					$this->send_error_form($model->get_error());
			}
			else
			{
				$this->send_error(DATA_NOT_SCUSSECS);
			}
		}
		else
		{
			$this->send_error(DATA_NOT_SCUSSECS);
		}
	}

	/**
	 * 提现时验证是否绑定银行========普通用户
	 * @param $model
	 * @return array|bool|mixed|null
	 */
	public function validate_bank($model)
	{
		$user_model = $this->get_user_bank_info();

		if( ! (isset($user_model->User_BankCard) && is_array($user_model->User_BankCard) && isset($user_model->User_BankCard[0]) && $user_model->User_BankCard[0]) ){
				$model->addError('msg','未绑定银行帐号');
				$model->addError('status','001');
				return false;
		}

		return $user_model->User_BankCard[0];
	}
	/**
	 * 获得用户银行卡相关信息======普通用户
	 */
	private function get_user_bank_info(){
		$this->_class_model = 'User';
		$user_model = $this->loadModel(Yii::app()->api->id,array(
			'with'=>array(
				'User_BankCard'
			),
			'condition'=>'t.status=:status and User_BankCard.status=:bank_status and User_BankCard.card_type=:card_type and User_BankCard.card_id=:card_id',
			'params'=>array(':status'=>1,':bank_status'=>1,':card_type'=>BankCard::user,':card_id'=>Yii::app()->api->id),
			'order'=>'User_BankCard.id,User_BankCard.is_default desc'
		));
		return $user_model;
	}

	/**
	 * 提现时验证钱包
	 * @param $model
	 * @param $total_money
	 * @return bool
	 */
	private function validate_account($model, $total_money='')
	{
		$account_model = Account::getAccount(Yii::app()->api->id,Account::user);

		if ($account_model->money <=0)
		{
			$model->addError('msg', '提现金额不足');
			$model->addError('status', '002');
			return false;
		}
		$price = bccomp($account_model->money, $total_money,3);

		if (! ($price ==0 || $price==1) )
		{
			//throw new Exception("商家提现-错误-提取金额不足");
			$model->addError('msg', '金额数目出错');
			$model->addError('status', '003');
			return false;
		}
		return array(
			'total_money' => $account_model->money,
		);
	}

	/**
	 * 提现时验证是否还有提现申请
	 * @param $model
	 * @return bool
	 */
	private function validate_cash($model)
	{
		$this->_class_model = 'Cash';
		$criteria_cash = new CDbCriteria;
		$criteria_cash->addCondition('`status`=1 AND `cash_type`=:cash_type AND `cash_id`=:cash_id AND (`audit_status`=:audit_status_first OR `audit_status`=:audit_status_double OR `audit_status`=:audit_status_submit)');
		$criteria_cash->params[':cash_type'] = Cash::cash_type_user;
		$criteria_cash->params[':cash_id'] = Yii::app()->api->id;
		$criteria_cash->params[':audit_status_first'] = Cash::audit_status_first;
		$criteria_cash->params[':audit_status_double'] = Cash::audit_status_double;
		$criteria_cash->params[':audit_status_submit'] = Cash::audit_status_submit;
		$cash_model = Cash::model()->find($criteria_cash);
		if (! empty($cash_model))
		{
			// throw new Exception("商家提现-错误-还有没处理完的提现申请");
			$model->addError('msg','还有没处理完的提现申请');
			$model->addError('status','004');
			return false;
		}
		return true;
	}



	/**
	 * 提现时，冻结金额
	 * @param $model
	 * @return bool
	 * @throws CDbException
	 */
	private function update_cash_account($model)
	{
		$result=Account::moneyPendingCashFrozenRmb(
			$model->price,
			array('account_id'=>Yii::app()->api->id,'account_type'=>Account::user),
			array('info_id'=>$model->id,'info'=>'提现申请','name'=>'提现申请','address'=>'')
		);

		if (! $result)
		{
			$model->addError('msg','提现失败');
			$model->addError('status','005');
			return false;
			//throw new Exception("商家提现-错误-冻结提现金额失败");
		}
		return true;
	}


}
