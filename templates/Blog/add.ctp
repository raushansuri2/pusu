            <?php echo $this->Flash->render(); ?>
            <?php echo $this->Form->create($freestaffs, ['type' =>'file', 'novalidate'=>'novalidate']);?>
            <div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25">
            <div class="row mrg-r-10 mrg-l-10">
                        <div class="col-sm-6">
                        <label>Select Category</label>
                        <?php echo $this->Form->input('categoryId', ['options' => $parentClientList, 'empty' => 'Select Category', 'class' => 'form-control chosen-select', 'data-placeholder' => 'Choose One', 'style' => 'width:100%', 'id' => 'category', 'label' => false,'div' => false]);?>
                        </div>
                        
                        <div class="col-sm-6">
                        <label>Post Title</label>
                        <?php echo $this->Form->input('postTitle', ['class' => 'form-control','type'=>'text', 'required'=>false,'placeholder' => '', 'div' => false, 'label' => false, 'maxlength'=>200]); ?>
                        </div>
                        <div class="col-sm-6">
                        <label>Image-1</label>
                        <?php echo $this->Form->input('image_1', ['class' => '','type'=>'file', 'required'=>false,'placeholder' => '', 'div' => false, 'label' => false]); ?>
                        </div>
                        <div class="col-sm-6">
                        <label>Image-2</label>
                        <?php echo $this->Form->input('image_2', ['class' => '','type'=>'file', 'required'=>false,'placeholder' => '', 'div' => false, 'label' => false]); ?>
                        </div>
                        <div class="col-sm-6">
                        <label>Image-3</label>
                        <?php echo $this->Form->input('image_3', ['class' => '','type'=>'file', 'required'=>false,'placeholder' => '', 'div' => false, 'label' => false]); ?>
                        </div>
                        <div class="col-sm-6">
                        <label>Image-4</label>
                        <?php echo $this->Form->input('image_4', ['class' => '','type'=>'file', 'required'=>false,'placeholder' => '', 'div' => false, 'label' => false]); ?>
                        </div>
                        <div class="col-sm-6">
                        <label>Image-5</label>
                        <?php echo $this->Form->input('image_5', ['class' => '','type'=>'file', 'required'=>false,'placeholder' => '', 'div' => false, 'label' => false]); ?>
                        </div>
                        <div class="col-sm-6">
                        <label>Video</label>
                        <?php echo $this->Form->input('video', ['class' => '','type'=>'file', 'required'=>false,'placeholder' => '', 'div' => false, 'label' => false]); ?>
                        </div>
                        <div class="col-sm-12" style="margin-top:30px;">
                        <label>Video Link</label>
                        <input type="text" name="videolink" class="form-control"  placeholder="Enter video link" maxlength="200" id="posttitldsf">
                        </div>
                        
                        <div class="col-sm-12">
                        <label>Description</label>
                        <?php echo $this->Form->input('description', ['class' => 'form-control','type'=>'textarea', 'required'=>false,'placeholder' => '', 'div' => false, 'label' => false]); ?>
                        </div>
                        
              
              
              
            </div>
         
        </div>

       <div class="col-sm-12">
            <div class="text-center">
            <input class="btn theme-btn" type="submit" value="Submit Now">
            </div>
       </div>
       <?php echo $this->Form->end();?>
       <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>-->
       <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>-->
       <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
      <script>
            jQuery(document).ready(function(){
            $("#category").on('change', function(){
	var country_id = $(this).val();
            $("#newsAjaxLoader").css("display", "block");
                    $.ajax({
                        url : "<?php echo $this->Url->build(['controller' => 'products', 'action' => 'subcategory']);?>/"+country_id,
                        success: function(response) {
                                    var obj = jQuery.parseJSON(response);
                                    //$('#subcategory22').find('div').find('ul').find('li').first().remove().next().remove();
                                    $('#subcategory22').next('div').find('ul').html(obj.first);
                                    $('select#subcategory22').html(obj.second);
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
        });
            });
      </script>