<div class="mainwrapper">
    <div class="leftpanel">
        <?= $this->element('admin/sidebar') ?>
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-user"></i>
                </div>
                <div class="media-body" style="width:80%;">
                    <ul class="breadcrumb">
                        <li>
                            <?= $this->Html->link(
                                '<i class="glyphicon glyphicon-home"></i> Dashboard',
                                ['controller' => 'Admins', 'action' => 'dashboard'],
                                ['escape' => false]
                            ) ?>
                        </li>
                        <li>
                            <?= $this->Html->link(
                                $MMM,
                                ['controller' => 'Users', 'action' => $ACTION]
                            ) ?>
                        </li>
                        <li><?= h($NNN) ?> Details</li>
                    </ul>
                    <h4><?= h($NNN) ?> Details</h4>
                </div>
                <div class="search-body">
                    <?= $this->Html->link(
                        'Back',
                        ['controller' => 'Users', 'action' => 'index'],
                        ['class' => 'btn btn-primary mr5 ml10']
                    ) ?>
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">
            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Member Detail</h3>
                        </div>
                        <div class="panel-body">
                            <?php
                            $fields = [
                                'First Name' => h($users->user->firstName ?? 'N/A'),
                                'Last Name' => h($users->user->lastName ?? 'N/A'),
                                'Gender' => h($users->user->gender ?? 'N/A'),
                                'User  Email' => h($users->user->email ?? 'N/A'),
                                'User  Phone' => h($users->user->contactNumber ?? 'N/A'),
                                'User  Address' => h($users->user->address ?? 'N/A'),
                                'Year In Business' => h($users->YearInBusiness ?? 'N/A'),
                                'Type Of Business' => implode(" | ", array_map(function($val) use ($typeOfBusiness) {
                                    return isset($typeOfBusiness[$val]) ? h($typeOfBusiness[$val]) : '';
                                }, explode(",", $users->typeOfBusiness ?? ''))),
                                'Type Of Pets' => implode(" | ", array_map(function($val) use ($typeOfPet) {
                                    return isset($typeOfPet[$val]) ? h($typeOfPet[$val]) : '';
                                }, explode(",", $users->typeOfPets ?? ''))),
                                'Type Of Service' => implode(" | ", array_map(function($val) use ($typeOfServices) {
                                    return isset($typeOfServices[$val]) ? h($typeOfServices[$val]) : '';
                                }, explode(",", $users->TypeOfService ?? ''))),
                                'Account Name' => h($users->ACName ?? 'N/A'),
                                'Bank Name' => h($users->BankName ?? 'N/A'),
                                'Account No' => h($users->AccountNo ?? 'N/A'),
                                'Routing No.' => h($users->RoutingNo ?? 'N/A'),
                                'Account Type' => h($users->accountType ?? 'N/A'),
                                'Swift Number' => h($users->swiftNumber ?? 'N/A'),
                                'Paypal Email' => h($users->PaypalEmail ?? 'N/A'),
                                'Biography' => h($users->biography ?? 'N/A'),
                                'Created' => h(date('Y-m-d H:i:s', strtotime($users->created ?? 'now'))),
                                'Modify Date' => h(date('Y-m-d H:i:s', strtotime($users->modified ?? 'now'))),
                            ];

                            foreach ($fields as $label => $value) {
                                echo '<div class="form-group">';
                                echo '<label class="col-sm-2"><strong>' . $label . ':</strong></label>';
                                echo '<div class="col-sm-9">' . $value . '</div>';
                                echo '</div>';
                            }
                            ?>

                            <div class="form-group">
                                <label class="col-sm-2"><strong>Own Picture:</strong></label>
                                <div class="col-sm-9">
                                    <?= $this->Html->link(
                                        $this->Html->image('/img/uploads/users/' . $users->user->profile_picture, [
                                            'style' => 'max-height:100px; max-width:100px'
                                        ]),
                                        '/img/uploads/users/' . $users->user ->profile_picture,
                                        ['target' => '_blank', 'escape' => false]
                                    ) ?>
                                </div>
                            </div>

                            <?php
                            $imageTypes = ['Business', 'Transcript', 'License', 'Document'];
                            foreach ($imageTypes as $imageType) : ?>
                                <div class="form-group">
                                    <label class="col-sm-2"><strong><?= $imageType ?>:</strong></label>
                                    <div class="col-sm-9">
                                        <?php
                                        if (!empty($users->images)) {
                                            foreach ($users->images as $imv) {
                                                if ($imv->imageType === $imageType) {
                                                    echo $this->Html->link(
                                                        $this->Html->image('/img/uploads/multiimage/' . $imv->image, [
                                                            'style' => 'max-height:100px; max-width:100px'
                                                        ]),
                                                        '/img/uploads/multiimage/' . $imv->image,
                                                        ['target' => '_blank', 'escape' => false]
                                                    ) . '    ';
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div><!-- panel -->
                </div>
            </div><!-- row -->
        </div>
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->