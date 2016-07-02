<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-12-10 11:07:11 */
class Tmm_bankCardController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='BankCard';

	/**
	 * 查看详情
	 * @param integer $id
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id,array(
				'with'=>array(
						'BankCard_Bank',
						'BankCard_AdminCard'=>array('select'=>'username'),
						'BankCard_AgentCard'=>array('select'=>'phone'),
						'BankCard_StoreUserCard'=>array('select'=>'phone'),
						'BankCard_UserCard'=>array('select'=>'phone'),
						'BankCard_Admin'=>array('select'=>'username'),
						'BankCard_Agent'=>array('select'=>'phone'),
						'BankCard_StoreUser'=>array('select'=>'phone'),
						'BankCard_User'=>array('select'=>'phone'),
				),
			)),
		));
	}

	/**
	 * 创建
	 */
	public function actionCreate($id,$type)
	{
		if( !(is_numeric($id) && is_numeric($type)) )
			$this->back();

		$model=new BankCard;
	
		$model->scenario='create';
		$this->_Ajax_Verify($model,'bank-card-form');
		
		if(isset($_POST['BankCard']))
		{
			$model->attributes=$_POST['BankCard'];
			$model->manage_id 		= Yii::app()->admin->id;
			$model->manage_who		= BankCard::admin;
			$model->card_type		= $type;
			$model->card_id		= $id;
			$set_card				= BankCard::set_card_type_id($type);
			$model->$set_card		= $id;
			if($model->save() && $this->log('创建'.BankCard::$_manage_who[BankCard::admin].'银行卡',ManageLog::admin,ManageLog::create))
				$this->back();
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * 更新
	 * @param integer $id
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id,'status=1');

		$model->scenario='update';
		$this->_Ajax_Verify($model,'bank-card-form');

		if(isset($_POST['BankCard']))
		{
			$model->attributes=$_POST['BankCard'];
			if($model->save() && $this->log('更新'.BankCard::$_manage_who[$model->manage_who].'银行卡',ManageLog::admin,ManageLog::update))
				$this->back();
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 *管理页
	 */
	public function actionAdmin($card_type='',$card_id='')
	{

		$model=new BankCard('search');
		$model->unsetAttributes();  // 删除默认属性
		
		if($card_type != '')
			$model->card_type = $card_type;
		if($card_id != '')
			$model->card_id = $card_id;
		
		if(isset($_GET['BankCard']))
			$model->attributes=$_GET['BankCard'];
			
		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	/**
	 * 银行卡的日志
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;

		$criteria->with = array(
				'BankCard_Bank',
				'BankCard_AdminCard'=>array('select'=>'username'),
				'BankCard_AgentCard'=>array('select'=>'phone'),
				'BankCard_StoreUserCard'=>array('select'=>'phone'),
				'BankCard_UserCard'=>array('select'=>'phone'),
				'BankCard_Admin'=>array('select'=>'username'),
				'BankCard_Agent'=>array('select'=>'phone'),
				'BankCard_StoreUser'=>array('select'=>'phone'),
				'BankCard_User'=>array('select'=>'phone'),
		);
		$criteria->addColumnCondition(array('t.status'=>-1));
		$model=new BankCard;
		$this->render('index',array(
				'model'=>$model->search($criteria),
		));
	}
	
// 	/**
// 	 * 禁用
// 	 * @param integer $id
// 	 */
// 	public function actionDisable($id){
// 		if($this->loadModel($id,'`status`=1')->updateByPk($id,array('status'=>0)))
// 			$this->log('禁用银行卡',ManageLog::admin,ManageLog::update);
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
			
// 	}
	
// 	/**
// 	 * 激活
// 	 * @param integer $id
// 	 */
// 	public function actionStart($id){
// 		if($this->loadModel($id,'`status`=0')->updateByPk($id,array('status'=>1)))
// 	 		$this->log('激活银行卡',ManageLog::admin,ManageLog::update);
// 		if(!isset($_GET['ajax']))
// 			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));	
// 	}

// 	/**
// 	 *将原银行卡账号迁移到新表
// 	 */
// 	public function actionBan(){

// 		$br = '</br>';
// 		// 代理商
// 		$model_agent = Agent::model()->findAll('bank_id !=""  and status =1');
// 		$agent = $this->bankcard($model_agent,BankCard::agent);
// 		echo $agent?'agent succend':'agent fail';
// 		echo $br;
// 		//用户
// 		$model_user = Organizer::model()->findAll('bank_id !=""  and status =1');
// 		$user = $this->bankcard($model_user,BankCard::user);
// 		echo $user?'user succent':'user fail';
// 		echo $br;
// 		//商家
// 		$model_store = StoreContent::model()->findAll('bank_id !="" ');
// 		$store = $this->bankcard($model_store,BankCard::store);
// 		echo $store?'store succend':'store fail';
// 		echo $br;

// 		exit;
// 	}

// 	public function bankcard($model_arr,$type){
// 		if(!$model_arr)
// 			return true;

// 		foreach($model_arr as $v){
// 			$model_bank = new BankCard();
// 			$model_bank->manage_id 	= Yii::app()->admin->id;
// 			$model_bank->manage_who	= $type;
// 			$model_bank->card_type		= $type;
// 			$model_bank->card_id		= $v->id;
// 			$model_bank->bank_id		= $v->bank_id;
// 			$model_bank->bank_name		= $v->bank_name;
// 			$model_bank->bank_branch	= $v->bank_branch;
// 			$model_bank->bank_code		= $v->bank_code;
// 			$set_card					= BankCard::set_card_type_id($type);
// 			$model_bank->$set_card		= $v->id;
// 			$m_count[] = $model_bank->save(false);
// 		}
// 		if(count($model_arr) == count($m_count))
// 			return true;
// 		else
// 			return false;
// 	}

}
