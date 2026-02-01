$("#subitPageForm").click(function(){
    var pageTitle = $("#pageTitle").val();
    var status = $("#status").val();
    var editorText = $(".cke_wysiwyg_frame").contents().find("body").text();
    
    var formError = 0;
    var focus = '';
    $(".error-message").remove('');
    $(".jsFormError").remove();
    if (pageTitle == '') {
        $("#pageTitle").after('<p class="jsFormError">Please enter page title</p>');
        focus = 'pageTitle';
        formError++;
    }else if(pageTitle.length < 3 ) {
        $("#pageTitle").after("<p class='jsFormError'>Page title must be atleast 3 character long.</p>");
        focus = 'pageTitle';
        formError++;
    }   
    else if(pageTitle.length > 255) {
        $("#pageTitle").after("<p class='jsFormError'>Page title exceeds maximum 255 character limit.</p>");
        focus = 'pageTitle';
        formError++;
    }
    
    
    if (editorText == '') {
        $("#cke_editor1").after('<p class="jsFormError">Please enter page content</p>');
        if (focus == '') {
            focus = '';
        } 
        formError++;
    }else if(editorText.length < 10) {
        $("#cke_editor1").after("<p class='jsFormError'>Page content must be atleast 10 character long.</p>");
        if (focus == '') {
            focus = '';
        }formError++;
    }
    
    
    
    if (status == '') {
        $("#status").after("<p class='jsFormError'>Please select page status.</p>");
        if (focus == '') {
            focus = 'status';
        }   
        formError++;
    }
    
    if (formError == '0') {
        $("#pageSubmissionForm").submit();
    }else{
        $("#"+focus).focus();
        return false;
    }
});