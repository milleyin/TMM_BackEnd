<?php

/**
 * This is the model class for table "{{sms_log}}".
 *
 * The followings are the available columns in table '{{sms_log}}':
 * @property string $id
 * @property string $phone
 * @property string $sms_id
 * @property string $sms_type
 * @property string $role_id
 * @property string $role_type
 * @property string $sms_use
 * @property string $code
 * @property string $sms_content
 * @property string $sms_source
 * @property string $sms_ip
 * @property string $login_address
 * @property string $error_count
 * @property string $sms_error
 * @property string $end_time
 * @property string $add_time
 * @property integer $is_code
 * @property integer $status
 */
class SmsLog extends CActiveRecord
{
	/*************************************************谁发短信*********************************************************/
	/**
	 * 其他
	 * @var integer
	 */
	const send_other=0;
	/**
	 * 管理员
	 * @var integer
	 */
	const send_admin=1;
	/**
	 * 商家
	 * @var integer
	 */
	const send_store=2;
	/**
	 * 代理商
	 * @var integer
	 */
	const send_agent=3;
	/**
	 * 用户
	 * @var integer
	 */
	const send_user=4;
	/*************************************************发给谁短信*********************************************************/

	/**
	 * 其他
	 * @var integer
	 */
	const sms_other=0;
	/**
	 * 管理员
	 * @var integer
	 */
	const sms_admin=1;
	/**
	 * 商家
	 * @var integer
	 */
	const sms_store=2;
	/**
	 * 代理商
	 * @var integer
	 */
	const sms_agent=3;
	/**
	 * 用户
	 * @var integer
	 */
	const sms_user=4;
	/*************************************************短信用途*********************************************************/
	/**
	 * 注册
	 * @var integer
	 */
	const use_register=1;
	/**
	 * 登录
	 * @var integer
	 */
	const use_login=2;
	/**
	 * 修改银行
	 * @var integer
	 */
	const use_bank=3;
	/**
	 * 修改手机
	 * @var integer
	 */
	const use_phone=4;
	/**
	 * 发送通知
	 * @var integer
	 */
	const use_notify=5;
	/**
	 * 修改密码
	 * @var integer
	 */
	const use_password=6;
	/**
	 * 提现
	 * @var integer
	 */
	const use_cash=7;
	/**
	 * 操作支付密码
	 * @var integer
	 */
	const use_pay_password=8;
	/**
	 * 活动报名验证
	 * @var integer
	 */
	const use_attend=9;

	/**
	 * 来源
	 * @var integer
	 */
	const source_pc=1;
	/**
	 * 来源
	 * @var integer
	 */
	const source_app=2;
	/**
	 * 来源
	 * @var integer
	 */
	const source_weixin=3;


	const is_code=1;

	/**
	 * 解释字段 role_type 的 含义 谁发在短信
	 * @var array
	 */
	public static $_role_type=array(0=>'其他','管理员','商家','代理商','用户');
	/**
	 * 解释字段 sms_type 的 含义 发给谁在
	 * @var array
	 */
	public static $_sms_type=array(0=>'其他','管理员','商家','代理商','用户');
	/**
	 * 解释字段 role_type smodules 的 模块名 用来获取当前登录ID
	 * @var array
	 */
	public static $_sms_modules=array(1=>'admin',2=>'store',3=>'agent',4=>'api');
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
	public static $_search_time_type=array('end_time','add_time'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('end_time','add_time'); 
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
		return '{{sms_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('phone, code, sms_content', 'required'),
			array('status,is_code', 'numerical', 'integerOnly'=>true),
			array('phone, sms_id, sms_type, role_id, role_type, sms_use, sms_source, error_count, sms_error', 'length', 'max'=>11),
			array('code', 'length', 'max'=>6),
			array('sms_content', 'length', 'max'=>255),
			array('sms_ip', 'length', 'max'=>15),
			array('login_address', 'length', 'max'=>100),
			array('end_time, add_time', 'length', 'max'=>10),
			
			//array('','safe','on'=>'create,update'),
			//array('','unsafe','on'=>'create,update'),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id,is_code, phone, sms_id, sms_type, role_id, role_type, sms_use, code, sms_content, sms_source, sms_ip, login_address, error_count, sms_error, end_time, add_time, status', 'safe', 'on'=>'search'),
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
			'id' => '主键id',
			'phone' => '手机号码',
			'sms_id' => '角色id',
			'sms_type' => '角色类型0其他1=管理员2=商家3代理商4=用户',
			'role_id' => '操作角色id',
			'role_type' => '操作角色类型0其他1=管理员2=商家3代理商4=用户',
			'sms_use' => '短信用途0注册、登录1银行2手机3密码4通知',
			'code' => '手机验证码',
			'sms_content' => '手机内容',
			'sms_source' => '短信来源（手机，平板，电脑）',
			'sms_ip' => '发送短信IP',
			'login_address' => '登录地址（手机端）',
			'error_count' => '错误次数统计',
			'sms_error' => '最大错误次数',
			'end_time' => '失效时间',
			'add_time' => '创建时间',
			'is_code'=>'是否使用',
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
			$criteria->compare('phone',$this->phone,true);
			$criteria->compare('sms_id',$this->sms_id,true);
			$criteria->compare('sms_type',$this->sms_type,true);
			$criteria->compare('role_id',$this->role_id,true);
			$criteria->compare('role_type',$this->role_type,true);
			$criteria->compare('sms_use',$this->sms_use,true);
			$criteria->compare('code',$this->code,true);
			$criteria->compare('sms_content',$this->sms_content,true);
			$criteria->compare('sms_source',$this->sms_source,true);
			$criteria->compare('sms_ip',$this->sms_ip,true);
			$criteria->compare('login_address',$this->login_address,true);
			$criteria->compare('error_count',$this->error_count,true);
			$criteria->compare('sms_error',$this->sms_error,true);
			if($this->end_time != '')
				$criteria->addBetweenCondition('end_time',strtotime($this->end_time),(strtotime($this->end_time)+3600*24-1));
			if($this->add_time != '')
				$criteria->addBetweenCondition('add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));

			$criteria->compare('is_code',$this->is_code);
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
	 * @return SmsLog the static model class
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
				$this->add_time=time();
			return true;
		}else
			return false;
	}
}
