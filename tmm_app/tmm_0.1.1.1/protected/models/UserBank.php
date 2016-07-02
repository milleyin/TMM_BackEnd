<?php

/**
 * This is the model class for table "{{user_bank}}".
 *
 * The followings are the available columns in table '{{user_bank}}':
 * @property string $id
 * @property string $role_id
 * @property string $type_id
 * @property string $bank_type
 * @property string $bank_id
 * @property string $bank_name
 * @property string $bank_identity
 * @property string $bank_branch
 * @property string $bank_code
 * @property integer $sort
 * @property integer $is_default
 * @property integer $is_verify
 * @property integer $status
 * @property string $add_time
 * @property string $up_time
 */
class UserBank extends CActiveRecord
{

	/*******************************我是分隔线**********************************/
	/**
	 * 角色类型====用户
	 */
	const type_user = 1;
	/**
	 * 角色类型====商家
	 */
	const type_store = 2;
	/**
	 * 角色类型====代理商
	 */
	const type_agent = 3;
	/**
	 * 角色类型====组织者
	 */
	const type_organizer = 4;
	/**
	 * 解释字段 element_type 的含义
	 * @var array
	 */
	public static $_type_id=array(
		1=>'用户',
		2=>'商家',
		3=>'代理商',
		4=>'组织者',
	);

	/**
	 * 银行类型====信用卡
	 */
	const bank_type_credit = 1;
	/**
	 * 银行类型====商家
	 */
	const bank_type_debit= 2;
	/**
	 * 解释字段 $_bank_type 的含义
	 * @var array
	 */
	public static $_bank_type=array(
		1=>'信用卡',
		2=>'借记卡',
	);
	/**
	 * 默认类型====默认
	 */
	const is_default_yes = 0;
	/**
	 * 默认类型====非默认
	 */
	const is_default_no= -1;
	/**
	 * 解释字段 $_is_default 的含义
	 * @var array
	 */
	public static $_is_default=array(
		-1=>'非默认',
		0=>'默认',
	);
	/**
	 * 验证类型====验证无效
	 */
	const is_verify_no = -1;
	/**
	 * 验证类型====没有验证
	 */
	const is_verify_null = 0;
	/**
	 * 验证类型====验证有效
	 */
	const is_verify_yes = 1;
	/**
	 * 解释字段 $_is_verify 的含义
	 * @var array
	 */
	public static $_is_verify=array(
		-1=>'验证无效',
		0=>'没有验证',
		1=>'验证有效'
	);

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
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('开始时间','更新时间');
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('add_time','up_time'); 
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
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_bank}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('role_id, add_time, up_time', 'required'),
			array('sort, is_default, is_verify, status', 'numerical', 'integerOnly'=>true),
			array('role_id, type_id, bank_type, bank_id', 'length', 'max'=>11),
			array('bank_name', 'length', 'max'=>20),
			array('bank_identity', 'length', 'max'=>18),
			array('bank_branch', 'length', 'max'=>100),
			array('bank_code', 'length', 'max'=>50),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			//设置银行信息
			array('bank_id,bank_name,bank_branch,bank_code','required','on'=>'bank'),
			array('bank_id,bank_name,bank_branch,bank_code,bank_type,is_default,bank_identity','safe','on'=>'bank'),
			array('search_time_type,search_start_time,search_end_time,id, role_id, type_id, sort, is_verify, status, add_time, up_time', 'unsafe', 'on'=>'bank'),
			array('bank_id','ext.Validator.Validator_bank','on'=>'bank'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, role_id, type_id, bank_type, bank_id, bank_name, bank_identity, bank_branch, bank_code, sort, is_default, is_verify, status, add_time, up_time', 'safe', 'on'=>'search'),
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
			// 关联银行表
			'UserBank_Bank'=>array(self::BELONGS_TO,'Bank','bank_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'role_id' => '角色ID',
			'type_id' => '角色类型',
			'bank_type' => '用户银行类型 信用卡 借记卡 ……',
			'bank_id' => '开户银行',
			'bank_name' => '开户姓名',
			'bank_identity' => '开户身份证',
			'bank_branch' => '开户支行',
			'bank_code' => '开户银行账号',
			'sort' => '排序',
			'is_default' => '是否默认 0默认 -1 非默认',
			'is_verify' => '是否验证有效 验证无效-1 0没有验证 1验证有效',
			'status' => '状态0禁用1启用',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
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
			$criteria->compare('status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}			
			$criteria->compare('id',$this->id,true);
			$criteria->compare('role_id',$this->role_id,true);
			$criteria->compare('type_id',$this->type_id,true);
			$criteria->compare('bank_type',$this->bank_type,true);
			$criteria->compare('bank_id',$this->bank_id,true);
			$criteria->compare('bank_name',$this->bank_name,true);
			$criteria->compare('bank_identity',$this->bank_identity,true);
			$criteria->compare('bank_branch',$this->bank_branch,true);
			$criteria->compare('bank_code',$this->bank_code,true);
			$criteria->compare('sort',$this->sort);
			$criteria->compare('is_default',$this->is_default);
			$criteria->compare('is_verify',$this->is_verify);
			$criteria->compare('status',$this->status);
			if($this->add_time != '')
				$criteria->addBetweenCondition('add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
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
	 * @return UserBank the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 保存之前的操作
	 * @see CActiveRecord::beforeSave()
	 */
	public function beforeSave(){
		if(parent::beforeSave()){		
			if($this->isNewRecord)
				$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
}
