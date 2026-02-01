<?php echo $this->Flash->render(); ?>
<?php echo $this->Form->create($product, ['type' => 'file', 'novalidate' => 'novalidate']); ?>
<div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25">
    <div class="row mrg-r-10 mrg-l-10">
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
                'div' => false,
                'placeholder' => 'Choose a category' // Added placeholder
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Select Sub Category <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('subCategory', [
                'options' => [],
                'empty' => 'Select Sub Category',
                'class' => 'form-control chosen-select',
                'data-placeholder' => 'Choose One',
                'style' => 'width:100%',
                'id' => 'subcategory22',
                'label' => false,
                'div' => false,
                'placeholder' => 'Choose a sub category'
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Product Name <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('productName', [
                'class' => 'form-control',
                'placeholder' => 'Enter product name',
                'div' => false,
                'label' => false,
                'maxlength' => 100
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Description <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('description', [
                'class' => 'form-control',
                'placeholder' => 'Enter a description',
                'div' => false,
                'label' => false,
                'rows' => 5,
                'style' => 'resize:none' // disable resizing
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>SKU (Stock Keeping Unit) <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('SKU', [
                'class' => 'form-control',
                'placeholder' => 'Enter a SKU',
                'div' => false,
                'label' => false,
                'maxlength' => 50
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Quantity <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('quantity', [
                'class' => 'form-control',
                'min' => '1',
                'step' => '1',
                'type' => 'number',
                'placeholder' => 'Enter a quantity',
                'div' => false,
                'label' => false,
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Unit Price <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('unitPrice', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => '0',
                'placeholder' => 'Enter a unit price',
                'div' => false,
                'label' => false,
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Special Price</label>
            <?php echo $this->Form->control('specialPrice', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => '0',
                'placeholder' => 'Special Price less or same as Price',
                'div' => false,
                'label' => false,
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Weight (lbs) <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('weight', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => '1',
                'step' => '1',
                'placeholder' => 'Enter weight in pounds',
                'div' => false,
                'label' => false,
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Length (inches) <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('length', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => '1',
                'step' => '1',
                'placeholder' => 'Enter length in inches',
                'div' => false,
                'label' => false,
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Width (inches) <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('width', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => '1',
                'step' => '1',
                'placeholder' => 'Enter width in inches',
                'div' => false,
                'label' => false,
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Height (inches) <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('height', [
                'class' => 'form-control',
                'type' => 'number',
                'min' => '1',
                'step' => '1',
                'placeholder' => 'Enter height in inches',
                'div' => false,
                'label' => false,
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Product Image <span class="mandatory">*</span></label>
            <?php echo $this->Form->control('image', [
                'type' => 'file',
                'div' => false,
                'label' => false,
            ]); ?>
            <!-- Image preview -->
            <img id="image-preview" src="#" alt="Image preview" style="display: none; max-width: 100px; max-height: 100px;">
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="text-center">
        <input class="btn theme-btn" type="submit" value="Add">
    </div>
</div>
<?php echo $this->Form->end(); ?>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script>
    $(document).ready(function(){
        $("#category").on('change', function() {
            var categoryId = $(this).val();
            $("#newsAjaxLoader").css("display", "block");
            $.ajax({
                url: "<?= $this->Url->build(['controller' => 'Products', 'action' => 'subcategory']) ?>/" + categoryId,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#subcategory22').next('div').find('ul').html(response.first);
                    $('select#subcategory22').html(response.second);
                    $("#newsAjaxLoader").css("display", "none");
                },
                error: function() {
                    $("#newsAjaxLoader").css("display", "none");
                    alert('Error loading subcategories');
                }
            });
        });
    });
</script>
<script>
    const form = document.querySelector('form');
    
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const categoryId = document.getElementById('category').value;
        const subCategory = document.getElementById('subcategory22').value;
        const productName = document.querySelector('input[name="productName"]').value;
        const description = document.querySelector('textarea[name="description"]').value;
        const SKU = document.querySelector('input[name="SKU"]').value;
        const quantity = document.querySelector('input[name="quantity"]').value;
        const price = document.querySelector('input[name="unitPrice"]').value;
        const specialPrice = document.querySelector('input[name="specialPrice"]').value;
        const image = document.getElementById('image');
        const weight = document.querySelector('input[name="weight"]').value;
        const length = document.querySelector('input[name="length"]').value;
        const width = document.querySelector('input[name="width"]').value;
        const height = document.querySelector('input[name="height"]').value;

        if (categoryId === '') {
            alert('Please select a category');
            return;
        }

        if (subCategory === '') {
            alert('Please select a sub category');
            return;
        }

        if (productName === '') {
            alert('Please enter a product name');
            return;
        }

        if (description === '') {
            alert('Please enter a description');
            return;
        }

        if (SKU === '') {
            alert('Please enter a SKU');
            return;
        }

        if (quantity === '' || quantity < 1) {
            alert('Please enter a valid quantity');
            return;
        }

        if (price === '' || price < 0) {
            alert('Please enter a valid unit price');
            return;
        }

        if (specialPrice !== '' && specialPrice > price) {
            alert('Special price must be less than or equal to unit price');
            return;
        }

        if (weight === '' || weight < 1) {
            alert('Please enter a valid weight');
            return;
        }

        if (length === '' || length < 1) {
            alert('Please enter a valid length');
            return;
        }

        if (width === '' || width < 1) {
            alert('Please enter a valid width');
            return;
        }

        if (height === '' || height < 1) {
            alert('Please enter a valid height');
            return;
        }

        if (image.value === '') {
            alert('Please select an image');
            return;
        }

        const files = image.files;
        const file = files[0];
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            image.value = '';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Please select an image file less than 2MB');
            image.value = '';
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select an image file of type JPEG, JPG or PNG');
            image.value = '';
            return;
        }

        form.submit();
    });
</script>
<script>
    $(document).ready(function(){
        $('#image').on('change', function(){
            var file = this.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result);
                $('#image-preview').show();
            };
            reader.readAsDataURL(file);
        });
    });
</script>