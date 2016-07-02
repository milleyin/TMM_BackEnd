$(function(){
    /**
     * 点击添加项目操作
     */
    $('.info_nav_float_left').click(function () {
        $(this).css("background-color","#999999");							//设置自己背景顔色
        $('.info_nav_float_right').css("background-color","#F2F2F2");  //设置nav  背景顔色
        $('.create_items').css("display","inherit");						//设置添加项目显示
        $('.create_descirbe').css("display","none");						//设置添加描述隐藏

        var cost_info = CKEDITOR.instances['Shops[cost_info]'].getData();
        var book_info = CKEDITOR.instances['Shops[book_info]'].getData();
        $('#Shops_cost_info').val(cost_info);
        $('#Shops_book_info').val(book_info);

        if(CKEDITOR.instances['Actives[remark]']) {
            var actives_remarks = CKEDITOR.instances['Actives[remark]'].getData();
            console.log(actives_remarks);
            $('#Actives_remark').val(actives_remarks);
        }


    });
    /**
     * 点击添加描述操作
     */
    $('.info_nav_float_right').click(function(){
        $(this).css("background-color","#999999");
        $('.info_nav_float_left').css("background-color","#F2F2F2");
        $('.create_descirbe').css("display","inherit");
        $('.create_items').css("display","none");

    });
})