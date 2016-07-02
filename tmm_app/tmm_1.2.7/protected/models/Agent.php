<?php

/**
 * This is the model class for table "{{agent}}".
 *
 * The followings are the available columns in table '{{agent}}':
 * @property string $id
 * @property string $admin_id
 * @property string $phone
 * @property string $password
 * @property integer $merchant_count
 * @property double $push
 * @property integer $is_push
 * @property string $firm_name
 * @property string $area_id_p
 * @property string $area_id_m
 * @property string $area_id_c
 * @property string $address
 * @property string $firm_tel
 * @property string $firm_postcode
 * @property string $bl_code
 * @property string $bl_img
 * @property string $tax_img
 * @property string $occ_img
 * @property string $com_contacts
 * @property string $com_identity
 * @property string $com_phone
 * @property string $manage_name
 * @property string $manage_identity
 * @property string $manage_phone
 * @property string $identity_hand
 * @property string $identity_before
 * @property string $identity_after
 * @property string $bank_id
 * @property string $bank_branch
 * @property string $bank_name
 * @property string $bank_code
 * @property string $deposit
 * @property string $income_count
 * @property string $cash
 * @property string $money
 * @property string $count
 * @property string $login_error
 * @property string $error_count
 * @property string $login_time
 * @property string $login_ip
 * @property string $last_time
 * @property string $last_ip
 * @property string $login_unique
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Agent extends CActiveRecord
{
	/**
	 * 登录唯一
	 * @var unknown
	 */
	const login_unique = 'login_unique';
	/**
	 * 登录消息
	 * @var unknown
	 */
	const login_unique_info = 'login_unique_info';
    /**
     * 未设置分成比例
     * @var integer
     */
    const no_push=0;
    /**
     * 已设置分成比例
     * @var integer
     */
    const yes_push=1;
    /**
     * 解释字段 is_push 的含义
     * @var array
     */
    public static $_is_push=array('未设置分成比例','已设置分成比例');
    
    /**
     * 删除
     */
    const status_del = -1;
    /**
     * 禁用
     */
    const status_dis = 0;
    /**
     * 正常
     */
    const status_suc = 1;
    /**
     * 解释字段 status 的含义
     * @var array
     */
    public static $_status=array(-1=>'删除',0=>'禁用',1=>'正常');
    
    /**
     * 搜索的时间类型
     * @var array
     */
    public $search_time_type;
    /**
     *	解释搜索类型时间的含义 search_time_type
     * @var string
     */
    public static $_search_time_type=array('登录时间','上次登录时间','创建时间','更新时间');
    /**
     *	解释搜索类型时间 search_time_type 的字段搜索
     * @var string
     */
    public $__search_time_type=array('login_time','last_time','add_time','up_time');
    /**
     * 搜索开始的时间
     * @var string
     */
    public $search_start_time;
    /**
     * 搜索结束的时间
     * @var string
     */
    public $search_end_time;
    /**
     * 密码
     * @var string
     */
    public $_pwd;
    /**
     * 确认密码
     * @var string
     */
    public $confirm_pwd;
    /**
     * 新密码
     * @var string
     */
    public $new_pwd;
    /**
     * 原来密码
     * @var string
     */
    public $old_pwd;
    /**
     * 区域选择
     * @var string
     */
    public $area_select;

    /**
     * 短信
     */
    public $sms;
    /**
     * 验证码
     */
    public $verify;
    /**
     * 
     * @var unknown
     */
    protected $is_validator=false;
    
     
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{agent}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('merchant_count, status', 'numerical', 'integerOnly'=>true),
            array('push', 'numerical'),
            array('admin_id, area_id_p, area_id_m, area_id_c, bank_id, count, login_error, error_count, login_time, last_time, add_time, up_time', 'length', 'max'=>11),
            array('com_phone, manage_phone, login_ip, last_ip', 'length', 'max'=>15),
            array('password', 'length','min'=>32,'max'=>64),
            array('firm_name, address, bl_code, bl_img, tax_img, occ_img, identity_hand, identity_before, identity_after, bank_branch', 'length', 'max'=>100),
            array('firm_tel, com_contacts, com_identity, manage_name, manage_identity, bank_name', 'length', 'max'=>20),
            array('firm_postcode', 'length', 'max'=>10),
            array('bank_code', 'length', 'max'=>50),
            array('deposit, income_count, cash, money', 'length', 'max'=>13),
            array('phone', 'length','min'=>11, 'max'=>11),
            
            array('firm_name,address', 'length', 'max'=>25),
            // 创建，更新
            array('phone', 'unique'),
            array('_pwd, confirm_pwd',
                'required', 'on' => 'create'),
            array('phone,firm_name, area_id_p,area_id_m, area_id_c, address, firm_tel, firm_postcode, bl_code,com_contacts, com_identity, com_phone, manage_name, manage_identity,manage_phone',
                'required','on' => 'create,update'),
            array('phone, _pwd, confirm_pwd, firm_name, area_id_p,area_id_m, area_id_c, address, firm_tel, firm_postcode, bl_code,com_contacts, com_identity, com_phone, manage_name, manage_identity,manage_phone',
                'safe','on'=>'create,update'),
            array('search_time_type,search_start_time,search_end_time,id, admin_id,password, merchant_count,push, bl_img, tax_img, occ_img, identity_hand, identity_before, identity_after,bank_id, bank_branch, bank_name, bank_code, deposit, income_count, cash, money, count, login_error,error_count, login_time, login_ip, last_time, last_ip, add_time, up_time, status',
                'unsafe','on'=>'create,update'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'message'=>'{attribute} 不是有效的'),
            array('_pwd, confirm_pwd', 'length','min'=>8,'max'=>20,'on'=>'create,update'),
            array('_pwd','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/','message'=>'密码必须有数字和字母组合！','on'=>'create,update'),
            array('confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'create,update'),
            array('firm_tel,com_phone,manage_phone','match','pattern'=>'/^1\d{10}$|^(0\d{2,3}-?|\(0\d{2,3}\))?[1-9]\d{4,7}(-\d{1,8})?$/','message'=>'{attribute} 不是有效的','on'=>'create,update'),
            array('firm_postcode','match','pattern'=>'/[1-9]\d{5}(?!\d)/','message'=>'{attribute} 不是有效的','on'=>'create,update'),
            array('area_id_p, area_id_m, area_id_c','ext.Validator.Validator_area','on'=>'create,update'),
            array('com_identity,manage_identity','ext.Validator.Validator_identity','on'=>'create,update'),

            //代理商修改当前手机号码====老手机号
            array('phone,sms,verify', 'required','on'=>'agent_update_old'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'agent_update_old'),
            array('phone,sms,verify', 'safe', 'on'=>'agent_update_old'),    
            array('search_time_type,search_start_time,search_end_time, admin_id, password, merchant_count, push,is_push, firm_name, area_id_p, area_id_m, area_id_c, address, firm_tel, firm_postcode, bl_code, bl_img, tax_img, occ_img, com_contacts, com_identity, com_phone, manage_name, manage_identity, manage_phone, identity_hand, identity_before, identity_after, bank_id, bank_branch, bank_name, bank_code, deposit, income_count, cash, money, count, login_error, error_count, login_time, login_ip, last_time, last_ip, add_time, up_time, status', 'unsafe', 'on'=>'agent_update_old'),
            //代理商修改当前手机号码====新手机号
            array('phone,sms', 'required','on'=>'agent_update_new'),
            array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'agent_update_new'),
            array('phone,sms', 'safe', 'on'=>'agent_update_new'),
            array('search_time_type,search_start_time,search_end_time, admin_id, password, merchant_count, push,is_push, firm_name, area_id_p, area_id_m, area_id_c, address, firm_tel, firm_postcode, bl_code, bl_img, tax_img, occ_img, com_contacts, com_identity, com_phone, manage_name, manage_identity, manage_phone, identity_hand, identity_before, identity_after, bank_id, bank_branch, bank_name, bank_code, deposit, income_count, cash, money, count, login_error, error_count, login_time, login_ip, last_time, last_ip, add_time, up_time, status', 'unsafe', 'on'=>'agent_update_new'),
            array('phone','is_validator','on'=>'agent_update_old,agent_update_new'),
            //代理商绑定银行卡====手机短信验证
            array('sms,verify', 'required','on'=>'agent_bind_bank'),
            array('sms,verify', 'safe', 'on'=>'agent_bind_bank'),
            array('search_time_type,search_start_time,phone,search_end_time, admin_id, password, merchant_count, push,is_push, firm_name, area_id_p, area_id_m, area_id_c, address, firm_tel, firm_postcode, bl_code, bl_img, tax_img, occ_img, com_contacts, com_identity, com_phone, manage_name, manage_identity, manage_phone, identity_hand, identity_before, identity_after, bank_id, bank_branch, bank_name, bank_code, deposit, income_count, cash, money, count, login_error, error_count, login_time, login_ip, last_time, last_ip, add_time, up_time, status', 'unsafe', 'on'=>'agent_bind_bank'),
            //代理商更改手机密码====手机短信验证
            array('sms,verify', 'required','on'=>'agent_update_pwd'),
            array('sms,verify', 'safe', 'on'=>'agent_update_pwd'),
            array('search_time_type,search_start_time,phone,search_end_time, admin_id, password, merchant_count, push,is_push, firm_name, area_id_p, area_id_m, area_id_c, address, firm_tel, firm_postcode, bl_code, bl_img, tax_img, occ_img, com_contacts, com_identity, com_phone, manage_name, manage_identity, manage_phone, identity_hand, identity_before, identity_after, bank_id, bank_branch, bank_name, bank_code, deposit, income_count, cash, money, count, login_error, error_count, login_time, login_ip, last_time, last_ip, add_time, up_time, status', 'unsafe', 'on'=>'agent_update_pwd'),
            //代理商手机密码====新密码验证
            array('_pwd, confirm_pwd', 'required', 'on' => 'agent_new_pwd'),
            array('_pwd, confirm_pwd', 'length','min'=>8,'max'=>20,'on'=>'agent_new_pwd'),
            array('_pwd','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/','message'=>'密码必须有数字和字母组合！','on'=>'agent_new_pwd'),
            array('confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'agent_new_pwd'),
            // 分成比例验证
            // array('push', 'ext.Validator.Validator_push', 'on'=>'create,update'),
            // 图片上传 bl_img, identity_hand, identity_before, identity_after
            array(
                'bl_img, identity_hand, identity_before, identity_after','file','allowEmpty'=>true,
                'types'=>'jpg,gif,png', 'maxSize'=>1024*1024*2,
                'tooLarge'=>'图片超过2M,请重新上传', 'wrongType'=>'图片格式错误',
                'on'=>'create,update'
            ),

            // 设置银行信息
            array('bank_id, bank_branch, bank_name, bank_code','required','on'=>'bank'),
            array('bank_id, bank_branch, bank_name, bank_code','safe','on'=>'bank'),
            array('search_time_type,search_start_time,search_end_time,id, admin_id, phone, password, merchant_count, push, firm_name, area_id_p, area_id_m, area_id_c, address, firm_tel, firm_postcode, bl_code, bl_img, tax_img, occ_img, com_contacts, com_identity, com_phone, manage_name, manage_identity, manage_phone, identity_hand, identity_before, identity_after, deposit, income_count, cash, money, count, login_error, error_count, login_time, login_ip, last_time, last_ip, add_time, up_time, status', 'unsafe', 'on'=>'bank'),
            array('bank_id','ext.Validator.Validator_bank','on'=>'bank'),

            // 设置区域权限
            array('area_select','required', 'message'=>'{attribute} 未改变不可提交', 'on'=>'area'),
            array('area_select','safe','on'=>'area'),
            array('search_time_type,search_start_time,search_end_time,id, admin_id, phone, password, merchant_count, push,is_push, firm_name, area_id_p, area_id_m, area_id_c, address, firm_tel, firm_postcode, bl_code, bl_img, tax_img, occ_img, com_contacts, com_identity, com_phone, manage_name, manage_identity, manage_phone, identity_hand, identity_before, identity_after, bank_id, bank_branch, bank_name, bank_code, deposit, income_count, cash, money, count, login_error, error_count, login_time, login_ip, last_time, last_ip, add_time, up_time, status',
                'unsafe', 'on'=>'area'),
            
            //修改密码 
            array('old_pwd, new_pwd, confirm_pwd', 'required', 'on'=>'pwd'),
            array('old_pwd, new_pwd, confirm_pwd', 'safe', 'on'=>'pwd'),
            array('new_pwd, confirm_pwd', 'length', 'min'=>8, 'max'=>20, 'on'=>'pwd'),
            array('new_pwd', 'match', 'pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/', 'message'=>'密码必须有数字和字母组合！','on'=>'pwd'),
            array('confirm_pwd', 'compare', 'compareAttribute'=>'new_pwd', 'on'=>'pwd'),
            array('old_pwd', 'isOldPwd', 'on'=>'pwd'),
            array('search_time_type,search_start_time,search_end_time,id, admin_id, phone, password, merchant_count, push,is_push, firm_name, area_id_p, area_id_m, area_id_c, address, firm_tel, firm_postcode, bl_code, bl_img, tax_img, occ_img, com_contacts, com_identity, com_phone, manage_name, manage_identity, manage_phone, identity_hand, identity_before, identity_after, bank_id, bank_branch, bank_name, bank_code, deposit, income_count, cash, money, count, login_error, error_count, login_time, login_ip, last_time, last_ip, add_time, up_time, status', 'unsafe', 'on'=>'pwd'),
            
            //短信修改密码
            array('sms, new_pwd, confirm_pwd', 'required', 'on'=>'smsSetPwd'),
            array('sms, new_pwd, confirm_pwd', 'safe', 'on'=>'smsSetPwd'),
            array('new_pwd, confirm_pwd', 'length', 'min'=>8, 'max'=>20, 'on'=>'smsSetPwd'),
            array('new_pwd', 'match', 'pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/', 'message'=>'密码必须有数字和字母组合！','on'=>'smsSetPwd'),
            array('confirm_pwd', 'compare', 'compareAttribute'=>'new_pwd', 'on'=>'smsSetPwd'),
            array('sms', 'isSms', 'on'=>'smsSetPwd'),
            array('search_time_type,search_start_time,search_end_time,id, admin_id, phone, password, merchant_count, push,is_push, firm_name, area_id_p, area_id_m, area_id_c, address, firm_tel, firm_postcode, bl_code, bl_img, tax_img, occ_img, com_contacts, com_identity, com_phone, manage_name, manage_identity, manage_phone, identity_hand, identity_before, identity_after, bank_id, bank_branch, bank_name, bank_code, deposit, income_count, cash, money, count, login_error, error_count, login_time, login_ip, last_time, last_ip, add_time, up_time, status', 'unsafe', 'on'=>'smsSetPwd'),
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('search_time_type,search_start_time,search_end_time,id, admin_id, phone, password, merchant_count, push,is_push, firm_name, area_id_p, area_id_m, area_id_c, address, firm_tel, firm_postcode, bl_code, bl_img, tax_img, occ_img, com_contacts, com_identity, com_phone, manage_name, manage_identity, manage_phone, identity_hand, identity_before, identity_after, bank_id, bank_branch, bank_name, bank_code, deposit, income_count, cash, money, count, login_error, error_count, login_time, login_ip, last_time, last_ip, add_time, up_time, status', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            // 关联管理员
            'Agent_Admin' => array(self::BELONGS_TO, 'Admin', 'admin_id'),
            // 关联地址，取省名称
            'Agent_area_id_p_Area_id' => array(self::BELONGS_TO, 'Area', 'area_id_p'),
            // 关联地址，取市名称
            'Agent_area_id_m_Area_id' => array(self::BELONGS_TO, 'Area', 'area_id_m'),
            // 关联地址，取县（区）名称
            'Agent_area_id_c_Area_id' => array(self::BELONGS_TO, 'Area', 'area_id_c'),
            // 关联银行
            'Agent_Bank'=>array(self::BELONGS_TO,'Bank','bank_id'),
            //保障金详情
            'Agent_deposit'=>array(self::HAS_MANY,'DepositLog','deposit_id'),
            //代理商区域详情
            'Agent_area_info'=>array(self::HAS_MANY,'Area','agent_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'admin_id' => '创建人',
            'phone' => '登录手机号',
            'password' => '密码',
            'merchant_count' => '商家量',
            'push' => '分成比例',
            'is_push' => '是否设置比例',
            'firm_name' => '公司名称',
            'area_id_p' => '省',
            'area_id_m' => '市',
            'area_id_c' => '县(区)',
            'address' => '详细地址',
            'firm_tel' => '公司电话',
            'firm_postcode' => '公司邮编',
            'bl_code' => '营业执照号码',
            'bl_img' => '营业执照图',
            'tax_img' => '税务登记证',
            'occ_img' => '组织机构证',
            'com_contacts' => '法人名称',
            'com_identity' => '法人身份证',
            'com_phone' => '法人电话',
            'manage_name' => '负责人姓名',
            'manage_identity' => '身份证号',
            'manage_phone' => '联系电话',
            'identity_hand' => '手执身份证',
            'identity_before' => '身份证前',
            'identity_after' => '身份证后',
            'bank_id' => '开户银行',
            'bank_branch' => '开户支行',
            'bank_name' => '开户姓名',
            'bank_code' => '银行账号',
            'deposit' => '剩余保证金',
            'income_count' => '收益总额',
            'cash' => '已提现总额',
            'money' => '可提现金额',
            'count' => '总登录次数',
            'login_error' => '登录错误次数',
            'error_count' => '错误次数统计',
            'login_time' => '登录时间',
            'login_ip' => '登录ip',
            'last_time' => '上次登录时间',
            'last_ip' => '上次登录ip',
            'add_time' => '创建时间',
            'up_time' => '更新时间',
            'status' => '状态',
            'search_time_type'=>'时间类型',
            'search_start_time'=>'开始时间',
            'search_end_time'=>'结束时间',
            'area_select' => '区域选择',
            '_pwd'=>'密码',
            'confirm_pwd' => '确认密码',
            'sms' => '手机验证码',
        	'new_pwd'=>'新密码',
        	'old_pwd'=>'原密码',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search($criteria='')
    {
        // @todo Please modify the following code to remove attributes that should not be searched.
        if($criteria ===''){
            $criteria=new CDbCriteria;
            $criteria->with = array(
                'Agent_Admin'=>array('select'=>'name'),
                'Agent_area_id_p_Area_id'=>array('select'=>'name'),
                'Agent_area_id_m_Area_id'=>array('select'=>'name'),
                'Agent_area_id_c_Area_id'=>array('select'=>'name'),
                'Agent_Bank'=>array('select'=>'id,name'),
            );
            $criteria->compare('t.status','<>-1');
            if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
            {
                if($this->search_start_time !='' && $this->search_end_time !='')
                    $criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
                elseif($this->search_start_time !='' && $this->search_end_time =='')
                    $criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
                elseif($this->search_start_time =='' && $this->search_end_time !='')
                    $criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
            }
            $criteria->compare('t.id',$this->id,true);
            $criteria->compare('Agent_Admin.name',$this->admin_id,true);
            $criteria->compare('t.phone',$this->phone,true);
            $criteria->compare('t.password',$this->password,true);
            $criteria->compare('t.merchant_count',$this->merchant_count);
            $criteria->compare('t.push',$this->push);
            $criteria->compare('t.is_push',$this->is_push);
            $criteria->compare('t.firm_name',$this->firm_name,true);
            $criteria->compare('Agent_area_id_p_Area_id.name',$this->area_id_p,true);
            $criteria->compare('Agent_area_id_m_Area_id.name',$this->area_id_m,true);
            $criteria->compare('Agent_area_id_c_Area_id.name',$this->area_id_c,true);
            $criteria->compare('t.address',$this->address,true);
            $criteria->compare('t.firm_tel',$this->firm_tel,true);
            $criteria->compare('t.firm_postcode',$this->firm_postcode,true);
            $criteria->compare('t.bl_code',$this->bl_code,true);
            $criteria->compare('t.bl_img',$this->bl_img,true);
            $criteria->compare('t.tax_img',$this->tax_img,true);
            $criteria->compare('t.occ_img',$this->occ_img,true);
            $criteria->compare('t.com_contacts',$this->com_contacts,true);
            $criteria->compare('t.com_identity',$this->com_identity,true);
            $criteria->compare('t.com_phone',$this->com_phone,true);
            $criteria->compare('t.manage_name',$this->manage_name,true);
            $criteria->compare('t.manage_identity',$this->manage_identity,true);
            $criteria->compare('t.manage_phone',$this->manage_phone,true);
            $criteria->compare('t.identity_hand',$this->identity_hand,true);
            $criteria->compare('t.identity_before',$this->identity_before,true);
            $criteria->compare('t.identity_after',$this->identity_after,true);
            $criteria->compare('Agent_Bank.name',$this->bank_id,true);
            $criteria->compare('t.bank_branch',$this->bank_branch,true);
            $criteria->compare('t.bank_name',$this->bank_name,true);
            $criteria->compare('t.bank_code',$this->bank_code,true);
            $criteria->compare('t.deposit',$this->deposit,true);
            $criteria->compare('t.income_count',$this->income_count,true);
            $criteria->compare('t.cash',$this->cash,true);
            $criteria->compare('t.money',$this->money,true);
            $criteria->compare('t.count',$this->count,true);
            $criteria->compare('t.login_error',$this->login_error,true);
            $criteria->compare('t.error_count',$this->error_count,true);
            if($this->login_time != '')
                $criteria->addBetweenCondition('t.login_time',strtotime($this->login_time),(strtotime($this->login_time)+3600*24-1));
            $criteria->compare('t.login_ip',$this->login_ip,true);
            if($this->last_time != '')
                $criteria->addBetweenCondition('t.last_time',strtotime($this->last_time),(strtotime($this->last_time)+3600*24-1));
            $criteria->compare('t.last_ip',$this->last_ip,true);
            if($this->add_time != '')
                $criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
            if($this->up_time != '')
                $criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
            $criteria->compare('t.status',$this->status);
        }
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>Yii::app()->params['admin_pageSize'],
            ),
            'sort'=>array(
                'defaultOrder'=>'t.add_time desc', //设置默认排序
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Agent the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * 错误登录次数添加
     * @return int
     */
    public function login_error()
    {
    	return $this->updateByPk($this->id, array(
            'login_error'=>new CDbExpression('`login_error`+1'),
            'error_count'=>new CDbExpression('`error_count`+1'),
        ));
    }

    /**
     * 登录后更新的数据
     * @param $time
     * @return int
     */
    public function set_login($time, $login_unique)
    {
        return $this->updateByPk($this->id,array(
            'last_ip'=>$this->login_ip,
            'last_time'=>$this->login_time,
            'login_ip'=>Yii::app()->request->userHostAddress,
            'login_time'=>$time,
            'login_error'=>0,
            'count'=>new CDbExpression('`count`+1'),
        	'login_unique'=>$login_unique,
        ));
    }
    
    /**
     * 登录状态
     */
    public function login_status($_this, $isSms = false)
    {
    	if ($isSms)
    	{
    		if ($this->status == -1)//删除，没有这个用户
    			$_this->addError('phone', '用户名或验证码错误');
    		else if($this->status == 0)
    			$_this->addError('phone', '用户名 被禁用了 请联系管理员');
    		else if($this->status != 1)
    			$_this->addError('phone', '用户名或验证码错误');
    	}
    	else
    	{
	        if ($this->status == -1)//删除，没有这个用户
	            $_this->addError('password', '用户名或密码错误');
	        else if ($this->status == 0)
	            $_this->addError('password', '用户名 被禁用了 请联系管理员');
	        else if ($this->status != 1)
	            $_this->addError('password', '用户名或密码错误');
    	}
    }

    /**
     * 保存之前的操作
     * @see CActiveRecord::beforeSave()
     */
    public function beforeSave()
    {
        if(parent::beforeSave())
        {
            if($this->isNewRecord)
            {
                $time=time();
                if(isset($this->add_time))
                    $this->add_time=$time;
                if(isset($this->up_time))
                    $this->up_time=$time;
            }
            else
            {
                if(isset($this->up_time))
                    $this->up_time=time();
            }
            return true;
        }else
            return false;
    }

    /**
     * 验证密码是否对
     * @param $pwd
     * @param $password
     * @return bool
     */
    public function validatePassword($pwd,$password)
    {
        return self::pwdEncrypt($pwd) === $password;
    }

    /**
     * 密码加密函数
     * @param $pwd
     * @return string
     */
    public static function pwdEncrypt($pwd)
    {
        return md5(md5($pwd));
    }

    /**
     * 限制登录错误次数
     */
    public function login_error_confine($_this)
    {
    	if($this->login_error > Yii::app()->params['agent_login_error']) 
    	{
    		$_this->addError('password', '登录错误次数过多，请联系管理员或明天登录');
    		return false;
    	}
    	return true;
    }
    
    /*
     * 所有代理商
     */
    public static function data()
    {
       return CHtml::listData(self::model()->findAll(array('condition'=>'status=1','select'=>'id,firm_name')),'id', 'firm_name');
    }

    /**
     * 是否验证 
     */
    public function is_validator()
    {
    	$this->is_validator=true;
    }
    
    /**
     * 验证手机短信
     * 修改新手机用 修改旧手机用
     */
    public function verifycode()
    {
    	if(! $this->is_validator)
    		$this->validate();
    	if(!$this->hasErrors())
    	{
	        Yii::import('ext.Send_sms.Send_sms');
	        $params=array(
	            'sms_id'=>Yii::app()->agent->id,
	            'sms_type'=>SmsLog::sms_agent,
	            'role_id'=>Yii::app()->agent->id,
	            'role_type'=>SmsLog::send_agent,
	            'sms_use'=>SmsLog::use_phone,
	        );
	        if (Send_sms::verifycode($this->phone, $params, $this->sms))
	            return true;
    	}
        $this->addError('sms', '手机验证码 错误');
        return false;
    }
    /**
     * 验证手机短信
     * 修改新手机用 修改旧手机用
     */
    public function verifycode_bank()
    {
        if(! $this->is_validator)
            $this->validate();
        if(!$this->hasErrors())
        {
            Yii::import('ext.Send_sms.Send_sms');
            $params=array(
                'sms_id'=>Yii::app()->agent->id,
                'sms_type'=>SmsLog::sms_agent,
                'role_id'=>Yii::app()->agent->id,
                'role_type'=>SmsLog::send_agent,
                'sms_use'=>SmsLog::use_bank,
            );
            if (Send_sms::verifycode($this->phone, $params, $this->sms))
                return true;
        }
        $this->addError('sms', '手机验证码 错误');
        return false;
    }
    /**
     * 验证手机短信
     * 修改新手机用 修改旧手机用
     */
    public function verifycode_pwd()
    {
        if(! $this->is_validator)
            $this->validate();
        if(!$this->hasErrors())
        {
            Yii::import('ext.Send_sms.Send_sms');
            $params=array(
                'sms_id'=>Yii::app()->agent->id,
                'sms_type'=>SmsLog::sms_agent,
                'role_id'=>Yii::app()->agent->id,
                'role_type'=>SmsLog::send_agent,
                'sms_use'=>SmsLog::use_password,
            );
            if (Send_sms::verifycode($this->phone, $params, $this->sms))
                return true;
        }
        $this->addError('sms', '手机验证码 错误');
        return false;
    }
    
   	/**
   	 * 短信验证
   	 * @return boolean
   	 */
    public function isSms()
    {
    	if( !$this->hasErrors())
    	{
    		Yii::import('ext.Send_sms.Send_sms');
    		$params=array(
    				'sms_id'=>$this->id,
    				'sms_type'=>SmsLog::sms_agent,
    				'role_id'=>$this->id,
    				'role_type'=>SmsLog::send_agent,
    				'sms_use'=>SmsLog::use_password,
    		);
    		if ( !Send_sms::verifycode($this->phone, $params, $this->sms))
    			$this->addError('sms', '短信验证码 错误');
    	}
    }
    
    /**
     * 查看原来的密码对不对
     */
    public function isOldPwd()
    {
    	if (! $this->hasErrors())
    	{
	    	$agent = self::model()->findByPk(Yii::app()->operator->id,array('select'=>'password'));
	    	if (!$this->validatePassword($this->old_pwd, $agent->password))
	    		$this->addError('old_pwd', '原密码错误');
    	}
    }
}
