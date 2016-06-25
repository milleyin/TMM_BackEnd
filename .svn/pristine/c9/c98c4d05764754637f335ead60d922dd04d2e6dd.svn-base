<?php
/**
 * 
 * @author Changhai Zhan
 *	创建时间：2015-08-20 14:28:07 */
class Tmm_shopsController extends MainController
{
	/**
	 * 默认操作数据模型
	 * @var string
	 */
	public $_class_model = 'Shops';
	
	/**
	 * 管理员的单独页面
	 * @var unknown
	 */
	public $admin_views = array(
					'is_sale'=>Shops::is_sale_yes,
					'tops'=>Shops::tops_yes,
					'selected'=>Shops::selected,
					'selected_tops'=>Shops::selected_tops_yes,
					'hot'=>Shops::hot_yes,
					'audit'=>Shops::audit_pending,
				);

	/**
	 * 垃圾回收页
	 */
	public function actionIndex()
	{
		$criteria = new CDbCriteria;
		$criteria->with = array(
				'Shops_Agent',
				'Shops_ShopsClassliy',
		);
		$criteria->addColumnCondition(array('`t`.`status`'=>Shops::status_del));
		
		$model = new Shops;	
		$this->render('index', array(
			'model'=>$model->search($criteria),
		));
	}
	
	/**
	 *管理页
	 */
	public function actionAdmin($view='admin')
	{
		$model = new Shops('search');
		// 删除默认属性
		$model->unsetAttributes(); 
		if (isset($_GET['Shops']))
			$model->attributes = $_GET['Shops'];
		//单独页面
		if (isset($this->admin_views[$view]))
		{
			if ($model->$view == '')
				$model->$view = $this->admin_views[$view];
			$view = 'admin_' . $view;
		}
		else 
			$view = 'admin';

		$this->render($view, array(
			'model'=>$model,
		));
	}
	
	/**
	 *管理页=======推荐内容
	 */
	public function actionAdmin_selected()
	{
		$model = new Shops('search');
		$model->unsetAttributes();  // 删除默认属性
		if (isset($_GET['Shops']))
			$model->attributes = $_GET['Shops'];
		
		if ($model->selected == '')
			$model->selected = Shops::selected;
		
		$this->render('admin_selected', array(
			'model'=>$model,
		));
	}
	
	/**
	 * 推荐商品
	 * @param unknown $id
	 */
	public function actionSelected($id)
	{
		$model = $this->loadModel($id,array(
				'with'=>'Shops_ShopsClassliy',
				'condition'=>'`t`.`status`=1 AND `t`.`audit`=:audit AND `t`.`selected`=:selected',
				'params'=>array(
					':audit'=>Shops::audit_pass,
					':selected'=>Shops::selected_not,
		)));		
		
		$model->scenario = 'selected';
		$this->_Ajax_Verify($model, 'shops-form');
		if (isset($_POST['Shops']))
		{
			$model->attributes = $_POST['Shops'];
			$model->selected_time = time();
			$model->selected = Shops::selected;
			if ($model->save() && $this->log('设置推荐线路('.$model->Shops_ShopsClassliy->name.')', ManageLog::admin, ManageLog::update))
				$this->back();
		}
		$this->render('selected', array(
				'model'=>$model,
		));	
	}
	
	/**
	 * 取消推荐
	 * @param integer $id
	 */
	public function actionRemove($id)
	{
		$model = $this->loadModel($id, array(
								'with'=>'Shops_ShopsClassliy',
								'condition'=>'`t`.`selected`=:selected',
								'params'=>array(':selected'=>Shops::selected, )
						));
		if ($model->updateByPk($id, array('selected'=>Shops::selected_not, 'selected_time'=>0, 'up_time'=>time(), )))
			$this->log('取消推荐商品('.$model->Shops_ShopsClassliy->name.')', ManageLog::admin, ManageLog::update);
		
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 设置置顶
	 * @param unknown $id
	 */
	public function actionTops($id)
	{
		if ($this->loadModel($id, '`t`.`status`=1 AND `t`.`audit`=:audit AND `t`.`tops`=:tops',array(
					':audit'=>Shops::audit_pass,
					':tops'=>Shops::tops_no
				))->updateByPk($id, array('tops'=>Shops::tops_yes, 'tops_time'=>time(), 'up_time'=>time() ))
			)
			$this->log('设置商品为置顶', ManageLog::admin, ManageLog::update);
			
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 取消置顶
	 * @param integer $id
	 */
	public function actionTops_no($id)
	{
		if ($this->loadModel($id,'`t`.`tops`=:tops',array(
					':tops'=>Shops::tops_yes
				))->updateByPk($id, array('tops'=>Shops::tops_no, 'tops_time'=>0, 'selected_tops'=>Shops::selected_tops_no, 'selected_tops_time'=>0, 'up_time'=>time()))
			)
			$this->log('设置商品为取消置顶相关置顶', ManageLog::admin, ManageLog::update);
		
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 热门活动 需要上线 活动类型
	 * @param integer $id
	 */
	public function actionHot($id)
	{
		if ($this->loadModel($id, 'status=:status AND hot=:hot AND c_id=:c_id', array(
					':status'=>Shops::status_online, 
					':c_id'=>Shops::shops_actives,
					':hot'=>Shops::hot_no,
				))->updateByPk($id, array('hot'=>Shops::hot_yes, 'hot_time'=>time(), 'up_time'=>time()))
		)
			$this->log('设置商品(活动)为热门活动',ManageLog::admin,ManageLog::update);
		
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 取消热门活动 需要活动类型
	 * @param integer $id
	 */
	public function actionHot_no($id)
	{
		if ($this->loadModel($id, 'hot=:hot AND c_id=:c_id', array(
				':c_id'=>Shops::shops_actives,
				':hot'=>Shops::hot_yes,
		))->updateByPk($id, array('hot'=>Shops::hot_no, 'hot_time'=>0, 'up_time'=>time()))
		)
		{
			$this->log('取消商品(活动)为热门活动', ManageLog::admin, ManageLog::update);
		}
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 设置推荐置顶
	 * @param unknown $id
	 */
	public function actionSelected_tops($id)
	{
		if( $this->loadModel($id, '`status`=1 AND `audit`=:audit AND `selected`=:selected AND `selected_tops`=:selected_tops',array(
					':audit'=>Shops::audit_pass,
					':selected'=>Shops::selected,
					':selected_tops'=>Shops::selected_tops_no
				))->updateByPk($id, array('selected_tops'=>Shops::selected_tops_yes, 'selected_tops_time'=>time(), 'up_time'=>time()))
			)
			$this->log('设置商品为推荐置顶', ManageLog::admin, ManageLog::update);
		
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 取消推荐置顶
	 * @param integer $id
	 */
	public function actionSelected_tops_no($id)
	{
		if ($this->loadModel($id,'`t`.`selected_tops`=:selected_tops',array(
					':selected_tops'=>Shops::selected_tops_yes
				))->updateByPk($id, array('selected_tops'=>Shops::selected_tops_no, 'selected_tops_time'=>0, 'up_time'=>time())))
			$this->log('设置商品为取消推荐置顶', ManageLog::admin, ManageLog::update);
		
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}
	
	/**
	 * 选择创建商品类型
	 */
	public function actionSelect($id)
	{
		$model=new Shops;
		$model->scenario='select_create';
		$this->_class_model='Agent';
		$model->Shops_Agent=$this->loadModel($id,'`status`=1');
		 
		$this->_Ajax_Verify($model,'shops-form');

		if (isset($_POST['Shops'])) 
		{
			$model->attributes=$_POST['Shops'];
			$this->_class_model='ShopsClassliy';
			$model_class=$this->loadModel($model->c_id,'is_agent=:is_agent',array(':is_agent'=>ShopsClassliy::create_agent));
			
			$this->redirect(array('/admin/'.$this->prefix.$model_class->admin.'/create','id'=>$id));
		}
		$this->render('shops',array(
				'model'=>$model,
		));
	}

	/**
	 * 设为可卖
	 * @param integer $id
	 */
	public function actionSale_yes($id)
	{
		if ($this->loadModel($id)->updateByPk($id, array('is_sale'=>1)))
			$this->log('设置商品为可卖品', ManageLog::admin, ManageLog::update);
		
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}

	/**
	 * 设为非卖品
	 * @param integer $id
	 */
	public function actionSale_no($id)
	{
		if ($this->loadModel($id)->updateByPk($id, array('is_sale'=>0)))
			$this->log('设置商品为非卖品', ManageLog::admin, ManageLog::update);
		
		if (!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : $this->back(true));
	}


}
