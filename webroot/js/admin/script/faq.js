$("#submitFrm").on("click", function(){
    var question = $("#question").val();    
    var answer = $(".cke_wysiwyg_frame").contents().find("body").text();
    
    //var answer = $("#answer").val();
    var status = $("#status").val();
    $(".jsFormError").remove();
    $(".error-message").remove('');
    var formError = 0;
    var focus = '';
    if (question == '') {
        $("#question").after("<p class='jsFormError'>Please enter question.</p>");
        focus = 'question';
        formError++;
    }else if(question.length < 10 ) {
        $("#question").after("<p class='jsFormError'>Question must be atleast 10 character long.</p>");
        focus = 'question';
        formError++;
    }   
    else if(question.length > 255) {
        $("#question").after("<p class='jsFormError'>Question exceeds maximum 255 character limit.</p>");
        focus = 'question';
        formError++;
    }
    
    
    if (answer == '') {
        $("#cke_answer").after("<p class='jsFormError'>Please enter answer.</p>");
        if (focus == '') {
            focus = '';
        }   
        formError++;
    }else if (answer.length < 10 ) {
        $("#cke_answer").after("<p class='jsFormError'>Answer must be atleast 10 character long.</p>");
        if (focus == '') {
            focus = '';
        }   
        formError++;
    }else if (answer.length > 1000) {
        $("#cke_answer").after("<p class='jsFormError'>Answer exceeds maximum 1000 character limit.</p>");
        if (focus == '') {
            focus = '';
        }   
        formError++;
    }
        
        
    if (status == '') {
        $("#status").after("<p class='jsFormError'>Please select member status.</p>");
        if (focus == '') {
            focus = 'status';
        }   
        formError++;
    }
   
    if (formError == '0') {
        $("#faqSubmissionForm").submit();
    }else{
        $("#"+focus).focus();  
    }
});