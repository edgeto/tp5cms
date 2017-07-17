/**
 * 通用AJAX提交
 * @param  {string} url    表单提交地址
 * @param  {string} formObj    待提交的表单对象或ID
 */
function commonAjaxSubmit(url, formObj, callback){
    if(!formObj||formObj==''){
        var formObj="form";
    }
    if(!url||url==''){
        var url=document.URL;
    }
    $(formObj).ajaxSubmit({
        url:url,
        type:"POST",
        success:function(data, st) {
            if(data.status==1){
                $('.alert-success').removeClass('h');
            }else{
                $('.alert-warning-msg').html(data.info);
                $('.alert-warning').removeClass('h');
            }
            if(data.url&&data.url!=''){
                setTimeout(function(){
                    top.window.location.href=data.url;
                },2000);
            }
            if(data.status==1&&data.url==''){
                setTimeout(function(){
                    top.window.location.reload();
                },1000);
            }

            if(callback){
                callback()
            }
        }
    });
    return false;
}