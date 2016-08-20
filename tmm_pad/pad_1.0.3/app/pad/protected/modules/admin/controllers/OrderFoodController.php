<?php
namespace app\admin\controllers;

use AdminModulesController;

/**
 * @author Changhai Zhan
 *    创建时间：2016-07-27 17:44:20 */
class OrderfoodController extends AdminModulesController
{
    /**
     * 当前操作模型的名称
     * @var string
     */
    public $_modelName = 'OrderFood';
    
    /**
     * 管理
     */
    public function actionAdmin()
    {
        $model = new \OrderFood('search');
        $model->OrderFood_Order = new \Order('search');
        $model->OrderFood_Store = new \Store('search');
        $model->OrderFood_Pad = new \Pad('search');
        //清除默认值
        $model->unsetAttributes();
        $model->OrderFood_Order->unsetAttributes();
        $model->OrderFood_Store->unsetAttributes();
        $model->OrderFood_Pad->unsetAttributes();
        if (isset($_GET['OrderFood']))
            $model->attributes = $_GET['OrderFood'];
        if (isset($_GET['Order']))
            $model->OrderFood_Order->attributes = $_GET['Order'];
        if (isset($_GET['Store']))
            $model->OrderFood_Store->attributes = $_GET['Store'];
        if (isset($_GET['Pad']))
            $model->OrderFood_Pad->attributes = $_GET['Pad'];
        
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
        $criteria = new \CDbCriteria;
        $criteria->with = array(
            'OrderFood_Order',
            'OrderFood_User',
            'OrderFood_Store' => array(
                'with' => array(
                    'Store_Area_province',
                    'Store_Area_city',
                    'Store_Area_district',
                ),
            ),
            'OrderFood_Pad',
        );
        $this->render('view', array(
            'model'=>$this->loadModelByPk($id, $criteria),
        ));
    }
}
