
<div class="content_box">
<?php
	echo $this->breadcrumbs(array(
				'商家账号管理'=>array('admin'),
				'账号详情',
				$model->phone,
			));
?>
 <div class="box_div">
 <div style="visibility:hidden"><span>账号信息</span></div>
 <div class="title"><span>账号信息</span></div>
  <div class="box box_one"> 
    <div class="box_left">
    <?php echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/head.png','',array('class'=>'head_img'))?>
    </div>  <!-- .box_left -->
    <div class="box_right">
    <div class="account_table">
    <table border="0" class="account_info">
     <tbody>
       <tr>
         <td>登录手机号：</td>
         <td><?php echo CHtml::encode($model->phone); ?></td>
       </tr>
       <tr>
         <td>已开通子账号数：</td>
         <td><?php echo CHtml::encode($model->Store_Content->son_count); ?></td>
       </tr>
       <tr>
         <td>当前子账号上限：</td>
         <td><?php echo CHtml::encode($model->Store_Content->son_limit); ?></td>
       </tr>
       <tr>
         <td>项目数：</td>
         <td><?php echo CHtml::encode($model->Store_Items_Count); ?></td>
       </tr>
       <tr>
         <td>订单量：</td>
         <td><?php echo CHtml::encode('待查'); ?></td>
       </tr>
       <tr>
         <td>剩余保证金：</td>
         <td><?php echo CHtml::encode($model->Store_Content->deposit);?>元 &nbsp;<?php echo CHtml::link('保证金记录',array('/agent/agent_store/deposit','id'=>$model->id))?></td>
       </tr>
       <tr>
         <td>账号状态：</td>
         <td class="<?php echo $model->status==1?'state_normal':'state'?>"><?php echo CHtml::encode($model::$_status[$model->status]); ?></td>
       </tr>
       <tr>
         <td>商家标签：</td>
         <td>     
         <?php foreach($model->Store_TagsElement as $v) { ?>
				<div class="tag_img one">
					<?php echo CHtml::image(Yii::app()->request->baseUrl.'/css/agent/images/tag_bg.png');?>
					<span><?php echo CHtml::encode($v->TagsElement_Tags->name); ?></span>
				</div>
		<?php } ?>                   
         </td>
       </tr>
       <tr>       
         <td>注册时间：</td>
         <td><?php echo CHtml::encode(date('Y-m-d H:i:s',$model->add_time));?></td>
       </tr>
       <tr>
         <td>登录次数：</td>
         <td><?php echo CHtml::encode($model->count);?></td>
       </tr>
       <tr>
         <td>最后登录时间：</td>
         <td><?php echo CHtml::encode(date('Y/m/d H:i:s',$model->last_time));?>  <?php echo CHtml::encode($model->last_address); ?></td>
       </tr>
       <tr>
     </tbody>        
     </table>
      </div>
    </div>   <!-- .box_right -->
  </div> <!--  .box -->
 </div>   <!-- .box_div -->
 
<div class="box_div">
 <div style="visibility:hidden"><span>公司信息</span></div>
  <div class="title"><span>公司信息</span></div>
    <div class="box box_two">     
    <div class="box_left">
      <div class="account_table">
        <table border="0" class="business_info">
         <tbody>
           <tr>
             <td>公司名称：</td>
             <td><?php echo CHtml::encode($model->Store_Content->name);?></td>
           </tr>
           <tr>
             <td>公司地址：</td>
             <td class="address">
             <?php echo CHtml::encode(
             		$model->Store_Content->Content_area_id_p_Area_id->name.
             		$model->Store_Content->Content_area_id_m_Area_id->name.
             		$model->Store_Content->Content_area_id_c_Area_id->name.
             		$model->Store_Content->address
				);?>
             </td>
           </tr>
           <tr>
             <td>公司电话：</td>
             <td><?php echo CHtml::encode($model->Store_Content->store_tel);?></td>
           </tr>
           <tr>
             <td>公司邮编：</td>
             <td><?php echo CHtml::encode($model->Store_Content->store_postcode);?></td>
           </tr>
           <tr>
             <td>公司法人：</td>
             <td><?php echo CHtml::encode($model->Store_Content->com_contacts);?></td>
           </tr>
           <tr>
             <td>法人身份证号：</td>
             <td><?php echo CHtml::encode($model->Store_Content->com_identity);?></td>
           </tr>
           <tr>
             <td>法人联系电话：</td>
             <td><?php echo CHtml::encode($model->Store_Content->com_phone);?></td>
           </tr>
           <tr>
             <td>营业执照编号：</td>
             <td><?php echo CHtml::encode($model->Store_Content->bl_code);?></td>
           </tr>
           <tr>       
             <td>企业负责人：</td>
             <td><?php echo CHtml::encode($model->Store_Content->lx_contacts);?></td>
           </tr>
           <tr>
             <td>联系电话：</td>
             <td><?php echo CHtml::encode($model->Store_Content->lx_phone);?></td>
           </tr>
           <tr>
             <td>企业负责人身份证号：</td>
             <td><?php echo CHtml::encode($model->Store_Content->lx_identity_code);?></td>
           </tr>
         </tbody>        
         </table>
      </div>
    </div>  <!-- .box_left -->
    <div class="box_right">
      <div class="certificate_imgs">
        <div class="certificate_one">
       			 <?php echo $this->show_img($model->Store_Content->bl_img);?>
        </div>
        <div class="certificate_two">
        		 <?php echo $this->show_img($model->Store_Content->identity_hand);?>
        </div>
        <div class="certificate_three">
        		 <?php echo $this->show_img($model->Store_Content->identity_before);?>
        </div>
        <div class="certificate_four">
         		<?php echo $this->show_img($model->Store_Content->identity_after);?>
         </div>
      </div>
    </div>   <!-- .box_right -->
  </div> <!--  .box -->
</div>   <!-- .box_div -->

<div class="box_div">
<div style="visibility:hidden"><span>公司信息</span></div>
  <div class="title"><span>公司信息</span></div>
    <div class="box box_three">    
    <div class="box_left">
        <div class="bank_table">
        <table border="0" class="bank_info">
         <tbody>
           <tr>
             <td>开户行：</td>
             <td><?php 
//             		if(! $model->Store_Content->Content_Bank)
//             			echo CHtml::link('去设置',array('/agent/agent_bank/bank'));
//             		else
             			echo Chtml::encode($model->Store_Content->Content_Bank->name.' '.$model->Store_Content->bank_branch);?></td>
           </tr>
           <tr>
             <td>开户名：</td>
             <td><?php echo Chtml::encode($model->Store_Content->bank_name);?></td>
           </tr>
           <tr>
             <td>银行账号：</td>
             <td><?php echo Chtml::encode($model->Store_Content->bank_code);?></td>
           </tr>
         </tbody>        
         </table>
      </div>
    </div>  <!-- .box_left -->
    <div class="box_right">
      <div class="bank_table">
        <table border="0" class="bank_info">
         <tbody>
           <tr>
             <td>收益总额：</td>
             <td><?php echo CHtml::encode($model->Store_Content->Content_Account->total);?>元</td>
           </tr>
           <tr>
             <td>已提现收益：</td>
             <td><?php echo CHtml::encode($model->Store_Content->Content_Account->cash_count);?>元</td>
           </tr>
           <tr>
             <td>可提现收益：</td>
             <td><?php echo CHtml::encode($model->Store_Content->Content_Account->money);?>元</td>
           </tr>
         </tbody>        
         </table>
      </div>
    </div>   <!-- .box_right -->
  </div> <!--  .box -->
 </div>   <!-- .box_div -->

  <div class="copyright">
    <span>Copyright &copy; TMM365.com All Rights Reserved</span>
  </div>  <!--.copyright--> 
</div>  <!--.content_box-->
