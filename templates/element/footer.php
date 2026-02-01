<footer class="footer dark-footer dark-bg">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="footer-widget">
                    <h3 class="widgettitle widget-title">About RiteVet</h3>
                    <p>Download the app and find best veterinarians and other pet services instantly</p>
                    <a href="#" class="other-store-link">
                        <img src="<?php echo $this->Url->build('/');?>assets/img/app-download.png">
                    </a>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="footer-widget">
                    <h3 class="widgettitle widget-title">Popular Services</h3>
                    <ul class="footer-navigation sinlge">
                        <li>
                            <a href="">
                                Veterinarian Register Here
                            </a>
                        </li>
                        <li>
                            <a href="">
                                Pet Parent Register Here
                            </a>
                        </li>
                        <li>
                            <a href="">
                                Other Pet Services Providers
                            </a>
                        </li>
                        <li>
                            <a href="">
                                Request Service
                            </a>
                        </li>
                        <li><a href="">Pet Store</a></li>
                        <li><a href="">Free Stuff</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="footer-widget">
                    <div class="textwidget">
                        <h3 class="widgettitle widget-title">Get In Touch</h3>
                        <div class="address-box">
                            <div class="sing-add">
                                <i class="ti-location-pin"></i><?php echo $globalparameters->address;?>
                            </div>
                            <div class="sing-add">
                                <i class="ti-email"></i><?php echo $globalparameters->emailAddress;?>
                            </div>
                            <div class="sing-add">
                                <i class="ti-mobile"></i><?php echo $globalparameters->phoneNo;?>
                            </div>
                            <div class="sing-add">
                                <i class="ti-world"></i>www.ritevet.com
                            </div>
                        </div>
                        <ul class="footer-social">
                            <li><a href="<?php echo $globalparameters->facebook;?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="<?php echo $globalparameters->skype;?>" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                            <li><a href="<?php echo $globalparameters->twitter;?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                            <li><a href="<?php echo $globalparameters->instagram;?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                            <li><a href="<?php echo $globalparameters->pinterest;?>" target="_blank"><i class="fa fa-pinterest"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="footer-widget">
                    <h3 class="widgettitle widget-title">Subscribe to our newsletter</h3>
                    <p>Subscribe our newsletter to latest news and update from RiteVet.</p>

                    <form class="sup-form">
                        <input type="text" id="SUBSCRIBEEMAIL" class="form-control sigmup-me" placeholder="Your Email Address" required>
                        <button type="button" id="SUBSCRIBEbutton" class="btn" value="Get Started"><i class="fa fa-location-arrow"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <p>Copyright@ <?php echo date('Y');?> RiteVet, All Rights Reserved. <a href="<?php echo $this->Url->build('/');?>privacy-policy.html" target="_blank" style="color:#000;">Privacy Policy</a>  |  <a target="_blank" href="<?php echo $this->Url->build('/');?>terms.html" style="color:#000;">Terms & Conditions</a></p>
    </div>
</footer>

<!-- ================ End Footer Section ======================= -->

<style>
    .serviceclass::-webkit-input-placeholder {
        color: #ff0000 !important;
    }
    .serviceclass2::-webkit-input-placeholder {
        color: #00FF55 !important;
    }
</style>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>-->
<script>
    jQuery(document).ready(function(){
        $("#SUBSCRIBEbutton").on('click', function(){
            var SUBID = $("#SUBSCRIBEEMAIL").val();
            //alert(SUBID);
            var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            if (!$("#SUBSCRIBEEMAIL").val().match(pattern)) {
                $("#SUBSCRIBEEMAIL").val('');
                $("#SUBSCRIBEEMAIL").addClass('serviceclass');
                $("#SUBSCRIBEEMAIL").attr("placeholder", 'Invaild Email');
                return false;
            }else{
                $("#newsAjaxLoader").css("display", "block");
                $.ajax({
                    url : "<?php echo $this->Url->build(['controller' => 'Pages', 'action' => 'subscriber']);?>/"+SUBID,
                    success: function(response) {
                        var obj = jQuery.parseJSON(response);
                        //alert(obj.message);
                        if(obj.type == 'ERROR'){
                            $("#SUBSCRIBEEMAIL").addClass('serviceclass');
                        }else{
                            $("#SUBSCRIBEEMAIL").addClass('serviceclass2');
                        }
                        $("#SUBSCRIBEEMAIL").val('');
                        $("#SUBSCRIBEEMAIL").attr("placeholder", obj.message);
                        $("#newsAjaxLoader").css("display", "none");
                        //alert(response);
                        //jQuery("#department_name").html(response);
                        //var obj = jQuery.parseJSON(response);
                        //alert(JSON.stringify(response));
                        //alert( obj.contactperson);
                        //$('select#subcategory').html(response);
                        //$('#subcategory').html(response);
                        return false;
                    }
                });
            }
        });
    });
</script>

<div id="newsAjaxLoader" style="width: 100%; height: 100%; position: fixed; z-index: 10000000; top: 0px; left: 0px; right: 0px; bottom: 0px; margin: auto; display: none;">
    <div style="width: 250px; height: 75px; text-align: center; position: fixed; top: 0px; left: 0px; right: 0px; bottom: 0px; margin: auto; font-size: 16px; z-index: 10; color: rgb(255, 255, 255);">
        <img src="<?php echo $this->Url->build('/');?>img/admin/fancybox_loading@2x.gif">
    </div>
    <div class="bg" style="background: rgb(0, 0, 0) none repeat scroll 0% 0%; opacity: 0.7; width: 100%; height: 100%; position: absolute; top: 0px;"></div>
</div>