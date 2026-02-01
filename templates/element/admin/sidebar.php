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

     <!-- Post -->
    <li class="parent <?= ($module_name == 'Posts' || $module_name == 'Likes' || $module_name == 'Comments') ? 'active' : '' ?>">
        <a href="#"><i class="fa fa-share-square-o"></i> <span>Post</span></a>
        <ul class="children">
            <li class="<?= ($module_name == 'Posts' && $action_name == 'index') ? 'active' : '' ?>">
                <a href="<?= $this->Url->build(['controller' => 'Posts', 'action' => 'index', 'prefix' => 'Admin']) ?>">
                    <i class="fa fa-share-square-o"></i> Manage Post(s)
                </a>
            </li>
            <li class="<?= ($module_name == 'Comments' && $action_name == 'index') ? 'active' : '' ?>">
                <a href="<?= $this->Url->build(['controller' => 'Comments', 'action' => 'index', 'prefix' => 'Admin']) ?>">
                    <i class="fa fa-comment-o"></i> Manage Comment(s)
                </a>
            </li>
            <li class="<?= ($module_name == 'Likes' && $action_name == 'index') ? 'active' : '' ?>">
                <a href="<?= $this->Url->build(['controller' => 'Likes', 'action' => 'index', 'prefix' => 'Admin']) ?>">
                    <i class="fa fa-star-half-o"></i> Manage Like(s)
                </a>
            </li>
            <li class="<?= ($module_name == 'Posts' && $action_name == 'album') ? 'active' : '' ?>">
                <a href="<?= $this->Url->build(['controller' => 'Posts', 'action' => 'album', 'prefix' => 'Admin']) ?>">
                    <i class="fa fa-bell"></i> <span>Alubm(s)</span>
                </a>
            </li>
        </ul>
    </li>

    <li class="<?= ($module_name == 'Friends' && in_array( $action_name, ['index'])) ? 'active' : '' ?>">
        <a href="<?= $this->Url->build(['controller' => 'Friends', 'action' => 'index', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-bell"></i> <span>Friends(s)</span>
        </a>
    </li>

    <li class="<?= ($module_name == 'Friends' && in_array( $action_name, ['block'])) ? 'active' : '' ?>">
        <a href="<?= $this->Url->build(['controller' => 'Friends', 'action' => 'block', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-bell"></i> <span>Block User(s)</span>
        </a>
    </li>

    

    <li>
        <a href="<?= $this->Url->build(['controller' => 'Notifications', 'action' => 'index', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-bell"></i> <span>Notification(s)</span>
        </a>
    </li>
    

    
    <!-- <li class="parent <?= ($module_name == 'Ipos') ? 'active' : '' ?>">
        <a href="#"><i class="fa fa-shopping-cart"></i> <span>IPOS</span></a>
        <ul class="children">
            <li class="<?= ($module_name == 'Ipos' && in_array($action_name, ['index', 'edit'])) ? 'active' : '' ?>">
                <a href="<?= $this->Url->build(['controller' => 'Ipos', 'action' => 'index', 'prefix' => 'Admin']) ?>">
                    <i class="fa fa-tags"></i> IPO(s)
                </a>
            </li>
            <li class="<?= ($module_name == 'Ipos' && in_array($action_name, ['upload'])) ? 'active' : '' ?>">
                <a href="<?= $this->Url->build(['controller' => 'Ipos', 'action' => 'upload', 'prefix' => 'Admin']) ?>">
                    <i class="fa fa-shopping-cart"></i> Upload(s)
                </a>
            </li>
        </ul>
    </li> -->
    <!-- Pages -->
    <li>
        <a href="<?= $this->Url->build(['controller' => 'Pages', 'action' => 'index', 'prefix' => 'Admin']) ?>">
            <i class="fa fa-file-text"></i> <span>Pages</span>
        </a>
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
    max-height: 600px; /* Adjust height as needed */
    overflow-y: auto; /* Enable vertical scrolling */
}
</style>