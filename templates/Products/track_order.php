<div class="col-md-12 col-sm-12">
    <div class="row">
        <?= $this->Flash->render() ?>
        <br style="clear:both">
        <div class="search-body" style="width: 100%;">
            <?= $this->Form->create(null, [
                'type' => 'post',
                'novalidate' => 'novalidate',
                'style' => "float:left;width:70%;"
            ]) ?>
            <?= $this->Form->input('orderNo', [
                'templates' => ['inputContainer' => '{{content}}'],
                'value' => '',
                'class' => 'form-control width200',
                'placeholder' => 'Enter your order number',
                'style' => 'float:left; width:75%;',
                'div' => false,
                'label' => false,
                'autocomplete' => 'off'
            ]) ?>
            <?= $this->Form->button('Submit', [
                'style' => "float:right;",
                'class' => 'btn btn-primary mr5 ml10',
                'div' => false,
                'label' => false
            ]) ?>
            <?= $this->Form->end() ?>
        </div>
        <br style="clear:both">
    </div>
</div>