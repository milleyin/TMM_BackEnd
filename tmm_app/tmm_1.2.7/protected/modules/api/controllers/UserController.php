<?php
/**
 * 用户控制器
 * @author Moore Mo
 * Class UserController
 */
class UserController extends ApiController
{
    /**
     * 设置当前操作数据模型
     * @var string
     */
    public $_class_model='User';

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
     * 用户个人资料
     */
    public function actionView()
    {
        $model = $this->loadModel(Yii::app()->api->id, '`status`=1');
        $model->User_Retinue=Retinue::model()->findAll(array(
            'condition'=>'status=1 AND user_id=:user_id',
            'params'=>array(':user_id'=>Yii::app()->api->id)
        ));
        $return = array(
            'userInfo' => array(),
            'main_retinueInfo' => array(),
            'retinueInfo' => array(),
            'tagsInfo' => array(),
        );

        // 用户信息
        $userInfo = array(
            'phone',
            'nickname',
            'is_organizer',
            'audit',
            'gender',
            'count',
            'last_ip'
        );
        foreach($userInfo as $val) {
            $return['userInfo'][$val] = $model->$val;
        }

       $organizer = Organizer::model()->findByPk($model->id);

        $return['userInfo']['organizer'] = array(
            'status' => isset($organizer->status)?$organizer->status:'0',
            'name' =>   isset($organizer->status)?Organizer::$_status[$organizer->status]:'不是组织者',
        );

        $return['userInfo']['is_set'] = empty($model->password) ? false : true;

        $return['userInfo']['update_link'] = Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/user/update');

        // 主要联系人，随行人员
        if (! empty($model->User_Retinue)) {
            foreach($model->User_Retinue as $val) {
                if ($val->is_main == 1) {
                    $return['main_retinueInfo']['list'][] = array(
                        'value' => $val->id,
                        'name' => $val->name,
                        'view_link' => Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/retinue/view', array('id'=> $val->id))
                    );
                } else {
                    $return['retinueInfo']['list'][] = array(
                        'value' => $val->id,
                        'name' => $val->name,
                        'view_link' => Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/retinue/view', array('id'=> $val->id))
                    );
                }
            }

            $return['main_retinueInfo']['index_link'] = Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/retinue/index', array('type'=>1));
            $return['main_retinueInfo']['create_link'] = Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/retinue/create', array('type'=>1));
            $return['retinueInfo']['index_link'] = Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/retinue/index', array('type'=>0));
            $return['retinueInfo']['create_link'] = Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/retinue/create', array('type'=>0));
        }

        //用户选择的标签分类
        $select_tags_type = TagsElement::user_select_tages_type($model->id, false, true);

        $user_tags_models=TagsType::model()->findAll(array(
                'with'=>array('TagsType_TagsType'=>array('condition'=>'TagsType_TagsType.status=1 AND TagsType_TagsType.p_id=0')),
                'condition'=>'t.status=1 AND t.is_user=:is_user',
                'params'=>array(':is_user'=>TagsType::yes_is_user),
        ));

        // 用户标签
        $user_tags = array();
        foreach($user_tags_models as $key=>$user_tags_model)
        {
            $user_tags[$user_tags_model->TagsType_TagsType->id]['value'] = $user_tags_model->TagsType_TagsType->id;
            $user_tags[$user_tags_model->TagsType_TagsType->id]['name'] = $user_tags_model->TagsType_TagsType->name;
            $user_tags[$user_tags_model->TagsType_TagsType->id]['index_link'] = Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/user/tags_index', array('id'=>$user_tags_model->TagsType_TagsType->id));
            $user_tags[$user_tags_model->TagsType_TagsType->id]['son']=array();
        }

        foreach($select_tags_type as $key=>$tag) {

            $p_key = $tag->TagsElement_TagsType->TagsType_TagsType->id;
            // 限制为3个
            if (isset($user_tags[$p_key]['son']) && count($user_tags[$p_key]['son']) >= 3) {
                continue;
            }
            $user_tags[$p_key]['value'] = $p_key;
            $user_tags[$p_key]['name'] = $tag->TagsElement_TagsType->TagsType_TagsType->name;
            $user_tags[$p_key]['index_link'] = Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/user/tags_index', array('id'=>$p_key));

            $user_tags[$p_key]['son'][] = array(
                'value' => $tag->TagsElement_TagsType->id,
                'name' => $tag->TagsElement_TagsType->name,
            );

        }

        $return['tagsInfo'] = array_values($user_tags);

        $this->send($return);
    }

    /**
     * 更新昵称、性别
     */
    public function actionUpdate()
    {
        if(isset($_POST['User']))
        {
            $model=$this->loadModel(Yii::app()->api->id);
            $model->scenario='api_user_update';
            $model->attributes=$_POST['User'];
            if ($model->validate())
            {
                if($model->save(false) && $this->log('更新用户',ManageLog::user,ManageLog::update))
                    // 修改成功
                    $this->send();
                else
                    $this->send_error_form($this->form_error($model));
            } else
            {
                $this->send_error_form($this->form_error($model));
            }

        }
        $this->send_csrf();
    }

    /**
     * 标签列表
     * @param $id 父标签分类的id
     */
    public function actionTags_index($id)
    {
        $tags = array(
            'select_link' => Yii::app()->params['app_api_domain'] . Yii::app()->createUrl('/api/user/tags_select', array('id'=>$id)),
            'tag_count' => Yii::app()->params['tags']['user'],
            'select_tags' => array(),
            'not_select_tags' => array()
        );
        if ($id) {
            $select_tags = array();
            $select_ids = array();
            $not_select_tags = array();
            // 选过的标签
            $select_list = TagsElement::user_select_tags_type_son(Yii::app()->api->id, $id);
            foreach($select_list as $val) {
                $select_ids[] = $val->TagsElement_TagsType->id;
                $select_tags[] = array(
                    'value' => $val->TagsElement_TagsType->id,
                    'p_value' => $val->TagsElement_TagsType->p_id,
                    'name' => $val->TagsElement_TagsType->name,
                );
            }
            // 全部标签
            $all_list = TagsType::user_tages_type_p($id);
            foreach($all_list as $tag) {
                if (! in_array($tag->id, $select_ids)) {
                    $not_select_tags[] = array(
                        'value' => $tag->id,
                        'p_value' => $tag->p_id,
                        'name' => $tag->name,
                    );
                }
            }

            // 返回
            $tags['select_tags'] = $select_tags;
            $tags['not_select_tags'] = $not_select_tags;

        }
        $this->send($tags);
    }

    /**
     * 选择标签
     * @param $id
     */
    public function actionTags_select($id)
    {
        if ($id <= 0) {
            $this->send_error(DATA_NOT_SCUSSECS);
            exit;
        }

        if(isset($_POST['TagsElement']))
        {
            $select_tags_count = explode(',', $_POST['TagsElement']['user_select_tags_type']);
            if (count($select_tags_count) > Yii::app()->params['tags']['user']) {
                $this->send_error(DATA_NOT_SCUSSECS);
                exit;
            }

            $user_id = Yii::app()->api->id;
            $model=$this->loadModel($user_id,'`status`=1');
            $model->User_TagsElement = new TagsElement;

            $model->User_TagsElement->scenario='user_tags_type';

            //用户原来选择的标签分类
            // 选过的标签
            $select_tags_type = TagsElement::user_select_tags_type_son($user_id, $id);
            $select=array();
            if($select_tags_type)
            {
                foreach ($select_tags_type as $type)
                    $select[]=$type->TagsElement_TagsType->id;
            }

            $model->User_TagsElement->attributes=$_POST['TagsElement'];
            $select_tags = explode(',', $model->User_TagsElement->user_select_tags_type);
            $select_count = count($select_tags);
            $yes_select_tags=TagsType::user_tages_type(true,$id);//可以选择的tag_type
            // $select_tags 用户选择的标签id
            // 过滤非法标签
            foreach ($select_tags as $k=>$v){
                if(! in_array($v, $yes_select_tags))
                    unset($select_tags[$k]);
            }
            $select_tags = array_unique($select_tags);

            if (count($select_tags) != $select_count || empty($select_tags)) {
                $this->send_error(DATA_NOT_SCUSSECS);
            }

            $deletes=$saves=array();
            foreach ($select as $type)
            {
                if(!in_array($type, $select_tags))
                    $deletes[]=$type;
            }

            foreach ($select_tags as $tags)
            {
                if(!in_array($tags, $select))
                    $saves[]=$tags;
            }

            if(!empty($saves) || !empty($deletes))
            {
                $transaction = $model->dbConnection->beginTransaction();
                try {
                    if(!empty($saves)){
                        foreach($saves as $save)
                        {
                            $user_type=clone $model->User_TagsElement;
                            $user_type->select_id=$user_id;
                            $user_type->select_type=TagsElement::user;
                            $user_type->element_id=$user_id;
                            $user_type->element_type=TagsElement::tags_user;
                            $user_type->type_id=$save;
                            //创建
                            if(!$user_type->save())
                            {
                                throw new Exception("添加用户属性标签错误");
                                continue;
                            }
                        }
                    }
                    if(!empty($deletes)){
                        $criteria = new CDbCriteria;
                        $criteria->addInCondition('type_id', $deletes) ;
                        $criteria->addColumnCondition(array(
                            'element_type'=>TagsElement::tags_user,
                            'element_id'=>$user_id,
                        ));
                        // 删除
                        TagsElement::model()->deleteAll($criteria);
                    }
                    $return=$this->log('更新用户属性标签',ManageLog::user,ManageLog::update);
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    $this->error_log($e->getMessage(),ErrorLog::user,ErrorLog::update,ErrorLog::rollback,__METHOD__);
                    $this->send_error(DATA_NOT_SCUSSECS);
                }
            }
            if(isset($return))
                $this->send();
        }
        $this->send_csrf();
    }

    /**
     * 设置密码
     */
    public function actionPwd()
    {
        if(isset($_POST['User'])) 
        {
            $id = Yii::app()->api->id;
            $model=$this->loadModel($id, '`status`=1');

            $model->scenario='api_pwd_update';
            $model->attributes = $_POST['User'];
    
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
     * 银行信息列表
     */
    public function actionBank_index()
    {

        $bank_list = Bank::bank_data();

        $this->send($bank_list);
    }


    /**
     * 绑定银行卡====创建
     */
    public function actionBank(){

        $id = Yii::app()->api->id;
        $model = $this->loadModel($id, '`status`=1');

        $model->scenario='user_bank';

        if(isset($_POST['User']) && isset($_POST['BankCard']) && isset($_POST['User']['sms']))
        {
            $model->attributes=array('phone'=>$model->phone,'sms'=>$_POST['User']['sms']);
            //验证====验证码是否正确
            if($model->validate() &&  $model->verifycode_bank())
            {
                //验证银行卡ID
                if (! Bank::model()->find('`id`=:id',array(':id'=>$_POST['BankCard']['bank_id']))) {
                    $this->send_error(DATA_NOT_SCUSSECS);
                }

                //验证银行卡银行
                $model->User_BankCard = new BankCard();
                $model->User_BankCard->scenario='user_bank';
                $model->User_BankCard->attributes = $_POST['BankCard'];

                if ($model->User_BankCard->validate())
                {
                    $model->User_BankCard->manage_id 	= $id;
                    $model->User_BankCard->manage_who	= BankCard::user;
                    $model->User_BankCard->card_type		= BankCard::user;
                    $model->User_BankCard->card_id		= $id;
                    $set_card				                = BankCard::set_card_type_id(BankCard::user);
                    $model->User_BankCard->$set_card		= $id;

                    if($model->User_BankCard->save(false) && $this->log('更新/设置用户的银行信息',ManageLog::user,ManageLog::update))
                    {
                        //成功
                        $return = array(
                            'status' => STATUS_SUCCESS,
                        );
                        $this->send($return);
                    }
                    else
                      $this->send_error_form($this->form_error($model->User_BankCard));
                }else
                    $this->send_error_form($this->form_error($model->User_BankCard));
              }else
                $this->send_error_form($this->form_error($model));
        }else
            $this->send_csrf();

    }
    /**
     * 绑定银行卡====更新
     */
    public function actionBank_update($id){

        $model = $this->loadModel(Yii::app()->api->id, '`status`=1');

        $model->scenario='user_bank';

        if(isset($_POST['User']) && isset($_POST['BankCard']) && isset($_POST['User']['sms']))
        {
            $model->attributes=array('phone'=>$model->phone,'sms'=>$_POST['User']['sms']);
            //验证====验证码是否正确
            if($model->validate() &&  $model->verifycode_bank())
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
                    $model->User_BankCard->manage_id 	= Yii::app()->api->id;
                    $model->User_BankCard->manage_who	= BankCard::user;
                    $model->User_BankCard->card_type		= BankCard::user;
                    $model->User_BankCard->card_id		= Yii::app()->api->id;
                    $set_card				                = BankCard::set_card_type_id(BankCard::user);
                    $model->User_BankCard->$set_card		= Yii::app()->api->id;

                    if($model->User_BankCard->save(false) && $this->log('更新/设置用户的银行信息',ManageLog::user,ManageLog::update))
                    {
                        //成功
                        $return = array(
                            'status' => STATUS_SUCCESS,
                        );
                        $this->send($return);
                    }
                    else
                        $this->send_error_form($this->form_error($model->User_BankCard));
                }else
                    $this->send_error_form($this->form_error($model->User_BankCard));
            }else
                $this->send_error_form($this->form_error($model));
        }else
            $this->send_csrf();

    }
    /**
     * 发送短信=====设置银行
     */
    public function actionCaptcha_bank_sms()
    {
        $model_user = $this->loadModel(Yii::app()->api->id, '`status`=1 ');
//        //用户端暂不支持代理商绑定银行卡，请联系客服绑定
//        if($model_user->is_organizer == 1)
//            $this->send_error(BIND_BANK_ORGANIZER_NOT);
        if($model_user->phone)
        {
            $model = new SmsApiLoginForm;

            $model->scenario = 'bank_phone';
            $model->attributes=array('phone'=>$model_user->phone);

            if($model->validate())
            {
                //验证
                if($model->user_update_bank_phone_sms())
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
     * 修改手机号====老手机号
     */
    /**
     * 更换手机号  ====  输入旧手机号
     */
    public function actionPhone_old()
    {
        $id = Yii::app()->api->id;
        $model = $this->loadModel($id, '`status`=1');
        $model->scenario='user_update_old';

        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            if($model->validate() && $model->verifycode())
            {
            	 Yii::app()->api->setState($this->_update_phone_key,$this->_update_phone_key_value);
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
        if(!(Yii::app()->api->hasState($this->_update_phone_key)  && Yii::app()->api->getState($this->_update_phone_key)==$this->_update_phone_key_value) )
            $this->send_error(DATA_NOT_SCUSSECS);

        $id = Yii::app()->api->id;
        $model = $this->loadModel($id, '`status`=1');
        $model->scenario='user_update_new';
        if(isset($_POST['User']))
        {
            $model->attributes=$_POST['User'];
            if($model->validate() && $model->verifycode())
            {
                if($model->save(false))
                {
                    // Retinue随行人员模型
                    Retinue::model()->updateAll(array(
                        'phone' => $model->phone
                    ), 'user_id=:user_id AND is_main=1 AND status=1', array(':user_id' => Yii::app()->api->id));
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
            $model=new SmsApiLoginForm;
            $model->scenario='old_phone';
            $model->attributes=array('phone'=>$_POST['phone']);
            //验证手机号及  
            if($model->validate())
            {
                if($model->user_update_old_phone_sms())
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
            if(Yii::app()->api->hasState($this->_update_phone_key)  && Yii::app()->api->getState($this->_update_phone_key)==$this->_update_phone_key_value)
            {
                $model=new SmsApiLoginForm;
                $model->scenario='new_phone';
                $model->attributes=array('phone'=>$_POST['phone']);
                //验证手机号
                if($model->validate())
                {
                    if($model->user_update_new_phone_sms())
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
            $model=new SmsApiLoginForm;
            $model->scenario='pwd_phone';
            $model->attributes=array('phone'=>$_POST['phone']);
            if($model->validate())
            {
                //验证
                if($model->user_update_pwd_phone_sms())
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