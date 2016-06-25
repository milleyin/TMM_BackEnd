<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-05 13:59:50 */
class Tmm_itemsController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model='Items';

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria=new CDbCriteria;
		$criteria->with=array(
				'Items_agent',
				'Items_StoreContent'=>array('select'=>'name','with'=>array('Content_Store'=>array('select'=>'phone'))),
				'Items_Store_Manager'=>array('select'=>'phone'),
				'Items_ItemsClassliy',
				'Items_area_id_p_Area_id'=>array('select'=>'name'),
				'Items_area_id_m_Area_id'=>array('select'=>'name'),
				'Items_area_id_c_Area_id'=>array('select'=>'name')
		);
		$criteria->addColumnCondition(array('t.status'=>-1));

		$model=new Items;
		
		$this->render('index',array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin()
	{
		$model=new Items('search');
		$model->unsetAttributes();  // 删除默认属性
		if(isset($_GET['Items']))
			$model->attributes=$_GET['Items'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

    /**
     * 创建项目类型
     */
    public function actionSelect($id)
    {
         $model=new Items;    
         $model->scenario='select_create';
         $this->_class_model='StoreContent';
         $model->Items_StoreContent=$this->loadModel($id,array('with'=>array(
         		'Content_Store'=>array('with'=>array('Store_Agent')),
         )));
         
         $this->_Ajax_Verify($model,'items-form');
    
         if (isset($_POST['Items'])) {
         	$model->attributes=$_POST['Items'];
         	
         	$this->_class_model='ItemsClassliy';	
         	$model_class=$this->loadModel($model->c_id);
               	
             $this->redirect(array('/admin/'.$this->prefix.$model_class->admin.'/create','id'=>$id));
         }
        $this->render('items',array(
            'model'=>$model,
        ));
    }
    
    /**
     * 设置分成比例
     * @param unknown $id
     */
    public function actionPush($id)
    {
    	$model=new Push;
    	$model->scenario='create';
    	$model->Push_Items=$this->loadModel($id,array(
    			'with'=>array('Items_ItemsClassliy'),
    			'condition'=>'t.audit != :audit AND t.is_push=:is_push',
    			'params'=>array(':audit'=>Items::audit_pending,':is_push'=>Items::push_init),
    	));
    	$this->_Ajax_Verify($model,'push-form');
    
    	if(isset($_POST['Push']))
    	{
    		$model->attributes=$_POST['Push'];
    		$model->start_time=strtotime($model->_start_time);//生效时间
    		$model->manage_who=Push::admin;//操作者
    		$model->push_id=$model->Push_Items->id;//被操作者的id
    		$model->push_element=Push::push_items;//被操作者的类型
    		if($model->save() && $this->log('设置代理商的分成比例',ManageLog::admin,ManageLog::create))
    			$this->back();
    	}
    
    	$this->render('/tmm_push/push',array(
    			'model'=>$model,
    	));
    }
}
