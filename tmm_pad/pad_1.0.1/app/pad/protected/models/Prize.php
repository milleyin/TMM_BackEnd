<?php

/**
 * This is the model class for table "{{prize}}".
 *
 * The followings are the available columns in table '{{prize}}':
 * @property string $id
 * @property string $store_id
 * @property string $pad_id
 * @property string $config_id
 * @property string $manager_id
 * @property string $name
 * @property string $info
 * @property string $url
 * @property string $count
 * @property string $number
 * @property string $odds
 * @property integer $receive_type
 * @property integer $position
 * @property string $up_time
 * @property string $add_time
 * @property integer $status
 */
class Prize extends ActiveRecord
{
    /**
     * 解释 status 的状态
     * @var array
     */
    public static $_status = array(
        self::_STATUS_DELETED => '谢谢参与',
        self::_STATUS_DISABLE => '已禁用',
        self::_STATUS_NORMAL => '正常',
    );
    /********************************  receive_type领取方式 **********************************/
    /**
     * 兑奖方式 无需兑换
     *     @var integer
     */
    const PRIZE_RECEIVE_TYPE_NONE = 0;
    /**
     * 免费快递
     *     @var integer
     */
    const PRIZE_RECEIVE_TYPE_EXPRESS = 1;
    /**
     * 到店兑换
     * @var integer
     */
    const PRIZE_RECEIVE_TYPE_EXCHANGE = 2;
    /**
     * 有赞领取
     * @var integer
     */
    const PRIZE_RECEIVE_TYPE_YZ = 3;
    /**
     * 解释字段 state 的含义
     * @var array
     */
    public static $_receive_type = array(
        self::PRIZE_RECEIVE_TYPE_NONE => '无需兑换',
        self::PRIZE_RECEIVE_TYPE_EXPRESS => '免费快递',
        self::PRIZE_RECEIVE_TYPE_EXCHANGE => '到店兑换',
        self::PRIZE_RECEIVE_TYPE_YZ => '有赞领取',
    );

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{prize}}';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('receive_type, position, status', 'numerical', 'integerOnly'=>true),
            array('store_id, pad_id, config_id, manager_id', 'length', 'max'=>20),
            array('name', 'length', 'max'=>32),
            array('info, url', 'length', 'max'=>128),
            array('count, number, odds', 'length', 'max'=>11),
            array('up_time, add_time', 'length', 'max'=>10),
            
            array('status', 'in', 'range'=>array_keys(self::$_status)),
            array('receive_type', 'in', 'range'=>array_keys(self::$_receive_type)),
            
            array('count', 'numerical', 'integerOnly'=>true, 'min'=>-1, 'max'=>1000000),
            array('odds', 'numerical', 'integerOnly'=>true, 'min'=>-1, 'max'=>10000),
            
            //创建抽奖配置
            array('store_id, pad_id, config_id', 'required', 'on'=>'create'),
            array('name, info', 'ext.validators.DefaultValueValidator', 'value'=>'谢谢参与', 'on'=>'create, reset'),
            array('odds', 'ext.validators.DefaultValueValidator', 'value'=>10000, 'on'=>'create, reset'),
            array('count, number', 'ext.validators.DefaultValueValidator', 'value'=>-1, 'on'=>'create, reset'),                                               //不限数量
            array('status', 'ext.validators.DefaultValueValidator', 'value'=>self::_STATUS_DELETED, 'on'=>'create, reset'),                           //谢谢参与
            array('receive_type', 'ext.validators.DefaultValueValidator', 'value'=>self::PRIZE_RECEIVE_TYPE_NONE, 'on'=>'create, reset'),//不用兑换
            array('url', 'ext.validators.DefaultValueValidator', 'value'=>'', 'on'=>'create, reset'),                                                                     //url
            array('pad_id', 'isPadIdValidator', 'on'=>'create'),
            array('store_id, pad_id, config_id', 'safe', 'on'=>'create'),
            array('id, manager_id, name, info, url, count, number, odds, receive_type, position, up_time, add_time, status', 'unsafe', 'on'=>'create'),
            
            // 修改奖品
            array('name, info, count, odds, receive_type, url', 'required', 'on'=>'update_yz'),
            array('name, info, count, odds, receive_type', 'required', 'on'=>'update'),
            array('url', 'url', 'on'=>'update_yz'),
            array('name, info, count, odds, receive_type, url', 'safe', 'on'=>'update, update_yz'),
            array('id, store_id, pad_id, config_id, manager_id, number, position, up_time, add_time, status', 'unsafe', 'on'=>'update, update_yz'),
            // 修改谢谢参与
            array('name, odds, count', 'required', 'on'=>'thanks'),
            array('name, info, odds, count', 'safe', 'on'=>'thanks'),
            array('id, store_id, pad_id, config_id, manager_id, url, number, info, receive_type, position, up_time, add_time, status', 'unsafe', 'on'=>'thanks'),
            
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, store_id, pad_id, config_id, manager_id, name, info, url, count, number, odds, receive_type, position, up_time, add_time, status', 'safe', 'on'=>'search'),
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
            'Prize_Store' => array(self::BELONGS_TO, 'Store', 'store_id'),
            'Prize_Pad' => array(self::BELONGS_TO, 'Pad', 'pad_id'),
            'Prize_Config' => array(self::BELONGS_TO, 'Config', 'config_id'),
            'Prize_Upload' => array(self::HAS_ONE, 'Upload', 'upload_id', 'on'=>'Prize_Upload.type=' . Upload::UPLOAD_TYPE_PRIZE)
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'store_id' => '体验店',
            'pad_id' => '展示屏',
            'config_id' => '抽奖配置',
            'manager_id' => '操作角色',
            'name' => '名称',
            'info' => '奖品描述',
            'url' => 'Url链接',
            'count' => '库存',
            'number' => '剩余数量',
            'odds' => '中奖率',
            'receive_type' => '领取方式',
            'position' => '奖品位置',
            'up_time' => '更新时间',
            'add_time' => '创建时间',
            'status' => '状态',
        );
    }

    /**
     * 自动创建奖品
     * @param array $data 创建需要的数据
     * @param string $scenario 应用情景
     * @return bool
     * @author Moore Mo
     */
    public function autoCreatePrize($data, $number = 8, $scenario = 'create')
    {
        for ($i = 1; $i <= $number; $i++)
        {
            $this->isNewRecord = true;
            $this->id = null;
            $this->position = $i;
            $this->scenario = $scenario;
            $this->attributes = $data;
            if ( ! $this->save()) 
                return false;
        }
        return true;
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
            'Prize_Store',
            'Prize_Pad',
            'Prize_Upload',
        );

        $criteria->compare('t.id', $this->id,true);
        
        if (strpos($this->store_id, '=') === 0) {
            $criteria->compare('t.store_id', $this->store_id);
        } else {
            $criteria->compare('Prize_Store.store_name', $this->store_id, true);
        }        
        if (strpos($this->pad_id, '=') === 0) {
            $criteria->compare('t.pad_id', $this->pad_id);
        } else {
            $criteria->compare('Prize_Pad.name', $this->pad_id,true);
        }    
        $criteria->compare('Prize_Upload.path', $this->Prize_Upload->path, true);
        
        $criteria->compare('t.config_id', $this->config_id);
        $criteria->compare('t.manager_id', $this->manager_id,true);
        $criteria->compare('t.name', $this->name, true);
        $criteria->compare('t.info', $this->info, true);
        $criteria->compare('t.url', $this->url, true);
        $criteria->compare('t.count', $this->count, true);
        $criteria->compare('t.number', $this->number, true);
        $criteria->compare('t.odds', $this->odds, true);
        $criteria->compare('t.receive_type', $this->receive_type);
        $criteria->compare('position', $this->position);
        $this->timeSearch('t.up_time', $criteria);
        $this->timeSearch('t.add_time', $criteria);
        $criteria->compare('t.status', $this->status);
        
        $criteria->compare('Prize_Store.phone', $this->Prize_Store->phone, true);    
        $criteria->compare('Prize_Pad.number', $this->Prize_Pad->number, true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
            'pagination'=>array(
                'pageSize'=>10,
            ),
            'sort'=>array(
                'defaultOrder'=>'t.pad_id,t.position',
                'attributes'=>array(
                    'Prize_Upload.path'=>array(
                        'desc'=>'Prize_Upload.path desc',
                    ),
                    'Prize_Pad.number'=>array(
                            'desc'=>'Prize_Pad.number desc',
                    ),
                    'Prize_Store.phone'=>array(
                            'desc'=>'Prize_Store.phone desc',
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
     * @return Prize the static model class
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
     * 验证 改展示屏 是否已经设置抽奖记录 并创建了奖品
     */
    public function isPadIdValidator()
    {
        if ( !$this->hasErrors())
        {
            if (self::model()->find('pad_id=:pad_id AND position=:position', array(':pad_id'=>$this->pad_id, ':position'=>$this->position)))
                $this->addError('pad_id', '展示屏 已经有设置奖品');
        }
    }
}