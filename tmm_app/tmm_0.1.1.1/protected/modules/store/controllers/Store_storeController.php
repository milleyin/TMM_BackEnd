<?php
/**
 * 商家控制器
 * @author Moore Mo
 * Class Store_storeController
 */
class Store_storeController extends StoreMainController
{
    /**
     * 设置当前操作数据模型
     * @var string
     */
    public $_class_model='StoreUser';
    /**
     * 修改手机的秘钥
     * @var unknown
     */
    public $_update_phone_key='__input_sms';
    /**
     * 修改手机的秘钥的值
     * @var unknown
     */
    public $_update_phone_key_value=true;

    /**
     * 商家主帐号详情
     */
    public function actionView()
    {
        $main_store = array(
            'store_info'=>array(),
            'agent_info'=>array(),
            'frm_info'=>array(),
            'contact_info'=>array(),
            'bank_info'=>array(),
            'son_info'=>array(),
        );

        $model = $this->loadModel(Yii::app()->store->id,array(
            'with'=>array(
                'Store_Content'=>array(
                    'with'=>array(
                        'Content_area_id_p_Area_id',
                        'Content_area_id_m_Area_id',
                        'Content_area_id_c_Area_id',
                        'Content_Bank',
                    )
                ),
                'Store_Agent',
            ),
            'condition'=>'`t`.`status`=1 AND `t`.`p_id`=0',
        ));
        $img_domain = Yii::app()->params['admin_img_domain'];
        if (!empty($model->Store_Content)) {
            // 商家基本信息
            $main_store['store_info'] = array(
                'value' => $model->id,
                'phone' => $model->phone,
                'is_set' => empty($model->password) ? false : true,
                'is_main' => $model->p_id ? false : true,
            );
            // 归属代理商信息
            $main_store['agent_info'] = array(
                'agent_name' => $model->Store_Agent->firm_name,
                'agent_phone' => $model->Store_Agent->phone,
            );
            // 商家公司信息
            $main_store['frm_info'] = array(
                'name' => $model->Store_Content->name,
                'address' => $model->Store_Content->Content_area_id_p_Area_id->name .
                    $model->Store_Content->Content_area_id_m_Area_id->name .
                    $model->Store_Content->Content_area_id_c_Area_id->name .
                    $model->Store_Content->address,
                'store_tel' => $model->Store_Content->store_tel,
                'bl_img' => !empty($model->Store_Content->bl_img) ? $img_domain.trim($this->litimg_path($model->Store_Content->bl_img),'.') : '',
                'com_contacts' => $model->Store_Content->com_contacts,
                'com_identity' => $model->Store_Content->com_identity,
                'com_phone' => $model->Store_Content->com_phone,
            );
            // 商家公司联系人信息
            $main_store['contact_info'] = array(
                'lx_contacts' => $model->Store_Content->lx_contacts,
                'lx_phone' => $model->Store_Content->lx_phone,
                'lx_identity_code' => $model->Store_Content->lx_identity_code,
                'identity_before' => !empty($model->Store_Content->identity_before) ? $img_domain.trim($this->litimg_path($model->Store_Content->identity_before),'.') : '',
                'identity_after' => !empty($model->Store_Content->identity_after) ? $img_domain.trim($this->litimg_path($model->Store_Content->identity_after),'.') : '',
                'identity_hand' => !empty($model->Store_Content->identity_hand) ? $img_domain.trim($this->litimg_path($model->Store_Content->identity_hand),'.') : '',
            );

            // 商家公司账务信息
//            if (! empty($model->Store_Content->Content_Bank)) {
//                $main_store['bank_info'] = array(
//                    'bank_value' => $model->Store_Content->Content_Bank->name,
//                    'bank_name' => $model->Store_Content->bank_name,
//                    'bank_code' => $model->Store_Content->bank_code,
//                );
//            }
            $main_store['bank_info'] =  $this->Bank_view();
            // 获取商家子帐号的信息
            $Store_Son = StoreUser::model()->findAll(array(
                'condition'=>'status=1 AND p_id=:p_id',
                'params'=>array(':p_id'=>Yii::app()->store->id)
            ));

            foreach ($Store_Son as $son) {
                $main_store['son_info'][] = array(
                    'value' => $son->id,
                    'name' => $model->Store_Content->name,
                    'phone' => $son->phone,
                    'link' => Yii::app()->params['app_api_domain'].Yii::app()->createUrl('/store/store_store/son_view',array('id'=>$son->id)),
                );
            }


        } else {
            $this->send_error(DATA_NOT_SCUSSECS);
        }

        $this->send($main_store);
    }

    /**
     * 商家子帐号详情
     * @param $id
     */
    public function actionSon_view($id)
    {
        $son_store = array(
            'store_info'=>array(),
        );

        $params = array();
        if ($id != Yii::app()->store->id) {
            $condition = '`status`=1 AND `p_id`=:p_id';
            $params[':p_id'] = Yii::app()->store->id;
        } else {
            $id = Yii::app()->store->id;
            $condition = '`status`=1 AND `p_id` != 0';
        }

        $model = $this->loadModel($id, array(
            'condition' => $condition,
            'params' => $params,
        ));
        // 商家子帐号基本信息
        $son_store['store_info'] = array(
            'value' => $model->id,
            'phone' => $model->phone,
            'is_set' => empty($model->password) ? false : true,
            'is_main' => $model->p_id ? false : true,
        );

        $this->send($son_store);
    }

    /**
     * 获得 银行卡信息
     * @return array
     */
    private function Bank_view(){
        $model = StoreUser::model()->findByPk(Yii::app()->store->id,array(
            'with'=>array(
                'Store_BankCard'
            ),
            'condition'=>'t.status=:status and Store_BankCard.status=:bank_status',
            'params'=>array(':status'=>1,':bank_status'=>1),
            'order'=>'Store_BankCard.id desc'
        ));

        $return  = array();

        if(!$model)
            return $return;

        foreach($model->Store_BankCard as $v){
            $return[] = BankCard::get_field_data($v);
        }

        return $return;
    }

    /**
     *  商家绑定银行信息列表
     */
    public function actionStore_bank(){
        //登入验证
        $model_store = $this->verify_store_login();
        //获得所有银行卡信息
        $return = $this->Bank_view();

        if(!$return)
            $this->send_error(DATA_NOT_SCUSSECS);
        $this->send($return);
    }
    /**
     * 银行信息列表
     */
    public function actionBank_index()
    {
        $bank_list = Bank::bank_data();

        $this->send($bank_list);
    }

    /**
     * 设置银行信息
     */
    public function actionBank()
    {
        $model = $this->loadModel(Yii::app()->store->id,'`status`=1 AND `p_id`=0');

        $model->scenario = 'store_bank';

        if( isset($_POST['StoreContent']) ) {

            if (!Bank::model()->find('`id`=:id', array(':id' => $_POST['StoreContent']['bank_id']))) {
                $this->send_error(DATA_NOT_SCUSSECS);
            }
            //验证银行卡银行
            $model->Store_BankCard = new BankCard();
            $model->Store_BankCard->scenario='store_bank';
            $model->Store_BankCard->attributes = $_POST['StoreContent'];

            if ($model->Store_BankCard->validate())
            {
                //保存session
                Yii::app()->session['create_Store_BankCard'.Yii::app()->store->id]= $_POST['StoreContent'];

                //成功
                $return = array(
                    'status' => STATUS_SUCCESS,
                );
                $this->send($return);
            }else
                $this->send_error_form($this->form_error($model->Store_BankCard));


        }

        $this->send_csrf();
    }
    public function actionA(){
                $this->p_r(Yii::app()->session['create_Store_BankCard'.Yii::app()->store->id]);
            exit;
    }
    /**
     * 设置银行信息======验证短信
     */
    public function actionBank_sms()
    {
//        $this->p_r(Yii::app()->session['create_Store_BankCard'.Yii::app()->store->id]);
//            exit;
        $this->_class_model = 'StoreUser';
        $model = $this->loadModel(Yii::app()->store->id,'`status`=1 AND `p_id`=0');

        $model->scenario = 'store_bank';

        if(  isset($_POST['sms']) ) {

            if(! (isset(Yii::app()->session['create_Store_BankCard'.Yii::app()->store->id]) && Yii::app()->session['create_Store_BankCard'.Yii::app()->store->id]) )
                $this->send_error(DATA_NOT_SCUSSECS);

            $model->attributes=array('phone'=>$model->phone,'sms'=>$_POST['sms']);

            if($model->validate() && $model->verifycode_bank()){

                    //验证银行卡银行
                $model->Store_BankCard = new BankCard();
                $model->Store_BankCard->scenario='store_bank';
                $model->Store_BankCard->attributes = Yii::app()->session['create_Store_BankCard'.Yii::app()->store->id];

                if ($model->Store_BankCard->validate())
                {
                    //开启事物
                    $transaction = Yii::app()->db->beginTransaction();
                    try {
                        //查看是否有卡号已存在    存在设置为已删除
                        if(!$this->bank_code_val())
                            throw new Exception("更新/设置用户的银行失败");

                        $model->Store_BankCard->manage_id 	= Yii::app()->store->id;
                        $model->Store_BankCard->manage_who	= BankCard::store;
                        $model->Store_BankCard->card_type	= BankCard::store;
                        $model->Store_BankCard->card_id		= Yii::app()->store->id;
                        $set_card				                = BankCard::set_card_type_id(BankCard::store);
                        $model->Store_BankCard->$set_card		= Yii::app()->store->id;

                        if($model->Store_BankCard->save(false) && $this->log('更新/设置商家的银行信息',ManageLog::store,ManageLog::update))
                        {
                            //session 设置为 空
                            Yii::app()->session['create_Store_BankCard' . Yii::app()->store->id] = '';
                        }
                        else
                            throw new Exception("设置用户的银行失败");

                        $return = true;

                        $transaction->commit();

                    }catch (Exception $e)
                    {
                        $transaction->rollBack();
                        $this->error_log($e->getMessage(), ErrorLog::store, ErrorLog::create, ErrorLog::rollback, __METHOD__);
                    }

                    if (isset($return)) {
                        //成功
                        $return=array(
                            'status'=>STATUS_SUCCESS,
                        );
                        $this->send($return);
                    } else {
                        $this->send_error_form($this->form_error($model->Store_BankCard));
                    }

                }else
                    $this->send_error_form($this->form_error($model->Store_BankCard));

            }else
                $this->send_error_form($this->form_error($model));
        }

        $this->send_csrf();
    }
    /**
     * 检查是否有同卡号的账号存在     存在则设置为已删除
     * @param $code
     * @return bool
     */
    private function bank_code_val($code=''){

        $model_bank_card = BankCard::model()->findAll( 'card_type=:card_type AND card_id =:card_id AND status=:status ',
            array(
                ':card_type'=>BankCard::store,
                ':card_id'=>Yii::app()->store->id,
                ':status'=>BankCard::status_suc,
            ));
        if(!$model_bank_card)
            return true;
        else
        {
            // 批量更新
            $criteria =new CDbCriteria;
            $criteria->addColumnCondition(array(
                'card_type'=>BankCard::store,
                'card_id'=>Yii::app()->store->id,
            ));

            if( BankCard::model()->updateAll(array(
                    'status'=>BankCard::status_del,
                ),$criteria) && $this->log('更新/设置商家的银行信息为'.BankCard::$_status[BankCard::status_del],ManageLog::store,ManageLog::update))
                return true;
            else
                return false;
        }
    }
    /**
     * 设置银行信息

    public function actionBank()
    {
        $model = $this->loadModel(Yii::app()->store->id,array('with'=>'Store_Content','condition'=>'`t`.`status`=1 AND `t`.`p_id`=0'));

        $model->Store_Content->scenario = 'bank';

        if( isset($_POST['StoreContent']))
        {

            if (! Bank::model()->find('`id`=:id',array(':id'=>$_POST['StoreContent']['bank_id']))) {
                $this->send_error(DATA_NOT_SCUSSECS);
            }

            $model->sms = $_POST['StoreContent']['sms'];

            $model->Store_Content->attributes = $_POST['StoreContent'];

            if ($model->Store_Content->validate())
            {

                if ($model->verifycode_bank())
                {

                    if($model->Store_Content->save(false) && $this->log('更新/设置商家主账号的银行信息',ManageLog::store,ManageLog::update))
                    {
                        //成功
                        $return = array(
                            'status' => STATUS_SUCCESS,
                        );
                        $this->send($return);
                    }
                    else
                    {
                        $this->send_error_form($this->form_error($model->Store_Content));
                    }
                }
                else
                {
                    $this->send_error_form($model->getErrors());
                }
            }
            else
            {
                $this->send_error_form($this->form_error($model->Store_Content));;
            }
        }

        $this->send_csrf();
    }
     */
    /**
     * 发送短信=====设置银行
     */
    public function actionCaptcha_bank_sms()
    {
        $model_store = $this->loadModel(Yii::app()->store->id, '`status`=1 AND `p_id`=0');
        if($model_store->phone)
        {
            $model = new SmsStoreLoginForm;
            $model->scenario = 'bank_phone';
            $model->attributes=array('phone'=>$model_store->phone);
            $model->attributes = $model->phone;
            if($model->validate())
            {
                //验证
                if($model->store_update_bank_phone_sms())
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
        }else{
            $this->send_error(DATA_NOT_SCUSSECS);
        }
    }

    /**
     * 设置密码
     */
    public function actionPwd()
    {
        if(isset($_POST['StoreUser']))
        {
            $model = $this->loadModel(Yii::app()->store->id, '`status`=1');

            $model->scenario='store_pwd_update';
            $model->attributes = $_POST['StoreUser'];

            if ($model->validate() &&  $model->verifycode_pwd())
            {
                $model->password = $model::pwdEncrypt($model->_pwd);
                if ($model->save(false)) {
                    //成功
                    $return=array(
                        'status'=>STATUS_SUCCESS,
                    );
                    $this->send($return);
                }else
                    $this->send_error_form($this->form_error($model));
            }else
                $this->send_error_form($this->form_error($model));
        }else
            $this->send_csrf();
    }

    /**
     * 修改手机号====老手机号
     */
    /**
     * 更换手机号  ====  输入旧手机号
     */
    public function actionPhone_old()
    {
        $model = $this->loadModel(Yii::app()->store->id, '`status`=1');
        $model->scenario='store_update_old';

        if(isset($_POST['StoreUser']))
        {
            $model->attributes = $_POST['StoreUser'];
            if($model->validate() && $model->verifycode_store())
            {
                Yii::app()->store->setState($this->_update_phone_key,$this->_update_phone_key_value);
                //成功
                $return=array(
                    'status'=>STATUS_SUCCESS,
                );
                $this->send($return);
            }else
                $this->send_error_form($this->form_error($model));
        }else
            $this->send_csrf();
    }

    /**
     * 更换手机号 ==== 输入新手机号
     */
    public function actionPhone_new(){
        //判断是否是从是一个控制器过来的
        if(!(Yii::app()->store->hasState($this->_update_phone_key)  && Yii::app()->store->getState($this->_update_phone_key)==$this->_update_phone_key_value) )
            $this->send_error(DATA_NOT_SCUSSECS);

        $model = $this->loadModel(Yii::app()->store->id, '`status`=1');
        $model->scenario='store_update_new';

        if(isset($_POST['StoreUser']))
        {
            $model->attributes=$_POST['StoreUser'];
            if($model->validate() && $model->verifycode_store())
            {
                if($model->save(false))
                {
                    //成功
                    $return=array(
                        'status'=>STATUS_SUCCESS,
                    );
                    $this->send($return);
                }
            }else
                $this->send_error_form($this->form_error($model));
        }else
            $this->send_csrf();
    }

    /**
     *短信发送    ====  输入安全手机号
     */
    public function actionCaptcha_old_sms()
    {
        if(isset($_POST['phone']) && $_POST['phone'])
        {
            $model=new SmsStoreLoginForm;
            $model->scenario='old_phone';
            $model->attributes=array('phone'=>$_POST['phone']);
            //验证手机号
            if($model->validate())
            {
                if($model->store_update_old_phone_sms())
                {          //成功
                    $return=array(
                        'status'=>STATUS_SUCCESS,
                    );
                    $this->send($return);
                }else
                    $this->send_error_form($model->get_error());
            }else
                $this->send_error_form($model->get_error());
        }else
            $this->send_error(DATA_NOT_SCUSSECS);
    }

    /**
     *短信发送   ==== 输入新手机号
     */
    public function actionCaptcha_new_sms()
    {
        if(isset($_POST['phone']) && $_POST['phone'])
        {
            if(Yii::app()->store->hasState($this->_update_phone_key)  && Yii::app()->store->getState($this->_update_phone_key)==$this->_update_phone_key_value)
            {
                $model=new SmsStoreLoginForm;
                $model->scenario='new_phone';
                $model->attributes=array('phone'=>$_POST['phone']);
                //验证手机号
                if($model->validate())
                {
                    if($model->store_update_new_phone_sms())
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
        }else
            $this->send_error(DATA_NOT_SCUSSECS);
    }

    /**
     * 发送短信=====修改密码
     */
    public function actionCaptcha_pwd_sms()
    {
        if(isset($_POST['phone']) && $_POST['phone'])
        {
            $model=new SmsStoreLoginForm;
            $model->scenario='pwd_phone';
            $model->attributes=array('phone'=>$_POST['phone']);
            if($model->validate())
            {
                //验证
                if($model->store_update_pwd_phone_sms())
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
        }else{
            $this->send_error(DATA_NOT_SCUSSECS);
        }
    }


}