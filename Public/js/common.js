/*账单详细资料的显示*/
$(window).load(function(){
    $(".first-tbody").on('tap',function(){
        var secondTbody = $(this).next(".second-tbody");
        var dropArrow = $(this).find(".drop-arrow");
        if (typeof dropArrow[0] == "object") {
            dropArrow[0]['style']['transform'] = dropArrow[0]['style']['transform'] != "rotate(180deg)" ? "rotate(180deg)" : "rotate(0deg)";
        }
        if ($(secondTbody).css("display") == "table-row-group") {
            $(secondTbody).hide(); 
            return false;
        }
        $('.second-tbody').hide();
        $(secondTbody).css("display","table-row-group");
    });
})