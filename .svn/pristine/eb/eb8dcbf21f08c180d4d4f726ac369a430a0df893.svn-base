<?php

/**
 * This is the model class for table "{{tags_type}}".
 *
 * The followings are the available columns in table '{{tags_type}}':
 * @property string $id
 * @property string $p_id
 * @property string $name
 * @property integer $sort
 * @property integer $status
 * @property integer $is_user
 * @property integer $is_search
 * @property integer $is_left
 * @property string $add_time
 * @property string $up_time
 * @property array $_p_id
 */
class TagsType extends CActiveRecord
{	
	/**
	 * 用户属性 否
	 * @var integer
	 */
	const no_is_user=0;
	/**
	 * 用户属性 是
	 * @var integer
	 */
	const yes_is_user=1;
	/**
	 *内容检索 否
	 * @var integer
	 */
	const no_is_search=0;
	/**
	 *内容检索 是
	 * @var integer
	 */
	const yes_is_search=1;
	/**
	 *侧边栏检索 是
	 * @var integer
	 */
	const no_is_left=0;
	/**
	 *侧边栏检索 否
	 * @var integer
	 */
	const yes_is_left=1;	
	
	/*******************************我是分隔线**********************************/
	/**
	 * 用户属性
	 * @var array
	 */
	public static $_is_user=array('否','是');
	/**
	 *内容检索
	 * @var array
	 */
	public static $_is_search=array('否','是');
	/**
	 *侧边栏检索
	 * @var array
	 */
	public static $_is_left=array('否','是');
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
	public static $_search_time_type=array('创建时间','更新时间'); 
	/**
	 *	解释搜索类型时间 search_time_type 的字段搜索
	 * @var string
	 */
	public $__search_time_type=array('add_time','up_time'); 
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
	 * 选中的标签
	 * @var string
	 */
	public $_select_tags;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tags_type}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(		
			array('sort, status,is_user, is_search, is_left', 'numerical', 'integerOnly'=>true),
			array('p_id', 'length', 'max'=>11),
			array('name', 'length', 'max'=>4),
			array('add_time, up_time', 'length', 'max'=>10),
			
			//创建、修改
			array('name,p_id,is_user, is_search, is_left,sort', 'required'),
			array('name,p_id,is_user, is_search, is_left,sort','safe','on'=>'create,update'),
			array('search_time_type,search_start_time,search_end_time,id, status, add_time, up_time','unsafe','on'=>'create,update'),
			array('p_id','exist_in','on'=>'create,update'),
			array('p_id','exist_son','on'=>'update'),//验证是否有子类
			array('name','unique_son','on'=>'create,update'),
			array('is_left','unique_is_left','on'=>'create,update'),
				
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('search_time_type,search_start_time,search_end_time,id, p_id, name, sort, status, is_user, is_search, is_left, add_time, up_time', 'safe', 'on'=>'search'),
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
				//关联自己父级
				'TagsType_TagsType'=>array(self::BELONGS_TO,'TagsType','p_id'),
				 //选择了那些标签
				'TagsType_TagsSelect'=>array(self::HAS_MANY,'TagsSelect','type_id'),
				 // 统计选择了多少标签
				'TagsType_TagsSelect_Count'=>array(self::STAT,'TagsSelect','type_id'),
				//用户选择了多少属性 TagsType
				'TagsType_TagsElement'=>array(self::HAS_ONE,'TagsElement','type_id'),
				//用户选择了多少属性 多 TagsType
				'TagsType_TagsElement_MANY'=>array(self::HAS_MANY,'TagsElement','type_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'p_id' => '标签分类',
			'name' => '名称',
			'sort' => '排序',
			'status' => '状态',
			'is_user'=>'用户属性',
			'is_search'=>'内容检索',
			'is_left'=>'侧边栏检索',
			'add_time' => '创建时间',
			'up_time' => '更新时间',
			'search_time_type'=>'时间类型',
			'search_start_time'=>'开始时间',
			'search_end_time'=>'结束时间',
			'_select_tags'=>'选中的标签',
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
			$criteria->with=array('TagsType_TagsType'=>array('select'=>'name'));
			if($this->search_time_type != '' && isset($this->__search_time_type[$this->search_time_type]))
			{
				if($this->search_start_time !='' && $this->search_end_time !='')
					$criteria->addBetweenCondition('t.'.$this->__search_time_type[$this->search_time_type],strtotime($this->search_start_time),strtotime($this->search_end_time)+3600*24-1);
				elseif($this->search_start_time !='' && $this->search_end_time =='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'>='.strtotime($this->search_start_time));
				elseif($this->search_start_time =='' && $this->search_end_time !='')
					$criteria->compare('t.'.$this->__search_time_type[$this->search_time_type],'<='.strtotime($this->search_end_time)+3600*24-1);
			}			
			$criteria->compare('t.id',$this->id,true);
			$criteria->compare('TagsType_TagsType.name',$this->p_id,true);
			$criteria->compare('t.name',$this->name,true);
			$criteria->compare('t.sort',$this->sort);
			$criteria->compare('t.status',$this->status);
			$criteria->compare('t.is_user',$this->is_user);
			$criteria->compare('t.is_search',$this->is_search);
			$criteria->compare('t.is_left',$this->is_left);
			if($this->add_time != '')
				$criteria->addBetweenCondition('t.add_time',strtotime($this->add_time),(strtotime($this->add_time)+3600*24-1));
			if($this->up_time != '')
				$criteria->addBetweenCondition('t.up_time',strtotime($this->up_time),(strtotime($this->up_time)+3600*24-1));
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
	 * @return TagsType the static model class
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
	 * 返回 定级标签分类
	 * @return array get_p_id
	 */
	public  function get_p_id(){
		if($this->p_id==0)
			return CHtml::listData($this->findAll('`status`=1 AND `p_id`=0 AND id != :id',array(':id'=>$this->id)), 'id', 'name');
		return CHtml::listData($this->findAll('`status`=1 AND `p_id`=0'), 'id', 'name');
	}
	
	/**
	 * 验证是否有子类
	 */
	public function exist_son(){
		if($this->p_id !=0){
			if($this->find('p_id=:id',array(':id'=>$this->id)))
				$this->addError('p_id', '分类下有子类 不能成为子类');		
		}elseif(TagsSelect::model()->find('type_id=:type_id',array(':type_id'=>$this->id)))
				$this->addError('p_id', '分类下有选中标签 不能成为顶级分类');
	}
	
	/**
	 * 检测是否有效
	 */
	public function exist_in(){
		if(!in_array($this->p_id,array_merge(array(0),array_keys($this->_p_id))))
			$this->addError('p_id', $this->getAttributeLabel('p_id').' 不是有效的值');
	}
	
	/**
	 * 二级分类名称唯一
	 */
	public function unique_son(){
		if($this->p_id !=0 && $this->isNewRecord){
			if($this->find('`p_id`=:p_id AND `name`=:name',array(':p_id'=>$this->p_id,':name'=>$this->name)))
				$this->addError('name', $this->getAttributeLabel('name').' "'.$this->name.'" 已被取用');
		}elseif($this->p_id !=0){
			if($this->find('`p_id`=:p_id AND `name`=:name AND id !=:id',array(':p_id'=>$this->p_id,':name'=>$this->name,':id'=>$this->id)))
				$this->addError('name', $this->getAttributeLabel('name').' "'.$this->name.'" 已被取用');
		}elseif($this->p_id == 0 && $this->id == ''){
			if($this->find('`p_id`=0 AND `name`=:name',array(':name'=>$this->name)))
				$this->addError('name', $this->getAttributeLabel('name').' "'.$this->name.'" 已被取用');
		}elseif($this->p_id == 0 && $this->id != ''){
			if($this->find('`p_id`=0 AND `name`=:name AND id !=:id ',array(':name'=>$this->name,':id'=>$this->id)))
				$this->addError('name', $this->getAttributeLabel('name').' "'.$this->name.'" 已被取用');
		}		
	}
	
	public function unique_is_left(){
		if($this->p_id ==0 && $this->is_left==1){
			if(!! $model=$this->find('p_id=0 AND is_left=1'))
				$this->addError('is_left', $this->getAttributeLabel('is_left').' 已被"'.$model->name.'" 取用');
		}
	}
	
	/**
	 * 栏目无限极递归
	 * @param unknown $model
	 * @param number $id
	 * @param array $array
	 * @return multitype:unknown multitype:unknown NULL
	 */
	public static function _data($model,$id=0,$array=array())
	{
		foreach ($model as $data)
		{
			if($id==$data->p_id)
				$array[]=array('data'=>$data,'son'=>self::_data($model,$data->id));
		}
		return $array;
	}
	
	/**
	 * 递归分类展示
	 * @param unknown $data
	 * @param string $html
	 * @return string
	 */
	public static function _show($data,$html='',$template=array('<li>','<ul>','</ul>','</li>'))
	{
		$html='';
		foreach($data as $v){
			$html .= $template[0].self::buttons($v['data']);
			if(isset($v['son'][0]))
				$html .=$template[1].self::_show($v['son']).$template[2];
			$html .=$template[3];
		}
		return $html;
	}
	
	/**
	 * 分类显示按钮操作
	 * @param unknown $data
	 * @return string
	 */
	public static function buttons($data){
		$html = '<input type="checkbox" value="'.$data->id.'" />';
		$html .='<label>'.$data->name.' [ID:'.$data->id.']</label>';
		if($data->p_id !=0)
			$html .=' [选择标签数:'.(isset($data->TagsType_TagsSelect_Count)?$data->TagsType_TagsSelect_Count:0).']';
		$html .='  ['.CHtml::link('查看分类',array("/admin/tmm_tagsType/view","id"=>$data->id)).'] ';//修改
		$html .='  ['.CHtml::link('修改分类',array("/admin/tmm_tagsType/update","id"=>$data->id)).'] ';//修改
		if($data->p_id==0)
			$html .='  ['.CHtml::link('添加子分类',array("/admin/tmm_tagsType/create","id"=>$data->id),array('title'=>'添加子分类')).'] ';//添加
		if($data->p_id !=0)
			$html .='  ['.CHtml::link('选择添加标签',array("/admin/tmm_tagsType/select","id"=>$data->id),array('title'=>'选择添加标签')).'] ';//添加
						
		if($data->status==1)
			$html .='  ['.CHtml::link('禁用',array("/admin/tmm_tagsType/disable","id"=>$data->id)).'] ';//禁用
		else
			$html .='  ['.CHtml::link('激活',array("/admin/tmm_tagsType/start","id"=>$data->id)).'] ';//激活
		$html .='  ['.CHtml::link('删除','#',array('submit'=>array('/admin/tmm_tagsType/delete','id'=>$data->id),'confirm'=>'你确定删除该分类?')).'] ';//删除
	
		$html .='  [排序]'.CHtml::textField('name['.$data->id.']',$data->sort,array('class'=>'name'));
		return $html;
	}
	
	/**
	 * 树形显示
	 * @param unknown $model
	 * @param unknown $id
	 */
	public static function tree($model,$id){
		return self::_show(self::_data($model,$id));
	}
	
	/**
	 * 树形选择器 需要些自己的 json
	 * @param unknown $model
	 * @param unknown $params
	 * @param number $id
	 * @param string $action
	 */
	public static function dynatree($model,$params=array(),$id=0,$action='user_json')
	{
		return self::$action(self::_data($model,$id),$params);
	}
	
	/**
	 * 用户专用分类标签
	 * @param unknown $array
	 * @param unknown $params
	 * @param unknown $tree
	 * @return string
	 */
	public static function user_json($array,$params=array(),$tree=array())
	{
		$key=0;
		foreach($array as $data)
		{
			if($data['son'] != array()){
				$tree[$key] = array(
						'title' => $data['data']->name,
						'key' => $data['data']->id,
						'icon' => false,
						//'select'=>false,
				);
				foreach($data['son'] as  $data_son)
				{
					$tree[$key]['children'][] = array(
							'title' => $data_son['data']->name,
							'key' => $data_son['data']->id,
							'icon' => false,
							'select' =>in_array($data_son['data']->id,$params),
					);
				}
				$key++;
			}
		}
		return json_encode($tree);
	}
	
	/**
	 * 
	 * @param string $array false 返回model 对象 包含父类 true 返回 子类数组
	 * @return Ambigous <multitype:static , mixed, static, NULL, multitype:unknown Ambigous <static, NULL> , multitype:, multitype:unknown >
	 */
	public static function user_tages_type($array=false, $p_id=false)
	{	
		$criteria=new CDbCriteria;
		$criteria->order='`t`.`sort`';
		$criteria->select='`id`,`p_id`,`name`';
		$criteria->addCondition('`t`.`status`=1');
		if($array)
			$criteria->addCondition('`t`.`p_id` !=0 AND `t`.`is_user` =:is_user');
		else
			$criteria->addCondition('(`t`.`p_id` !=0 AND `t`.`is_user` =:is_user) OR `t`.`p_id` =0');
		if($p_id)
			$criteria->addColumnCondition(array('`t`.`p_id`'=>$p_id));
		$criteria->params[':is_user']=TagsType::yes_is_user;
		if($array)
		{
			$models=self::model()->findAll($criteria);
			$return=array();
			foreach ($models as $model)
				$return[]=$model->id;
			return $return;
		}else
			return self::model()->findAll($criteria);
	}

	/**
	 * 某个父分类下的用户可选择的分类
	 * @param bool|false $array
	 * @return array|mixed|null
	 */
	public static function user_tages_type_p($p_id)
	{
		$array = array(
			'condition'=>'`t`.`p_id`=:p_id AND `t`.`is_user` =:is_user AND `t`.`status`=1',
			'params'=>array(
				':p_id'=>$p_id,
				':is_user'=>TagsType::yes_is_user,
			)
		);
		return self::model()->findAll($array);
	}
	
	/**
	 * 显示标签分类
	 * @param unknown $data
	 * @return string
	 */
	public function select_tags($data){
		$i=0;
		$return='';
		foreach ($data as $v)
		{
			if($i>10) {
				$return .= '[<b>' . $v->TagsSelect_Tags->name . '</b>]<br>';
				$i=-1;
			}else
				$return .= '[<b>' . $v->TagsSelect_Tags->name . '</b>] ';
			$i++;
		}
		return $return;
	}
}
