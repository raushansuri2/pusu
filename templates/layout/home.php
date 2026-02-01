<!DOCTYPE html>
<html class="no-js" lang="en">

<?php echo $this->element('head'); ?>

<body class="home-2">
    <div class="wrapper">

        <!-- Start Navigation -->
        <?php echo $this->element('header'); ?>
        <!-- End Navigation -->
        <div class="clearfix"></div>

        <!-- Main Banner Section Start -->
        <?php echo $this->fetch('content'); ?>
        <div class="clearfix"></div>
        <!-- Main Banner Section End -->
        
        <!-- Testimonial Section -->
        <section class="testimonials-3" style="background:url(assets/img/testimonial.png)">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="heading">
                            <h2>What Our <span>Customers Say</span></h2>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div id="testimonial-3" class="slick-carousel-3">
                            <?php if(isset($TESTIMONIALS)) {
                            foreach ($TESTIMONIALS as $TESTIMONIAL) { ?>
                                <div class="testimonial-detail">
                                    <div class="client-detail-box">
                                        <div class="pic">
                                            <?php $TTIMG = ($TESTIMONIAL->testimonialsimage != '') ? $this->Url->build('/') . 'img/uploads/testimonials/original/' . $TESTIMONIAL->testimonialsimage : $this->Url->build('/') . 'img/dummy.jpg'; ?>
                                            <img src="<?php echo $TTIMG; ?>" alt="">
                                        </div>
                                        <div class="client-detail">
                                            <h3 class="testimonial-title"><?php echo $TESTIMONIAL->name; ?></h3>
                                            <span class="post"><?php echo $TESTIMONIAL->designation; ?></span>
                                        </div>
                                    </div>
                                    <p class="description">" <?php echo $TESTIMONIAL->text; ?> "</p>
                                </div>
                            <?php }} ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Testimonial Section -->

        <!-- Counter Section -->
        <section class="company-state theme-overlap" style="background:url(assets/img/tag-bg.jpg);">
            <div class="container-fluid">
                <div class="col-md-3 col-sm-6">
                    <div class="work-count">
                        <span class="theme-cl icon icon-trophy"></span>
                        <span class="counter"><?php echo $globalparameters->AwardsWinning; ?></span> <span class="counter-incr">+</span>
                        <p>Awards</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="work-count">
                        <span class="theme-cl icon icon-layers"></span>
                        <span class="counter"><?php echo $globalparameters->Veterinarian; ?></span> <span class="counter-incr">+</span>
                        <p>Veterinarians</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="work-count">
                        <span class="theme-cl icon icon-happy"></span>
                        <span class="counter"><?php echo $globalparameters->HappyClients; ?></span> <span class="counter-incr">+</span>
                        <p>Happy Clients</p>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6">
                    <div class="work-count">
                        <span class="theme-cl icon icon-dial"></span>
                        <span class="counter"><?php echo $globalparameters->ServiceProvides; ?></span> <span class="counter-incr">+</span>
                        <p>Service Providers</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Counter Section -->

        <!-- Listings Section -->
        <section class="sec-bt">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="heading">
                            <h2>Top & Popular <span>Listings</span></h2>
                            <p>RiteVet offers professional, reliable and loving dog walking, pet sitting, cat visits, boarding, and other pet care services.</p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <?php if(isset($users)) {
                    foreach ($users as $user) { ?>
                        <div class="col-lg-4 col-md-6 col-sm-12">
                            <?php if ($user->UTYPE == 2) { // User Type 2 ?>
                                <div class="property_item classical-list">
                                    <div class="image">
                                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'veterinarydetail', base64_encode($user->id)]); ?>" class="listing-thumb" target="_blank">
                                            <?php $BIMG = ($user->user->profile_picture != '') ? $this->Url->build('/') . 'img/uploads/users/' . $user->user->profile_picture : $this->Url->build('/') . 'img/dummy.jpg'; ?>
                                            <img src="<?php echo $BIMG; ?>" alt="latest property" class="img-responsive">
                                        </a>
                                        <span class="list-review" title="reviews"><?php echo (@count($user->reviews)) ? @count($user->reviews) : '0'; ?></span>
                                        <?php if ($user->user->AVGRating == 5) { ?>
                                            <span class="list-rate good" title="rating"><?php echo ($user->user->AVGRating) ? round($user->user->AVGRating,1) : '0'; ?></span>
                                        <?php } elseif ($user->user->AVGRating > 4) { ?>
                                            <span class="list-rate great" title="rating"><?php echo ($user->user->AVGRating) ? round($user->user->AVGRating,1) : '0'; ?></span>
                                        <?php } else { ?>
                                            <span class="list-rate medium" title="rating"><?php echo ($user->user->AVGRating) ? round($user->user->AVGRating,1) : '0'; ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="proerty_content">
                                        <div class="author-avater">
                                            <?php $IMG = ($user->user->profile_picture != '') ? $this->Url->build('/') . 'img/uploads/users/' . $user->user->profile_picture : $this->Url->build('/') . 'img/dummy.jpg'; ?>
                                            <img src="<?php echo $IMG; ?>" class="author-avater-img" alt="">
                                        </div>
                                        <div class="proerty_text">
                                            <h3 class="captlize">
                                                <a target="_blank" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'veterinarydetail', base64_encode($user->id)]); ?>">
                                                    <?php echo $user->user->firstName . ' ' . $user->user->lastName; ?>
                                                </a>
                                                <span class="veryfied-author"></span>
                                            </h3>
                                        </div>
                                        <p class="property_add">Veterinarian</p>
                                        <div class="property_meta">
                                            <div class="list-fx-features">
                                                <div class="listing-card-info-icon">
                                                    <span class="inc-fleat inc-add"><?php echo $user->user->address; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } elseif ($user->UTYPE == 3) { // User Type 3 ?>
                                <div class="property_item classical-list">
                                    <div class="image">
                                        <a href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'serviceProviderDetail', base64_encode($user->id)]); ?>" class="listing-thumb" target="_blank">
                                            <?php $BIMG = (@$user->user->profile_picture != '') ? $this->Url->build('/') . 'img/uploads/users/' . @$user->user->profile_picture : $this->Url->build('/') . 'img/dummy.jpg'; ?>
                                            <img src="<?php echo $BIMG; ?>" alt="latest property" class="img-responsive">
                                        </a>
                                        <span class="list-review" title="reviews"><?php echo (@count($user->reviews)) ? @count($user->reviews) : '0'; ?></span>
                                        <?php if ($user->user->AVGRating == 5) { ?>
                                            <span class="list-rate good" title="rating"><?php echo ($user->user->AVGRating) ? round($user->user->AVGRating,1) : '0'; ?></span>
                                        <?php } elseif ($user->user->AVGRating > 4) { ?>
                                            <span class="list-rate great" title="rating"><?php echo ($user->user->AVGRating) ? round($user->user->AVGRating,1) : '0'; ?></span>
                                        <?php } else { ?>
                                            <span class="list-rate medium" title="rating"><?php echo ($user->user->AVGRating) ? round($user->user->AVGRating,1) : '0'; ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="proerty_content">
                                        <div class="author-avater">
                                            <?php $IMG = (@$user->user->profile_picture != '') ? $this->Url->build('/') . 'img/uploads/users/' . @$user->user->profile_picture : $this->Url->build('/') . 'img/dummy.jpg'; ?>
                                            <img src="<?php echo $IMG; ?>" class="author-avater-img" alt="">
                                        </div>
                                        <div class="proerty_text">
                                            <h3 class="captlize">
                                                <a target="_blank" href="<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'serviceProviderDetail', base64_encode($user->id)]); ?>">
                                                    <?php echo @$user->user->firstName . ' ' . @$user->user->lastName; ?>
                                                </a>
                                                <span class="veryfied-author"></span>
                                            </h3>
                                        </div>
                                        <p class="property_add">Other Service Provider</p>
                                        <div class="property_meta">
                                            <div class="list-fx-features">
                                                <div class="listing-card-info-icon">
                                                    <span class="inc-fleat inc-add"><?php echo @$user->user->address; ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } } ?>
                </div>
            </div>
        </section>
        <!-- End Listings Section -->

        <!-- App Download Section -->
        <section class="tag-section" style="background-image:url(assets/img/bg-simple-2.jpg); padding-bottom:0;" data-overlay="7">
            <div class="container">
                <div class="row">
                    <div class="col-md-7 lp-tag-wrap">
                        <h2 style="color:#FFF">Download Our App Today!</h2>
                        <p style="color:#FFF; margin:20px 0">Try the app that lets you connect immediately with a licensed veterinarian via phone.</p>
                        <a href="#"><img src="assets/img/app.png" alt="Download App"></a>
                    </div>
                    <div class="col-md-5">
                        <img src="assets/img/mockup.png" style="width:100%;" alt="App Mockup">
                    </div>
                </div>
            </div>
        </section>

        <!-- Free Stuff Section -->
        <section class="sec-bt">
            <div class="container">
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="heading">
                            <h2>Our Free <span>Stuff</span></h2>
                            <p>RiteVet offers professional, reliable and loving dog walking, pet sitting, cat visits, boarding, and other pet care services.</p>
                        </div>
                    </div>
                </div>
                <!-- Uncomment the following block if you want to display free stuff -->
                 <div class="row">
                    <?php if(isset($FREESTAFFS)) {
                    foreach ($FREESTAFFS as $FREESTAFF) { ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="blog-box blog-grid-box">
                                <div class="blog-grid-box-img">
                                    <?php $IMG = ($FREESTAFF->image != '') ? $this->Url->build('/') . 'img/uploads/products/' . $FREESTAFF->image : $this->Url->build('/') . 'img/dummy.jpg'; ?>
                                    <img src="<?php echo $IMG; ?>" class="img-responsive" alt="">
                                </div>
                                <div class="blog-grid-box-content">
                                    <div class="blog-avatar text-center">
                                        <?php $UIMG = ($FREESTAFF->user->profile_picture != '') ? $this->Url->build('/') . 'img/uploads/users/' . $FREESTAFF->user->profile_picture : $this->Url->build('/') . 'img/1no-image-100x100.jpg'; ?>
                                        <img src="<?php echo $UIMG; ?>" class="img-responsive" alt="">
                                        <p><strong>By</strong> <span class="theme-cl"><?php echo $FREESTAFF->user->firstName;?> <?php echo $FREESTAFF->user->lastName;?></span></p>
                                    </div>
                                    <h4><?php echo $FREESTAFF->productName; ?></h4>
                                    <?php echo substr($FREESTAFF->description, 0, 200); ?>
                                    <a href="<?php echo $this->Url->build(['controller' => 'Freestaffs', 'action' => 'details', base64_encode($FREESTAFF->id)]); ?>" class="theme-cl" title="Read More.." target="_blank">Continue...</a>
                                </div>
                            </div>
                        </div>
                    <?php } }?>
                </div>
            </div>
        </section>

        <!-- Start Footer -->
        <?php echo $this->element('footer'); ?>
        <a id="back2Top" class="theme-bg" title="Back to top" href="#"><i class="ti-arrow-up"></i></a>
        <?php echo $this->element('footer_bottom'); ?>

        <!-- START JAVASCRIPT -->
        <script>
            function openRightMenu() {
                document.getElementById("rightMenu").style.display = "block";
            }

            function closeRightMenu() {
                document.getElementById("rightMenu").style.display = "none";
            }

            $(document).ready(function () {
                $('[data-toggle="tooltip"]').tooltip();
                $('select').niceSelect();
            });
        </script>
    </div><!--//wrapper close-->
</body>
</html>