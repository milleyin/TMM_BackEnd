
<div class="left_box" style="width:100%;"> <!--左侧的内容-->
   <div class="logo">
   <?php echo CHtml::link(CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/logo.png'),array('/agent'),array("target"=>"_parent"));?>
    <div class="title">人与自然的互联</div>
    <div class="info">
      <span class="person_phone" title="<?php echo CHtml::encode(Yii::app()->agent->name); ?>"><?php
      			echo substr(Yii::app()->agent->phone,0,3).'***'.substr(Yii::app()->agent->phone,7,4);
      		?></progress></span>
      <span class="exit"><?php echo CHtml::link('退出',array('/agent/agent_login/out'),array("target"=>"_parent"))?></span>
    </div>
  </div>
  <div class="content">
   <div class="model_one">
    <div class="model_style">
     <div class="model_name">商家</div>
     <div class="line"></div>
     <div class="model_icon">
       <img src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/images/business/business_icon.png">
     </div>      
    </div>
    <div class="model_content">
      <div><a href="<?php echo Yii::app()->createUrl('/agent/agent_store/admin')?>" title="账号管理"  target="content"  ><span>账号管理</span></a></div>
      <div><a href="<?php echo Yii::app()->createUrl('/agent/agent_store/adminSon')?>" title="子账号管理"  target="content" ><span>子账号管理</span></a></div>
    </div>
   </div> <!--.model_one-->

   <div class="model_two">
    <div class="model_style">
     <div class="model_name">内容</div>
     <div class="line"></div>
     <div class="model_icon">
       <img src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/images/business/content_icon.png">
     </div>
    </div>
    <div class="model_content">
      <div><a href="<?php echo Yii::app()->createUrl('/agent/agent_items/admin')?>"  target="content" ><span>项目管理</span></a></div>
      <div><a href="<?php echo Yii::app()->createUrl('/agent/agent_dot/admin')?>" title="景点管理"  target="content" ><span>景点管理</span></a></div>
      <div><a href="<?php echo Yii::app()->createUrl('/agent/agent_thrand/admin')?>"  target="content" ><span>线路管理</span></a></div>
    </div>    
   </div>   <!--.model_two-->

   <div class="model_three">
     <div class="model_style">
     <div class="model_name">订单</div>
     <div class="line"></div>
     <div class="model_icon">
       <img src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/images/business/order_icon.png">
     </div>
    </div>
    <div class="model_content">
     <div><a href="<?php echo Yii::app()->createUrl('/agent/agent_order/admin')?>"  target="content" ><span>自助游订单</span></a></div>
    </div>    
   </div> <!--.model_three-->

   <div class="model_four">
    <div class="model_style">
     <div class="model_name">收益</div>
     <div class="line"></div>
     <div class="model_icon">
       <img src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/images/business/profit.png">
     </div>
    </div>
    <div class="model_content">
     <div><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/income')?>" target="content" ><span>我的收益</span></a></div>
    </div>    
   </div> <!--.model_four-->

   <div class="model_five">
    <div class="model_style">
     <div class="model_name">设置</div>
     <div class="line"></div>
     <div class="model_icon">
       <img src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/images/business/set_icon.png">
     </div>
    </div>
    <div class="model_content">
     <div><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/account')?>" target="content" ><span>账号信息</span></a></div>
     <div><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/company')?>"  target="content" ><span>公司认证</span></a></div>
     <div><a href="<?php echo Yii::app()->createUrl('/agent/agent_agent/safe')?>"  target="content" ><span>安全信息</span></a></div>
      
    </div>    
   </div> <!--.model_five-->  
  </div>  <!--.content_model-->

  <div class="serve_model">
    <div class="phone">
      <img src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/images/phone.png">
      <span>0755 - 22831369</span>
    </div>    
    <div class="url">
      <img src="<?php echo Yii::app()->request->baseUrl; ?>/css/agent/images/url.png">
       <span>Business@Tmm365.com</span>
    </div>           
  </div>  <!--.serve_model-->
  </div>  <!--.left_box-->

<?php 
Yii::app()->clientScript->registerScript('left_target', '
 	 /* 动态改变a便签的背景*/
    $(".model_content a").hover(function(e){
       $(this).css("background","#332E2D");
    },function(){
      $(this).css("background","#403A39");
    }).click(function(e){       
     $(".model_content a").css("background","#403A39");
     $(this).unbind("mouseenter").unbind("mouseleave"); 
     $(this).css("background","#332E2D"); 
   	});
');
?>