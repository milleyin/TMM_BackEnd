<?php

/**
 * This is the model class for table "{{agent_link}}".
 *
 * The followings are the available columns in table '{{agent_link}}':
 * @property string $id
 * @property string $p_id
 * @property string $name
 * @property string $title
 * @property string $info
 * @property string $url
 * @property string $params
 * @property string $target
 * @property integer $sort
 * @property string $add_time
 * @property string $up_time
 * @property integer $show
 * @property integer $status
 */
class AgentLink extends CActiveRecord
{
	/**
	 * 导航
	 * @var unknown
	 */
	public $nav;
	/**
	 * 分组
	 * @var unknown
	 */
	public $group;
	/**
	 * status 解释
	 * @var unknown
	 */
	public static $_status=array('禁用','正常');
	/**
	 * target 解释
	 * @var unknown
	*/
	public static $_target=array('_parent'=>'整个页面', 'admin_left'=>'左边页面', 'admin_right'=>'右边页面');
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{agent_link}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			//array('name, title', 'required'),
			array('sort, status,show', 'numerical', 'integerOnly'=>true),
			array('p_id, add_time, up_time', 'length', 'max'=>11),
			array('name, target', 'length', 'max'=>20),
			array('title', 'length', 'max'=>50),
			array('info', 'length', 'max'=>200),
			array('url, params', 'length', 'max'=>100),
			
			array('target', 'in', 'range'=>array_keys(self::$_target)),
			array('status', 'in', 'range'=>array_keys(self::$_status)),
							
			array('name, title, url, params, target, sort', 'required'),
			array('url, target', 'required', 'on'=>'menu'),
			array('name, title, url', 'unique', 'on'=>'menu'),
			array('name,title,info,url,params,target,sort','safe','on'=>'create,update,menu'),
			array('p_id,add_time,up_time,show,status','unsafe','on'=>'create,update,menu'),
		
			array('name,title,info,sort,url,params,target', 'safe', 'on'=>'group'),
			array('p_id,up_time,add_time,show,status', 'unsafe', 'on'=>'group'),
			
			array('nav', 'numerical', 'integerOnly'=>true,'on'=>'mgroup'),
			array('nav, name,title,sort,url,params,target', 'required','on'=>'mgroup'),
			array('nav,name,title,info,sort,url,params,target,','safe', 'on'=>'mgroup'),
			array('p_id,up_time,add_time,show,status', 'unsafe', 'on'=>'mgroup'),
				
			array('nav,group, name,title, sort,url,params,target', 'numerical', 'integerOnly'=>true,'on'=>'mmenu'),
			array('nav,group, name,title, sort,url,params,target','required','on'=>'mmenu'),
			array('nav,group,name,title,info,sort,url,params,target','safe', 'on'=>'mmenu'),
			array('add_time,up_time,show,status', 'unsafe', 'on'=>'mmenu'),
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, p_id, name, title, info, url, params, target, sort, add_time, up_time,show, status', 'safe', 'on'=>'search'),
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
				'Link_Link'=>array(self::HAS_MANY,'AgentLink','p_id'),
				'Link_Link_Link'=>array(self::HAS_MANY,'AgentLink','p_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'p_id' => '父级ID',
			'name' => '名称',
			'title' => '标题',
			'info' => '说明',
			'url' => 'url地址',
			'params' => '链接参数',
			'target' => '链接目标',
			'sort' => '排序',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'show'=>'是否显示',
			'status' => '状态',
			'nav'=>'导航',
			'group'=>'分组',
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

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('p_id',$this->p_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('info',$this->info,true);
		$criteria->compare('url',$this->url,true);
		$criteria->compare('params',$this->params,true);
		$criteria->compare('target',$this->target,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('add_time',$this->add_time,true);
		$criteria->compare('up_time',$this->up_time,true);
		$criteria->compare('status',$this->status);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AgentLink the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * 找到导航中的第一个链接
	 * @param unknown $link_id
	 * @return multitype:multitype: NULL |multitype:
	 */
	public static function first_url($link_id)
	{
		$model = self::model()->find(array(
				'condition'=>'t.p_id=:p_id AND t.status=1',
				'params'=>array(':p_id'=>$link_id),
				'with'=>array('Link_Link'=>array(
						'order'=>'Link_Link.sort',
						'condition'=>'Link_Link.status=1',
				)),
				'order'=>'t.sort'
		));
		if($model)
		{	
			foreach ($model->Link_Link as $data)//默认页
				return array('url'=>$data->url,'params'=>array_merge(array('link_id'=>$data->id),eval('return '.$data->params.';')));
		}
		else
			return array();
	}
	
	/**
	 * 返回导航链接
	 * @param unknown $link_id
	 * @return multitype:multitype:multitype: multitype:NULL  NULL
	 */
	public static function Top($link_id)
	{
		$navbar=array();
		$naber=self::model()->findAll(array(
				'condition'=>'status=1 AND p_id=0',
				'order'=>'sort'
		));	
		foreach ($naber as $data)
		{
			if(!$data->show)
				continue;
			if($data->id==$link_id)
				$array=array('class'=>'active');
			else
				$array=array();
			$navbar[]= array(
					'label'=>$data->name,
					'url'=>array($data->url,'link_id'=>$data->id),
					'linkOptions'=>array('title'=>$data->title,'target'=>$data->target),
					'itemOptions'=>$array,
			);
		}
		return $navbar;
	}
	
	/**
	 * 返回左边组=>链接
	 * @param unknown $link_id
	 * @return Ambigous <multitype:, multitype:multitype: multitype:NULL  NULL >
	 */
	public static function left($link_id)
	{
		$menu=array();
		$model=self::model()->findAll(array(
				'condition'=>'t.p_id=:p_id and t.status=1',
				'params'=>array(':p_id'=>$link_id),
				'with'=>array('Link_Link'=>array(
						'order'=>'Link_Link.sort',
						'condition'=>'Link_Link.status=1',
				)),
				'order'=>'t.sort'
		));
		foreach ($model as $v)
		{
			foreach ($v->Link_Link as $data)
				$menu[$v->name][]= array(
						'label'=>$data->name,
						'url'=>array_merge(array($data->url,'link_id'=>$data->id),eval('return '.$data->params.';')),
						'linkOptions'=>array('title'=>$data->title,'target'=>$data->target),
				);
		}
		return $menu;
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
			{
				$this->add_time=$this->up_time=time();
			}
			else
			{
				$this->up_time=time();
			}
			return true;
		}else
			return false;
	}
}
