$("#editAdmin").click(function(){
    var fullName = $("#fullName").val();    
    var email = $("#email").val();
    var profilePicture = $("#profilePicture").val();
    
    var email_regx = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/     
     
    var status = $("#status").val();
    $(".error-message").remove('');
    $(".jsFormError").remove();
    var formError = 0;
    var focus = '';
    
    if (fullName == '') {
        $("#fullName").after("<p class='jsFormError'>Please enter admin full name.</p>");
        if (focus == '') {
            focus = 'fullName';
        }   
        formError++;
    }else if (fullName.length < 3 ) {
        $("#fullName").after("<p class='jsFormError'>Full name must be atleast 3 character long.</p>");
        if (focus == '') {
            focus = 'fullName';
        }   
        formError++;
    }else if (fullName.length > 255) {
        $("#fullName").after("<p class='jsFormError'>Full name exceeds maximum 255 character limit.</p>");
        if (focus == '') {
            focus = 'fullName';
        }   
        formError++;
    }
    
    if (email == '') {
        $("#email").after("<p class='jsFormError'>Please enter email address</p>");
        if (focus == '') {
            focus = 'email';
        }            
        formError++;
    }else if(!email_regx.test(email)){
        $("#email").after("<p class='jsFormError'>Please enter valid email id</p>");
        if (focus == '') {
            focus = 'email';
        }
        formError++;
    }
    
    if (profilePicture != '') {
        var allowd = 'jpg,jpeg,png,gif';
        var ext = allowd.split(",");            
        var fileext = profilePicture.split(".");            
        if(!inArray(fileext[fileext.length-1], ext)){
            $("#profilePicture").next('samll').after("<p class='jsFormError'>Please select "+allowd+" files.</p>");                   
            formError++;            
        }
    }
        
        
    if (status == '') {
        $("#status").after("<p class='jsFormError'>Please select member status.</p>");
        if (focus == '') {
            focus = 'status';
        }   
        formError++;
    }
   
    if (formError == '0') {
        $("#editAdminFrm").submit();
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