var step = 1;

$("#next").click(function(){
    var focus = '';
    var formError = 0;
    $(".jsFormError").remove();
    $(".error-message").remove('');
    var email_regx = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/
    var phone_regx = /^([0-9]{1,9})+[\(\)\s\-\+\d]{9,15}$/;
    var url_regx = /^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/
  
    if (step == '1') {
        var username = $("#username").val();
        var email = $("#email").val();
        var fullName = $("#fullName").val();
        var lastName = $("#lastName").val();
        var password = $("#password").val();
        var confirm_password = $("#confirm_password").val();
        var status = $("#status").val();
        var profilePicture = $("#profilePicture").val();
        var formSrc = $("#formSrc").val();
        
        if (username == '') {
            $("#username").after("<p class='jsFormError'>Please enter username</p>");
            focus = 'username';
            formError++;
        }else if(username.length < 5 ) {
            $("#username").after("<p class='jsFormError'>Username must be atleast 5 character long.</p>");
            focus = 'username';
            formError++;
        }
       
        else if(username.length > 50) {
            $("#username").after("<p class='jsFormError'>Username exceeds maximum 50 character limit.</p>");
            focus = 'username';
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
        
        if (fullName == '') {
            $("#fullName").after("<p class='jsFormError'>Please enter your first name.</p>");
            if (focus == '') {
                focus = 'fullName';
            }   
            formError++;
        }else if (fullName.length < 3 ) {
            $("#fullName").after("<p class='jsFormError'>First name must be atleast 3 character long.</p>");
            if (focus == '') {
                focus = 'fullName';
            }   
            formError++;
        }else if (fullName.length > 255) {
            $("#fullName").after("<p class='jsFormError'>First name exceeds maximum 255 character limit.</p>");
            if (focus == '') {
                focus = 'fullName';
            }   
            formError++;
        }
        
        
        if (lastName == '') {
            $("#lastName").after("<p class='jsFormError'>Please enter your last name.</p>");
            if (focus == '') {
                focus = 'lastName';
            }   
            formError++;
        }else if (lastName.length < 3 ) {
            $("#lastName").after("<p class='jsFormError'>last name must be atleast 3 character long.</p>");
            if (focus == '') {
                focus = 'lastName';
            }   
            formError++;
        }else if (lastName.length > 100) {
            $("#lastName").after("<p class='jsFormError'>Last name exceeds maximum 100 character limit.</p>");
            if (focus == '') {
                focus = 'lastName';
            }   
            formError++;
        }
        
        
        if (formSrc == 'add') {
            if (password == '') {
                $("#password").after("<p class='jsFormError'>Please enter password.</p>");
                if (focus == '') {
                    focus = 'password';
                }   
                formError++;
            }else if (password.length < 6 ) {
                $("#password").after("<p class='jsFormError'>Password must be atleast 6 character long.</p>");
                if (focus == '') {
                    focus = 'password';
                }   
                formError++;
            }else if (password.length > 25) {
                $("#password").after("<p class='jsFormError'>Password exceeds maximum 25 character limit.</p>");
                if (focus == '') {
                    focus = 'password';
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
                if (password != '' && password != confirm_password) {
                    $("#confirm_password").after("<p class='jsFormError'>Confirm password do not match with password.</p>");
                    if (focus == '') {
                        focus = 'confirm_password';
                    }   
                    formError++;               
                }
            }
        }
        else if (formSrc == 'edit') {            
            if (password != '') {
                if (password.length < 6 ) {
                    $("#password").after("<p class='jsFormError'>Password must be atleast 6 character long.</p>");
                    if (focus == '') {
                        focus = 'password';
                    }   
                    formError++;
                }else if (password.length > 25) {
                    $("#password").after("<p class='jsFormError'>Password exceeds maximum 25 character limit.</p>");
                    if (focus == '') {
                        focus = 'password';
                    }   
                    formError++;
                }
            }
            if (confirm_password != '') {
                if (confirm_password.length < 6 ) {
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
                }
            }            
            if (password != '' && password != confirm_password) {
                $("#confirm_password").after("<p class='jsFormError'>Confirm password do not match with password.</p>");
                if (focus == '') {
                    focus = 'confirm_password';
                }   
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
        
        if (profilePicture != '') {
            var allowd = 'jpg,jpeg,png,gif';
            var ext = allowd.split(",");            
            var fileext = profilePicture.split(".");            
            if(!inArray(fileext[fileext.length-1], ext)){
                $("#profilePicture").next('samll').after("<p class='jsFormError'>Please select "+allowd+" files.</p>");                   
                formError++;            
            }
        }
        
        
    }
    
    if (step == '2') {
        focus = '';
        formError = 0;
        var contactNumber = $("#contactNumber").val();
        var dob = $("#dob").val();
        //var address = $("#address").val();
        var city = $("#city").val();
        var state = $("#state").val();
        var country = $("#country").val();
        //var zipCode = $("#zipCode").val();
        //var snsName = $("#snsName").val();
        var heightFoot = $("#heightFoot").val();
        var heightInch = $("#heightInch").val();
        var hairColor = $("#hairColor").val();
        var eyeColor = $("#eyeColor").val();        
        var relationshipStatus = $("#relationshipStatus").val();
        var perfectFridayNight = $("#perfectFridayNight").val();
        var topQualities = $("#topQualities").val();
        var bodyType = $("#bodyType").val();
        var occupation = $("#occupation").val();
        var religion = $("#religion").val();
        var countryOfOrigin = $("#countryOfOrigin").val();
        var languages = $("#languages").val();
        var zodiacSign = $("#zodiacSign").val();
        var smokes = $("#smokes").val();
        var drinkAlcohol = $("#drinkAlcohol").val();
        var childrens = $("#childrens").val();
        //var interest = $("#interest").val();
        var ethnicity = $("#ethnicity").val();
        //var countryOfSignup = $("#countryOfSignup").val();
        var otherReligion = $("#otherReligion").val();
        var otherOccupation = $("#otherOccupation").val();
        var otherPerfectFriday = $("#otherPerfectFriday").val();
        var matchOtherPerfectFriday = $("#matchOtherPerfectFriday").val();
        var introduction = $("#introduction").val();
        
        if (contactNumber != '') {
            if(!phone_regx.test(contactNumber)){
                $("#contactNumber").after("<p class='jsFormError'>Please enter valid contact number.</p>");
                if (focus == '') {
                    focus = 'contactNumber';
                }   
                formError++;
            }
        }
        
        if (dob == '') {           
            $("#dob").after("<p class='jsFormError'>Please enter date of birth.</p>");
            if (focus == '') {
                focus = 'dob';
            }   
            formError++;            
        }else{
            var today = new Date();
            var birthDate = new Date(dob);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
              age--;
            }
            if (age < 18) {
                $("#dob").after("<p class='jsFormError'>Age must be above or equal to 18 years</p>");
                if (focus == '') {
                    focus = 'dob';
                }   
                formError++; 
            }
        }
        
        
        /*if (address == '') {
            $("#address").after("<p class='jsFormError'>Please enter your address.</p>");
            if (focus == '') {
                focus = 'address';
            }   
            formError++;
        }else if (address.length < 3 ) {
            $("#address").after("<p class='jsFormError'>Address must be atleast 3 character long.</p>");
            if (focus == '') {
                focus = 'address';
            }   
            formError++;
        }else if (address.length > 255) {
            $("#address").after("<p class='jsFormError'>Address exceeds maximum 255 character limit.</p>");
            if (focus == '') {
                focus = 'address';
            }   
            formError++;
        }*/
        
        if (country != 'New Zealand') {
            if (city == '') {
                $("#city").after("<p class='jsFormError'>Please enter city.</p>");
                if (focus == '') {
                    focus = 'city';
                }   
                formError++;
            }else if (city.length < 3 ) {
                $("#city").after("<p class='jsFormError'>City must be atleast 3 character long.</p>");
                if (focus == '') {
                    focus = 'city';
                }   
                formError++;
            }else if (city.length > 255) {
                $("#city").after("<p class='jsFormError'>City exceeds maximum 255 character limit.</p>");
                if (focus == '') {
                    focus = 'city';
                }   
                formError++;
            }
        }
        
        
        if (state == '') {
            $("#state").after("<p class='jsFormError'>Please enter state.</p>");
            if (focus == '') {
                focus = 'state';
            }   
            formError++;
        }else if (state.length < 3 ) {
            $("#state").after("<p class='jsFormError'>State must be atleast 3 character long.</p>");
            if (focus == '') {
                focus = 'state';
            }   
            formError++;
        }else if (state.length > 255) {
            $("#state").after("<p class='jsFormError'>State exceeds maximum 255 character limit.</p>");
            if (focus == '') {
                focus = 'state';
            }   
            formError++;
        }
        
        if (country == '') {
            $("#country").after("<p class='jsFormError'>Please select country.</p>");
            if (focus == '') {
                focus = 'country';
            }   
            formError++;
        }
        
        /*if (zipCode == '') {
            $("#zipCode").after("<p class='jsFormError'>Please enter member address zip code.</p>");
            if (focus == '') {
                focus = 'zipCode';
            }   
            formError++;
        }else if (zipCode.length < 5 ) {
            $("#zipCode").after("<p class='jsFormError'>Member address zip code must be atleast 5 character long.</p>");
            if (focus == '') {
                focus = 'zipCode';
            }   
            formError++;
        }else if (zipCode.length > 10) {
            $("#zipCode").after("<p class='jsFormError'>Member address zip code exceeds maximum 10 character limit.</p>");
            if (focus == '') {
                focus = 'zipCode';
            }   
            formError++;
        }*/
                
        
        /*if (snsName == '') {
            $("#snsName").after("<p class='jsFormError'>Please enter sweet or sour name.</p>");
            if (focus == '') {
                focus = 'snsName';
            }   
            formError++;
        }else if (snsName.length < 2 ) {
            $("#snsName").after("<p class='jsFormError'>Sweet or sour name must be atleast 2 character long.</p>");
            if (focus == '') {
                focus = 'snsName';
            }   
            formError++;
        }else if (snsName.length > 50) {
            $("#snsName").after("<p class='jsFormError'>Sweet or sour name exceeds maximum 50 character limit.</p>");
            if (focus == '') {
                focus = 'snsName';
            }   
            formError++;
        }*/
        
        
        if (heightFoot == '') {
            $("#heightFoot").after("<p class='jsFormError'>Please select height in foot.</p>");
            if (focus == '') {
                focus = 'heightFoot';
            }   
            formError++;
        }
        
        if (heightInch == '') {
            $("#heightInch").after("<p class='jsFormError'>Please select height in inch.</p>");
            if (focus == '') {
                focus = 'heightInch';
            }   
            formError++;
        }
        
        if (hairColor == '') {
            $("#hairColor").after("<p class='jsFormError'>Please select hair colour.</p>");
            if (focus == '') {
                focus = 'hairColor';
            }   
            formError++;
        }
        
        if (eyeColor == '') {
            $("#eyeColor").after("<p class='jsFormError'>Please select eye colour.</p>");
            if (focus == '') {
                focus = 'eyeColor';
            }   
            formError++;
        }
        
        if (relationshipStatus == '') {
            $("#relationshipStatus").after("<p class='jsFormError'>Please select relationship status.</p>");
            if (focus == '') {
                focus = 'relationshipStatus';
            }   
            formError++;
        }
        
        if (perfectFridayNight == '') {
            $("#perfectFridayNight").after("<p class='jsFormError'>Please select perfect friday night.</p>");
            if (focus == '') {
                focus = 'perfectFridayNight';
            }   
            formError++;
        }else{
            if (perfectFridayNight == 'Other') {
                if (otherPerfectFriday == '') {
                    $("#otherPerfectFriday").after("<p class='jsFormError'>Please enter other perfct friday night text.</p>");
                    if (focus == '') {
                        focus = 'otherPerfectFriday';
                    }   
                    formError++;
                }else if (otherPerfectFriday.length < 2 ) {
                    $("#otherPerfectFriday").after("<p class='jsFormError'>Other perfct friday night text must be atleast 2 character long.</p>");
                    if (focus == '') {
                        focus = 'otherPerfectFriday';
                    }   
                    formError++;
                }else if (otherPerfectFriday.length > 255) {
                    $("#otherPerfectFriday").after("<p class='jsFormError'>Other perfct friday night text exceeds maximum 255 character limit.</p>");
                    if (focus == '') {
                        focus = 'otherPerfectFriday';
                    }   
                    formError++;
                }                
            }            
        }
        
        
        if (topQualities == '' || topQualities == null) {
            $("#topQualities").after("<p class='jsFormError'>Please select top 5 qualities.</p>");
            if (focus == '') {
                focus = 'topQualities';
            }   
            formError++;
        }else{
            if ($("#topQualities option:selected[value!='']").length < '5') {
                $("#topQualities").after("<p class='jsFormError'>Please select a total of 5 qualities</p>");
                if (focus == '') {
                    focus = 'topQualities';
                }   
                formError++;
            }else if ($("#topQualities option:selected[value!='']").length > '5') {
                $("#topQualities").after("<p class='jsFormError'>Please select a total of 5 qualities</p>");
                if (focus == '') {
                    focus = 'topQualities';
                }   
                formError++;
            }
        }
        
        
        if (bodyType == '') {
            $("#bodyType").after("<p class='jsFormError'>Please select body type.</p>");
            if (focus == '') {
                focus = 'bodyType';
            }   
            formError++;
        }
        
        if (occupation == '') {
            $("#occupation").after("<p class='jsFormError'>Please select occupation.</p>");
            if (focus == '') {
                focus = 'occupation';
            }   
            formError++;
        }else{
            
            if (occupation == 'Other') {
                if (otherOccupation == '') {
                    $("#otherOccupation").after("<p class='jsFormError'>Please enter other occupation.</p>");
                    if (focus == '') {
                        focus = 'otherOccupation';
                    }   
                    formError++;
                }else if (otherOccupation.length < 2 ) {
                    $("#otherOccupation").after("<p class='jsFormError'>Other occupation must be atleast 2 character long.</p>");
                    if (focus == '') {
                        focus = 'otherOccupation';
                    }   
                    formError++;
                }else if (otherOccupation.length > 100) {
                    $("#otherOccupation").after("<p class='jsFormError'>Other occupation exceeds maximum 255 character limit.</p>");
                    if (focus == '') {
                        focus = 'otherOccupation';
                    }   
                    formError++;
                }
                
            }
        }
        
        if (religion == '') {
            $("#religion").after("<p class='jsFormError'>Please select religion.</p>");
            if (focus == '') {
                focus = 'religion';
            }   
            formError++;
        }else{
            if (religion == 'Other') {
                if (otherReligion == '') {
                    $("#otherReligion").after("<p class='jsFormError'>Please enter other religion.</p>");
                    if (focus == '') {
                        focus = 'otherReligion';
                    }   
                    formError++;
                }else if (otherReligion.length < 2 ) {
                    $("#otherReligion").after("<p class='jsFormError'>Other religion must be atleast 2 character long.</p>");
                    if (focus == '') {
                        focus = 'otherReligion';
                    }   
                    formError++;
                }else if (otherReligion.length > 100) {
                    $("#otherReligion").after("<p class='jsFormError'>Other religion exceeds maximum 255 character limit.</p>");
                    if (focus == '') {
                        focus = 'otherReligion';
                    }   
                    formError++;
                }
                
            }
        }
        
        
        
        if (countryOfOrigin == '') {
            $("#countryOfOrigin").after("<p class='jsFormError'>Please enter country of origin.</p>");
            if (focus == '') {
                focus = 'countryOfOrigin';
            }   
            formError++;
        }else if (countryOfOrigin.length < 2 ) {
            $("#countryOfOrigin").after("<p class='jsFormError'>Country of origin must be atleast 2 character long.</p>");
            if (focus == '') {
                focus = 'countryOfOrigin';
            }   
            formError++;
        }else if (countryOfOrigin.length > 255) {
            $("#countryOfOrigin").after("<p class='jsFormError'>Country of origin exceeds maximum 255 character limit.</p>");
            if (focus == '') {
                focus = 'countryOfOrigin';
            }   
            formError++;
        }
        
        if (languages == '') {
            $("#languages").after("<p class='jsFormError'>Please enter languages you speak.</p>");
            if (focus == '') {
                focus = 'languages';
            }   
            formError++;
        }else if (languages.length < 2 ) {
            $("#languages").after("<p class='jsFormError'>Languages you speak must be atleast 2 character long.</p>");
            if (focus == '') {
                focus = 'languages';
            }   
            formError++;
        }else if (languages.length > 255) {
            $("#languages").after("<p class='jsFormError'>Languages you speak exceeds maximum 255 character limit.</p>");
            if (focus == '') {
                focus = 'languages';
            }   
            formError++;
        }
        
        
        if (zodiacSign == '') {
            $("#zodiacSign").after("<p class='jsFormError'>Please enter zodiac sign.</p>");
            if (focus == '') {
                focus = 'zodiacSign';
            }   
            formError++;
        }else if (zodiacSign.length < 2 ) {
            $("#zodiacSign").after("<p class='jsFormError'>Zodiac sign must be atleast 2 character long.</p>");
            if (focus == '') {
                focus = 'zodiacSign';
            }   
            formError++;
        }else if (zodiacSign.length > 255) {
            $("#zodiacSign").after("<p class='jsFormError'>Zodiac sign exceeds maximum 255 character limit.</p>");
            if (focus == '') {
                focus = 'zodiacSign';
            }   
            formError++;
        }
        
        
        if (smokes == '') {
            $("#smokes").after("<p class='jsFormError'>Please select smoking status.</p>");
            if (focus == '') {
                focus = 'smokes';
            }   
            formError++;
        }
        
        if (drinkAlcohol == '') {
            $("#drinkAlcohol").after("<p class='jsFormError'>Please select drinking status.</p>");
            if (focus == '') {
                focus = 'drinkAlcohol';
            }   
            formError++;
        }
        
        if (childrens == '') {
            $("#childrens").after("<p class='jsFormError'>Please select do you have children.</p>");
            if (focus == '') {
                focus = 'childrens';
            }   
            formError++;
        }
        
        
        
        /*if (interest == '') {
            $("#interest").after("<p class='jsFormError'>Please select interest.</p>");
            if (focus == '') {
                focus = 'interest';
            }   
            formError++;
        }*/
        
        /*if (countryOfSignup == '') {
            $("#countryOfSignup").after("<p class='jsFormError'>Please select country of signup.</p>");
            if (focus == '') {
                focus = 'countryOfSignup';
            }   
            formError++;
        }*/
        
        if (ethnicity == '') {
            $("#ethnicity").after("<p class='jsFormError'>Please select ethnicity.</p>");
            if (focus == '') {
                focus = 'ethnicity';
            }   
            formError++;
        }
        
        if (introduction == '') {
            //$("#fullName").closest("div").next('a').parent("div").next("a").next("div").after("<p class='error'>Please enter your full name.</p>");
            $("#introduction").after("<p class='jsFormError'>Please enter your introduction.</p>");
            if (focus == '') {
                focus = 'introduction';
            }   
            formError++;
        }else if (introduction.length < 2 ) {
            $("#introduction").after("<p class='jsFormError'>Introduction must be atleast 2 characters long.</p>");
            if (focus == '') {
                focus = 'introduction';
            }   
            formError++;
        }
        
    }
    
    
    if (step == '3') {
        focus = '';
        formError = 0;
        var instagramLink = $("#instagramLink").val();
        var twitterLink = $("#twitterLink").val();
        var youtubeLink = $("#youtubeLink").val();
        var pinterestLink = $("#pinterestLink").val();
        var facebookLink = $("#facebookLink").val();
        var linkedinLink = $("#linkedinLink").val();
        if (instagramLink != '') {
            if(!url_regx.test(instagramLink)){
                $("#instagramLink").after("<p class='jsFormError'>Please enter a valid link.</p>");
                if (focus == '') {
                    focus = 'instagramLink';
                }   
                formError++;
            }
        }
        if (twitterLink != '') {
            if(!url_regx.test(twitterLink)){
                $("#twitterLink").after("<p class='jsFormError'>Please enter a valid link.</p>");
                if (focus == '') {
                    focus = 'twitterLink';
                }   
                formError++;
            }
        }
        if (youtubeLink != '') {
            if(!url_regx.test(youtubeLink)){
                $("#youtubeLink").after("<p class='jsFormError'>Please enter a valid link.</p>");
                if (focus == '') {
                    focus = 'youtubeLink';
                }   
                formError++;
            }
        }
        
        if (pinterestLink != '') {
            if(!url_regx.test(pinterestLink)){
                $("#pinterestLink").after("<p class='jsFormError'>Please enter a valid link.</p>");
                if (focus == '') {
                    focus = 'pinterestLink';
                }   
                formError++;
            }
        }
        
        if (facebookLink != '') {
            if(!url_regx.test(facebookLink)){
                $("#facebookLink").after("<p class='jsFormError'>Please enter a valid link.</p>");
                if (focus == '') {
                    focus = 'facebookLink';
                }   
                formError++;
            }
        }
        
        if (linkedinLink != '') {
            if(!url_regx.test(linkedinLink)){
                $("#linkedinLink").after("<p class='jsFormError'>Please enter a valid link.</p>");
                if (focus == '') {
                    focus = 'linkedinLink';
                }   
                formError++;
            }
        }
        
    }
    
    
    if (step == '4') {
        focus = '';
        formError = 0;
        
        var personalityTraits = $("#personalityTraits").val();
        var attribute = $("#attribute").val();
        var groupDate = $("#groupDate").val();
        var matchPerfectFridayNight = $("#matchPerfectFridayNight").val();
        var matchType = $("#matchType").val();
        var matchRelationship = $("#matchRelationship").val();
        var matchAge = $("#matchAge").val();
        var matchHeightFoot = $("#matchHeightFoot").val();
        var matchHeightInch = $("#matchHeightInch").val();
        var matchBodyType = $("#matchBodyType").val();
        var matchEyeColor = $("#matchEyeColor").val();
        var matchHairColor = $("#matchHairColor").val();
        var matchSmokes = $("#matchSmokes").val();
        var matchDrinks = $("#matchDrinks").val();        
        var matchPerfectMatch = $("#matchPerfectMatch").val();
        var matchDealBreaker = $("#matchDealBreaker").val();
        var matchOtherPerfectFriday = $("#matchOtherPerfectFriday").val();
        var matchOtherType = $("#matchOtherType").val();
        
        if (personalityTraits == '' || personalityTraits == null) {
            $("#personalityTraits").after("<p class='jsFormError'>Please select personality traits.</p>");
            if (focus == '') {
                focus = 'personalityTraits';
            }   
            formError++;
        }else{
            if ($("#personalityTraits option:selected[value!='']").length < '7') {
                $("#personalityTraits").after("<p class='jsFormError'>Please select atleast 7 personality traits.</p>");
                if (focus == '') {
                    focus = 'personalityTraits';
                }   
                formError++;
            }else if ($("#personalityTraits option:selected[value!='']").length > '7') {
                $("#personalityTraits").after("<p class='jsFormError'>Maximum 7 personality traits allowed.</p>");
                if (focus == '') {
                    focus = 'personalityTraits';
                }   
                formError++;
            }
        }
        
        if (attribute == '') {
            $("#attribute").after("<p class='jsFormError'>Please select attribute you value most.</p>");
            if (focus == '') {
                focus = 'attribute';
            }   
            formError++;
        }
        
        if (groupDate == '') {
            $("#groupDate").after("<p class='jsFormError'>Please select would you go on a group date.</p>");
            if (focus == '') {
                focus = 'groupDate';
            }   
            formError++;
        }
        
        if (groupDate == '') {
            $("#groupDate").after("<p class='jsFormError'>Please select would you go on a group date.</p>");
            if (focus == '') {
                focus = 'groupDate';
            }   
            formError++;
        }
        
        if (matchPerfectFridayNight == '') {
            $("#matchPerfectFridayNight").after("<p class='jsFormError'>Please select perfect friday night.</p>");
            if (focus == '') {
                focus = 'matchPerfectFridayNight';
            }   
            formError++;
        }else{
            if (matchPerfectFridayNight == 'Other') {
                if (matchOtherPerfectFriday == '') {
                    $("#matchOtherPerfectFriday").after("<p class='jsFormError'>Please enter other perfect friday night text.</p>");
                    if (focus == '') {
                        focus = 'matchOtherPerfectFriday';
                    }   
                    formError++;
                }else if (matchOtherPerfectFriday.length < 2 ) {
                    $("#matchOtherPerfectFriday").after("<p class='jsFormError'>Other perfect friday night text must be atleast 2 character long.</p>");
                    if (focus == '') {
                        focus = 'matchOtherPerfectFriday';
                    }   
                    formError++;
                }else if (matchOtherPerfectFriday.length > 255) {
                    $("#matchOtherPerfectFriday").after("<p class='jsFormError'>Other perfect friday night text exceeds maximum 255 character limit.</p>");
                    if (focus == '') {
                        focus = 'matchOtherPerfectFriday';
                    }   
                    formError++;
                }
                
            }
        }
        
        if (matchType == '') {
            $("#matchType").after("<p class='jsFormError'>Please select your type.</p>");
            if (focus == '') {
                focus = 'matchType';
            }   
            formError++;
        }else{
            if (matchType == 'Other') {
                if (matchOtherType == '') {
                    $("#matchOtherType").after("<p class='jsFormError'>Please enter other match type.</p>");
                    if (focus == '') {
                        focus = 'matchOtherType';
                    }   
                    formError++;
                }else if (matchOtherType.length < 2 ) {
                    $("#matchOtherType").after("<p class='jsFormError'>Other match type text must be atleast 2 character long.</p>");
                    if (focus == '') {
                        focus = 'matchOtherType';
                    }   
                    formError++;
                }else if (matchOtherType.length > 255) {
                    $("#matchOtherType").after("<p class='jsFormError'>Other match type text exceeds maximum 255 character limit.</p>");
                    if (focus == '') {
                        focus = 'matchOtherType';
                    }   
                    formError++;
                }
                
            }
        }
        
        if (matchRelationship == '') {
            $("#matchRelationship").after("<p class='jsFormError'>Please select relationship preference.</p>");
            if (focus == '') {
                focus = 'matchRelationship';
            }   
            formError++;
        }
        
        if (matchAge == '') {
            $("#matchAge").after("<p class='jsFormError'>Please select age.</p>");
            if (focus == '') {
                focus = 'matchAge';
            }   
            formError++;
        }
        
        if (matchHeightFoot == '') {
            $("#matchHeightFoot").after("<p class='jsFormError'>Please select height in foot.</p>");
            if (focus == '') {
                focus = 'matchHeightFoot';
            }   
            formError++;
        }
        
        if (matchHeightInch == '') {
            $("#matchHeightInch").after("<p class='jsFormError'>Please select height in inch.</p>");
            if (focus == '') {
                focus = 'matchHeightInch';
            }   
            formError++;
        }
        
        if (matchBodyType == '') {
            $("#matchBodyType").after("<p class='jsFormError'>Please select body type.</p>");
            if (focus == '') {
                focus = 'matchBodyType';
            }   
            formError++;
        }
        if (matchEyeColor == '') {
            $("#matchEyeColor").after("<p class='jsFormError'>Please select hair colour.</p>");
            if (focus == '') {
                focus = 'matchEyeColor';
            }   
            formError++;
        }
        
        if (matchHairColor == '') {
            $("#matchHairColor").after("<p class='jsFormError'>Please select eye colour.</p>");
            if (focus == '') {
                focus = 'matchHairColor';
            }   
            formError++;
        }
        if (matchSmokes == '') {
            $("#matchSmokes").after("<p class='jsFormError'>Please select smoking.</p>");
            if (focus == '') {
                focus = 'matchSmokes';
            }   
            formError++;
        }
        if (matchDrinks == '') {
            $("#matchDrinks").after("<p class='jsFormError'>Please select drink alcohol.</p>");
            if (focus == '') {
                focus = 'matchDrinks';
            }   
            formError++;
        }
        
        
        if (matchPerfectMatch == '') {
            $("#matchPerfectMatch").after("<p class='jsFormError'>Please enter perfect match.</p>");
            if (focus == '') {
                focus = 'matchPerfectMatch';
            }   
            formError++;
        }else if (matchPerfectMatch.length < 2 ) {
            $("#matchPerfectMatch").after("<p class='jsFormError'>Perfect match must be atleast 2 character long.</p>");
            if (focus == '') {
                focus = 'matchPerfectMatch';
            }   
            formError++;
        }else if (matchPerfectMatch.length > 255) {
            $("#matchPerfectMatch").after("<p class='jsFormError'>Perfect match exceeds maximum 255 character limit.</p>");
            if (focus == '') {
                focus = 'matchPerfectMatch';
            }   
            formError++;
        }
        
        if (matchDealBreaker == '') {
            $("#matchDealBreaker").after("<p class='jsFormError'>Please enter deal breaker.</p>");
            if (focus == '') {
                focus = 'matchDealBreaker';
            }   
            formError++;
        }else if (matchDealBreaker.length < 2 ) {
            $("#matchDealBreaker").after("<p class='jsFormError'>Deal breaker must be atleast 2 character long.</p>");
            if (focus == '') {
                focus = 'matchDealBreaker';
            }   
            formError++;
        }else if (matchDealBreaker.length > 255) {
            $("#matchDealBreaker").after("<p class='jsFormError'>Deal breaker exceeds maximum 255 character limit.</p>");
            if (focus == '') {
                focus = 'matchDealBreaker';
            }   
            formError++;
        }
        
    }
    
    
    
    
    
    
    if (formError == '0') {
       if (step == '1') {
            $("#login-credentials").hide();
            $("#about-me").show();
            $("#previous").show();
            step = '2';
       }
       else if (step == '2') {
            $("#login-credentials").hide();
            $("#about-me").hide();
            $("#previous").show();
            $("#social-links").show();
            step = '3';
       }
       else if (step == '3') {
            $("#login-credentials").hide();
            $("#about-me").hide();
            $("#social-links").hide();
            $("#previous").show();
            $("#perfect-match").show();
            $("#next").html('Save');
            step = '4';
       }
       else if (step == '4') {
           $("#memberRegistrationForm").submit();
       }
    }else{
        $("#"+focus).focus();
        return false;
    }
    return false;
});


$("#previous").on("click", function(){
    if (step == '2') {
        $("#login-credentials").show();
        $("#about-me").hide();
        $("#previous").hide();
        $("#perfect-match").hide();
        $("#social-links").hide();
        step = '1';
    }
    
    else if (step == '3') {
        $("#login-credentials").hide();
        $("#social-links").hide();
        $("#perfect-match").hide();
        $("#about-me").show();
        $("#previous").show();
        step = '2';
    }
    
    else if (step == '4') {
        $("#login-credentials").hide();
        $("#social-links").hide();
        $("#perfect-match").hide();
        $("#about-me").show();
        $("#previous").show();
        $("#next").html('Next');
        step = '3';
    }
    
    
});

function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}



$("document").ready(function(){
    $("#religion").on("change", function(){
        if ($(this).val() == 'Other') {
            $("#otherReligion").show();
            $("#otherReligion").focus();
        }else{
            $("#otherReligion").val('');
            $("#otherReligion").hide();   
        }
    });
    
    if ($("#religion").val() == 'Other') {
        $("#otherReligion").show();
    }
    
    
    $("#occupation").on("change", function(){
        if ($(this).val() == 'Other') {
            $("#otherOccupation").show();
            $("#otherOccupation").focus();
        }else{
            $("#otherOccupation").val('');
            $("#otherOccupation").hide();   
        }
    });
    
    if ($("#occupation").val() == 'Other') {
        $("#otherOccupation").show();
        $("#otherOccupation").focus();
    }
    
    $("#perfectFridayNight").on("change", function(){
        if ($(this).val() == 'Other') {
            $("#otherPerfectFriday").show();
            $("#otherPerfectFriday").focus();
        }else{
            $("#otherPerfectFriday").val('');
            $("#otherPerfectFriday").hide();   
        }
    });
    
    if ($("#perfectFridayNight").val() == 'Other') {
        $("#otherPerfectFriday").show();
        $("#otherPerfectFriday").focus();
    }
    
    $("#matchPerfectFridayNight").on("change", function(){
        if ($(this).val() == 'Other') {
            $("#matchOtherPerfectFriday").show();
            $("#matchOtherPerfectFriday").focus();
        }else{
            $("#matchOtherPerfectFriday").val('');
            $("#matchOtherPerfectFriday").hide();   
        }
    });
    
    
    if ($("#matchPerfectFridayNight").val() == 'Other') {
        $("#matchOtherPerfectFriday").show();
        $("#matchOtherPerfectFriday").focus();
    }
    
    $("#matchType").on("change", function(){
        if ($(this).val() == 'Other') {
            $("#matchOtherType").show();
            $("#matchOtherType").focus();
        }else{
            $("#matchOtherType").val('');
            $("#matchOtherType").hide();   
        }
    });
    
    
    if ($("#matchType").val() == 'Other') {
        $("#matchOtherType").show();
        $("#matchOtherType").focus();
    }
    
});

