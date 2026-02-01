$("#submitStory").click(function(){
    var categoryId = $("#categoryId").val();
    var storyTitle = $("#storyTitle").val();
    var postedBy = $("#postedBy").val();
    var status = $("#status").val();
    var editorText = $(".cke_wysiwyg_frame").contents().find("body").text();
    
    
    $(".error-message").remove('');
    $(".jsFormError").remove();
    
    var formError = 0;
    var focus = '';
    
    if (categoryId == '') {
        $("#categoryId").after("<p class='jsFormError'>Please select category name.</p>");
        if (focus == '') {
            focus = 'categoryId';
        }   
        formError++;
    }
    
    if (storyTitle == '') {
        $("#storyTitle").after("<p class='jsFormError'>Please select story title.</p>");
        if (focus == '') {
            focus = 'storyTitle';
        }   
        formError++;
    }
    else if(storyTitle.length < 2 ) {
        $("#storyTitle").after("<p class='jsFormError'>Story title must be atleast 2 character long.</p>");
        focus = 'storyTitle';
        formError++;
    }   
    else if(storyTitle.length > 100) {
        $("#storyTitle").after("<p class='jsFormError'>Story title exceeds maximum 100 character limit.</p>");
        focus = 'storyTitle';
        formError++;
    }
    
    if (editorText == '') {
        $("#cke_storyDescription").after('<p class="jsFormError">Please enter page content</p>');
        if (focus == '') {
            focus = '';
        } 
        formError++;
    }else if(editorText.length < 10) {
        $("#cke_storyDescription").after("<p class='jsFormError'>Page content must be atleast 10 character long.</p>");
        if (focus == '') {
            focus = '';
        }formError++;
    }
    
    
    if (postedBy == '') {
        $("#postedBy").after("<p class='jsFormError'>Please select posted by.</p>");
        if (focus == '') {
            focus = 'postedBy';
        }   
        formError++;
    }
    
    if (status == '') {
        $("#status").after("<p class='jsFormError'>Please select status.</p>");
        if (focus == '') {
            focus = 'status';
        }   
        formError++;
    }
    
   
    if (formError == '0') {
        $("#storySubmissionForm").submit();
    }else{
        $("#"+focus).focus();
        return false;
    }
});