<?php

/**
 * This is the model class for table "{{attend}}".
 *
 * The followings are the available columns in table '{{attend}}':
 * @property string $id
 * @property string $actives_id
 * @property string $founder_id
 * @property string $user_id
 * @property integer $p_id
 * @property string $number
 * @property string $people
 * @property string $children
 * @property string $name
 * @property string $phone
 * @property integer $is_people
 * @property integer $gender
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class Attend extends CActiveRecord
{
	const name_default = '自己（默认）';
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
	public static $_status=array(-1=>'删除报名','取消报名','正常报名');	
	/**
	 *	是否是成人  儿童
	 */
	const is_people_not = 0;
	/**
	 * 是否是成人 成人
	 */
	const is_people_yes = 1;
	/**
	 * 解释字段 is_people 的含义
	 * @var array
	 */
	public static $_is_people = array(0=>'儿童',1=>'成人');
	/**
	 * 性别 未知
	 */
	const gender_unknown = 0;
	/**
	 * 性别 男人
	 */
	const gender_man = 1;
	/**
	 * 性别 女人
	 */
	const gender_woman = 1;
	/**
	 * 解释字段 gender 的含义
	 * @var array
	 */
	public static $_gender = array(0=>'未知',1=>'男人',2=>'女人');
	/**
	 * 搜索的时间类型
	 * @var array
	 */
	public $search_time_type;
	/**
	 *	解释搜索类型时间的含义 search_time_type
	 * @var string
	 */
	public static $_search_time_type=array('报名时间','修改时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('add_time','up_time'); 
	/**
	 * 短信
	 * @var unknown
	 */
	public $sms;
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
	 * 统计借用
	 * @var unknown
	 */
	public $count_column = 0;
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{attend}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('id, actives_id, founder_id, user_id, add_time, up_time', 'required'),
			array('is_people, gender, status, number, people, children', 'numerical', 'integerOnly'=>true),
			array('id, actives_id, founder_id, user_id, p_id,number, people, children, phone', 'length', 'max'=>11),
			//array('name', 'length', 'max'=>64),
			array('add_time, up_time', 'length', 'max'=>10),
			
			array('name', 'length', 'min'=>2,'max'=>10),
			//报名状态
			array('status','in','range'=>array_keys(self::$_status)),
			//是否是成人
			array('is_people','in','range'=>array_keys(self::$_is_people)),
			//性别
			array('gender','in','range'=>array_keys(self::$_gender)),
			
			//创建父类
			array('name,phone,people,children,sms', 'required', 'on'=>'create_p'),
			array('sms', 'numerical', 'integerOnly'=>true, 'on'=>'create_p'),
			array('sms', 'length', 'min'=>6, 'max'=>6, 'on'=>'create_p'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'], 'on'=>'create_p'),
			array('p_id','in','range'=>array(0),'on'=>'create_p'),//主要的
			array('people,children','people_children', 'on'=>'create_p'),
			array('name,phone,people,children,gender,sms','safe', 'on'=>'create_p'),
			array('search_time_type,search_start_time,search_end_time,id, actives_id, founder_id, user_id, p_id, number, , is_people, add_time, up_time, status','unsafe', 'on'=>'create_p'),

			//创建子 成人
			array('name,phone,is_people', 'required','on'=>'create_people'),
			array('is_people','in','range'=>array(self::is_people_yes),'on'=>'create_people'),//成人				
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'create_people'),
			array('name,phone,is_people,gender','safe','on'=>'create_people'),
			array('sms,search_time_type,search_start_time,search_end_time,id, actives_id, founder_id, user_id, p_id, number, people, children, add_time, up_time, status','unsafe','on'=>'create_people'),
			
			//创建子 儿童
			array('name,is_people', 'required','on'=>'create_children'),
			array('is_people','in','range'=>array(self::is_people_not),'on'=>'create_children'),//儿童
			array('name,is_people,gender','safe','on'=>'create_children'),
			array('sms,search_time_type,search_start_time,search_end_time,id, actives_id, founder_id, user_id, p_id, number, people, children, phone, add_time, up_time, status','unsafe','on'=>'create_children'),
			
			//报名短信验证
			array('sms,phone', 'required','on'=>'validate_sms'),
			array('sms', 'length', 'min'=>6,'max'=>6,'on'=>'validate_sms'),
			array('sms', 'numerical', 'integerOnly'=>true,'on'=>'validate_sms'),
			array('phone','match','pattern'=>Yii::app()->params['pattern']['phone'],'on'=>'validate_sms'),
			array('sms,phone','safe','on'=>'validate_sms'),
			array('search_time_type,search_start_time,search_end_time,id, actives_id, founder_id, user_id, p_id, number, people, children, name, is_people, gender, add_time, up_time, status','unsafe','on'=>'validate_sms'),
			
			//修改报名的名字
			array('name', 'required','on'=>'update_p_name'),
			array('p_id','in','range'=>array(0),'on'=>'update_p_name'),//主要的		
			array('is_people','in','range'=>array(self::is_people_yes),'on'=>'update_p_name'),//成人
			array('name','safe','on'=>'update_p_name'),
			array('search_time_type,search_start_time,search_end_time,id, actives_id, founder_id, user_id, p_id, number, people, children, phone, is_people, gender, add_time, up_time, status', 'unsafe', 'on'=>'update_p_name'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, actives_id, founder_id, user_id, p_id, number, people, children, name, phone, is_people, gender, add_time, up_time, status', 'safe', 'on'=>'search'),
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
				//归属活动 多对一
				'Attend_Actives'=>array(self::BELONGS_TO,'Actives','actives_id'),
				//归属活动 多对一
				'Attend_Shops'=>array(self::BELONGS_TO,'Shops','actives_id'),
				//活动举办者 多对一
				'Attend_User_Founder'=>array(self::BELONGS_TO,'User','founder_id'),
				//活动归属报名 多对一
				'Attend_User'=>array(self::BELONGS_TO,'User','user_id'),
				//归属父级联系人 多对一
				'Attend_Attend_P'=>array(self::BELONGS_TO,'Attend','p_id'),
				//父级的子类 一对多
				'Attend_Attend'=>array(self::HAS_MANY,'Attend','p_id'),
				//管理活动总订单 一对一
				'Attend_OrderActives'=>array(self::HAS_ONE,'OrderActives',array('actives_id'=>'actives_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'actives_id' => '活动',
			'founder_id' => '创办者',
			'user_id' => '用户',
			'p_id' => '归属报名',
			'number' => '报名总数',
			'people' => '成人数量',
			'children' => '儿童数量',
			'name' => '姓名',
			'phone' => '手机号',
			'is_people' => '成人/儿童',
			'gender' => '性别',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '状态',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'sms'=>'短信验证码',
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
					'Attend_Shops',
					'Attend_Actives',
					'Attend_User_Founder',
					'Attend_User',
					'Attend_Attend_P',
			);	
			//$criteria->compare('`t`.`status`','<>-1');
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
			$criteria->compare('Attend_Shops.name',$this->actives_id,true);
			$criteria->compare('Attend_User_Founder.phone',$this->founder_id,true);
			$criteria->compare('Attend_User.phone',$this->user_id,true);
			$criteria->compare('t.p_id',$this->p_id);
			$criteria->compare('t.number',$this->number,true);
			$criteria->compare('t.people',$this->people,true);
			$criteria->compare('t.children',$this->children,true);
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.phone',$this->phone,true);
			$criteria->compare('t.is_people',$this->is_people);
			$criteria->compare('t.gender',$this->gender);
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
	 * @return Attend the static model class
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
				$this->up_time=$this->add_time=time();
			else
				$this->up_time=time();
			return true;
		}else
			return false;
	}
	
	/**
	 * 验证短信是否有效
	 * @param unknown $type
	 * @return boolean
	 */
	public function verifycode()
	{
		if(! $this->hasErrors())
		{
			Yii::import('ext.Send_sms.Send_sms');
			$params=array(
					'sms_id'=>0,
					'sms_type'=>SmsLog::sms_user,
					'role_id'=>0,
					'role_type'=>SmsLog::send_user,
					'sms_use'=>SmsLog::use_attend,
			);
			if (Send_sms::verifycode($this->phone, $params, $this->sms))
				return true;
			$this->addError('sms', '短信验证码 错误');
		}
		return false;
	}
	
	/**
	 * 验证大人小孩的数量
	 * @param unknown $attribute
	 * @param unknown $params
	 */
	public function people_children($attribute,$params)
	{
		if (ceil($this->children/2) > $this->people)
			$this->addError($attribute, '一个成人带二个儿童');
	}
	
	/**
	 * 获取活动报名人数
	 * @param unknown $id
	 * @param string $column {0 : 报名人数 1：成人 2：儿童} 
	 * @return number
	 */
	public static function getColumnCount($id, $column = 0)
	{
		$columns = array('number','people','children');
		if (isset($columns[$column]))
		{
			$criteria = new CDbCriteria;
			$criteria->select = "SUM(`$columns[$column]`) as count_column";
			$criteria->addColumnCondition(array(
				'actives_id'=>$id,					//归属活动
				'status'=>self::status_suc,		//正常报名
				'p_id'=>0,								//父类
			));
			$model = self::model()->find($criteria);
			if ($model)
				return (int)$model->count_column;
		}
		return 0;
	}
	
	/**
	 * 订单 获取报名人员
	 * @param unknown $id actives_id
	 * @param string $user_id
	 * @return Ambigous <multitype:static , mixed, static, NULL, multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function getActivesAttend($id, $user_id=false)
	{
		$criteria = new CDbCriteria;
		$criteria->with = array('Attend_Attend');
		$criteria->addColumnCondition(array(
				'`t`.`p_id`'=>0,								//父类
				'`t`.`actives_id`'=>$id,
				'`t`.`status`'=>self::status_suc,
		));
		if ($user_id !== false)
		{
			$criteria->addColumnCondition(array(
					'`t`.`user_id`'=>$user_id,
			));
		}
		return self::model()->findAll($criteria);
	}
		
	/**
	 * 是否有设置名字的权限
	 * @param unknown $id actives_id
	 * @param unknown $name
	 */
	public static function isSetPAttendName($id,$user_id,$name=null)
	{
		$criteria = new CDbCriteria;
		$criteria->addCondition('`founder_id`=`user_id`');
		$criteria->addCondition('`name`=:name_default');
		$criteria->params[':name_default'] = Attend::name_default;	
		if ($name !== null)
		{
			$criteria->addCondition('`name`!=:name');
			$criteria->params[':name'] = $name;
		}
		$criteria->addColumnCondition(array(
				'`p_id`'=>0,								//父类
				'`actives_id`'=>$id,
				'`status`'=>self::status_suc,
				'`user_id`'=>$user_id,
		));
		return Attend::model()->find($criteria);
	}
	
	/**
	 * 是否报名了
	 * @param integer $id actives_id
	 * @param integer $user_id
	 */
	public static function getIsAttend($id,$user_id)
	{
		$criteria = new CDbCriteria;
		$criteria->addColumnCondition(array(
				'`p_id`'=>0,								//父类
				'`actives_id`'=>$id,					//归属活动ID
				'`status`'=>self::status_suc,	//正常报名
				'`user_id`'=>$user_id,				//那个用户报名的
		));
		return Attend::model()->find($criteria);
	}
	
	/**
	 * 获取发布者报名者信息
	 * @param unknown $id
	 * @return Ambigous <static, mixed, NULL, multitype:static , multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function getMainAttend($id)
	{
		$criteria = new CDbCriteria;
		//发布者
		$criteria->addCondition('`founder_id`=`user_id`');
		$criteria->addColumnCondition(array(
				'`p_id`'=>0,								//父类
				'`actives_id`'=>$id,					//归属活动ID
				'`status`'=>self::status_suc,	//正常报名
		));
		return Attend::model()->find($criteria);
	}
}
