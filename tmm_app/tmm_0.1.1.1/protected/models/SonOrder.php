<?php

/**
 * This is the model class for table "{{son_order}}".
 *
 * The followings are the available columns in table '{{son_order}}':
 * @property string $id
 * @property string $son_order_no
 * @property string $order_id
 * @property string $order_no
 * @property integer $type_id
 * @property string $user_id
 * @property string $order_price
 * @property string $pay_price
 * @property string $price
 * @property string $trade_no
 * @property string $trade_name
 * @property string $service_price
 * @property string $service_fee
 * @property integer $pay_type
 * @property string $pay_time
 * @property string $add_time
 * @property string $up_time
 * @property integer $pay_status
 * @property integer $order_status
 * @property integer $centre_status
 * @property integer $status
 */
class SonOrder extends CActiveRecord
{
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
	public static $_search_time_type=array('pay_time','add_time','up_time'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('pay_time','add_time','up_time'); 
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
		return '{{son_order}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('son_order_no, order_id, order_no, user_id, order_price, pay_price, price, trade_name, service_price, service_fee', 'required'),
			array('type_id, pay_type, pay_status, order_status, centre_status, status', 'numerical', 'integerOnly'=>true),
			array('son_order_no, order_no, trade_name', 'length', 'max'=>128),
			array('order_id, user_id', 'length', 'max'=>11),
			array('order_price, pay_price, price, service_price, service_fee', 'length', 'max'=>13),
			array('trade_no', 'length', 'max'=>255),
			array('pay_time, add_time, up_time', 'length', 'max'=>10),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, son_order_no, order_id, order_no, type_id, user_id, order_price, pay_price, price, trade_no, trade_name, service_price, service_fee, pay_type, pay_time, add_time, up_time, pay_status, order_status, centre_status, status', 'safe', 'on'=>'search'),
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
			'son_order_no' => '子订单号',
			'order_id' => '归属订单',
			'order_no' => '订单号',
			'type_id' => ' 子订单类型 1 保险',
			'user_id' => ' 用户ID',
			'order_price' => '订单总价',
			'pay_price' => '支付回调总价',
			'price' => '实际支付总价',
			'trade_no' => '支付回调',
			'trade_name' => '支付账号',
			'service_price' => '第三方支付服务费',
			'service_fee' => '第三方支付服务费率%',
			'pay_type' => '支付类型1=支付宝2=银行...',
			'pay_time' => '支付成功时间',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'pay_status' => '支付状态1=已支付0=未支付2=已过期',
			'order_status' => '订单状态1=已支付0=未支付2=已退款....',
			'centre_status' => '状态',
			'status' => '记录状态0禁用1正常-1删除',
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
			$criteria->compare('son_order_no',$this->son_order_no,true);
			$criteria->compare('order_id',$this->order_id,true);
			$criteria->compare('order_no',$this->order_no,true);
			$criteria->compare('type_id',$this->type_id);
			$criteria->compare('user_id',$this->user_id,true);
			$criteria->compare('order_price',$this->order_price,true);
			$criteria->compare('pay_price',$this->pay_price,true);
			$criteria->compare('price',$this->price,true);
			$criteria->compare('trade_no',$this->trade_no,true);
			$criteria->compare('trade_name',$this->trade_name,true);
			$criteria->compare('service_price',$this->service_price,true);
			$criteria->compare('service_fee',$this->service_fee,true);
			$criteria->compare('pay_type',$this->pay_type);
			if($this->pay_time != '')
				$criteria->addBetweenCondition('pay_time',strtotime($this->pay_time),(strtotime($this->pay_time)+3600*24-1));
			if($this->add_time != '')
				$criteria->addBetweenCondition('add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
			$criteria->compare('pay_status',$this->pay_status);
			$criteria->compare('order_status',$this->order_status);
			$criteria->compare('centre_status',$this->centre_status);
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
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SonOrder the static model class
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
