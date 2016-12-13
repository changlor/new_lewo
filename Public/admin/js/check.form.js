/**
 * [后台注册验证]
 **/
function checkForm(){
    if ( '' == $("input[name='username']").val() ) {
        alert("请输入帐号");
        return false;
    }
    if ( '' == $("input[name='password']").val() ) {
        alert("请输入密码");
        return false;
    }
    if ( '' == $("input[name='repassword']").val() ) {
        alert("请输入确认密码");
        return false;
    }
    if ( $("input[name='password']").val() !== $("input[name='repassword']").val() ) {
        alert("两次密码不相同");
        return false;
    }
}

/**
 * [后台添加房源验证]
 **/
function checkAddHouse(){
    if ( '' == $("input[name='house_code']").val() ) {
        alert("房间编码丢失！");
        return false;
    }
    if ( '' == $("input[name='area_name']").val() ) {
        alert("请填写小区！");
        return false;
    }
    if ( '' == $("input[name='building']").val() ) {
        alert("请填写栋！");
        return false;
    }
    if ( '' == $("input[name='floor']").val() ) {
        alert("请填写层！");
        return false;
    }
    if ( '' == $("input[name='door_no']").val() ) {
        alert("请填写门号！");
        return false;
    }
    if ( '' == $("input[name='region_id']").val() ) {
        alert("请选择地区！");
        return false;
    }
    if ( '' == $("input[name='fee']").val() ) {
        alert("请填写物业费！");
        return false;
    }
    if ( '' == $("input[name='house_owner']").val() ) {
        alert("请填写房东姓名！");
        return false;
    }
    if ( '' == $("input[name='address']").val() ) {
        alert("请填写地址！");
        return false;
    }
}

/**
 * [后台添加房间验证]
 **/
function checkAddRoom(){
    if ( '' == $("input[name='room_code']").val() ) {
        alert("房间编码丢失！");
        return false;
    }
    if ( '' == $("input[name='bed_code']").val() ) {
        alert("请填写床位编码！");
        return false;
    }
    if ( '' == $("select[name='room_sort'] option[selected]").val() ) {
        alert("请选择房间序列！");
        return false;
    }
    if ( '' == $("input[name='room_area']").val() ) {
        alert("请填写房间面积！");
        return false;
    }
    if ( '' == $("input[name='rent']").val() ) {
        alert("请填写房间租金！");
        return false;
    }
    if ( '' == $("input[name='room_fee']").val() ) {
        alert("请填写服务费！");
        return false;
    }
    if ( '' == $("input[name='room_code']").val() ) {
        alert("房间编码丢失！");
        return false;
    }
    if ( '' == $("input[name='room_code']").val() ) {
        alert("房间编码丢失！");
        return false;
    }
    if ( '' == $("input[name='room_code']").val() ) {
        alert("房间编码丢失！");
        return false;
    }
}