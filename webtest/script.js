function clearText()
{
    var textarea = document.getElementById('post_area');
    if(textarea.value=="Hãy nói lên suy nghĩ của bạn") textarea.value='';
    return true;
}
function clearTt()
{
    var texttt = document.getElementById('post_tt');
    if(texttt.value == "Tiêu đề") texttt.value='';
    return true;
}

function clearSrch()
{
    var textsrch = document.getElementById('srch_area');
    if(textsrch.value == "Tìm kiếm...") textsrch.value='';
    return true;
}

function cfDelAcc()
{
    var confirmation = confirm("Bạn có chắc chắn muốn xóa tài khoản?");
    if(confirmation) {
        document.getElementById("confirm_del_acc").value = "1";
        document.getElementById("del_acc_form").submit();//gửi form
    }
}