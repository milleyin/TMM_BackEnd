<?php
/**
 * 代理商登录模型
 * @author moore
 */
class SmsLoginForm extends  CFormModel
{
    public $phone;
    public $old_phone;
    public $verifyCode;

    public function rules() {
        return array(
        	array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'no,yes'),
            array('phone, verifyCode', 'required','on'=>'yes'),
            array('verifyCode','captcha','allowEmpty'=>!CCaptcha::checkRequirements(),'skipOnError'=>true,'on'=>'yes'),
            array('phone', 'authenticate','on'=>'yes'),
            array('phone, verifyCode','safe','on'=>'yes'),
   		
            array('phone','required','on'=>'no'),
            array('phone','safe','on'=>'no'),

            array('phone, old_phone', 'required','on'=>'change_phone'),
        	array('old_phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'change_phone'),
        	array('phone,old_phone','required','on'=>'change_phone'),
        	array('phone,old_phone','safe','on'=>'change_phone'),

            //改变旧手机 , 验证绑定银行卡
            array('phone,verifyCode','required','on'=>'old_phone'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'old_phone'),
            array('verifyCode','captcha','allowEmpty'=>!CCaptcha::checkRequirements(),'skipOnError'=>true,'on'=>'old_phone'),
            array('phone,verifyCode','safe','on'=>'old_phone'),
            array('phone', 'authenticate_old','on'=>'old_phone'),

            //改变新手机号
            array('phone','required','on'=>'new_phone'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'new_phone'),
            array('phone','safe','on'=>'new_phone'),
            array('phone', 'authenticate_new','on'=>'new_phone'),

            //改变银行卡
            array('phone,verifyCode','required','on'=>'bank_phone'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'bank_phone'),
            array('verifyCode','captcha','allowEmpty'=>!CCaptcha::checkRequirements(),'skipOnError'=>true,'on'=>'bank_phone'),
            array('phone,verifyCode','safe','on'=>'bank_phone'),

            //修改密码
            array('phone,verifyCode','required','on'=>'pwd_phone'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'pwd_phone'),
            array('verifyCode','captcha','allowEmpty'=>!CCaptcha::checkRequirements(),'skipOnError'=>true,'on'=>'pwd_phone'),
            array('phone,verifyCode','safe','on'=>'pwd_phone'),

            // 代理商提现
            array('phone','required','on'=>'cash_phone'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'cash_phone'),
            array('phone', 'authenticate_cash','on'=>'cash_phone'),
            array('phone','safe','on'=>'cash_phone'),
        );
    }

    public function attributeLabels() {
        return array(
            'phone' => '手机号',
        	'old_phone' => '以前手机号',
            'verifyCode' => '验证码'
        );
    }

    /**
 * Authenticates the password.
 * This is the 'authenticate' validator as declared in rules().
 */
    public function authenticate($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            if(! preg_match(Yii::app()->params['pattern']['phone'],$this->phone)){
                $this->addError('phone','手机号码 不是有效值');
            }else{
                if(! Agent::model()->find('phone=:phone',array(':phone'=>$this->phone)))
                    $this->addError('phone','手机号码 没有注册');
            }
        }
    }
    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate_old($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            if(! preg_match(Yii::app()->params['pattern']['phone'],$this->phone)){
                $this->addError('phone','手机号码 不是有效值');
            }else{
                if(! Agent::model()->find('phone=:phone AND `id`=:id',array(':phone'=>$this->phone ,':id'=>Yii::app()->agent->id)))
                    $this->addError('phone','手机号码 不匹配');
            }
        }
    }
    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate_new($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            if(! preg_match(Yii::app()->params['pattern']['phone'],$this->phone)){
                $this->addError('phone','手机号码 不是有效值');
            }else{
                if( Agent::model()->find('phone=:phone ',array(':phone'=>$this->phone )))
                    $this->addError('phone','手机号码 已注册');
            }
        }
    }

    /**
     * 商家提现-短信验证
     * @param $attribute
     * @param $params
     */
    public function authenticate_cash($attribute,$params)
    {
        if(!$this->hasErrors())
        {
            if(! preg_match(Yii::app()->params['pattern']['phone'],$this->phone)){
                $this->addError('phone','手机号码 不是有效值');
            }
            else{
                if(! Agent::model()->find('`phone`=:phone AND `status`=1',array(':phone'=>$this->phone)))
                    $this->addError('phone','手机号 不是有效值');
            }
        }
    }

	/**
	 * 获取错误信息
	 * @return multitype:unknown
	 */
    public function get_error()
    {
        $result=array();
        foreach($this->getErrors() as $attribute=>$errors)
            $result[$attribute]=$errors;
        if(empty($result))
        	$result['phone'][]='操作过于频繁,请1分钟后再试';
        return $result;
    }
    

    public function init()
    {
    	parent::init();
    	Yii::import('ext.Send_sms.Send_sms');
    }

    /**
     * 代理商 登录 短信
     * @return bool
     */
    public function agent_login_send()
    {
        $model=Agent::model()->find('`phone`=:phone',array(':phone'=>$this->phone));
        if($model)
        {
            $params_v=array(
                'sms_id'=>$model->id,
                'sms_type'=>SmsLog::sms_agent,
                'role_id'=>$model->id,
                'role_type'=>SmsLog::send_agent,
                'sms_use'=>SmsLog::use_login,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['agent_login']['number'],Yii::app()->params['sms']['agent_login']['interval']))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>$model->id,
                    'sms_type'=>SmsLog::sms_agent,
                    'role_id'=>$model->id,
                    'role_type'=>SmsLog::send_agent,
                    'sms_use'=>SmsLog::use_login,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_pc,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['agent_login']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['agent_login']['time'],
                );
                return Send_sms::send($this->phone,$params,
                		strtr(Yii::app()->params['sms']['agent_login']['content'],array(
                			'{code}'=>$code
               			 ))
                	);
            }
        }
        return false;
    }
    
    /**
     * 代理商创建商家短信
     * @return boolean|Ambigous <boolean, string>
     */
    public function agent_create_store_send()
    {
    	$model_store=StoreUser::model()->find('`phone`=:phone',array(':phone'=>$this->phone));
    	if($model_store)
    	{
    		if($model_store->create_status !=0 && $model_store->agent_id==Yii::app()->agent->id)
    			$is_send=true;
    		else 
    			$is_send=false;
    	}else 
    		$is_send=true;
       	if($is_send)
    	{
    		$params_v=array(
    				'sms_id'=>0,
    				'sms_type'=>SmsLog::sms_store,
    				'role_id'=>Yii::app()->agent->id,
    				'role_type'=>SmsLog::send_agent,
    				'sms_use'=>SmsLog::use_register,
    		);
    		if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['agent_create_store']['number'],Yii::app()->params['sms']['agent_create_store']['interval']))
    		{
    			$code=rand(100000,999999);
    			$params=array(
    					'sms_id'=>0,
    					'sms_type'=>SmsLog::sms_store,
    					'role_id'=>Yii::app()->agent->id,
    					'role_type'=>SmsLog::send_agent,
    					'sms_use'=>SmsLog::use_register,
    					'code'=>$code,
    					'sms_source'=>SmsLog::source_pc,
    					'login_address'=>'',
    					'sms_error'=>Yii::app()->params['sms']['agent_create_store']['error'],
    					'end_time'=>time()+Yii::app()->params['sms']['agent_create_store']['time'],
    			);
    			return Send_sms::send($this->phone,$params,
    					strtr(
    							Yii::app()->params['sms']['agent_create_store']['content'],
    							array('{code}'=>$code)
    			));
    		}	
    	}
    	return false;
    }

    /**
     * 代理商创建商家子账号短信
     * @return boolean|Ambigous <boolean, string>
     */
    public function agent_create_store_son_send()
    {
        $model_store=StoreUser::model()->find('`phone`=:phone',array(':phone'=>$this->phone));
        if(! $model_store)
        {
            $params_v=array(
                'sms_id'=>0,
                'sms_type'=>SmsLog::sms_store,
                'role_id'=>Yii::app()->agent->id,
                'role_type'=>SmsLog::send_agent,
                'sms_use'=>SmsLog::use_register,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['agent_create_store_son']['number'],Yii::app()->params['sms']['agent_create_store_son']['interval']))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>0,
                    'sms_type'=>SmsLog::sms_store,
                    'role_id'=>Yii::app()->agent->id,
                    'role_type'=>SmsLog::send_agent,
                    'sms_use'=>SmsLog::use_register,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_pc,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['agent_create_store_son']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['agent_create_store_son']['time'],
                );
                return Send_sms::send($this->phone,$params,
                    strtr(
                        Yii::app()->params['sms']['agent_create_store_son']['content'],
                        array('{code}'=>$code)
                    ));
            }
        }
        return false;
    }
    
    /**
     * 代理商修改商家子账号短信
     * @return boolean|Ambigous <boolean, string>
     */
    public function agent_update_store_son_send()
    {
    	$old_model_store=StoreUser::model()->find('`phone`=:phone AND `status`=0 AND `p_id`!=0 AND `agent_id`=:agent_id',array(':phone'=>$this->old_phone,':agent_id'=>Yii::app()->agent->id));
    	$new_model_store=StoreUser::model()->find('`phone`=:phone',array(':phone'=>$this->phone));
    	if($old_model_store && !$new_model_store)
    	{
    		$params_v=array(
    				'sms_id'=>$old_model_store->id,
    				'sms_type'=>SmsLog::sms_store,
    				'role_id'=>Yii::app()->agent->id,
    				'role_type'=>SmsLog::send_agent,
    				'sms_use'=>SmsLog::use_phone,
    		);
    		if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['agent_update_store_son']['number'],Yii::app()->params['sms']['agent_update_store_son']['interval']))
    		{
    			$code=rand(100000,999999);
    			$params=array(
    					'sms_id'=>$old_model_store->id,
    					'sms_type'=>SmsLog::sms_store,
    					'role_id'=>Yii::app()->agent->id,
    					'role_type'=>SmsLog::send_agent,
    					'sms_use'=>SmsLog::use_phone,
    					'code'=>$code,
    					'sms_source'=>SmsLog::source_pc,
    					'login_address'=>'',
    					'sms_error'=>Yii::app()->params['sms']['agent_update_store_son']['error'],
    					'end_time'=>time()+Yii::app()->params['sms']['agent_update_store_son']['time'],
    			);
    			return Send_sms::send($this->phone,$params,
    					strtr(
    							Yii::app()->params['sms']['agent_update_store_son']['content'],
    							array('{code}'=>$code)
    					));
    		}
    	}
    	return false;
    }

    /**
     * 代理商更改自己手机的短信========旧手机
     *  @return boolean|Ambigous <boolean, string>
     */
    public function  agent_update_old_phone_sms()
    {
        $model=Agent::model()->find('`phone`=:phone AND `id`=:id',array(':phone'=>$this->phone,':id'=>Yii::app()->agent->id));
        if($model)
        {
            $params_v=array(
                'sms_id'=>Yii::app()->agent->id,
                'sms_type'=>SmsLog::sms_agent,
                'role_id'=>Yii::app()->agent->id,
                'role_type'=>SmsLog::send_agent,
                'sms_use'=>SmsLog::use_phone,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['agent_update_old_phone']['number'],Yii::app()->params['sms']['agent_update_old_phone']['interval']))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>Yii::app()->agent->id,
                    'sms_type'=>SmsLog::sms_agent,
                    'role_id'=>Yii::app()->agent->id,
                    'role_type'=>SmsLog::send_agent,
                    'sms_use'=>SmsLog::use_phone,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_pc,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['agent_update_old_phone']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['agent_update_old_phone']['time'],
                );
                return Send_sms::send($this->phone,$params,
                    strtr(Yii::app()->params['sms']['agent_update_old_phone']['content'],array(
                        '{code}'=>$code
                    ))
                );
            }
        }
        return false;
    }
    
    /**
     * 代理商更改自己手机的短信========新手机
     *  @return boolean|Ambigous <boolean, string>
     */
    public function  agent_update_new_phone_sms(){
        $model=Agent::model()->find('`phone`=:phone',array(':phone'=>$this->phone));
        //一个手机号是否可以成为多个代理商
        if(!$model)
        {
            $params_v=array(
                'sms_id'=>Yii::app()->agent->id,
                'sms_type'=>SmsLog::sms_agent,
                'role_id'=>Yii::app()->agent->id,
                'role_type'=>SmsLog::send_agent,
                'sms_use'=>SmsLog::use_phone,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['agent_update_new_phone']['number'],Yii::app()->params['sms']['agent_update_new_phone']['interval']))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>Yii::app()->agent->id,
                    'sms_type'=>SmsLog::sms_agent,
                    'role_id'=>Yii::app()->agent->id,
                    'role_type'=>SmsLog::send_agent,
                    'sms_use'=>SmsLog::use_phone,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_pc,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['agent_update_new_phone']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['agent_update_new_phone']['time'],
                );
                return Send_sms::send($this->phone,$params,
                    strtr(Yii::app()->params['sms']['agent_update_new_phone']['content'],array(
                        '{code}'=>$code
                    ))
                );
            }
        }
        return false;

    }

    /**
     * 代理商更改自己银行卡的短信========银行卡
     *  @return boolean|Ambigous <boolean, string>
     */
    public function  agent_update_bank_phone_sms()
    {
        $model=Agent::model()->find('`phone`=:phone AND `id`=:id',array(':phone'=>$this->phone,':id'=>Yii::app()->agent->id));

        if($model)
        {
            $params_v=array(
                'sms_id'=>Yii::app()->agent->id,
                'sms_type'=>SmsLog::sms_agent,
                'role_id'=>Yii::app()->agent->id,
                'role_type'=>SmsLog::send_agent,
                'sms_use'=>SmsLog::use_bank,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['agent_update_bank_phone']['number'],Yii::app()->params['sms']['agent_update_bank_phone']['interval']))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>Yii::app()->agent->id,
                    'sms_type'=>SmsLog::sms_agent,
                    'role_id'=>Yii::app()->agent->id,
                    'role_type'=>SmsLog::send_agent,
                    'sms_use'=>SmsLog::use_bank,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_pc,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['agent_update_bank_phone']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['agent_update_bank_phone']['time'],
                );
                return Send_sms::send($this->phone,$params,
                    strtr(Yii::app()->params['sms']['agent_update_bank_phone']['content'],array(
                        '{code}'=>$code
                    ))
                );
            }
        }
        return false;
    }

    /**
     * 代理商更改自己密码的短信========密码
     *  @return boolean|Ambigous <boolean, string>
     */
    public function  agent_update_pwd_phone_sms()
    {
        $model=Agent::model()->find('`phone`=:phone AND `id`=:id',array(':phone'=>$this->phone,':id'=>Yii::app()->agent->id));

        if($model)
        {
            $params_v=array(
                'sms_id'=>Yii::app()->agent->id,
                'sms_type'=>SmsLog::sms_agent,
                'role_id'=>Yii::app()->agent->id,
                'role_type'=>SmsLog::send_agent,
                'sms_use'=>SmsLog::use_password,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['agent_update_pwd_phone']['number'],Yii::app()->params['sms']['agent_update_pwd_phone']['interval'] ))
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>Yii::app()->agent->id,
                    'sms_type'=>SmsLog::sms_agent,
                    'role_id'=>Yii::app()->agent->id,
                    'role_type'=>SmsLog::send_agent,
                    'sms_use'=>SmsLog::use_password,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_pc,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['agent_update_pwd_phone']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['agent_update_pwd_phone']['time'],
                );
                return Send_sms::send($this->phone,$params,
                    strtr(Yii::app()->params['sms']['agent_update_pwd_phone']['content'],array(
                        '{code}'=>$code
                    ))
                );
            }
        }
        return false;
    }

    /**
     * 支付成功发短信通知用户
     */
    public function notify_pay_sms($user_id,$order_no,$time){
        $model = User::model()->findByPk($user_id);
        if($model)
        {
            $params_v=array(
                'sms_id'=>$user_id,
                'sms_type'=>SmsLog::sms_user,
                'role_id'=>0,
                'role_type'=>SmsLog::send_user,
                'sms_use'=>SmsLog::use_notify,
            );
			//发送通知 无需验证
                $params=array(
                    'sms_id'=>$user_id,
                    'sms_type'=>SmsLog::sms_user,
                    'role_id'=>0,
                    'role_type'=>SmsLog::send_user,
                    'sms_use'=>SmsLog::use_notify,
                    'code'=>'',
                    'sms_source'=>SmsLog::source_app,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['user_notify_sms_phone']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['user_notify_sms_phone']['time'],
                );
                return Send_sms::send($model->phone,$params,
                    strtr(Yii::app()->params['sms']['user_notify_sms_phone']['content'],array(
                        '{order_no}'=>$order_no,
                        '{time}'=>$time,
                        '{server}'=>Yii::app()->params['tmm_400']
                    ))
                );
            }
    }

    /**
     * 用户下单 商家短信通知接单
     */
    public static function order_store_notify($user_id,$order_id)
    {
    	Yii::import('ext.Send_sms.Send_sms');
    	$return=array();
    	$user = User::model()->findByPk($user_id);
    	if($user && !Yii::app()->params['sms']['order_store_notify']['off'])
    	{
    		$criteria=new CDbCriteria;
    		$criteria->addColumnCondition(array(
    			'`t`.`order_id`'=>$order_id,
    			'`t`.`user_id`'=>$user_id,
    			'`t`.`is_shops`'=>OrderItems::is_shops_store_query,//商家待接收
    		));
    		$criteria->with=array(
    			'OrderItems_StoreUser_Manager',
    			'OrderItems_Order',
    		);
    		$criteria->group='`t`.`manager_id`';  		
    		$models=OrderItems::model()->findAll($criteria);  		
    		foreach ($models as $model)
    		{
	    		//发送通知 无需验证
	    		$params=array(
	    				'sms_id'=>$user_id,
	    				'sms_type'=>SmsLog::sms_user,
	    				'role_id'=>$model->manager_id,
	    				'role_type'=>SmsLog::send_store,
	    				'sms_use'=>SmsLog::use_notify,
	    				'code'=>'',
	    				'sms_source'=>SmsLog::source_app,
	    				'login_address'=>'',
	    				'sms_error'=>Yii::app()->params['sms']['order_store_notify']['error'],
	    				'end_time'=>time()+Yii::app()->params['sms']['order_store_notify']['time'],
	    		);
	    		$is_send=Send_sms::send(
				    				$model->OrderItems_StoreUser_Manager->phone,
				    				$params,
				    				strtr(
				    						Yii::app()->params['sms']['order_store_notify']['content'],
				    						array(
					    						'{order_no}'=>$model->OrderItems_Order->order_no,
					    						'{server}'=>Yii::app()->params['tmm_400']
				    						)
				    			));
	    		if($is_send)
	    			$return[]=true;
    		}
     		return count($return)==count($models);
    	}
    }
    
    /**
     * 代理商提现的短信
     * @param $id 代理商id
     * @return bool
     */
    public function agent_cash_phone_sms($id)
    {
        $model = Agent::model()->find('`phone`=:phone AND `id`=:id AND `status`=1',array(':phone'=>$this->phone,':id'=>$id));

        if($model)
        {
            $params_v=array(
                'sms_id'=>$id,
                'sms_type'=>SmsLog::sms_agent,
                'role_id'=>$id,
                'role_type'=>SmsLog::send_agent,
                'sms_use'=>SmsLog::use_bank,
            );
            if(Send_sms::is_send($this->phone,$params_v,Yii::app()->params['sms']['agent_cash_phone']['number'] ,Yii::app()->params['sms']['agent_cash_phone']['interval'] ) )
            {
                $code=rand(100000,999999);
                $params=array(
                    'sms_id'=>$id,
                    'sms_type'=>SmsLog::sms_agent,
                    'role_id'=>$id,
                    'role_type'=>SmsLog::send_agent,
                    'sms_use'=>SmsLog::use_bank,
                    'code'=>$code,
                    'sms_source'=>SmsLog::source_app,
                    'login_address'=>'',
                    'sms_error'=>Yii::app()->params['sms']['agent_cash_phone']['error'],
                    'end_time'=>time()+Yii::app()->params['sms']['agent_cash_phone']['time'],
                );
                return Send_sms::send($this->phone,$params,
                    strtr(Yii::app()->params['sms']['agent_cash_phone']['content'],array(
                        '{code}'=>$code
                    ))
                );
            }
        }
        return false;
    }

}