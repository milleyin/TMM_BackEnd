<div class="content_box" id="page_project_create">
<?php
	echo $this->breadcrumbs(array(
				'项目'=>array('admin'),
				'项目创建',
				$model->name,
			));
?>
<!--.title-->
  <div class="create_nav modify_project_info">
      <?php
      if ($model->Items_ItemsClassliy->append == 'Hotel') {
          ?>
          <div class="create_steap_one five_steaps_width">
              <a class="done">
                  1
              </a>
			<span class="text_done">
				选择归属商家
			</span>
              <div class="line line_first done">
              </div>
          </div>
          <div class="create_steap_two five_steaps_width">
              <a class="done">
                  2
              </a>
			<span class="text_done">
				填写项目信息
			</span>
              <div class="line done">
              </div>
          </div>
          <div class="create_steap_three five_steaps_width">
              <a class="done">
                  3
              </a>
			<span class="text_done">
				选择服务
			</span>
              <div class="line done">
              </div>
          </div>
          <div class="create_steap_four five_steaps_width">
              <a class="done">
                  4
              </a>
			<span class="text_done">
				选择标签
			</span>
              <div class="line done">
              </div>
          </div>
          <div class="create_steap_five five_steaps_width">
              <a class="done">
                  5
              </a>
			<span class="text_done">
				提交审核
			</span>
              <div class="line line_last done last">
              </div>
          </div>
      <?php } else { ?>
          <div class="create_steap_one four_steaps_width">
              <a class="done">
                  1
              </a>
			<span class="text_done">
				选择归属商家
			</span>
              <div class="line line_first done">
              </div>
          </div>
          <div class="create_steap_two four_steaps_width">
              <a class="done">
                  2
              </a>
			<span class="text_done">
				填写项目信息
			</span>
              <div class="line done">
              </div>
          </div>
          <div class="create_steap_three four_steaps_width">
              <a class="done">
                  3
              </a>
			<span class="text_done">
				选择标签
			</span>
              <div class="line done">
              </div>
          </div>
          <div class="create_steap_four four_steaps_width">
              <a class="done">
                  4
              </a>
			<span class="text_done">
				提交审核
			</span>
              <div class="line line_last done last">
              </div>
          </div>
      <?php } ?>
  </div>    <!-- .create_nav -->
  <div class="content">
    <div class="content_five">
       <div class="box_div">
        <div class="box box_one"> 
            <img src="<?php echo Yii::app()->request->baseUrl;?>/css/agent/images/success.png" class="head_img">
            <div class="right">
              <div class="business_name">
                <span class="big">项目添加成功，已提交至平台审核。</span>
                <span class="small">审核进度请致电：<?php echo Yii::app()->params['tmm_400']?> 咨询</span>
              </div>
            </div> <!-- .right -->
        </div> <!--  .box -->
        </div>
       <div class="row-fluid enter check">
          <?php echo CHtml::link('返回项目',array('/agent/agent_items/admin'));?>
          <?php echo CHtml::link('继续添加',array('/agent/agent_items/select'));?>
      </div>
    </div>  <!-- .content_five -->
  </div>   <!--  .content -->
  
  <div class="copyright">
    <span>Copyright &copy; TMM365.com All Rights Reserved</span>
  </div>  <!--.copyright--> 
</div>  <!--.content_box-->
