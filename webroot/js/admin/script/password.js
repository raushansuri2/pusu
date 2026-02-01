$("#changePassword").click(function(){
    var fullName = $("#fullName").val();    
    var email = $("#email").val();
    var profilePicture = $("#profilePicture").val();
    
    var email_regx = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/     
     
    var status = $("#status").val();
    $(".jsFormError").remove();
    $(".error-message").remove('');
    var formError = 0;
    var focus = '';
    
    var old_password = $("#old_password").val();
    var new_password = $("#new_password").val();
    var confirm_password = $("#confirm_password").val();
    
    
    if (old_password == '') {
        $("#old_password").after("<p class='jsFormError'>Please enter old password.</p>");
        if (focus == '') {
            focus = 'old_password';
        }   
        formError++;
    }else if (old_password.length < 6 ) {
        $("#old_password").after("<p class='jsFormError'>Old password must be atleast 6 character long.</p>");
        if (focus == '') {
            focus = 'old_password';
        }   
        formError++;
    }else if (old_password.length > 25) {
        $("#old_password").after("<p class='jsFormError'>Old password exceeds maximum 25 character limit.</p>");
        if (focus == '') {
            focus = 'old_password';
        }   
        formError++;
    }
    
    if (new_password == '') {
        $("#new_password").after("<p class='jsFormError'>Please enter new password.</p>");
        if (focus == '') {
            focus = 'new_password';
        }   
        formError++;
    }else if (new_password.length < 6 ) {
        $("#new_password").after("<p class='jsFormError'>New password must be atleast 6 character long.</p>");
        if (focus == '') {
            focus = 'new_password';
        }   
        formError++;
    }else if (new_password.length > 25) {
        $("#new_password").after("<p class='jsFormError'>New password exceeds maximum 25 character limit.</p>");
        if (focus == '') {
            focus = 'new_password';
        }   
        formError++;
    }
    
    if (confirm_password == '') {
        $("#confirm_password").after("<p class='jsFormError'>Please re enter password.</p>");
        if (focus == '') {
            focus = 'confirm_password';
        }   
        formError++;
    }else if (confirm_password.length < 6 ) {
        $("#confirm_password").after("<p class='jsFormError'>Confirm password must be atleast 6 character long.</p>");
        if (focus == '') {
            focus = 'confirm_password';
        }   
        formError++;
    }else if (confirm_password.length > 25) {
        $("#confirm_password").after("<p class='jsFormError'>Confirm password exceeds maximum 25 character limit.</p>");
        if (focus == '') {
            focus = 'confirm_password';
        }   
        formError++;
    }else{
        if (new_password != '' && new_password != confirm_password) {
            $("#confirm_password").after("<p class='jsFormError'>Confirm password do not match with password.</p>");
            if (focus == '') {
                focus = 'confirm_password';
            }   
            formError++;               
        }
    }
        
    if (formError == '0') {
        $("#changePasswordFrm").submit();
    }else{
        $("#"+focus).focus();  
    }
});

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}