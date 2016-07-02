<?php

/**
 * This is the model class for table "{{store_user}}".
 *
 * The followings are the available columns in table '{{store_user}}':
 * @property string $id
 * @property string $phone
 * @property string $password
 * @property string $p_id
 * @property string $agent_id
 * @property string $icon
 * @property string $count
 * @property integer $phone_type
 * @property string $login_token
 * @property string $login_mac
 * @property string $last_mac
 * @property string $login_error
 * @property string $error_count
 * @property string $login_ip
 * @property string $login_time
 * @property string $login_address
 * @property string $last_ip
 * @property string $last_time
 * @property string $last_address
 * @property string $add_time
 * @property string $up_time
 * @property string $out_time 
 * @property integer $create_status
 * @property integer $status
 */
class StoreUser extends CActiveRecord
{
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
	public static $_status=array(-1=>'删除','禁用','正常');
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_status_agent=array('禁用','正常');
    /**
	 * 解释字段 audit 的含义
	 * @var array
	 */
	public static $_audit=array(-1=>'未通过','未审核','已通过');
	/**
	 * 解释字段 phone_type 的含义
	 * @var array
	 */
	public static $_phone_type=array(1=>'IOS',2=>'ANDROID');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
    public $__search_time_type=array('login_time','last_time','add_time','up_time','out_time');
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('登录时间','上次登录时间','创建时间','更新时间'); 
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
	 * 确认密码
	 * @var string
	 */
	public $confirm_pwd;
	/**
	 * 密码
	 * @var string
	 */
	public $_pwd;
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
	 * 选中标签
	 */
	public $select_tags;
	/**
	 * 手机验证码
	 * @var unknown
	 */
	public $sms;
	/**
	 * 是否验证
	 * @var
	 */
	public $is_validator;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{store_user}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('phone', 'required'),
			array('phone_type, status,create_status', 'numerical', 'integerOnly'=>true),
			array('phone, login_ip, last_ip', 'length', 'max'=>15),
			array('password', 'length', 'min'=>32,'max'=>32),
			array('p_id, agent_id, count, login_error, error_count', 'length', 'max'=>11),
			array('icon, login_address, last_address', 'length', 'max'=>100),
			array('login_token, login_mac, last_mac', 'length', 'max'=>128),
			array('login_time, last_time, add_time, up_time, out_time', 'length', 'max'=>10),
			// 手机号唯一性验证
			array('phone', 'unique'),
			//创建、修改
			array('phone,_pwd,confirm_pwd', 'required','on'=>'create'),
			array('phone', 'required','on'=>'update'),
			array('_pwd,confirm_pwd','length','min'=>8,'max'=>20,'on'=>'create,update'),
			//代理商修改商家子账号信息
			array('phone,sms', 'required','on'=>'agent_update_son'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'agent_update_son'),
			array('phone,sms', 'safe', 'on'=>'agent_update_son'),
			array('search_time_type,search_start_time,search_end_time,id,password, p_id, agent_id, icon, count, phone_type, login_token, login_mac, last_mac, login_error, error_count, login_ip, login_time, login_address, last_ip, last_time, last_address, add_time, up_time, out_time,create_status, status',
				'unsafe',
				'on'=>'agent_update_son'),

			//代理商创建商家子账号信息
			array('phone,sms,_pwd,confirm_pwd', 'required','on'=>'agent_create_son'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'agent_create_son'),
			array('_pwd','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/','message'=>'密码必须有数字和字母组合！','on'=>'agent_create_son'),
			array('confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'agent_create_son'),
			array('phone,sms,_pwd,confirm_pwd', 'safe', 'on'=>'agent_create_son'),
			array('search_time_type,search_start_time,search_end_time,id,password, p_id, agent_id, icon, count, phone_type, login_token, login_mac, last_mac, login_error, error_count, login_ip, login_time, login_address, last_ip, last_time, last_address, add_time, up_time, out_time,create_status, status',
				'unsafe',
				'on'=>'agent_create_son'),


			//密码
			array('_pwd','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/','message'=>'密码必须有数字和字母组合！','on'=>'create,update'),
			array('confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'create,update'),
			//是否接收表单值
			array('phone,_pwd,confirm_pwd','safe','on'=>'create,update'),
			array('search_time_type,search_start_time,search_end_time,id, password, p_id, agent_id, icon, count, phone_type, login_token, login_mac, last_mac, login_error, error_count, login_ip, login_time, login_address, last_ip, last_time, last_address, add_time, up_time, out_time, create_status,status','unsafe','on'=>'create,update'),
			array('phone','match','pattern'=>'/^1[3458][0-9]{9}$/','message'=>'{attribute} 不是有效的'),

			//代理商创建商家主账号第一步
			array('phone,sms,_pwd,confirm_pwd','required','on'=>'agent_create_1'),
			array('_pwd','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/','message'=>'密码必须有数字和字母组合！','on'=>'agent_create_1'),
			array('confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'agent_create_1'),
			array('phone,sms,_pwd,confirm_pwd', 'safe', 'on'=>'agent_create_1'),
			array('search_time_type,search_start_time,search_end_time,id,password, p_id, agent_id, icon, count, phone_type, login_token, login_mac, last_mac, login_error, error_count, login_ip, login_time, login_address, last_ip, last_time, last_address, add_time, up_time, out_time,create_status, status', 'unsafe', 'on'=>'agent_create_1'),

			//商家手机端-修改当前手机号码====老手机号
			array('phone,sms', 'required','on'=>'store_update_old'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'store_update_old'),
			array('phone,sms', 'safe', 'on'=>'store_update_old'),
			array('phone','is_old','on'=>'store_update_old'),

			//商家手机端-修改当前手机号码====新手机号
			array('phone,sms', 'required','on'=>'store_update_new'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'store_update_new'),
			array('phone,sms', 'safe', 'on'=>'store_update_new'),
			array('phone','is_new','on'=>'store_update_new'),
			array('search_time_type,search_start_time,search_end_time,id,password, p_id, agent_id, icon, count, phone_type, login_token, login_mac, last_mac, login_error, error_count, login_ip, login_time, login_address, last_ip, last_time, last_address, add_time, up_time, out_time,create_status, status', 'unsafe', 'on'=>'store_update_new'),

			array('phone','is_validator','on'=>'store_update_old,store_update_new'),

			//用户绑定银行呀
			array('phone,sms', 'required','on'=>'store_bank'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'store_bank'),
			array('phone,sms', 'safe', 'on'=>'store_bank'),
			array('phone','is_old','on'=>'store_bank'),

			// 商家手机端-更新密码验证
			array('phone,sms,_pwd,confirm_pwd', 'required','on'=>'store_pwd_update'),
			array('phone', 'match', 'pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'store_pwd_update'),
			array('_pwd, confirm_pwd', 'length','min'=>8,'max'=>20,'on'=>'store_pwd_update'),
			array('_pwd','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/','message'=>'密码必须有数字和字母组合！','on'=>'store_pwd_update'),
			array('confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'store_pwd_update'),
			array('phone','is_validator','on'=>'store_pwd_update'),
			array('phone,sms,_pwd,confirm_pwd', 'safe','on'=>'store_pwd_update'),
			array('search_time_type,search_start_time,search_end_time,id,password, p_id, agent_id, icon, count, phone_type, login_token, login_mac, last_mac, login_error, error_count, login_ip, login_time, login_address, last_ip, last_time, last_address, add_time, up_time, out_time,create_status, status', 'unsafe', 'on'=>'store_pwd_update'),

			// 商家手机端-绑定银行账户
			array('phone,sms,_pwd,confirm_pwd', 'required','on'=>'store_pwd_update'),
			array('phone', 'match', 'pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'store_pwd_update'),
			array('_pwd, confirm_pwd', 'length','min'=>8,'max'=>20,'on'=>'store_pwd_update'),
			array('_pwd','match','pattern'=>'/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]+$/','message'=>'密码必须有数字和字母组合！','on'=>'store_pwd_update'),
			array('confirm_pwd', 'compare', 'compareAttribute'=>'_pwd','on'=>'store_pwd_update'),
			array('phone','is_validator','on'=>'store_pwd_update'),
			array('phone,sms,_pwd,confirm_pwd', 'safe','on'=>'store_pwd_update'),
			array('search_time_type,search_start_time,search_end_time,id,password, p_id, agent_id, icon, count, phone_type, login_token, login_mac, last_mac, login_error, error_count, login_ip, login_time, login_address, last_ip, last_time, last_address, add_time, up_time, out_time,create_status, status', 'unsafe', 'on'=>'store_pwd_update'),


			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array(//'password',
				'sms,search_time_type,search_start_time,search_end_time,id, phone, p_id, agent_id, icon, count, phone_type, login_token, login_mac, last_mac, login_error, error_count, login_ip, login_time, login_address, last_ip, last_time, last_address, add_time, up_time, out_time,create_status, status', 'safe', 'on'=>'search'),
			array('phone,status', 'safe', 'on'=>'agent_store_search'),			
			array('phone,status,search_start_time,search_end_time', 'safe', 'on'=>'agent_store_son_search'),		
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
                return array(
                    'Store_Store_Son'=>array(self::HAS_MANY,'StoreUser','p_id'),// 父级与子在关系
                    'Store_Store'=>array(self::BELONGS_TO,'StoreUser','p_id'),// 子与父级
                    'Store_Content'=>array(self::HAS_ONE,'StoreContent','id'),//账号详情
                    'Store_Agent'=>array(self::BELONGS_TO,'Agent','agent_id'),// 自己与父级
					'Store_TagsElement'=>array(self::HAS_MANY,'TagsElement','element_id'),
                	'Store_Items_Count'=>array(self::STAT,'Items','store_id'),//统计商家多少个项目
                	'Store_Items_Manage_Count'=>array(self::STAT,'Items','manager_id'),//统计管理商家有多少个项目
					//用户关联银行卡信息  2015-12-14
					'Store_BankCard'=>array(self::HAS_MANY,'BankCard','card_id'),
                );
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'phone' => '手机号',
			'password' => '密码',
			'p_id' => '归属商家',
			'agent_id' => '归属运营商',
			'icon' => '商家头像',
			'count' => '登录次数',
			'phone_type' => '手机类型',
			'login_token' => '推送TOKEN',
			'login_mac' => '当次登录MAC',
			'last_mac' => '最近登录MAC',
			'login_error' => '错误次数',
			'error_count' => '错误统计',
			'login_ip' => '登录IP',
			'login_time' => '登录时间',
			'login_address' => '登录地址',
			'last_ip' => '最近登录IP',
			'last_time' => '最近登录时间',
			'last_address' => '最近登录地址',
			'add_time' => '注册时间',
			'up_time' => '更新时间',
			'out_time' => '过期时间',
			'create_status'=>'创建步骤',
			'status' => '状态',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'old_pwd'=>'原来密码',
			'_pwd'=>'密码',
			'new_pwd'=>'新密码',
			'confirm_pwd'=>'确认密码',
			'select_tags'=>'选中标签',
			'sms'=>'手机验证码',
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
                        
			$criteria->compare('t.status','>-1');
                        
			$criteria->with=array(
				'Store_Content'=>array(
					'with'=>array('Content_area_id_p_Area_id','Content_area_id_m_Area_id','Content_area_id_c_Area_id')
				 ),
				'Store_Agent'=>array('select'=>'phone,firm_name'),);
			$criteria->compare('t.p_id','0');
    
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}
                        
                        //关联地址
			if(!! $model_p=Area::name($this->Store_Content->area_id_p)){
				$model_m=Area::name($this->Store_Content->area_id_m);
				if($model_m && $model_p->id != $model_m->pid)
					$this->Store_Content->area_id_m='';	
			}else
				$this->Store_Content->area_id_m='';

			$criteria->compare('Content_area_id_p_Area_id.name',$this->Store_Content->area_id_p,true);
			$criteria->compare('Content_area_id_m_Area_id.name',$this->Store_Content->area_id_m,true);
			$criteria->compare('Content_area_id_c_Area_id.name',$this->Store_Content->area_id_c,true);
			$criteria->compare('Store_Agent.phone',$this->agent_id,true);
			$criteria->compare('Store_Content.name',$this->Store_Content->name,true);
			$criteria->compare('Store_Content.address',$this->Store_Content->address,true);
			$criteria->compare('Store_Content.audit',$this->Store_Content->audit,true);
			$criteria->compare('Store_Content.bank_id',$this->Store_Content->bank_id,true);
			$criteria->compare('Store_Content.bank_branch',$this->Store_Content->bank_branch,true);
			$criteria->compare('Store_Content.bank_name',$this->Store_Content->bank_name,true);
			$criteria->compare('Store_Content.bank_code',$this->Store_Content->bank_code,true);
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.phone',$this->phone,true);
		//	$criteria->compare('t.password',$this->password,true);
			$criteria->compare('t.p_id',$this->p_id,true);
			//$criteria->compare('agent_id',$this->agent_id,true);
			$criteria->compare('t.icon',$this->icon,true);
			$criteria->compare('t.count',$this->count,true);
			$criteria->compare('t.phone_type',$this->phone_type);
			$criteria->compare('t.login_token',$this->login_token,true);
			$criteria->compare('t.login_mac',$this->login_mac,true);
			$criteria->compare('t.last_mac',$this->last_mac,true);
			$criteria->compare('t.login_error',$this->login_error,true);
			$criteria->compare('t.error_count',$this->error_count,true);
			$criteria->compare('t.login_ip',$this->login_ip,true);
			if($this->login_time != '')
				$criteria->addBetweenCondition('t.login_time',strtotime($this->login_time),(strtotime($this->login_time)+3600*24-1));
			$criteria->compare('t.login_address',$this->login_address,true);
			$criteria->compare('t.last_ip',$this->last_ip,true);
			if($this->last_time != '')
				$criteria->addBetweenCondition('t.last_time',strtotime($this->last_time),(strtotime($this->last_time)+3600*24-1));
			$criteria->compare('t.last_address',$this->last_address,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			if($this->out_time != '')
				$criteria->addBetweenCondition('t.out_time',strtotime($this->out_time),(strtotime($this->out_time)+3600*24-1));
			$criteria->compare('t.status',$this->status);
			$criteria->compare('t.create_status',0);
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
	 * 商家子账号管理
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function search_son($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.
            
		if($criteria ===''){
			$criteria=new CDbCriteria;
                        
			$criteria->compare('t.status','>-1');
			$criteria->with=array('Store_Store'=>array('with'=>array('Store_Content')));
			//$criteria->with=array('Store_Store','Store_Content_Son'=>array('on'=>'Store_Store.id = Store_Content_Son.id'));
			$criteria->compare('t.p_id','>0');
                                                
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}

			$criteria->compare('Store_Content.name',$this->Store_Store->Store_Content->name,true);
			$criteria->compare('Store_Content.address',$this->Store_Store->Store_Content->address,true);
			$criteria->compare('Store_Content.audit',$this->Store_Store->Store_Content->audit,true);
			$criteria->compare('Store_Content.lx_contacts',$this->Store_Store->Store_Content->lx_contacts,true);
			$criteria->compare('Store_Content.lx_phone',$this->Store_Store->Store_Content->lx_phone,true);
			$criteria->compare('Store_Store.phone',$this->sms,true);
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.phone',$this->phone,true);
			$criteria->compare('t.p_id',$this->p_id,true);
			$criteria->compare('t.agent_id',$this->agent_id,true);
			$criteria->compare('t.icon',$this->icon,true);
			$criteria->compare('t.count',$this->count,true);
			$criteria->compare('t.phone_type',$this->phone_type);
			$criteria->compare('t.login_token',$this->login_token,true);
			$criteria->compare('t.login_mac',$this->login_mac,true);
			$criteria->compare('t.last_mac',$this->last_mac,true);
			$criteria->compare('t.login_error',$this->login_error,true);
			$criteria->compare('t.error_count',$this->error_count,true);
			$criteria->compare('t.login_ip',$this->login_ip,true);
			if($this->login_time != '')
				$criteria->addBetweenCondition('t.login_time',strtotime($this->login_time),(strtotime($this->login_time)+3600*24-1));
			$criteria->compare('t.login_address',$this->login_address,true);
			$criteria->compare('t.last_ip',$this->last_ip,true);
			if($this->last_time != '')
				$criteria->addBetweenCondition('t.last_time',strtotime($this->last_time),(strtotime($this->last_time)+3600*24-1));
			$criteria->compare('t.last_address',$this->last_address,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			if($this->out_time != '')
				$criteria->addBetweenCondition('t.out_time',strtotime($this->out_time),(strtotime($this->out_time)+3600*24-1));
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
	public function search_agent($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		if($criteria ===''){
			$criteria=new CDbCriteria;
			$criteria->compare('t.status','>=0');

			$criteria->with=array(
				'Store_Content'=>array(
					'with'=>array(
							'Content_area_id_p_Area_id',
							'Content_area_id_m_Area_id',
							'Content_area_id_c_Area_id',
					),			
				),
				'Store_Agent'=>array('select'=>'phone,firm_name'),
				'Store_Items_Count',
			);
			$criteria->compare('t.p_id',0);
			$criteria->compare('t.agent_id',Yii::app()->agent->id);
			$criteria->compare('t.phone',$this->phone,true);
			$criteria->compare('Store_Content.name',$this->Store_Content->name,true);
			$criteria->compare('t.status',$this->status);
			$criteria->compare('t.create_status',0);
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
	 * 商家子账号管理
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function search_agent_son($criteria='')
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		if($criteria ===''){
			$criteria=new CDbCriteria;

			$criteria->compare('t.status','>=0');
			$criteria->with=array('Store_Store'=>array('with'=>array('Store_Content')));
			$criteria->compare('t.p_id','<>0');
			
			$this->search_time_type = 2;
			if(isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}
			$criteria->compare('Store_Content.name',$this->Store_Store->Store_Content->name,true);
			$criteria->compare('Store_Store.phone',$this->p_id,true);
			$criteria->compare('t.phone',$this->phone,true);
			$criteria->compare('t.agent_id',Yii::app()->agent->id);
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
	 * @return StoreUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
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
				$this->last_time=$this->login_time=$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();
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
     * 验证手机短信
     */
    public function verifycode()
    {
	    Yii::import('ext.Send_sms.Send_sms');
	  	$params=array(
	    		'sms_id'=>0,
	    		'sms_type'=>SmsLog::sms_store,
	    		'role_id'=>Yii::app()->agent->id,
	    		'role_type'=>SmsLog::send_agent,
	    		'sms_use'=>SmsLog::use_register,
	    );
	    if (Send_sms::verifycode($this->phone, $params, $this->sms))
	    	return true;    
    	$this->addError('sms', '手机验证码 错误');
    	return false;
    }

	/**
	 * 验证手机短信
	 */
	public function verifycode_create_son()
	{
		Yii::import('ext.Send_sms.Send_sms');
		$params=array(
			'sms_id'=>0,
			'sms_type'=>SmsLog::sms_store,
			'role_id'=>Yii::app()->agent->id,
			'role_type'=>SmsLog::send_agent,
			'sms_use'=>SmsLog::use_register,
		);
		if (Send_sms::verifycode($this->phone, $params, $this->sms))
			return true;
		$this->addError('sms', '手机验证码 错误');
		return false;
	}
	
	/**
	 * 验证手机短信
	 */
	public function verifycode_update_son()
	{
		Yii::import('ext.Send_sms.Send_sms');
		$params=array(
				'sms_id'=>$this->id,
				'sms_type'=>SmsLog::sms_store,
				'role_id'=>Yii::app()->agent->id,
				'role_type'=>SmsLog::send_agent,
				'sms_use'=>SmsLog::use_phone,
		);
		if (Send_sms::verifycode($this->phone, $params, $this->sms))
			return true;
		$this->addError('sms', '手机验证码 错误');
		return false;
	}

	/**
	 * 限制登录错误次数
	 */
	public function login_error_confine($_this){
		if($this->login_error > Yii::app()->params['store_login_error'])
		{
			$_this->addError('password', '登录错误次数过多，用短信登录');
			return false;
		}
		return true;
	}

	/**
	 * 错误登录次数添加
	 * @return int
	 */
	public function login_error(){
		return $this->updateByPk($this->id,array(
			'login_error'=>new CDbExpression('`login_error`+1'),
			'error_count'=>new CDbExpression('`error_count`+1'),
		));
	}

	/**
	 * 登录后更新的数据
	 * @param $time
	 * @return int
	 */
	public function set_login($time)
	{
		return $this->updateByPk($this->id,array(
			'last_ip'=>$this->login_ip,
			'last_time'=>$this->login_time,
			'login_ip'=>Yii::app()->request->userHostAddress,
			'login_time'=>$time,
			'login_error'=>0,
			'count'=>new CDbExpression('`count`+1'),
		));
	}

	/**
	 * 登录状态
	 */
	public function login_status($_this){
		if($this->status==-1)//删除，没有这个用户
			$_this->addError('password','用户名或密码错误');
		elseif($this->status==0)
			$_this->addError('password','用户名 被禁用了 请联系管理员');
		elseif($this->status !=1)
			$_this->addError('password','用户名或密码错误');
		elseif($this->p_id !=0)
		{
			if(! StoreUser::model()->findByPk($this->p_id,'status=1'))
				$_this->addError('password','主账号 被禁用了 请联系管理员');
		}
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
	public function verifycode_store()
	{
		if(! $this->is_validator)
			$this->validate();
		if(!$this->hasErrors())
		{
			Yii::import('ext.Send_sms.Send_sms');
			$params=array(
				'sms_id'=>Yii::app()->store->id,
				'sms_type'=>SmsLog::sms_store,
				'role_id'=>Yii::app()->store->id,
				'role_type'=>SmsLog::send_store,
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
	 * 商家绑定银行账户
	 */
	public function verifycode_bank()
	{
		if(! $this->is_validator)
			$this->validate();
		if(!$this->hasErrors())
		{
			Yii::import('ext.Send_sms.Send_sms');
			$params=array(
				'sms_id'=>Yii::app()->store->id,
				'sms_type'=>SmsLog::sms_store,
				'role_id'=>Yii::app()->store->id,
				'role_type'=>SmsLog::send_store,
				'sms_use'=>SmsLog::use_bank,
			);
			if (Send_sms::verifycode($this->phone, $params, $this->sms))
				return true;
		}
		$this->addError('StoreContent_sms', '手机验证码 错误');
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
				'sms_id'=>Yii::app()->store->id,
				'sms_type'=>SmsLog::sms_store,
				'role_id'=>Yii::app()->store->id,
				'role_type'=>SmsLog::send_store,
				'sms_use'=>SmsLog::use_password,
			);
			if (Send_sms::verifycode($this->phone, $params, $this->sms))
				return true;
		}
		$this->addError('sms', '手机验证码 错误');
		return false;
	}

	/**
	 * 验证手机是不是原来的值
	 */
	public function is_old()
	{
		if(! StoreUser::model()->find('`phone`=:phone AND `id`=:id AND `status`=1',array(':phone'=>$this->phone,':id'=>Yii::app()->store->id)))
		{
			$this->addError('phone', '手机号 不是有效值');
		}
	}

	/**
	 * 验证手机是不是原来的值
	 */
	public function is_new()
	{
		if(StoreUser::model()->find('`phone`=:phone',array(':phone'=>$this->phone)))
		{
			$this->addError('phone', '手机号 不是有效值');
		}
	}
}
