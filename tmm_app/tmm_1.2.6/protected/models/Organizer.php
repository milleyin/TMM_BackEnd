<?php

/**
 * This is the model class for table "{{organizer}}".
 *
 * The followings are the available columns in table '{{organizer}}':
 * @property string $id
 * @property string $firm_name
 * @property string $firm_phone
 * @property string $firm_postcode
 * @property string $area_id_p
 * @property string $area_id_m
 * @property string $area_id_c
 * @property string $address
 * @property string $law_name
 * @property string $law_identity
 * @property string $law_phone
 * @property string $bl_code
 * @property string $bl_img
 * @property string $manage_name
 * @property string $manage_email
 * @property string $manage_phone
 * @property string $manage_identity
 * @property string $identity_begin
 * @property string $identity_after
 * @property string $identity_hand
 * @property string $identity_job
 * @property string $bank_id
 * @property string $bank_name
 * @property string $bank_branch
 * @property string $bank_code
 * @property double $push
 * @property integer $is_push
 * @property string $income_count
 * @property string $cash
 * @property string $money
 * @property string $deposit
 * @property string $add_time
 * @property string $up_time
 * @property string $pass_time
 * @property integer $status
 */
class Organizer extends CActiveRecord
{
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
	public static $_search_time_type=array('创建时间','更新时间','通过时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('add_time','up_time','pass_time'); 
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
		return '{{organizer}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('id,firm_name, area_id_p, area_id_m, area_id_c, address, manage_name, manage_phone, manage_identity', 'required'),
			array('is_push,status,bank_code', 'numerical', 'integerOnly'=>true),
			array('push', 'numerical'),
			array('id, area_id_p, area_id_m, area_id_c, bank_id', 'length', 'max'=>11),
			array('firm_name, address, bl_img, manage_email, identity_begin, identity_after, identity_hand, identity_job, bank_branch', 'length', 'max'=>100),
			array('firm_phone, law_phone, manage_name, manage_phone, bank_name', 'length', 'max'=>20),
			array('firm_postcode, law_name, add_time, up_time, pass_time', 'length', 'max'=>10),
			array('law_identity, manage_identity', 'length', 'max'=>18),
			array('bl_code', 'length', 'max'=>45),
			array('bank_code', 'length', 'max'=>50),
			array('income_count, cash, money, deposit', 'length', 'max'=>13),
				
			//创建、修改	
			// bl_img,identity_begin, identity_after, identity_hand, identity_job
			array('firm_name, firm_phone, firm_postcode, area_id_p, area_id_m, area_id_c, address, law_name, law_identity, law_phone, bl_code, manage_name, manage_phone, manage_identity', 'required','on'=>'create,update'),	
			array('firm_name, firm_phone, firm_postcode, area_id_p, area_id_m, area_id_c, address, law_name, law_identity, law_phone, bl_code, manage_name, manage_phone, manage_identity','safe','on'=>'create,update'),
			array('bl_img,identity_begin, identity_after, identity_hand, identity_job,search_time_type,search_start_time,search_end_time,id, bank_id, bank_name, bank_branch, bank_code, push,is_push,income_count, cash, money, deposit, add_time, up_time, pass_time, status','unsafe','on'=>'create,update'),
			array(
					'bl_img,identity_begin, identity_after, identity_hand, identity_job','file','allowEmpty'=>true,
					'types'=>'jpg,png', 'maxSize'=>1024*1024*2,
					'tooLarge'=>'图片超过2M,请重新上传', 'wrongType'=>'图片格式错误',
					'on'=>'create,update',
			),
			array('firm_phone,law_phone,manage_phone','match','pattern'=>'/^1\d{10}$|^(0\d{2,3}-?|\(0\d{2,3}\))?[1-9]\d{4,7}(-\d{1,8})?$/','message'=>'{attribute} 不是有效的','on'=>'create,update'),
			array('firm_postcode','match','pattern'=>'/[1-9]\d{5}(?!\d)/','message'=>'{attribute} 不是有效的','on'=>'create,update'),
			array('area_id_p, area_id_m, area_id_c','ext.Validator.Validator_area','on'=>'create,update'),
			array('law_identity,manage_identity','ext.Validator.Validator_identity','on'=>'create,update'),	
			
			//通过审核
			array('push','length','max'=>6),
			array('push', 'required','on'=>'pass'),
			array('push','safe','on'=>'pass'),
			array('push','ext.Validator.Validator_push','on'=>'pass'),
			array('search_time_type,search_start_time,search_end_time,id, is_push,firm_name, firm_phone, firm_postcode, area_id_p, area_id_m, area_id_c, address, law_name, law_identity, law_phone, bl_code, bl_img, manage_name, manage_email, manage_phone, manage_identity, identity_begin, identity_after, identity_hand, identity_job, bank_id, bank_name, bank_branch, bank_code, income_count, cash, money, deposit, add_time, up_time, pass_time, status','unsafe','on'=>'pass'),
			
			//设置银行信息
			array('bank_id,bank_name,bank_branch,bank_code','required','on'=>'bank'),
			array('bank_id,bank_name,bank_branch,bank_code','safe','on'=>'bank'),
			array('search_time_type,search_start_time,search_end_time,id, is_push,firm_name, firm_phone, firm_postcode, area_id_p, area_id_m, area_id_c, address, law_name, law_identity, law_phone, bl_code, bl_img, manage_name, manage_email, manage_phone, manage_identity, identity_begin, identity_after, identity_hand, identity_job, push, income_count, cash, money, deposit, add_time, up_time, pass_time, status', 'unsafe', 'on'=>'bank'),
			array('bank_id','ext.Validator.Validator_bank','on'=>'bank'),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id,is_push, firm_name, firm_phone, firm_postcode, area_id_p, area_id_m, area_id_c, address, law_name, law_identity, law_phone, bl_code, bl_img, manage_name, manage_email, manage_phone, manage_identity, identity_begin, identity_after, identity_hand, identity_job, bank_id, bank_name, bank_branch, bank_code, push, income_count, cash, money, deposit, add_time, up_time, pass_time, status', 'safe', 'on'=>'search'),
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
				// 关联用户表
				'Organizer_User'=>array(self::HAS_ONE,'User','id'),
				// 关联银行
				'Organizer_Bank'=>array(self::BELONGS_TO,'Bank','bank_id'),
				// 关联地址表
				'Organizer_area_id_p_Area_id' => array(self::BELONGS_TO, 'Area', 'area_id_p'),
				'Organizer_area_id_m_Area_id' => array(self::BELONGS_TO, 'Area', 'area_id_m'),
				'Organizer_area_id_c_Area_id' => array(self::BELONGS_TO, 'Area', 'area_id_c'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'firm_name' => '公司名称',
			'firm_phone' => '公司电话',
			'firm_postcode' => '公司邮编',
			'area_id_p' => '省',//'关联地名表(aera)主键id (p_id=0) 省(市)',
			'area_id_m' =>'市',//'关联地名表(aera)主键id (p_id=t.area_id_p) 市(区)',
			'area_id_c' => '县(区)',//'关联地名表(aera)主键id (p_id=t.area_id_m) 县(区)',
			'address' => '详细地址',
			'law_name' => '法人名称',
			'law_identity' => '法人身份证',
			'law_phone' => '法人电话',
			'bl_code' => '营业执照号码',
			'bl_img' => '营业执照图',
			'manage_name' => '负责人姓名',
			'manage_email' => '邮箱地址',
			'manage_phone' => '联系方式',
			'manage_identity' =>'身份证',
			'identity_begin' => '身份证前',
			'identity_after' => '身份证后',
			'identity_hand' => '手持身份证',
			'identity_job' => '在职证明',
			'bank_id' => '开户银行',
			'bank_name' => '开户姓名',
			'bank_branch' => '开户支行',
			'bank_code' => '银行账号',
			'push' => '分成比例',
			'is_push' => '是否设置比例',
			'income_count' => '收益总额',
			'cash' => '已提现总额',
			'money' => '可提现金额',
			'deposit' => '剩余保证金',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'pass_time' => '通过时间',
			'status' => '状态',
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
			$criteria->compare('t.status','<>-1');
			$criteria->with=array(
				'Organizer_User'=>array(
						'select'=>'id,phone,nickname,is_organizer,status',
						'condition'=>'Organizer_User.is_organizer=:is_organizer',
						'params'=>array(':is_organizer'=>User::organizer),
				),
				'Organizer_Bank'=>array('select'=>'id,name'),
				// 关联地址表
				'Organizer_area_id_p_Area_id'=>array('select'=>'id,name') ,
				'Organizer_area_id_m_Area_id'=>array('select'=>'id,name'),
				'Organizer_area_id_c_Area_id'=>array('select'=>'id,name'),
			);
			//时间之间搜索
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}			
			$criteria->compare('Organizer_User.phone',$this->Organizer_User->phone,true);
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.firm_name',$this->firm_name,true);
			$criteria->compare('t.firm_phone',$this->firm_phone,true);
			$criteria->compare('t.firm_postcode',$this->firm_postcode,true);
			//关联地址
			if(!! $model_p=Area::name($this->area_id_p)){
				$model_m=Area::name($this->area_id_m);
				if($model_m && $model_p->id != $model_m->pid)
					$this->area_id_m='';	
			}else
				$this->area_id_m='';
			
			$criteria->compare('Organizer_area_id_p_Area_id.name',$this->area_id_p,true);
			$criteria->compare('Organizer_area_id_m_Area_id.name',$this->area_id_m,true);
			$criteria->compare('Organizer_area_id_c_Area_id.name',$this->area_id_c,true);
			$criteria->compare('t.address',$this->address,true);
			$criteria->compare('t.law_name',$this->law_name,true);
			$criteria->compare('t.law_identity',$this->law_identity,true);
			$criteria->compare('t.law_phone',$this->law_phone,true);
			$criteria->compare('t.bl_code',$this->bl_code,true);
			$criteria->compare('t.bl_img',$this->bl_img,true);
			$criteria->compare('t.manage_name',$this->manage_name,true);
			$criteria->compare('t.manage_email',$this->manage_email,true);
			$criteria->compare('t.manage_phone',$this->manage_phone,true);
			$criteria->compare('t.manage_identity',$this->manage_identity,true);
			$criteria->compare('t.identity_begin',$this->identity_begin,true);
			$criteria->compare('t.identity_after',$this->identity_after,true);
			$criteria->compare('t.identity_hand',$this->identity_hand,true);
			$criteria->compare('t.identity_job',$this->identity_job,true);
			$criteria->compare('Organizer_Bank.name',$this->bank_id,true);
			$criteria->compare('t.bank_name',$this->bank_name,true);
			$criteria->compare('t.bank_branch',$this->bank_branch,true);
			$criteria->compare('t.bank_code',$this->bank_code,true);
			$criteria->compare('t.push',$this->push,true);
			$criteria->compare('is_push',$this->is_push);
			$criteria->compare('t.income_count',$this->income_count,true);
			$criteria->compare('t.cash',$this->cash,true);
			$criteria->compare('t.money',$this->money,true);
			$criteria->compare('t.deposit',$this->deposit,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			if($this->pass_time != '')
				$criteria->addBetweenCondition('t.pass_time',strtotime($this->pass_time),(strtotime($this->pass_time)+3600*24-1));
			$criteria->compare('t.status',$this->status);
		}
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
					'pageSize'=>Yii::app()->params['admin_pageSize'],
			),
			'sort'=>array(
					'defaultOrder'=>'t.add_time desc', //设置默认排序
					'attributes'=>array(
							'id',
							'Organizer_User.phone'=>array(
									'desc'=>'Organizer_User.phone desc',
							),
							'firm_name','area_id_p','area_id_m','area_id_c',
							'manage_name','push','income_count','deposit',
							'add_time','up_time','pass_time','status',
					)
			),
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Organizer the static model class
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
				$this->up_time=$this->add_time=$this->pass_time=time();
			else
				$this->up_time=time();			
			return true;
		}else
			return false;
	}
}
