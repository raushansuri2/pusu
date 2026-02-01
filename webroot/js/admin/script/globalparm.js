$("#globalParmSubmit").click(function(){
    var minimumSweetCompatibility = $("#minimumSweetCompatibility").val();
    var minimumSourCompatibility = $("#minimumSourCompatibility").val();
    
    $(".error-message").remove('');
    $(".jsFormError").remove();
    
    var formError = 0;
    var focus = '';
    
    if (minimumSweetCompatibility == '') {
        $("#minimumSweetCompatibility").after("<p class='jsFormError'>Please enter minimum level of sweet compatibility percentage.</p>");
        if (focus == '') {
            focus = 'minimumSweetCompatibility';
        }   
        formError++;
    }
    else if(isNaN(minimumSweetCompatibility)){
        $("#minimumSweetCompatibility").after("<p class='jsFormError'>Minimum level of sweet compatibility percentage must be numeric.</p>");
        if (focus == '') {
            focus = 'minimumSweetCompatibility';
        }   
        formError++;        
    }else if (minimumSweetCompatibility < 0 ||  minimumSweetCompatibility > 100) {
        $("#minimumSweetCompatibility").after("<p class='jsFormError'>Minimum level of sweet compatibility percentage must be greater than or equal zero and less than hundred.</p>");
        if (focus == '') {
            focus = 'minimumSweetCompatibility';
        }   
        formError++;
    }
    
    
    if (minimumSourCompatibility == '') {
        $("#minimumSourCompatibility").after("<p class='jsFormError'>Please enter minimum level of sour compatibility percentage.</p>");
        if (focus == '') {
            focus = 'minimumSourCompatibility';
        }   
        formError++;
    }
    else if(isNaN(minimumSourCompatibility)){
        $("#minimumSourCompatibility").after("<p class='jsFormError'>Minimum level of sour compatibility percentage must be numeric.</p>");
        if (focus == '') {
            focus = 'minimumSourCompatibility';
        }   
        formError++;        
    }else if (minimumSourCompatibility < 0 ||  minimumSourCompatibility > 100) {
        $("#minimumSourCompatibility").after("<p class='jsFormError'>Minimum level of sour compatibility percentage must be greater than or equal zero and less than hundred.</p>");
        if (focus == '') {
            focus = 'minimumSourCompatibility';
        }   
        formError++;
    }
    
   
    if (formError == '0') {
        $("#globalParmForm").submit();
    }else{
        $("#"+focus).focus();
        return false;
    }
});