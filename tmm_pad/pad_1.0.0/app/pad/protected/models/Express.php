<?php

/**
 * This is the model class for table "{{express}}".
 *
 * The followings are the available columns in table '{{express}}':
 * @property string $id
 * @property string $user_id
 * @property string $record_id
 * @property string $manager_id
 * @property string $name
 * @property string $phone
 * @property string $province
 * @property string $city
 * @property string $district
 * @property string $address
 * @property integer $express_status
 * @property string $express_name
 * @property string $express_code
 * @property string $express_time
 * @property string $up_time
 * @property string $add_time
 * @property integer $status
 */
class Express extends ActiveRecord
{
    /**
     * 状态
     * @var array
     */
    public static $_status = array(
        self::_STATUS_DELETED => '无效',
        self::_STATUS_NORMAL => '有效',
    );
    /**
     * 未发货
     * @var integer
     */
    const EXPRESS_STATUS_NO = 0;
    /**
     * 已发货
     * @var integer
     */
    const EXPRESS_STATUS_YES = 1;
    /**
     * 发货状态
     * @var array
     */
    public static $_express_status = array(
        self::EXPRESS_STATUS_NO => '未发货',
        self::EXPRESS_STATUS_YES => '已发货'
    );
    
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{express}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('express_status, status', 'numerical', 'integerOnly'=>true),
            array('user_id, record_id, manager_id', 'length', 'max'=>20),
            array('name, express_name', 'length', 'max'=>32),
            array('phone, province, city, district', 'length', 'max'=>11),
            array('address, express_code', 'length', 'max'=>128),
            array('express_time, up_time, add_time', 'length', 'max'=>10),
                
            array('status', 'in', 'range'=>array_keys(self::$_status)),
            array('express_status', 'in', 'range'=>array_keys(self::$_express_status)),
            // 创建
            array('name, phone, province, city, district, address', 'required', 'on'=>'create'),
            array('phone', 'ext.validators.PhoneValidator', 'on'=>'create'),
            array('province, city, district', 'ext.validators.AreaValidator', 'on'=>'create'),
            array('name, phone, province, city, district, address', 'safe', 'on'=>'create'),
            array('id, user_id, record_id, manager_id, express_status, express_name, express_code, express_time, up_time, add_time, status', 'unsafe', 'on'=>'create'),
            //发货
            array('express_name, express_code', 'required', 'on'=>'update'),
            array('express_name, express_code', 'safe', 'on'=>'update'),
            array('id, user_id, record_id, manager_id, name, phone, province, city, district, address, express_status, express_time, up_time, add_time, status', 'unsafe', 'on'=>'update'),
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, record_id, manager_id, name, phone, province, city, district, address, express_status, express_name, express_code, express_time, up_time, add_time, status', 'safe', 'on'=>'search'),
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
            'Express_Record' => array(self::BELONGS_TO, 'Record', 'record_id'),
            'Express_User' => array(self::BELONGS_TO, 'User', 'user_id'),
            'Express_Area_province'=>array(self::BELONGS_TO, 'Area', 'province'),
            'Express_Area_city'=>array(self::BELONGS_TO, 'Area', 'city'),
            'Express_Area_district'=>array(self::BELONGS_TO, 'Area', 'district'),
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
            'record_id' => '中奖记录',
            'manager_id' => '操作角色',
            'name' => '收货人',
            'phone' => '联系电话',
            'province' => '省',
            'city' => '市',
            'district' => '县(区)',
            'address' => '详细地址',
            'express_status' => '发货状态',
            'express_name' => '快递名称',
            'express_code' => '快递单号',
            'express_time' => '发货时间',
            'up_time' => '更新时间',
            'add_time' => '创建时间',
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
            'Express_Record',
            'Express_User',
            'Express_Area_province',
            'Express_Area_city',
            'Express_Area_district'
        );

        $criteria->compare('t.id', $this->id,true);
        $criteria->compare('Express_User.name', $this->user_id,true);
        $criteria->compare('t.record_id', $this->record_id,true);
        $criteria->compare('t.manager_id', $this->manager_id,true);
        $criteria->compare('t.name', $this->name,true);
        $criteria->compare('t.phone', $this->phone,true);
        $criteria->compare('t.province', $this->province,true);
        $criteria->compare('t.city', $this->city,true);
        $criteria->compare('t.district', $this->district,true);
        $criteria->compare('t.address', $this->address,true);
        $criteria->compare('t.express_status', $this->express_status);
        $criteria->compare('t.express_name', $this->express_name,true);
        $criteria->compare('t.express_code', $this->express_code,true);
        $this->timeSearch('t.express_time', $criteria);
        $this->timeSearch('t.up_time', $criteria);
        $this->timeSearch('t.add_time', $criteria);
        $criteria->compare('t.status', $this->status);
        
        $criteria->compare('Express_Record.prize_name', $this->Express_Record->prize_name, true);
        
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>10,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.id desc',
                'attributes'=>array(
                    'Express_Record.prize_name'=>array(
                        'desc'=>'Express_Record.prize_name desc',
                    ),
                    '*',
                ),
            ),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Express the static model class
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
                $this->express_time = $this->up_time = $this->add_time = time();
            return true;
        }
        return false;
    }
}