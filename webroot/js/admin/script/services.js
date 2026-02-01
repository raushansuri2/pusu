$("#submitService").click(function(){
    var seviceName = $("#seviceName").val();
    var serviceDuration = $("#serviceDuration").val();
    var serviceCost = $("#serviceCost").val();
    
    $(".error-message").remove('');
    $(".jsFormError").remove();
    
    var formError = 0;
    var focus = '';
    
    if (seviceName == '') {
        $("#seviceName").after("<p class='jsFormError'>Please enter service name.</p>");
        if (focus == '') {
            focus = 'seviceName';
        }   
        formError++;
    }else if (seviceName.length < 2 ) {
        $("#seviceName").after("<p class='jsFormError'>Service name must be atleast 2 character long.</p>");
        if (focus == '') {
            focus = 'seviceName';
        }   
        formError++;
    }else if (seviceName.length > 100) {
        $("#seviceName").after("<p class='jsFormError'>Service name exceeds maximum 100 character limit.</p>");
        if (focus == '') {
            focus = 'seviceName';
        }   
        formError++;
    }
    
    
    if (serviceDuration == '') {
        $("#serviceDuration").after("<p class='jsFormError'>Please select service duration.</p>");
        if (focus == '') {
            focus = 'serviceDuration';
        }   
        formError++;
    }
    
    if (serviceCost == '') {
        $("#serviceCost").after("<p class='jsFormError'>Please enter price.</p>");
        if (focus == '') {
            focus = 'serviceCost';
        }   
        formError++;
    }else{
        if (isNaN(serviceCost)) {
            $("#serviceCost").after("<p class='jsFormError'>Please enter numeric value only.</p>");
            if (focus == '') {
                focus = 'serviceCost';
            }   
            formError++;
        }
        
    }
    
   
    if (formError == '0') {
        $("#servicesSubmissionForm").submit();
    }else{
        $("#"+focus).focus();
        return false;
    }
});

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}