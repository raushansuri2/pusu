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
                                'Posts',
                                ['controller' => 'Users', 'action' => 'index']
                            ) ?>
                        </li>
                        <li>Post Details</li>
                    </ul>
                    <h4>Post Details</h4>
                </div>
                <div class="search-body">
                    <?= $this->Html->link(
                        'Back',
                        ['controller' => 'Posts', 'action' => 'index'],
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
                            <h3 class="panel-title">Post Detail</h3>
                        </div>
                        <div class="panel-body">

                            <div class="form-group">
                                <label class="col-sm-2"><strong>Post Title:</strong></label>
                                <div class="col-sm-9">
                                        <?php echo h($post->postTitle ?? 'N/A'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2"><strong>Post Description:</strong></label>
                                <div class="col-sm-9">
                                        <?php echo h($post->description ?? 'N/A'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2"><strong>image_1:</strong></label>
                                <div class="col-sm-9">
                                        <?php 
                                        if ($post->image_1 !='') {
                                            echo $this->Html->link(
                                                $this->Html->image('/img/uploads/multiimage/' . $post->image_1, [
                                                    'style' => 'max-height:100px; max-width:100px'
                                                ]),
                                                '/img/uploads/post/' . $post->image_1,
                                                ['target' => '_blank', 'escape' => false]
                                            ) . '    ';
                                        }
                                        ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2"><strong>image-2:</strong></label>
                                <div class="col-sm-9">
                                        <?php 
                                        if ($post->image_2 !='') {
                                            echo $this->Html->link(
                                                $this->Html->image('/img/uploads/multiimage/' . $post->image_2, [
                                                    'style' => 'max-height:100px; max-width:100px'
                                                ]),
                                                '/img/uploads/post/' . $post->image_2,
                                                ['target' => '_blank', 'escape' => false]
                                            ) . '    ';
                                        }
                                        ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"><strong>image-3:</strong></label>
                                <div class="col-sm-9">
                                        <?php 
                                        if ($post->image_3 !='') {
                                            echo $this->Html->link(
                                                $this->Html->image('/img/uploads/multiimage/' . $post->image_3, [
                                                    'style' => 'max-height:100px; max-width:100px'
                                                ]),
                                                '/img/uploads/post/' . $post->image_3,
                                                ['target' => '_blank', 'escape' => false]
                                            ) . '    ';
                                        }
                                        ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"><strong>image-4:</strong></label>
                                <div class="col-sm-9">
                                        <?php 
                                        if ($post->image_4 !='') {
                                            echo $this->Html->link(
                                                $this->Html->image('/img/uploads/multiimage/' . $post->image_4, [
                                                    'style' => 'max-height:100px; max-width:100px'
                                                ]),
                                                '/img/uploads/post/' . $post->image_4,
                                                ['target' => '_blank', 'escape' => false]
                                            ) . '    ';
                                        }
                                        ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2"><strong>image-5:</strong></label>
                                <div class="col-sm-9">
                                        <?php 
                                        if ($post->image_5 !='') {
                                            echo $this->Html->link(
                                                $this->Html->image('/img/uploads/multiimage/' . $post->image_5, [
                                                    'style' => 'max-height:100px; max-width:100px'
                                                ]),
                                                '/img/uploads/post/' . $post->image_5,
                                                ['target' => '_blank', 'escape' => false]
                                            ) . '    ';
                                        }
                                        ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="col-sm-2"><strong>Total Comment:</strong></label>
                                <div class="col-sm-9">
                                    <a href="<?php echo $this->Url->build(['controller'=>'Comments','action'=>'index',$post->id]);?>" title="click to view comment list">
                                        <?php echo h($post->totalComment ?? 'N/A'); ?>
                                    </a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2"><strong>Total Like:</strong></label>
                                <div class="col-sm-9">
                                    <a href="<?php echo $this->Url->build(['controller'=>'Likes','action'=>'index',$post->id]);?>" title="click to view like list" >
                                        <?php echo h($post->totalLike ?? 'N/A'); ?>
                                    </a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2"><strong>Video Link:</strong></label>
                                <div class="col-sm-9">
                                        <?php echo h($post->videolink ?? 'N/A'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2"><strong>Post Type:</strong></label>
                                <div class="col-sm-9">
                                        <?php echo h($post->postType ?? 'N/A'); ?>
                                </div>
                            </div>

                            
                        </div>
                    </div><!-- panel -->
                </div>
            </div><!-- row -->
        </div>
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->