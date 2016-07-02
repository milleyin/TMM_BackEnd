<?php

//状态码
define('0_MSG', '服务器返回错误');
define('1_MSG', '成功');
define('2_MSG', '未授权');
define('3_MSG', '未登陆或登录超时');
define('4_MSG', '需要进一步确认');
define('5_MSG', '加密狗验证失败');
define('6_MSG', '系统抛错');
define('99_MSG', '重复提交');

//正常返回码
define('SUCCESS_MSG', '正确');

//系统相关错误码
define('40001_MSG', '参数不全');
define('40002_MSG', '参数格式不全');
define('40003_MSG', '验证失败');
define('40004_MSG', '资源不存在');
define('40005_MSG', '模块不允许访问');
define('40006_MSG', '方法不允许访问');
define('40100_MSG', '获取app_token失败');
define('40101_MSG', '该app_token无效，请重新获取');
define('40102_MSG', '系统繁忙，稍后再试');
define('40103_MSG', '发送短信次数过于频繁');
define('40201_MSG','表单数据验证不通过');
define('40202_MSG','CSRF非法请求');
define('40203_MSG', '请求出错');// 没有设置当前的数据模型

//结伴游
define('40301_MSG', '结伴游截止时间应小于当前时间十天后');
define('40302_MSG', '当前结伴游已经截止');
define('40303_MSG', '不是组织者不能创建结伴游');

//活动=====创建
define('40401_MSG','不是代理商不能创建活动');
define('40402_MSG','该创建活动不可以编辑');

//绑定银行卡相关
define('40501_MSG','代理商绑定银行卡请联系客服');

//数据库操作相关错误码
define('50001_MSG', '数据插入错误');
define('50002_MSG', '数据更新错误');
define('50003_MSG', '数据删除错误');
define('50004_MSG', '数据操作错误');
define('50005_MSG', '数据不合法');
define('50006_MSG', '没有相关数据');//没有查询到数据

//登录相关
define('60000_MSG','用户登录是必须的');
define('60001_MSG','供应商登录是必须的');

//订单支付相关
define('60100_MSG','钱包支付失败');
