/*账单详细资料的显示*/
$(window).load(function(){
    $(".first-tbody").click(function(){
    	
        var secondTbody = $(this).next(".second-tbody");
        if ($(secondTbody).css("display") == "table-row-group") {
            $(secondTbody).hide(); 
            return false;
        }
        $('.second-tbody').hide();
        $(secondTbody).css("display","table-row-group");
    });
})