$("#submitCategory").click(function(){
    var categoryName = $("#categoryName").val();
    var status = $("#status").val();
    
    $(".error-message").remove('');
    $(".jsFormError").remove();
    
    var formError = 0;
    var focus = '';
    
    if (categoryName == '') {
        $("#categoryName").after("<p class='jsFormError'>Please select category name.</p>");
        if (focus == '') {
            focus = 'categoryName';
        }   
        formError++;
    }
    else if(categoryName.length < 2 ) {
        $("#categoryName").after("<p class='jsFormError'>Category name must be atleast 2 character long.</p>");
        focus = 'categoryName';
        formError++;
    }   
    else if(categoryName.length > 100) {
        $("#categoryName").after("<p class='jsFormError'>Category name exceeds maximum 100 character limit.</p>");
        focus = 'categoryName';
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
        $("#categorySubmissionForm").submit();
    }else{
        $("#"+focus).focus();
        return false;
    }
});