<?php

/**
 * This is the model class for table "{{refund_log}}".
 *
 * The followings are the available columns in table '{{refund_log}}':
 * @property string $id
 * @property string $admin_id
 * @property string $user_id
 * @property string $order_id
 * @property string $order_no
 * @property string $refund_id
 * @property string $reason
 * @property string $refund_price
 * @property string $refund_type
 * @property string $refund_courier
 * @property string $refund_courier_num
 * @property string $admin_id_first
 * @property string $remark_first
 * @property string $first_time
 * @property string $admin_id_double
 * @property string $remark_double
 * @property string $double_time
 * @property string $admin_id_submit
 * @property string $remark_submit
 * @property string $submit_time
 * @property string $refund_img1
 * @property string $refund_img2
 * @property string $refund_img3
 * @property string $refund_img4
 * @property string $refund_img5
 * @property integer $is_organizer
 * @property double $push
 * @property double $user_push
 * @property double $push_orgainzer
 * @property double $push_store
 * @property double $push_agent
 * @property double $refund_status
 * @property double $audit_status
 * @property double $pay_type
 * @property string $refund_time
 * @property string $add_time
 * @property string $up_time
 * @property integer $status
 */
class RefundLog extends CActiveRecord
{
	/********************************退款状态*********************************************/

	/**
	 * 退款状态 退款失败
	 */
	const refund_status_cash_not=-1;
	/**
	 * 退款状态 待退款
	 */
	const refund_status_cashing=0;
	/**
	 * 退款状态 已退款
	 */
	const refund_status_cash=1;

	/**
	 * 解释字段 refund_status 的含义
	 * @var array
	 */
	public static $_refund_status=array(-1=>'退款失败',0=>'待退款',1=>'已退款');

	/********************************审核状态*********************************************/
	/**
	 * 审核状态 确认失败
	 */
	const audit_status_submit_not_pass=-3;
	/**
	 * 审核状态 待复失败
	 */
	const audit_status_double_not_pass=-2;
	/**
	 * 审核状态 初审失败
	 */
	const  audit_status_first_not_pass=-1;
	/**
	 * 审核状态 待初审
	 */
	const audit_status_first=0;
	/**
	 * 审核状态 待复审
	 */
	const audit_status_double=1;
	/**
	 * 审核状态 待确认
	 */
	const audit_status_submit=2;
	/**
	 * 审核状态 已确认
	 */
	const audit_status_finish=3;
	/**
	 * 解释字段 status 的含义
	 * @var array
	 */
	public static $_audit_status=array(-3=>'确认失败',-2=>'待复失败',-1=>'初审失败',0=>'待初审',1=>'待复审',2=>'待确认',3=>'已确认');

	/*************************支付类型 ****************************/
	/**
	 * 支付宝
	 * @var integer
	 */
	const pay_type_alipay=1;
	/**
	 * 解释字段 pay_type 的含义
	 * @var unknown
	 */
	public static $_pay_type=array(1=>'支付宝');

	/*************************退款类型 ****************************/
	/**
	 * 1=仅退款
	 */
	const  refund_type_money = 1;
	/**
	 * 2=商品退款',
	 */
	const  refund_type_shops = 2;
	/**
	 * 解释字段 refund_type 的含义
	 * @var array
	 */
	public static $_refund_type=array(1=>'仅退款',2=>'商品退款');

	/*************************是否有组织者 ****************************/
	/**
	 * 1====有组织者
	 */
	const is_organizer_yes = 1;
	/**
	 * 0=====没有组织者
	 */
	const is_organizer_no  = 0;
	/**
	 * 解释字段 $_is_organizer 的含义
	 * @var array
	 */
	public static $_is_organizer=array(0=>'否',1=>'是');

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
	public static $_search_time_type=array('(初)时间','(复)时间','(确认)时间','到账时间','创建时间','更新时间');
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('first_time','double_time','submit_time','refund_time','add_time','up_time'); 
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
		return '{{refund_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('admin_id, user_id, refund_time, add_time, up_time', 'required'),
			array('refund_status, audit_status, pay_type,is_organizer, status', 'numerical', 'integerOnly'=>true),
			array('push, user_push, push_orgainzer, push_store, push_agent', 'numerical'),
			array('admin_id, user_id, order_id, refund_id, refund_type, refund_courier, admin_id_first, admin_id_double, admin_id_submit', 'length', 'max'=>11),
			array('order_no', 'length', 'max'=>128),
			array('reason', 'length', 'max'=>200),
			array('refund_price', 'length', 'max'=>13),
			array('refund_courier_num', 'length', 'max'=>32),
			array('remark_first, remark_double, remark_submit, refund_img1, refund_img2, refund_img3, refund_img4, refund_img5', 'length', 'max'=>100),
			array('first_time, double_time, submit_time, refund_time, add_time, up_time', 'length', 'max'=>10),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			//结算申请（初审）
			array('remark_first', 'length','min'=>10,'max'=>100),
			array('remark_first', 'required','on'=>'pass_firets'),
			array('remark_first','safe','on'=>'pass_firets'),
			array('pay_type,audit_status,refund_status,search_time_type,search_start_time,search_end_time,id, admin_id, user_id, order_id, order_no, refund_id, reason, refund_price, refund_type, refund_courier, refund_courier_num, admin_id_first,  first_time, admin_id_double, remark_double, double_time, admin_id_submit, remark_submit, submit_time, refund_img1, refund_img2, refund_img3, refund_img4, refund_img5, is_organizer, push, user_push, push_orgainzer, push_store, push_agent, refund_time, add_time, up_time, status','unsafe','on'=>'pass_firets'),

			//结算申请（复审）
			array('remark_double', 'length','min'=>10,'max'=>100),
			array('remark_double', 'required','on'=>'pass_doubles'),
			array('remark_double','safe','on'=>'pass_doubles'),
			array('pay_type,audit_status,refund_status,search_time_type,search_start_time,search_end_time,id, admin_id, user_id, order_id, order_no, refund_id, reason, refund_price, refund_type, refund_courier, refund_courier_num, admin_id_first, remark_first, first_time, admin_id_double,  double_time, admin_id_submit, remark_submit, submit_time, refund_img1, refund_img2, refund_img3, refund_img4, refund_img5, is_organizer, push, user_push, push_orgainzer, push_store, push_agent, refund_time, add_time, up_time, status','unsafe','on'=>'pass_doubles'),

			//结算申请（确认）
			array('remark_submit', 'length','min'=>10,'max'=>100),
			array('remark_submit', 'required','on'=>'pass_submits'),
			array('remark_submit', 'safe','on'=>'pass_submits'),
			//array('fee_price,price', 'authenticate_price','on'=>'pass_submits'),
			array('pay_type,audit_status,refund_status,search_time_type,search_start_time,search_end_time,id, admin_id, user_id, order_id, order_no, refund_id, reason, refund_price, refund_type, refund_courier, refund_courier_num, admin_id_first, remark_first, first_time, admin_id_double, remark_double, double_time, admin_id_submit,  submit_time, refund_img1, refund_img2, refund_img3, refund_img4, refund_img5, is_organizer, push, user_push, push_orgainzer, push_store, push_agent, refund_time, add_time, up_time, status','unsafe','on'=>'pass_submits'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pay_type,audit_status,refund_status,search_time_type,search_start_time,search_end_time,id, admin_id, user_id, order_id, order_no, refund_id, reason, refund_price, refund_type, refund_courier, refund_courier_num, admin_id_first, remark_first, first_time, admin_id_double, remark_double, double_time, admin_id_submit, remark_submit, submit_time, refund_img1, refund_img2, refund_img3, refund_img4, refund_img5, is_organizer, push, user_push, push_orgainzer, push_store, push_agent, refund_time, add_time, up_time, status', 'safe', 'on'=>'search'),
		);
	}
	public function authenticate_price(){

		if($this->money != $this->price  )
			$this->addError('price','提现金额应等于 申请金额');

		if($this->money < $this->fee_price)
			$this->addError('fee_price','提现费用不能大于 申请金额');

		if( ($this->price - $this->fee_price) < 0  )
			$this->addError('fee_price','实际到账金额不能小于 0');

	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			//管理员初审核
			'RefundLogFirst_Admin'=>array(self::BELONGS_TO,'Admin','admin_id_first'),
			//管理员复审核
			'RefundLogDouble_Admin'=>array(self::BELONGS_TO,'Admin','admin_id_double'),
			//管理员确认
			'RefundLogSubmit_Admin'=>array(self::BELONGS_TO,'Admin','admin_id_submit'),
			//申请提现管理员
			'RefundLog_Admin'=>array(self::BELONGS_TO,'Admin','admin_id'),
			//退款用户
			'RefundLog_User'=>array(self::BELONGS_TO,'User','user_id'),
			//关联订单
			'RefundLog_Order'=>array(self::BELONGS_TO,'Order','order_id'),
			//关联退款理由
			'RefundLog_Refund'=>array(self::BELONGS_TO,'Refund','refund_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => '主键',
			'admin_id' => '管理员',
			'user_id' => '用户',
			'order_id' => '订单',
			'order_no' => '订单号',
			'refund_id' => '退款理由id',
			'reason' => '退款原因',
			'refund_price' => '退款价格',
			'refund_type' => '退款类型',
			'refund_courier' => '快速公司',
			'refund_courier_num' => '快速单号',
			'admin_id_first' => '管理员(初)',
			'remark_first' => '备注(初)',
			'first_time' => '(初)时间',
			'admin_id_double' => '管理员(复)',
			'remark_double' => '备注(复)',
			'double_time' => '(复)时间',
			'admin_id_submit' => '管理员(确认)',
			'remark_submit' => '备注(确认)',
			'submit_time' => '(确认)时间',
			'refund_img1' => '退款图片1',
			'refund_img2' => '退款图片2',
			'refund_img3' => '退款图片3',
			'refund_img4' => '退款图片4',
			'refund_img5' => '退款图片5',
			'is_organizer' => '组织者',
			'push' => '平台抽成%',
			'user_push' => '退款比例%',
			'push_orgainzer' => '组织者%',
			'push_store' => '商家抽成 %',
			'push_agent' => '代理商抽成 %',
			'refund_status'=>'付款状态',
			'audit_status'=>'审核状态',
			'pay_type'=>'退款类型',
			'refund_time' => '退款时间',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'status' => '记录状态',
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

			$criteria->with = array(
				'RefundLogFirst_Admin'=>array('select'=>'id,name') ,
				'RefundLogDouble_Admin'=>array('select'=>'id,name') ,
				'RefundLogSubmit_Admin'=>array('select'=>'id,name') ,
				'RefundLog_Admin'=>array('select'=>'id,name') ,
				'RefundLog_User',
				'RefundLog_Order',
				'RefundLog_Refund',
			);

			$criteria->compare('t.status','<>-1');
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition($this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare($this->__search_time_type[$this->search_time_type],'<=' . (strtotime($this->search_end_time)+3600*24-1));
			}			
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('t.admin_id',$this->admin_id,true);
			$criteria->compare('t.user_id',$this->user_id,true);
			$criteria->compare('t.order_id',$this->order_id,true);
			$criteria->compare('t.order_no',$this->order_no,true);
			$criteria->compare('t.refund_id',$this->refund_id,true);
			$criteria->compare('t.reason',$this->reason,true);
			$criteria->compare('t.refund_price',$this->refund_price,true);
			$criteria->compare('t.refund_type',$this->refund_type,true);
			$criteria->compare('t.refund_courier',$this->refund_courier,true);
			$criteria->compare('t.refund_courier_num',$this->refund_courier_num,true);
			$criteria->compare('t.admin_id_first',$this->admin_id_first,true);
			$criteria->compare('t.remark_first',$this->remark_first,true);
			$criteria->compare('t.refund_status',$this->refund_status,true);
			$criteria->compare('t.audit_status',$this->audit_status,true);
			$criteria->compare('t.pay_type',$this->pay_type,true);
			if($this->first_time != '')
				$criteria->addBetweenCondition('t.first_time',strtotime($this->first_time),(strtotime($this->first_time)+3600*24-1));
			$criteria->compare('t.admin_id_double',$this->admin_id_double,true);
			$criteria->compare('t.remark_double',$this->remark_double,true);
			if($this->double_time != '')
				$criteria->addBetweenCondition('double_time',strtotime($this->double_time),(strtotime($this->double_time)+3600*24-1));
			$criteria->compare('t.admin_id_submit',$this->admin_id_submit,true);
			$criteria->compare('t.remark_submit',$this->remark_submit,true);
			if($this->submit_time != '')
				$criteria->addBetweenCondition('t.submit_time',strtotime($this->submit_time),(strtotime($this->submit_time)+3600*24-1));
			$criteria->compare('t.refund_img1',$this->refund_img1,true);
			$criteria->compare('t.refund_img2',$this->refund_img2,true);
			$criteria->compare('t.refund_img3',$this->refund_img3,true);
			$criteria->compare('t.refund_img4',$this->refund_img4,true);
			$criteria->compare('t.refund_img5',$this->refund_img5,true);
			$criteria->compare('t.is_organizer',$this->is_organizer);
			$criteria->compare('t.push',$this->push);
			$criteria->compare('t.user_push',$this->user_push);
			$criteria->compare('t.push_orgainzer',$this->push_orgainzer);
			$criteria->compare('t.push_store',$this->push_store);
			$criteria->compare('t.push_agent',$this->push_agent);
			if($this->refund_time != '')
				$criteria->addBetweenCondition('t.refund_time',strtotime($this->refund_time),(strtotime($this->refund_time)+3600*24-1));
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
	 * @return RefundLog the static model class
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

	/**
	 * 查询订单是否正在退款
	 * 真==== 正在退款  否====可以重新退款
	 * @param $order_id
	 * @return bool
	 */
	public static function is_order_refund($order_id){

		$model = self::model()->find('order_id=:order_id AND audit_status>=:audit_status AND refund_status !=:refund_status AND status=:status',
			array(
				':order_id'=>$order_id,								//订单ID
				':audit_status'=>self::audit_status_first,		//审核状态失败
				':refund_status'=>self::refund_status_cash_not,	// 不等于 退款失败
				':status'=>1,											//正常
			));

		if($model)
			return true;

		return false;
	}
}
