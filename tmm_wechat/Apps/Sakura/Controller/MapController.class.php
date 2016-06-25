<?php
namespace Sakura\Controller;
use Think\Controller;

/**
 * 地图控制器
 * Class MapController
 * @package Sakura\Controller
 *
 * @author Moore Mo
 */
class MapController extends Controller {

    /**
     * 显示电子地图
     */
    public function index() {
        $this->assign('title', '电子地图');
        $this->display();
    }

    /**
     * 樱花大道
     */
    public function road() {
        $this->display();
    }

    /**
     * 森林雾峡
     */
    public function fog() {
        $this->display();
    }


    /**
     * 高山流水
     */
    public function waterfall() {
        $this->display();
    }

    /**
     * 哥特广场
     */
    public function plaza() {
        $this->display();
    }

}