<?php // templates/Users/request_other.php ?>
<style>
    .list-review {
        position: absolute;
        right: 20px;
        bottom: 20px;
        background: #dfd91a;
        padding: 4px 12px;
        border-radius: 3px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1.3;
    }

    .active {
        background-color: #337ab7;
        color: #fff;
        border-color: #337ab7;
    }

    .serviceType label.active {
        background-color: #337ab7;
        color: #fff;
        border-color: #337ab7;
    }

    .serviceType label.active:hover {
        background-color: #23527c;
    }

    .looking {
        margin-bottom: 10px;
    }

    .serviceType {
        margin-left: 115px;
    }

    .btn-group-toggle .btn-secondary input[type="checkbox"]:checked ~ span {
        font-weight: bold;
    }

    .card {
        margin-bottom: 20px;
    }

    .card-body {
        padding: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-check-label {
        margin-right: 20px;
    }

    .btn-group {
        margin-bottom: 20px;
    }

    .btn {
        margin-right: 10px;
    }

    .vertical-expand {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100px;
        height: auto;
    }
</style>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="row">
    <div class="col-md-10 col-md-offset-1">
        <div class="heading">
            <h2>Find Service Provider <span>Near You</span></h2>
        </div>
    </div>
</div>

<div class="row">
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-center">
                    <h5 class="card-title looking">I'm looking for service for my:</h5>
                    <?= $this->Form->create(null, [
                        'url' => ['controller' => 'Users', 'action' => 'requestOther'],
                        'method' => 'get',
                        'onsubmit' => 'return cleanForm(this);'
                    ]) ?>
                        <div class="form-group">
                            <?php foreach ($typeOfPets as $typeOfPet): ?>
                                <label class="form-check-label">
                                    <?= $this->Form->checkbox('petType[]', [
                                        'value' => $typeOfPet->id,
                                        'id' => 'P_' . $typeOfPet->id,
                                        'class' => 'form-check-input',
                                        'checked' => in_array($typeOfPet->id, (array)$this->request->getQuery('petType')),
                                        'hiddenField' => false // Disable hidden input
                                    ]) ?>
                                    <?= h($typeOfPet->name) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>

                        <div class="form-group text-center">
                            <label>Service type:</label>
                            <div class="btn-group btn-group-toggle justify-content-center serviceType" data-toggle="buttons">
                                <?php foreach ($typeOfServices as $typeOfService): ?>
                                    <?php $isChecked = $this->request->getQuery('serviceType') == $typeOfService->id; ?>
                                    <label class="btn btn-secondary <?= $isChecked ? 'active' : '' ?>">
                                        <?= $this->Form->radio('serviceType', [
                                            [
                                                'value' => $typeOfService->id,
                                                'text' => '',
                                                'id' => 'S_' . $typeOfService->id,
                                                'class' => 'SER',
                                                'onchange' => 'serviceChange(event)',
                                                'checked' => $isChecked
                                            ]
                                        ], ['hiddenField' => false]) ?>
                                        <span class="vertical-expand"><?= h($typeOfService->name) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group text-center form-inline">
                            <label for="location" class="d-block">Service near</label>
                            <?= $this->Form->text('zipCode', [
                                'class' => 'form-control',
                                'id' => 'location',
                                'autocomplete' => 'off',
                                'minlength' => 5,
                                'maxlength' => 5,
                                'placeholder' => 'Zip code',
                                'value' => h($this->request->getQuery('zipCode'))
                            ]) ?>
                        </div>

                        <?php $frequency = $this->request->getQuery('frequency'); ?>
                        <div class="form-group" id="frequency-div" style="<?= $frequency || ($this->request->getQuery('serviceType') && in_array($this->request->getQuery('serviceType'), [7,10,11,12,13,14,15])) ? '' : 'display: none;'; ?>">
                            <label class="d-block">Choose one of the following:</label>
                            <div class="btn-group btn-group-toggle d-inline-block" data-toggle="buttons">
                                <label class="btn btn-secondary <?= $frequency === 'Single-Day' ? 'active' : '' ?>">
                                    <?= $this->Form->radio('frequency', [
                                        [
                                            'value' => 'Single-Day',
                                            'text' => 'Single-Day',
                                            'id' => 'oneTime',
                                            'onchange' => 'frequencyChanged(this)',
                                            'checked' => $frequency === 'Single-Day'
                                        ]
                                    ], ['hiddenField' => false]) ?>
                                </label>
                                <label class="btn btn-secondary <?= $frequency === 'Multi-Day' ? 'active' : '' ?>">
                                    <?= $this->Form->radio('frequency', [
                                        [
                                            'value' => 'Multi-Day',
                                            'text' => 'Multiple Days',
                                            'id' => 'repeat',
                                            'onchange' => 'frequencyChanged(this)',
                                            'checked' => $frequency === 'Multi-Day'
                                        ]
                                    ], ['hiddenField' => false]) ?>
                                </label>
                            </div>
                        </div>

                        <div class="form-group text-center form-inline" id="dates-div" style="<?= ($this->request->getQuery('startDate') || ($this->request->getQuery('serviceType') && $this->request->getQuery('frequency') === 'Multi-Day' && in_array($this->request->getQuery('serviceType'), [7,10,11,12,13,14,15]))) ? '' : 'display: none;'; ?>">
                            <label class="d-block">Enter the dates you need the service:</label>
                            <div class="form-row" style="display: inline;">
                                <div class="col" style="display: inline;">
                                    <?= $this->Form->text('startDate', [
                                        'id' => 'startDate',
                                        'autocomplete' => 'off',
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter dates',
                                        'value' => h($this->request->getQuery('startDate'))
                                    ]) ?>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-center form-inline" id="reqSerDateDiv" style="<?= ($this->request->getQuery('request_service_date') || in_array($this->request->getQuery('serviceType'), [8,9,16]) || ($this->request->getQuery('serviceType') && $this->request->getQuery('frequency') === 'Single-Day' && in_array($this->request->getQuery('serviceType'), [7,10,11,12,13,14,15]))) ? '' : 'display: none;'; ?>">
                            <label class="d-block">Requested Service Date:</label>
                            <div class="form-row" style="display: inline;">
                                <div class="col" style="display: inline;">
                                    <?= $this->Form->text('request_service_date', [
                                        'id' => 'requestServiceDate',
                                        'autocomplete' => 'off',
                                        'class' => 'form-control',
                                        'placeholder' => 'Requested Service date',
                                        'value' => h($this->request->getQuery('request_service_date'))
                                    ]) ?>
                                </div>
                            </div>
                        </div>

                        <?php $dogSizes = (array)$this->request->getQuery('dogSize'); ?>
                        <div class="form-group" id="dog-size-div" <?= !empty($dogSizes) || in_array(1, (array)$this->request->getQuery('petType')) ? '' : 'style="display: none;"'; ?>>
                            <label class="d-block">My Dog Size</label>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <?php foreach (['small' => 'Small: (Up to 20 pounds)', 'medium' => 'Medium: (20 to 60 pounds)', 'large' => 'Large: (60 to 100 pounds)', 'giant' => 'Extra Large: (Over 100 pounds)'] as $size => $label): ?>
                                    <label class="btn btn-secondary <?= in_array($size, $dogSizes) ? 'active' : '' ?>">
                                        <?= $this->Form->checkbox('dogSize[]', [
                                            'value' => $size,
                                            'hiddenField' => false,
                                            'checked' => in_array($size, $dogSizes)
                                        ]) ?>
                                        <span></span> <?= h($label) ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <?= $this->Form->button('Search', ['type' => 'submit', 'class' => 'btn btn-primary']) ?>
                        <?= $this->Html->link('Reset', ['controller' => 'Users', 'action' => 'requestOther'], [
                            'style' => 'display: inline-block; height: 46px; background-color: #726d6d; color: #fff; padding: 10px 20px; border: none; cursor: pointer;',
                            'class' => ''
                        ]) ?>
                    <?= $this->Form->end() ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php if (!empty($users)): ?>
        <?php $GG = 0; ?>
        <?php foreach ($users as $user): ?>
            <div class="col-lg-4 col-md-6 col-sm-12 <?= $GG % 3 == 0 ? 'clear-both' : '' ?>">
                <div class="property_item classical-list">
                    <div class="image">
                        <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'serviceProviderDetail', base64_encode($user->id)]) ?>" class="listing-thumb" target="_blank">
                            <?php 
                            $imagePath = !empty($user->user->profile_picture) 
                                ? $this->Url->build('/img/uploads/users/' . $user->user->profile_picture) 
                                : $this->Url->build('/img/dummy.jpg');
                            ?>
                            <img src="<?= h($imagePath) ?>" alt="latest property" class="img-responsive">
                        </a>
                        <span class="list-review" title="reviews"><?= h(count($user->reviews) ?: 0) ?></span>
                        <?php 
                        $rating = $user->averagerating ?? 0;
                        $ratingClass = $rating == 5 ? 'good' : ($rating > 4 ? 'great' : 'medium');
                        ?>
                        <span class="list-rate <?= h($ratingClass) ?>" title="rating"><?= h($rating) ?></span>
                    </div>
                    <div class="proerty_content">
                        <div class="author-avater">
                            <img src="<?= h($imagePath) ?>" class="author-avater-img" alt="">
                        </div>
                        <div class="proerty_text">
                            <h3 class="captlize">
                                <a target="_blank" href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'serviceProviderDetail', base64_encode($user->id)]) ?>">
                                    <?= h($user->user->firstName . ' ' . $user->user->lastName) ?>
                                </a>
                                <span class="veryfied-author"></span>
                            </h3>
                        </div>
                        <p class="property_add">Other Service Provider</p>
                        <div class="property_meta">
                            <div class="list-fx-features">
                                <div class="listing-card-info-icon">
                                    <span class="inc-fleat inc-add"><?= h($user->user->address) ?></span>
                                </div>
                                <div class="listing-card-info-icon d-flex align-items-center">
                                    <span class="ml-2" style="color: #29af6a; font-size: 26px; margin-left: 16px;">$<?php
                                        $serviceType = $this->request->getQuery('serviceType');
                                        $finalRate = '0.0';
                                        if ($serviceType == 16 && !empty($user->videochatavailability)) {
                                            $finalRate = round($user->videochatavailability[0]->total_price, 1);
                                        } elseif ($serviceType >= 7 && $serviceType <= 15 && !empty($user->typeofservicesrate)) {
                                            foreach ($user->typeofservicesrate as $serviceRate) {
                                                if ($serviceRate->typeofservice_id == $serviceType) {
                                                    $finalRate = round($serviceRate->rate, 1);
                                                    break;
                                                }
                                            }
                                        } elseif (!empty($user->videochatavailability)) {
                                            $finalRate = round($user->videochatavailability[0]->total_price, 1);
                                        } elseif (!empty($user->typeofservicesrate)) {
                                            $finalRate = round($user->typeofservicesrate[0]->final_rate, 1);
                                        }
                                        echo h($finalRate);
                                        ?>
                                    </span>
                                    <span class="ml-2"><?= h($typeOfServicesPer->per ?? 'Per Service') ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php $GG++; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-lg-4 col-md-6 col-sm-12">
            <p>No data found</p>
        </div>
    <?php endif; ?>
</div>

<div class="pagination">
    <?= $this->Paginator->numbers() ?>
    <?= $this->Paginator->prev('« Previous') ?>
    <?= $this->Paginator->next('Next »') ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#requestServiceDate", {
        minDate: "today",
        mode: "single",
        dateFormat: "Y-m-d",
        allowInput: true
    });

    flatpickr("#startDate", {
        minDate: "today",
        mode: "multiple",
        dateFormat: "Y-m-d",
        allowInput: true
    });

    function serviceChange(event) {
        const selectedValue = event.target.value;
        const requestServiceDateDiv = document.getElementById('reqSerDateDiv');
        const datesDiv = document.getElementById('dates-div');
        const frequencyDiv = document.getElementById('frequency-div');

        if (['8', '9', '16'].includes(selectedValue)) {
            requestServiceDateDiv.style.display = 'block';
            frequencyDiv.style.display = 'none';
            datesDiv.style.display = 'none';
            clearInputs();
        } else if (['7', '10', '11', '12', '13', '14', '15'].includes(selectedValue)) {
            frequencyDiv.style.display = 'block';
            requestServiceDateDiv.style.display = 'none';
            datesDiv.style.display = 'none';
            clearInputs();
        } else {
            requestServiceDateDiv.style.display = 'none';
            frequencyDiv.style.display = 'none';
            datesDiv.style.display = 'none';
            clearInputs();
        }
    }

    function clearInputs() {
        document.getElementById('requestServiceDate').value = '';
        document.getElementById('startDate').value = '';
        clearFrequency();
    }

    function clearFrequency() {
        document.querySelectorAll('input[name="frequency"]').forEach(radio => {
            radio.checked = false;
            radio.closest('label').classList.remove('active');
        });
    }

    function frequencyChanged(radio) {
        const reqSerDateDiv = document.getElementById('reqSerDateDiv');
        const datesDiv = document.getElementById('dates-div');
        if (radio.value === 'Single-Day') {
            reqSerDateDiv.style.display = 'block';
            datesDiv.style.display = 'none';
            document.getElementById('startDate').value = '';
        } else if (radio.value === 'Multi-Day') {
            reqSerDateDiv.style.display = 'none';
            datesDiv.style.display = 'block';
            document.getElementById('requestServiceDate').value = '';
        }
    }

    function cleanForm(form) {
        // Remove unchecked checkboxes and their potential hidden fields
        form.querySelectorAll('input[type="checkbox"]').forEach(cb => {
            if (!cb.checked) {
                cb.name = '';
                // Remove any associated hidden inputs if they exist
                const hidden = cb.previousElementSibling;
                if (hidden && hidden.type === 'hidden' && hidden.name === cb.name) {
                    hidden.remove();
                }
            }
        });

        // Remove empty text inputs
        form.querySelectorAll('input[type="text"]').forEach(input => {
            if (!input.value.trim()) input.name = '';
        });

        // Remove unchecked radio buttons
        form.querySelectorAll('input[type="radio"]').forEach(radio => {
            if (!radio.checked) radio.name = '';
        });

        const isPetTypeChecked = document.getElementById('P_1')?.checked;
        const dogSizeChecked = form.querySelectorAll('input[name="dogSize[]"]:checked').length > 0;

        if (isPetTypeChecked && !dogSizeChecked) {
            alert("Please select at least one dog size.");
            return false;
        }
        return true;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const mainCheckbox = document.getElementById('P_1');
        const dogSizeDiv = document.getElementById('dog-size-div');

        if (mainCheckbox) {
            mainCheckbox.addEventListener('change', function() {
                dogSizeDiv.style.display = this.checked ? 'block' : 'none';
                if (!this.checked) {
                    dogSizeDiv.querySelectorAll('input[type="checkbox"]').forEach(cb => {
                        cb.checked = false;
                        cb.closest('label').classList.remove('active');
                    });
                }
            });
        }

        const radioButtons = document.querySelectorAll('.SER');
        radioButtons.forEach(radio => {
            radio.addEventListener('change', serviceChange);
        });
    });
</script>