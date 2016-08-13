<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-07-27 14:22:20 */
class OrderController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'Order';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \Order('search');
        //清除默认值
        $model->unsetAttributes();
        if (isset($_GET['Order']))
            $model->attributes = $_GET['Order'];

        $this->render('admin', array(
            'model'=>$model,
        ));
    }
    
    /**
     * 查看
     * @param integer $id
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model'=>$this->loadModelByPk($id),
        ));
    }
}
