<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class UserModulesController extends ModulesController
{
    /**
     * @var string the default layout for the controller view. Defaults to '//layouts/column1',
     * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
     */
    public $layout = '/layouts/column2';
    /**
     * @var array context menu items. This property will be assigned to {@link CMenu::items}.
     */
    public $menu = array();
    /**
     * @var array the breadcrumbs of the current page. The value of this property will
     * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
     * for more details on how to specify this property.
     */
    public $breadcrumbs = array();
    /**
     * 初始化
     * (non-PHPdoc)
     * @see ModulesController::init()
     */
    public function init()
    {
        parent::init();
        $this->name = '微信端';
        $this->title = $this->pageTitle . ' - ' . $this->name;
    }

    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @return void
     *
     * @author Moore Mo
     */
    public function ajaxReturn($data, $type = '') {
        if(empty($type)) $type  =   'JSON';
        switch (strtoupper($type)){
            case 'JSON' :
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                exit(json_encode($data));
            case 'XML'  :
                // 返回xml格式数据
                header('Content-Type:text/xml; charset=utf-8');
                exit(xml_encode($data));
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                header('Content-Type:application/json; charset=utf-8');
                $handler  =   isset($_GET['callback']) ? $_GET['callback'] : 'jsonpReturn';
                exit($handler.'('.json_encode($data).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                header('Content-Type:text/html; charset=utf-8');
                exit($data);
        }
    }

    /**
     * 处理Model返回的错误
     * @param array $error Model错误
     * @return string
     *
     * @author Moore Mo
     */
    public function ajaxReturnError($error = array()) {
        $errorMsg = '服务器繁忙，请稍候重试！';
        if (empty($error)) return $errorMsg;
        foreach ($error as $key => $val) {
            $errorMsg = $val[0];
            break;
        }
        return $errorMsg;
    }
}