<?php

/**
 * This is the model class for table "{{order_food}}".
 *
 * The followings are the available columns in table '{{order_food}}':
 * @property string $id
 * @property string $user_id
 * @property string $store_id
 * @property string $pad_id
 * @property string $money
 * @property string $manager_id
 * @property string $up_time
 * @property string $add_time
 * @property integer $order_status
 * @property integer $status
 */
class OrderFood extends ActiveRecord
{
    /**
     * 订单状态 未支付
     * @var integer
     */
    const ORDER_ORDER_STATUS_NO = 0;
    /**
     * 订单状态 已支付
     * @var integer
     */
    const ORDER_ORDER_STATUS_YES = 1;
    /**
     * 订单状态 解释字段 order_status 含义
     * @var array
     */
    public static $_order_status = array(
        self::ORDER_ORDER_STATUS_NO => '未支付',
        self::ORDER_ORDER_STATUS_YES => '已支付',
    );
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{order_food}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            //array('id', 'required'),
            array('order_status, status', 'numerical', 'integerOnly'=>true),
            array('id, user_id, store_id, pad_id, money, manager_id', 'length', 'max'=>20),
            array('up_time, add_time', 'length', 'max'=>10),
            
            array('order_status', 'in', 'range'=>array_keys(self::$_order_status)),
            array('status', 'in', 'range'=>array_keys(self::$_status)),
            //创建订单
            array('store_id, pad_id, money', 'required', 'on'=>'create'),
            array('money', 'numerical', 'integerOnly'=>true, 'max'=>99999999999, 'on'=>'create'),
            array('store_id, pad_id, money', 'safe', 'on'=>'create'),
            array('id, user_id, manager_id, up_time, add_time, order_status, status', 'unsafe', 'on'=>'create'),
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, store_id, pad_id, money, manager_id, up_time, add_time, order_status, status', 'safe', 'on'=>'search'),
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
            'OrderFood_Order' => array(self::BELONGS_TO, 'Order', 'id'),
            'OrderFood_User' => array(self::BELONGS_TO, 'User', 'user_id'),
            'OrderFood_Store' => array(self::BELONGS_TO, 'Store', 'store_id'),
            'OrderFood_Pad' => array(self::BELONGS_TO, 'Pad', 'pad_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'user_id' => '用户',
            'store_id' => '体验店',
            'pad_id' => '展示屏',
            'money' => '订单价格',
            'manager_id' => '操作角色',
            'up_time' => '更新时间',
            'add_time' => '创建时间',
            'order_status' => '订单状态',
            'status' => '状态',
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
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;
        $criteria->with = array(
            'OrderFood_Order',
            'OrderFood_User',
            'OrderFood_Store',
            'OrderFood_Pad',
        );
        $criteria->compare('t.id', $this->id, true);
        $criteria->compare('OrderFood_Order.order_no', $this->OrderFood_Order->order_no, true);
        if (strpos($this->user_id, '=') === 0) {
            $criteria->compare('t.user_id', $this->user_id);
        } else {
            $criteria->compare('OrderFood_User.name', $this->user_id, true);
        }
        if (strpos($this->store_id, '=') === 0) {
            $criteria->compare('t.store_id', $this->store_id);
        } else {
            $criteria->compare('OrderFood_Store.store_name', $this->store_id, true);
        }
        $criteria->compare('OrderFood_Store.phone', $this->OrderFood_Store->phone, true);
        
        if ( (!!$area = Area::model()->validateAttribute('city', $this->OrderFood_Store->city)) && $area->pid != $this->OrderFood_Store->province) {
            $this->OrderFood_Store->city = $this->OrderFood_Store->district = '';
        }
        $criteria->compare('OrderFood_Store.province', $this->OrderFood_Store->province);
        $criteria->compare('OrderFood_Store.city', $this->OrderFood_Store->city);
        $criteria->compare('OrderFood_Store.district', $this->OrderFood_Store->district);
        
        if (strpos($this->pad_id, '=') === 0) {
            $criteria->compare('t.pad_id', $this->pad_id);
        } else {
            $criteria->compare('OrderFood_Pad.name', $this->pad_id, true);
        }
        $criteria->compare('OrderFood_Pad.number', $this->OrderFood_Pad->number, true);
        $criteria->compare('t.money', $this->money, true);
        $criteria->compare('t.manager_id', $this->manager_id, true);
        $this->timeSearch('t.up_time', $criteria, $this->up_time);
        $this->timeSearch('t.add_time', $criteria, $this->add_time);
        $criteria->compare('t.order_status', $this->order_status);
        if ($this->status != self::_STATUS_DELETED)
            $criteria->compare('t.status', '<>' . self::_STATUS_DELETED);
        $criteria->compare('t.status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>10,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id desc',
                'attributes'=>array(
                    'OrderFood_Order.order_no'=>array(
                        'desc'=>'OrderFood_Order.order_no desc',
                    ),
                    'OrderFood_Store.phone'=>array(
                        'desc'=>'OrderFood_Store.phone desc',
                    ),
                    'OrderFood_Store.phone'=>array(
                        'desc'=>'OrderFood_Store.phone desc',
                    ),
                    'OrderFood_Store.province'=>array(
                        'desc'=>'OrderFood_Store.province desc',
                    ),
                    'OrderFood_Store.city'=>array(
                        'desc'=>'OrderFood_Store.city desc',
                    ),
                    'OrderFood_Store.district'=>array(
                        'desc'=>'OrderFood_Store.district desc',
                    ),
                    'OrderFood_Pad.number'=>array(
                        'desc'=>'OrderFood_Pad.number desc',
                    ),
                    '*'
                ),
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return OrderFood the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
     * 保存之前的操作
     * (non-PHPdoc)
     * @see CActiveRecord::beforeSave()
     */
    public function beforeSave()
    {
        if (parent::beforeSave())
        {
            if ($this->getIsNewRecord())
                $this->up_time = $this->add_time = time();
            return true;
        }
        return false;
    }
    
    /**
     * 创建抢菜订单
     * @param unknown $data array('store_id', 'pad_id', 'money');
     * @param unknown $id
     * @return boolean
     */
    public function createOrderFood($data, $id)
    {
        $model = new OrderFood;
        $model->scenario = 'create';
        $model->attributes = $data;
        $model->id = $id;
        $model->user_id = Yii::app()->user->id;
        $model->order_status = self::ORDER_ORDER_STATUS_NO;
        return $model->save() ? $model : false;
    }
    
    /**
     * 查询之前是否有 有效的时间
     * @param unknown $money
     * @param unknown $pad_id
     * @param number $time
     * @return Ambigous <NULL, unknown, multitype:unknown Ambigous <unknown, NULL> , mixed, multitype:, multitype:unknown >
     */
    public function getValidOrder($money, $pad_id, $time = 300)
    {
        $criteria = new CDbCriteria;
        $criteria->with = array(
            'OrderFood_Order'
        );
        $criteria->addColumnCondition(array(
            '`t`.`user_id`' => Yii::app()->user->id,
            '`t`.`pad_id`' => $pad_id,
            '`t`.`money`' => $money,
            '`t`.`order_status`' => self::ORDER_ORDER_STATUS_NO,
        ));
        $criteria->addCondition('`t`.`add_time` >= :time');
        $criteria->params[':time'] = time() - $time;
        $criteria->order = '`t`.`add_time` desc';
        return self::model()->find($criteria);
    }
}