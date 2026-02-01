<!-- Display flash messages -->
<?php echo $this->Flash->render(); ?>

<!-- Create form -->
<?php echo $this->Form->create($product, ['type' => 'file', 'novalidate' => 'novalidate']); ?>

<!-- General information section -->
<div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25">
    <div class="row mrg-r-10 mrg-l-10">
        <!-- Category and subcategory fields -->
        <div class="col-sm-6">
            <label>Select Category <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('categoryId', [
                'options' => $categories,
                'empty' => 'Select Category',
                'class' => 'form-control chosen-select',
                'data-placeholder' => 'Choose One',
                'style' => 'width:100%',
                'id' => 'category',
                'label' => false,
                'required' => true
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Select Sub Category <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('subCategory', [
                'options' => $subcategory,
                'empty' => 'Select Sub Category',
                'class' => 'form-control chosen-select',
                'data-placeholder' => 'Choose One',
                'style' => 'width:100%',
                'id' => 'subcategory22',
                'label' => false,
                'required' => true
            ]); ?>
        </div>

        <!-- Product name and SKU fields -->
        <div class="col-sm-6">
            <label>Product Name <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('productName', [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => '',
                'label' => false,
                'maxlength' => 200
            ]); ?>
        </div>
        
        <!-- Description field -->
        <div class="col-sm-6">
            <label>Description <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('description', [
                'class' => 'form-control',
                'type' => 'textarea',
                'required' => false,
                'placeholder' => '',
                'label' => false,
                'rows' => 5,
                'style' => 'resize:none'
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>SKU (Stock Keeping Unit) <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('SKU', [
                'class' => 'form-control',
                'required' => true,
                'placeholder' => '',
                'label' => false,
                'maxlength' => 200
            ]); ?>
        </div>

        <!-- Quantity, unit price, and special price fields -->
        <div class="col-sm-6">
            <label>Quantity <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('quantity', [
                'class' => 'form-control',
                'min' => '1',
                'type' => 'number',
                'required' => true,
                'placeholder' => '',
                'label' => false,
                'maxlength' => 200
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Unit Price <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('unitPrice', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => '0',
                'required' => true,
                'placeholder' => '',
                'label' => false,
                'maxlength' => 200
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Special Price</label>
            <?php echo $this->Form->control('specialPrice', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => '0',
                'placeholder' => 'Special Price less or same as Price',
                'label' => false,
                'maxlength' => 200
            ]); ?>
        </div>

        <!-- Image field -->
        <div class="col-sm-6">
            <label>Product Image <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('image', [
                'type' => 'file',
                'required' => true,
                'label' => false,
                'maxlength' => 100
            ]); ?>
            <!-- Image preview -->
            <?php if (!empty($product->image)): ?>
                <img id="image-preview" src="<?php echo $this->Url->build('/img/uploads/products/' . $product->image); ?>" alt="Image preview" style="max-width: 100px; max-height: 100px;">
            <?php else: ?>
                <img id="image-preview" src="#" alt="Image preview" style="display: none; max-width: 100px; max-height: 100px;">
            <?php endif; ?>
        </div>

        <!-- Weight and dimensions fields -->
        <div class="col-sm-6">
            <label>Weight (lbs) <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('weight', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => 1,
                'step' => 1,
                'required' => true,
                'placeholder' => 'Enter weight in pounds',
                'label' => false,
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Length (inches) <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('length', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => '1',
                'step' => 1,
                'required' => true,
                'placeholder' => 'Enter length in inches',
                'label' => false,
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Width (inches) <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('width', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => '1',
                'step' => 1,
                'required' => true,
                'placeholder' => 'Enter width in inches',
                'label' => false,
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Height (inches) <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('height', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => '1',
                'step' => 1,
                'required' => true,
                'placeholder' => 'Enter height in inches',
                'label' => false,
            ]); ?>
        </div>
    </div>
</div>

<!-- Form submit button -->
<div class="col-sm-12">
    <div class="text-center">
        <input class="btn theme-btn" type="submit" value="Update">
    </div>
</div>
<!-- End form -->
<?php echo $this->Form->end(); ?>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script>
    jQuery(document).ready(function(){
        $("#category").on('change', function(){
            var categoryId = $(this).val();
            $("#newsAjaxLoader").css("display", "block");
            $.ajax({
                url : "<?php echo $this->Url->build(['controller' => 'products', 'action' => 'subcategory']);?>/" + categoryId,
                success: function(response) {
                    var obj = jQuery.parseJSON(response);
                    $('#subcategory22').next('div').find('ul').html(obj.first);
                    $('select#subcategory22').html(obj.second);
                    $("#newsAjaxLoader").css("display", "none");
                }
            });
        });
    });

    // Image preview functionality
    $(document).ready(function(){
        $('#image').on('change', function(){
            var file = this.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
                $('#image-preview').show ();
            };
            reader.readAsDataURL(file);
        });
    });
</script>