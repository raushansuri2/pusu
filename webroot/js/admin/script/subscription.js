$("#subscriptionSubmit").click(function(){
    var startDate = $("#startDate").val();
    var endDate = $("#endDate").val();
    var status = $("#status").val();
    
    $(".error-message").remove('');
    $(".jsFormError").remove();
    
    var formError = 0;
    var focus = '';
    
    if (startDate == '') {
        $("#startDate").after("<p class='jsFormError'>Please select start date.</p>");
        if (focus == '') {
            focus = 'startDate';
        }   
        formError++;
    }
    
    if (endDate == '') {
        $("#endDate").after("<p class='jsFormError'>Please select end date.</p>");
        if (focus == '') {
            focus = 'endDate';
        }   
        formError++;
    }
    
    if (startDate != '' && endDate != '') {
        if (endDate < startDate) {
            $("#endDate").after("<p class='jsFormError'>End date must be less than start date.</p>");
            if (focus == '') {
                focus = 'endDate';
            }   
            formError++;
        }
    }
    
    
    if (status == '') {
        $("#status").after("<p class='jsFormError'>Please select status.</p>");
        if (focus == '') {
            focus = 'status';
        }   
        formError++;
    }
    
   
    if (formError == '0') {
        $("#subscriptionFrm").submit();
    }else{
        $("#"+focus).focus();
        return false;
    }
});