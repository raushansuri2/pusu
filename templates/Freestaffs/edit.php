<?php
echo $this->Flash->render();
echo $this->Form->create($freeStaff, ['type' => 'file', 'novalidate' => 'novalidate']);
?>
<div class="add-listing-box general-info mrg-bot-25 padd-bot-30 padd-top-25">
    <div class="row mrg-r-10 mrg-l-10">
        <div class="col-sm-6">
            <label>Select Category <span class="mandatory">*</span></label>
            <?= $this->Form->control('categoryId', [
                'options' => $categories,
                'empty' => 'Select Category',
                'class' => 'form-control chosen-select',
                'data-placeholder' => 'Choose One',
                'style' => 'width:100%',
                'id' => 'category',
                'label' => false,
                'div' => false
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Select Sub Category <span class="mandatory">*</span></label>
            <?= $this->Form->control('subCategory', [
                'options' => $subcategory, 
                'empty' => 'Select Sub Category', 
                'class' => 'form-control chosen-select', 
                'data-placeholder' => 'Choose One', 
                'style' => 'width:100%', 
                'id' => 'subcategory22', 
                'label' => false,
                'div' => false
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Stuff Name <span class="mandatory">*</span></label>
            <?= $this->Form->control('productName', [
                'class' => 'form-control',
                'type' => 'text', 
                'placeholder' => 'Enter stuff name', 
                'div' => false, 
                'label' => false, 
                'maxlength' => 100
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Description <span class="mandatory">*</span></label>
            <?= $this->Form->control('description', [
                'class' => 'form-control',
                'type' => 'textarea', 
                'placeholder' => 'Enter description', 
                'div' => false, 
                'label' => false,
                'rows' => 5,
                'cols' => 50,
                'style' => 'resize:none'
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>SKU (Stock Keeping Unit) <span class="mandatory">*</span></label>
            <?= $this->Form->control('SKU', [
                'class' => 'form-control',
                'type' => 'text', 
                'placeholder' => 'Enter SKU', 
                'div' => false, 
                'label' => false, 
                'maxlength' => 50
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Quantity <span class="mandatory">*</span></label>
            <?= $this->Form->control('quantity', [
                'type' => 'number',
                'class' => 'form-control',
                'min' => 1,
                'step' => 1,
                'placeholder' => 'Enter quantity',
                'label' => false,
                'autocomplete' => 'off',
                ]) ?>
        </div>
        <!-- New Fields -->
        <div class="col-sm-6">
            <label>Weight (lbs) <span class="mandatory">*</span></label>
            <?= $this->Form->control('weight', [
                'type' => 'number',
                'class' => 'form-control',
                'min' => 1,
                'step' => 1,
                'placeholder' => 'Enter weight in pounds',
                'label' => false,
                'autocomplete' => 'off',
            ]) ?>
        </div>
        <div class="col-sm-6">
            <label>Length (inches) <span class="mandatory">*</span></label>
            <?= $this->Form->control('length', [
                'type' => 'number',
                'class' => 'form-control',
                'min' => 1,
                'step' => 1,
                'placeholder' => 'Enter length in inches',
                'label' => false,
                'autocomplete' => 'off',
            ]) ?>
        </div>
        <div class="col-sm-6">
            <label>Width (inches) <span class="mandatory">*</span></label>
            <?= $this->Form->control('width', [
                'type' => 'number',
                'class' => 'form-control',
                'min' => 1,
                'step' => 1,
                'placeholder' => 'Enter width in inches',
                'label' => false,
                'autocomplete' => 'off',
            ]) ?>
        </div>
        <div class="col-sm-6">
            <label>Height (inches) <span class="mandatory">*</span></label>
            <?= $this->Form->control('height', [
                'type' => 'number',
                'class' => 'form-control',
                'min' => 1,
                'step' => 1,
                'placeholder' => 'Enter height in inches',
                'label' => false,
                'autocomplete' => 'off',
            ]) ?>
        </div>
        <div class="col-sm-6">
            <label>Unit Price <span class="mandatory">*</span></label>
            <?= $this->Form->control('unitPrice', [
                'class' => 'form-control',
                'type' => 'number', 
                'min' => 0,
                'value' => 0,
                'readonly' => true,
                'placeholder' => 'Unit price (fixed at 0)', 
                'div' => false, 
                'label' => false
            ]); ?>
        </div>
        <div class="col-sm-6">
            <label>Stuff Image <span class="mandatory">*</span></label>
            <?= $this->Form->control('image', [
                'type' => 'file',
                'div' => false,
                'label' => false
            ]); ?>
            <?php if (!empty($freeStaff['image'])): ?>
                <img id="image-preview" src="<?= $this->Url->build('/img/uploads/products/' . $freeStaff['image']); ?>" alt="Image preview" style="max-width: 100px; max-height: 100px; margin-top: 10px;">
            <?php else: ?>
                <img id="image-preview" src="#" alt="Image preview" style="display: none; max-width: 100px; max-height: 100px; margin-top: 10px;">
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="col-sm-12">
    <div class="text-center">
        <input class="btn theme-btn" type="submit" value="Update">
    </div>
</div>

<?= $this->Form->end(); ?>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>

<script>
    $(document).ready(function() {
        $("#category").on('change', function() {
            var categoryId = $(this).val();
            $("#newsAjaxLoader").css("display", "block");
            $.ajax({
                url: "<?= $this->Url->build(['controller' => 'Freestaffs', 'action' => 'subcategory']);?>" + "/" + categoryId,
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

        $('#image').on('change', function() {
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const categoryId = document.getElementById('category').value;
            const subCategory = document.getElementById('subcategory22').value;
            const productName = document.querySelector('input[name="productName"]').value;
            const description = document.querySelector('textarea[name="description"]').value;
            const SKU = document.querySelector('input[name="SKU"]').value;
            const quantity = document.querySelector('input[name="quantity"]').value;
            const weight = document.querySelector('input[name="weight"]').value;
            const length = document.querySelector('input[name="length"]').value;
            const width = document.querySelector('input[name="width"]').value;
            const height = document.querySelector('input[name="height"]').value;
            const image = document.getElementById('image');
            const imagePreview = document.getElementById('image-preview');

            const imageExists = imagePreview.src !== '' && imagePreview.src !== '#';

            if (!categoryId) {
                alert('Please select a category');
                return;
            }

            if (!subCategory) {
                alert('Please select a sub category');
                return;
            }

            if (!productName) {
                alert('Please enter a stuff name');
                return;
            }

            if (!description) {
                alert('Please enter a description');
                return;
            }

            if (!SKU) {
                alert('Please enter a SKU');
                return;
            }
            
            if (!quantity || quantity < 1) {
                alert('Please enter a valid quantity');
                return;
            }

            if (!weight || weight < 0.1) {
                alert('Please enter a valid weight (minimum 0.1 kg)');
                return;
            }

            if (!length || length < 1) {
                alert('Please enter a valid length (minimum 1 cm)');
                return;
            }

            if (!width || width < 1) {
                alert('Please enter a valid width (minimum 1 cm)');
                return;
            }

            if (!height || height < 1) {
                alert('Please enter a valid height (minimum 1 cm)');
                return;
            }

            if (!imageExists && !image.value) {
                alert('Please select an image');
                return;
            }

            if (image.value) {
                const file = image.files[0];
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
            }

            form.submit();
        });
    });
</script>