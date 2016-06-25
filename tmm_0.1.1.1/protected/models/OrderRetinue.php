<?php

/**
 * This is the model class for table "{{order_retinue}}".
 *
 * The followings are the available columns in table '{{order_retinue}}':
 * @property string $id
 * @property string $order_id
 * @property string $order_no
 * @property string $son_order_id
 * @property string $son_order_no
 * @property string $user_id
 * @property string $retinue_id
 * @property string $retinue_name
 * @property integer $retinue_gender
 * @property string $retinue_identity
 * @property string $retinue_phone
 * @property string $insure_name
 * @property integer $insure_gender
 * @property string $insure_identity
 * @property string $insure_phone
 * @property string $insure_age
 * @property integer $is_main
 * @property string $insure_no
 * @property string $insure_verify
 * @property string $start_time
 * @property string $end_time
 * @property string $insure_price
 * @property string $fact_price
 * @property string $insure_number
 * @property string $beneficiary
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class OrderRetinue extends CActiveRecord
{
	/**
	 * 不是主要的
	 * @var unknown
	 */
	const is_main_not=0;
	/**
	 * 是主要的
	 * @var unknown
	 */
	const is_main_yes=1;
	
	public static $_is_main=array('不是','是');
	/**
	 * 解释字段 retinue_gender 的含义
	 * @var array
	*/
	public static $_retinue_gender=array(0=>'未知',1=>'男',2=>'女');
	/**
	 * 解释字段 insure_gender 的含义
	 * @var array
	 */
	public static $_insure_gender=array(0=>'未知',1=>'男',2=>'女');
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
	public static $_search_time_type=array('start_time','end_time','add_time','up_time'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('start_time','end_time','add_time','up_time'); 
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
		return '{{order_retinue}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
		//	array('order_id, order_no, user_id, retinue_id, retinue_name, retinue_gender, retinue_identity, retinue_phone', 'required'),
			array('retinue_gender, insure_gender, is_main, status', 'numerical', 'integerOnly'=>true),
			array('order_id, son_order_id, user_id, retinue_id, retinue_phone, insure_phone, insure_number', 'length', 'max'=>11),
			array('order_no, son_order_no, insure_no, insure_verify', 'length', 'max'=>128),
			array('retinue_name, insure_name', 'length', 'max'=>20),
			array('retinue_identity, insure_identity', 'length', 'max'=>18),
			array('insure_age', 'length', 'max'=>3),
			array('start_time, end_time, add_time, up_time', 'length', 'max'=>10),
			array('insure_price, fact_price', 'length', 'max'=>13),
			array('beneficiary', 'length', 'max'=>15),
			
			//创建随行人员	
			array('is_main,retinue_id', 'required','on'=>'create'),				
			array('is_main,retinue_id','safe','on'=>'create'),
			array('is_main','in','range'=>array_keys(self::$_is_main),'on'=>'create'),
			array('search_time_type,search_start_time,search_end_time,id, order_id, order_no, son_order_id, son_order_no, user_id, retinue_name, retinue_gender, retinue_identity, retinue_phone, insure_name, insure_gender, insure_identity, insure_phone, insure_age, insure_no, insure_verify, start_time, end_time, insure_price, fact_price, insure_number, beneficiary, add_time, up_time, status', 'unsafe', 'on'=>'create'),
				
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, order_id, order_no, son_order_id, son_order_no, user_id, retinue_id, retinue_name, retinue_gender, retinue_identity, retinue_phone, insure_name, insure_gender, insure_identity, insure_phone, insure_age, is_main, insure_no, insure_verify, start_time, end_time, insure_price, fact_price, insure_number, beneficiary, add_time, up_time, status', 'safe', 'on'=>'search'),
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

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'order_id' => '订单ID',
			'order_no' => '订单号',
			'son_order_id' => '子订单ID',
			'son_order_no' => '子订单号',
			'user_id' => '用户ID',
			'retinue_id' => '来源id',
			'retinue_name' => '被保人姓名',
			'retinue_gender' => '被保人性别',
			'retinue_identity' => '被保人身份号',
			'retinue_phone' => '被保人手机号',
			'insure_name' => '投保人姓名',
			'insure_gender' => '投保人性别',
			'insure_identity' => '投保人身份号',
			'insure_phone' => '投保人手机号',
			'insure_age' => '投保年龄',
			'is_main' => '主要联系人',
			'insure_no' => '保单号',
			'insure_verify' => '验真码',
			'start_time' => '开始时间',
			'end_time' => '结束时间',
			'insure_price' => '保险金额',
			'fact_price' => '实际金额',
			'insure_number' => '保险份数',
			'beneficiary' => '收益人',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
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
			$criteria->compare('order_id',$this->order_id,true);
			$criteria->compare('order_no',$this->order_no,true);
			$criteria->compare('son_order_id',$this->son_order_id,true);
			$criteria->compare('son_order_no',$this->son_order_no,true);
			$criteria->compare('user_id',$this->user_id,true);
			$criteria->compare('retinue_id',$this->retinue_id,true);
			$criteria->compare('retinue_name',$this->retinue_name,true);
			$criteria->compare('retinue_gender',$this->retinue_gender);
			$criteria->compare('retinue_identity',$this->retinue_identity,true);
			$criteria->compare('retinue_phone',$this->retinue_phone,true);
			$criteria->compare('insure_name',$this->insure_name,true);
			$criteria->compare('insure_gender',$this->insure_gender);
			$criteria->compare('insure_identity',$this->insure_identity,true);
			$criteria->compare('insure_phone',$this->insure_phone,true);
			$criteria->compare('insure_age',$this->insure_age,true);
			$criteria->compare('is_main',$this->is_main);
			$criteria->compare('insure_no',$this->insure_no,true);
			$criteria->compare('insure_verify',$this->insure_verify,true);
			if($this->start_time != '')
				$criteria->addBetweenCondition('start_time',strtotime($this->start_time),(strtotime($this->start_time)+3600*24-1));
			if($this->end_time != '')
				$criteria->addBetweenCondition('end_time',strtotime($this->end_time),(strtotime($this->end_time)+3600*24-1));
			$criteria->compare('insure_price',$this->insure_price,true);
			$criteria->compare('fact_price',$this->fact_price,true);
			$criteria->compare('insure_number',$this->insure_number,true);
			$criteria->compare('beneficiary',$this->beneficiary,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('status',$this->status);
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
	 * 搜索
	 * @param string $criteria
	 * @return CActiveDataProvider
	 */
	public function search_order($order_id='')
	{
			$criteria=new CDbCriteria;
			$criteria->compare('status','<>-1');

			if(isset($order_id) && $order_id != '')
				$criteria->compare('order_id','='.$order_id);

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

			$criteria->compare('order_id',$this->order_id,true);
			$criteria->compare('order_no',$this->order_no,true);
			$criteria->compare('son_order_id',$this->son_order_id,true);
			$criteria->compare('son_order_no',$this->son_order_no,true);
			$criteria->compare('user_id',$this->user_id,true);
			$criteria->compare('retinue_id',$this->retinue_id,true);
			$criteria->compare('retinue_name',$this->retinue_name,true);
			$criteria->compare('retinue_gender',$this->retinue_gender);
			$criteria->compare('retinue_identity',$this->retinue_identity,true);
			$criteria->compare('retinue_phone',$this->retinue_phone,true);
			$criteria->compare('insure_name',$this->insure_name,true);
			$criteria->compare('insure_gender',$this->insure_gender);
			$criteria->compare('insure_identity',$this->insure_identity,true);
			$criteria->compare('insure_phone',$this->insure_phone,true);
			$criteria->compare('insure_age',$this->insure_age,true);
			$criteria->compare('is_main',$this->is_main);
			$criteria->compare('insure_no',$this->insure_no,true);
			$criteria->compare('insure_verify',$this->insure_verify,true);
			if($this->start_time != '')
				$criteria->addBetweenCondition('start_time',strtotime($this->start_time),(strtotime($this->start_time)+3600*24-1));
			if($this->end_time != '')
				$criteria->addBetweenCondition('end_time',strtotime($this->end_time),(strtotime($this->end_time)+3600*24-1));
			$criteria->compare('insure_price',$this->insure_price,true);
			$criteria->compare('fact_price',$this->fact_price,true);
			$criteria->compare('insure_number',$this->insure_number,true);
			$criteria->compare('beneficiary',$this->beneficiary,true);
			if($this->add_time != '')
				$criteria->addBetweenCondition('add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('status',$this->status);

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
	 * @return OrderRetinue the static model class
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
