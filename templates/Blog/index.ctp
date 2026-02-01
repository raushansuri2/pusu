<!-- =============== Blog Detail ================= -->
<div class="col-md-8 col-sm-12">
    <?php
    $ii = 1;
    if ($freestaffs) {
        foreach ($freestaffs as $freestaff) {
            if ($ii % 2 != 0) {
                echo '<div class="row">';
            }
            ?>
            <div class="col-md-6 col-sm-6">
                <div class="blog-box blog-grid-box">
                    <div class="blog-grid-box-img">
                        <?php
                        $IMG = ($freestaff->image_1 != '') ? $this->Url->build('/') . 'img/uploads/freestaff/' . $freestaff->image_1 : $this->Url->build('/') . 'img/dummy.jpg';
                        ?>
                        <img src="<?php echo $IMG;?>" class="img-responsive" alt="">
                    </div>
                    <div class="blog-grid-box-content">
                        <div class="blog-avatar text-center">
                            <?php
                            $UIMG = (@$freestaff->user->profile_picture != '') ? $this->Url->build('/') . 'img/uploads/users/thumb/' . @$freestaff->user->profile_picture : $this->Url->build('/') . 'img/1no-image-100x100.jpg';
                            ?>
                            <img src="<?php echo $UIMG;?>" class="img-responsive" alt="">
                            <p><strong>By</strong> <span class="theme-cl"><?php echo @$freestaff->user->fullName;?></span></p>
                        </div>
                        <h4><?php echo $freestaff->postTitle;?></h4>
                        <?php echo substr($freestaff->description, 0, 100);?>
                        <a href="<?php echo $this->Url->build(['controller' => 'freestaffs', 'action' => 'details', base64_encode($freestaff->id)]);?>" class="theme-cl" title="Read More..">Continue...</a>
                    </div>
                </div>
            </div>
            <?php
            if ($ii % 2 == 0 || count($freestaffs) == $ii) {
                echo '</div>';
            }
            $ii++;
        }
    } else {
        ?>
        <div class="row">
            <div class="col-md-6 col-sm-6">
                <p>No match found!</p>
            </div>
        </div>
        <?php
    }
    ?>
    <div class="pagination">
        <ul class="pagination pagination-sm">
            <?php
            echo $this->Paginator->prev(__('Previous'), array('tag' => 'li'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
            echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentClass' => 'active'));
            echo $this->Paginator->next(__('Next'), array('tag' => 'li', 'currentClass' => 'disabled'), null, array('tag' => 'li', 'class' => 'disabled', 'disabledTag' => 'a'));
            ?>
        </ul>
    </div>
</div>
<!-- /.col-md-8 -->

<!-- ===================== Blog Sidebar ==================== -->
<div class="col-md-4 col-sm-12">
    <div class="sidebar">
        <!-- Search Bar -->
        <div class="widget-boxed">
            <div class="widget-boxed-header border-0">
                <h4><i class="ti-search padd-r-10"></i>Search Here</h4>
            </div>
            <div class="widget-boxed-body padd-top-5">
                <div class="input-group">
                    <?php echo $this->Form->create('Users', ['type' => 'get', 'novalidate' => 'novalidate']); ?>
                    <input type="text" name="keyword" class="form-control" placeholder="Search…">
                    <span class="input-group-btn">
                        <button type="submit" class="btn height-50 theme-btn">Go</button>
                    </span>
                    <?php echo $this->Form->end(); ?>
                </div>
            </div>
        </div>

        <!-- Start: Latest Blogs -->
        <div class="widget-boxed">
            <div class="widget-boxed-header">
                <h4><i class="ti-check-box padd-r-10"></i>Latest Stuff</h4>
            </div>
            <div class="widget-boxed-body padd-top-5">
                <div class="side-list">
                    <ul class="side-blog-list">
                        <?php foreach ($latests as $latest) { ?>
                            <li>
                                <a href="#">
                                    <div class="blog-list-img">
                                        <?php $LIMG = ($latest->image_1 != '') ? $this->Url->build('/') . 'img/uploads/freestaff/' . $latest->image_1 : $this->Url->build('/') . 'img/dummy.jpg'; ?>
                                        <img src="<?php echo $LIMG;?>" class="img-responsive" alt="">
                                    </div>
                                </a>
                                <div class="blog-list-info">
                                    <h5><a href="#" title="blog"><?php echo $latest->postTitle;?></a></h5>
                                    <div class="blog-post-meta">
                                        <span class="updated"><?php echo date("M jS, Y", strtotime($latest->created));?></span> | <a href="<?php echo $this->Url->build(['controller' => 'freestaffs', 'action' => 'details', base64_encode($latest->id)]);?>" rel="tag"><?php echo substr(strip_tags($latest->description), 0, 17);?></a>                    
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End: Latest Blogs -->
        <!-- Start: Listing Category -->
        <div class="widget-boxed">
            <div class="widget-boxed-header">
                <h4><i class="ti-briefcase padd-r-10"></i>Stuff Categories</h4>
            </div>
            <div class="widget-boxed-body padd-top-10 padd-bot-0">
                <div class="side-list">
                    <ul class="category-list">
                        <?php foreach ($CATEArray as $val) { ?>
                            <li><a href="<?php echo $this->Url->build(['controller' => 'Freestaffs', 'action' => 'index/?categoryid=' . $val['id']]);?>"><?php echo $val['name'];?> <span class="badge bg-d"><?php echo $val['totalProduct'];?></span></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <!-- End: Listing Category -->
    </div>
</div>
						
				