<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-12-14 14:50:53 */
class BankCardController extends ApiController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='BankCard';

	/**
	 * 银行卡======查看
	 * @param $id
	 */
	public function actionView($id){
		$model = $this->loadModel($id,'status=1');

		if(!$model)
			$this->send_error(DATA_NULL);

		$return = BankCard::get_field_data($model);

		$this->send($return);
	}
	/**
	 * 绑定银行卡====查看list
	 */
	public function actionView_list(){

		$this->_class_model = 'User';
		$model = $this->loadModel(Yii::app()->api->id,array(
			'with'=>array(
				'User_BankCard'
			),
			'condition'=>'t.status=:status and User_BankCard.status=:bank_status and User_BankCard.card_type=:card_type and User_BankCard.card_id=:card_id',
			'params'=>array(':status'=>1,':bank_status'=>1,':card_type'=>BankCard::user,':card_id'=>Yii::app()->api->id),
			'order'=>'User_BankCard.id,User_BankCard.is_default desc'
		));

		if(!$model)
			$this->send_error(DATA_NULL);

		$return  = array();
		foreach($model->User_BankCard as $v){
			$return[] = BankCard::get_field_data($v);
		}
		$this->send($return);
	}
	/**
	 * 绑定银行卡====创建
	 */
	public function actionCreate(){

		$this->_class_model = 'User';
		$id = Yii::app()->api->id;
		$model = $this->loadModel($id, '`status`=1');


		if( isset($_POST['BankCard']) )
		{
				//验证银行卡ID
				if (! Bank::model()->find('`id`=:id',array(':id'=>$_POST['BankCard']['bank_id']))) {
					$this->send_error(DATA_NOT_SCUSSECS);
				}
				//验证银行卡银行
				$model->User_BankCard = new BankCard();
				$model->User_BankCard->scenario='user_bank';
				$model->User_BankCard->attributes = $_POST['BankCard'];

			    //验证信息
				if ($model->User_BankCard->validate())
				{

					//保存session
					Yii::app()->session['create_User_BankCard'.Yii::app()->api->id] = $_POST['BankCard'];

					//成功
					$return = array(
						'status' => STATUS_SUCCESS,
					);
					$this->send($return);

				}else
					$this->send_error_form($this->form_error($model->User_BankCard));
		}else
			$this->send_csrf();

	}

	/**
	 * 验证短信后保存
	 */
	public function actionBankcard_sms(){

		$this->_class_model = 'User';
		$id = Yii::app()->api->id;
		$model = $this->loadModel($id, '`status`=1');

		$model->scenario='user_bank';

		if(isset($_POST['User']) &&  isset($_POST['User']['sms']))
		{
			if(! (isset(Yii::app()->session['create_User_BankCard'.Yii::app()->api->id]) && Yii::app()->session['create_User_BankCard'.Yii::app()->api->id]) )
				$this->send_error(DATA_NOT_SCUSSECS);

			$model->attributes=array('phone'=>$model->phone,'sms'=>$_POST['User']['sms']);
			//验证====验证码是否正确
			if($model->validate() &&  $model->verifycode_bank())
			{
				//验证银行卡银行
				$model->User_BankCard = new BankCard();
				$model->User_BankCard->scenario='user_bank';
				$model->User_BankCard->attributes = Yii::app()->session['create_User_BankCard'.Yii::app()->api->id];

				if ($model->User_BankCard->validate())
				{
					//开启事物
					$transaction = Yii::app()->db->beginTransaction();
					try {
						//查看是否有卡号已存在    存在设置为已删除
						if (!$this->bank_code_val())
							throw new Exception("更新/设置用户的银行失败");

						$model->User_BankCard->manage_id = $id;
						$model->User_BankCard->manage_who = BankCard::user;
						$model->User_BankCard->card_type = BankCard::user;
						$model->User_BankCard->card_id = $id;
						$set_card = BankCard::set_card_type_id(BankCard::user);
						$model->User_BankCard->$set_card = $id;

						if ($model->User_BankCard->save(false) && $this->log('更新/设置用户的银行信息', ManageLog::user, ManageLog::update)) {
							//session 设置为 空
							Yii::app()->session['create_User_BankCard' . Yii::app()->api->id] = '';

						}else
							throw new Exception("设置用户的银行失败");

						$return = true;

						$transaction->commit();

					}catch (Exception $e)
					{
						$transaction->rollBack();
						$this->error_log($e->getMessage(), ErrorLog::user, ErrorLog::create, ErrorLog::rollback, __METHOD__);
					}

					if (isset($return)) {
						//成功
						$return=array(
							'status'=>STATUS_SUCCESS,
						);
						$this->send($return);
					} else {
						$this->send_error_form($this->form_error($model->User_BankCard));
					}

				}else
					$this->send_error_form($this->form_error($model->User_BankCard));
			}else
				$this->send_error_form($this->form_error($model));
		}else
			$this->send_csrf();

	}
	/**
	 * 绑定银行卡====更机关报
	 */
	public function actionUpdate($id){

		$this->_class_model = 'User';
		$model = $this->loadModel(Yii::app()->api->id, '`status`=1');

		$model->scenario='user_bank';

		if( isset($_POST['BankCard']) )
		{
				//验证银行卡ID
				if (! Bank::model()->find('`id`=:id',array(':id'=>$_POST['BankCard']['bank_id']))) {
					$this->send_error(DATA_NOT_SCUSSECS);
				}

				$this->_class_model = 'BankCard';
				//验证银行卡银行
				$model->User_BankCard = $this->loadModel($id, '`status`=1');
				$model->User_BankCard->scenario='user_bank';
				$model->User_BankCard->attributes = $_POST['BankCard'];

				if ($model->User_BankCard->validate())
				{

					//保存session
					Yii::app()->session['create_User_BankCard'.Yii::app()->api->id]= $_POST['BankCard'];

					//成功
					$return = array(
						'status' => STATUS_SUCCESS,
					);
					$this->send($return);
				}else
					$this->send_error_form($this->form_error($model->User_BankCard));
		}else
			$this->send_csrf();
	}

	public function actionA(){
		$this->bank_code_val();
	}
	/**
	 * 检查是否有同卡号的账号存在     存在则设置为已删除
	 * @param $code
	 * @return bool
	 */
	private function bank_code_val($code=''){

		$model_bank_card = BankCard::model()->findAll( 'card_type=:card_type AND card_id =:card_id AND status=:status ',
			array(
				':card_type'=>BankCard::user,
				':card_id'=>Yii::app()->api->id,
				':status'=>BankCard::status_suc,
			));
		if(!$model_bank_card)
			return true;
		else
		{
			// 批量更新
			$criteria =new CDbCriteria;
			$criteria->addColumnCondition(array(
				'card_type'=>BankCard::user,
				'card_id'=>Yii::app()->api->id,
			));

			if( BankCard::model()->updateAll(array(
				'status'=>BankCard::status_del,
			),$criteria) && $this->log('更新/设置用户的银行信息为'.BankCard::$_status[BankCard::status_del],ManageLog::user,ManageLog::update))
				return true;
			else
				return false;
		}
	}
	/**
	 * 一对一修改 保留
	 * 检查是否有同卡号的账号存在     存在则设置为已删除
	 * @param $code
	 * @return bool
	 */
	private function bank_code_val_bak($code){

		$model_bank_card = BankCard::model()->find( 'card_type=:card_type AND card_id =:card_id AND status=:status AND bank_code=:bank_code',
						array(
							':card_type'=>BankCard::user,
							':card_id'=>Yii::app()->api->id,
							':status'=>BankCard::status_suc,
							':bank_code'=>$code,
							));
		if(!$model_bank_card)
			return true;
		else
		{
			$model_bank_card->status = BankCard::status_del;
			if($model_bank_card->save(false) && $this->log('更新/设置用户的银行信息为'.BankCard::$_status[BankCard::status_del],ManageLog::user,ManageLog::update))
				return true;
			else
				return false;
		}
	}
	/**
	 * 银行信息列表
	 */
	public function actionBank_index()
	{
		$bank_list = Bank::bank_data();
		$this->send($bank_list);
	}
}
