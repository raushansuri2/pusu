<?php
// Get controller and action from request (use getParam for CakePHP 4.x compatibility)
$module_name = $this->request->getParam('controller');
$action_name = $this->request->getParam('action');
?>

<div class="media profile-left">
    <a class="pull-left profile-thumb" href="#">
        <?php
        // Use getSession() for CakePHP 4.x consistency
        $session = $this->request->getSession();
        $profilePicture = $session->read('AnnuityAdmin.profile_picture') ?: 'admin/no-image-60x60.jpg';
        echo $this->Html->image('uploads/users/admin/' . $profilePicture, ['class' => 'img-circle']);
        ?>
    </a>
    <div class="media-body" style="width: 65%; min-height: 40px;">
        <h4 class="media-heading">
            <?= h($session->read('AnnuityAdmin.firstName') ?? 'Admin') ?>
            <?= h($session->read('AnnuityAdmin.lastName') ?? '') ?>
        </h4>
    </div>
</div><!-- media -->

<ul class="nav nav-pills nav-stacked sidebar-scrollable">
    <!-- Dashboard -->
    <li class="<?= ($module_name == 'Admins' && $action_name == 'dashboard') ? 'active' : '' ?>">
        <a href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'dashboard', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
    </li>

    <!-- Manage Admin -->
    <li class="parent <?= ($module_name == 'Admins' && $action_name != 'dashboard') ? 'active' : '' ?>">
        <a href="#"><i class="fa fa-user"></i> <span>Manage Admin</span></a>
        <ul class="children">
            <li class="<?= ($module_name == 'Admins' && $action_name == 'edit') ? 'active' : '' ?>">
                <a href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'edit', 'prefix' => 'Admin']) ?>">
                    <i class="fa fa-pencil"></i> Edit Admin Details
                </a>
            </li>
            <li class="<?= ($module_name == 'Admins' && $action_name == 'changepassword') ? 'active' : '' ?>">
                <a href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'changepassword', 'prefix' => 'Admin']) ?>">
                    <i class="fa fa-lock"></i> Edit Password
                </a>
            </li>
        </ul>
    </li>

    <!-- Settings -->
    <li class="<?= ($module_name == 'Settings') ? 'active' : '' ?>">
        <a href="<?= $this->Url->build(['controller' => 'Settings', 'action' => 'index', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-cog"></i> <span>Settings</span>
        </a>
    </li>

    <!-- Programs -->
    <li class="<?= ($module_name == 'Programs') ? 'active' : '' ?>">
        <a href="<?= $this->Url->build(['controller' => 'Programs', 'action' => 'index', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-list-alt"></i> <span>Programs</span>
        </a>
    </li>

    <!-- Networks Repricing -->
    <li class="<?= ($module_name == 'NetworksRepricings') ? 'active' : '' ?>">
        <a href="<?= $this->Url->build(['controller' => 'NetworksRepricings', 'action' => 'index', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-network-wired"></i> <span>Networks Repricing</span>
        </a>
    </li>

    <!-- Benefit Plans -->
    <li class="<?= ($module_name == 'BenifitPlans') ? 'active' : '' ?>">
        <a href="<?= $this->Url->build(['controller' => 'BenifitPlans', 'action' => 'index', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-heartbeat"></i> <span>Benefit Plans</span>
        </a>
    </li>

    <!-- Loose Plans -->
    <li class="<?= ($module_name == 'LoosePlans') ? 'active' : '' ?>">
        <a href="<?= $this->Url->build(['controller' => 'LoosePlans', 'action' => 'index', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-cog"></i> <span>Loose Plans</span>
        </a>
    </li>

    <!-- Request Quotes -->
    <li class="<?= ($module_name == 'RequestQuotes') ? 'active' : '' ?>">
        <a href="<?= $this->Url->build(['controller' => 'RequestQuotes', 'action' => 'index', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-file-text"></i> <span>Request Quotes</span>
        </a>
    </li>

    <!-- Fees -->
    <li class="<?= ($module_name == 'Fees') ? 'active' : '' ?>">
        <a href="<?= $this->Url->build(['controller' => 'Fees', 'action' => 'index', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-dollar"></i> <span>Fees</span>
        </a>
    </li>

    <!-- Members -->
    <li class="parent <?= ($module_name == 'Users') ? 'active' : '' ?>">
        <a href="#"><i class="fa fa-users"></i> <span>Member</span></a>
        <ul class="children">
            <li class="<?= ($module_name == 'Users' && $action_name == 'index') ? 'active' : '' ?>">
                <a href="<?= $this->Url->build(['controller' => 'Users', 'action' => 'index', 'prefix' => 'Admin']) ?>">
                    <i class="fa fa-user"></i> Manage Member
                </a>
            </li>
        </ul>
    </li>

    <!-- Quoting Partner Permissions -->
    <li class="<?= ($module_name == 'QuotingPartnerPermissions') ? 'active' : '' ?>">
        <a href="<?= $this->Url->build(['controller' => 'QuotingPartnerPermissions', 'action' => 'index', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-shield"></i> <span>Partner Permissions</span>
        </a>
    </li>

    <!-- Pages -->
    <li class="parent <?= ($module_name == 'Pages') ? 'active' : '' ?>">
        <a href="#"><i class="fa fa-file-text"></i> <span>Pages</span></a>
        <ul class="children">
            <li class="<?= ($module_name == 'Pages' && $action_name == 'index') ? 'active' : '' ?>">
                <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'index', 'prefix' => 'Admin']) ?>">
                    <i class="fa fa-file-text"></i> Manage Pages
                </a>
            </li>
        </ul>
    </li>




    <!-- Sign Out -->
    <li>
        <a href="<?= $this->Url->build(['controller' => 'Admins', 'action' => 'logout', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-sign-out"></i> <span>Sign out</span>
        </a>
    </li>
</ul>

<style>
.sidebar-scrollable {
    max-height: 800px; /* Adjust height as needed */
    overflow-y: auto; /* Enable vertical scrolling */
}
</style>
