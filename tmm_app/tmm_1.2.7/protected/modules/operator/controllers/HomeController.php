<?php
/**
 * 系统默认控制器
 * @author Changhai Zhan
 *	运营商前端框架入口
 */
class HomeController extends OperatorMainController
{
	/**
	 * 入口
	 * @param number $link_id
	 */
	public function actionIndex($link_id=1)
	{
		$this->layout = '/layouts/_column';
		//获取该导航的第一个链接
		$right = AgentLink::first_url($link_id);
		if(empty($right))
			$this->redirect(Yii::app()->homeUrl);		
		$this->frame = array(
				'top'=>array(
						'url'=>'/operator/home/top',
						'params'=>array('link_id'=>$link_id)),
				'left'=>array(
						'url'=>'/operator/home/left',
						'params'=>array('link_id'=>$link_id)),
				'right'=>$right,
		);
		$this->render('index',array('content'=>'您的浏览器不支持框架,请更换其他浏览器'));
	}
	
	/**
	 * 顶部链接
	 * @param unknown $link_id
	 */
	public function actionTop($link_id)
	{
		$this->layout='/layouts/main_top';
		$this->navbar=AgentLink::top($link_id);
		$this->render('top');
	}
	
	/**
	 * 左边链接
	 * @param unknown $link_id
	 */
	public function actionLeft($link_id)
	{
		$this->layout='/layouts/main_left';
		$this->menu=AgentLink::left($link_id);
		$this->render('left');
	}

	/**
	 * 根据id  省市区联动
	 */
	public function actionArea()
	{
		Area::action();
	}

	/**
	 * 根据名字 省市区联动
	 */
	public function actionArea_name()
	{
		Area::action_name();
	}

}