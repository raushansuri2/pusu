<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar');?>             
    </div><!-- leftpanel -->
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-globe"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo $this->Url->build(['controller' => 'admins', 'action' => 'dashboard']);?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li>Manage States</li>
                    </ul>
                    <h4>Manage States</h4>
                </div>
                <div class="search-body" style="width: 39%;">
                    <a href="<?php echo $this->Url->build(['controller' => 'Locations', 'action' => 'addState']);?>" class="btn btn-primary mr5 ml10" style="float: right;">Add State</a>
                    <?php 
                        echo $this->Form->create('States',  ['type' => 'get', 'novalidate' => 'novalidate']);
                        echo $this->Form->input('keyword', ['templates' => ['inputContainer' => '{{content}}'],'value'=>$this->request->query('keyword'), 'class'=>'form-control width200','placeholder'=>'Enter Keyword to Search', 'style' => 'float:left', 'div'=>false, 'label'=>false, 'autocomplete'=>'off']);
                        
                        $this->Form->templates(['submitContainer' => '{{content}}']);                    
                        echo $this->Form->submit('Search', ['class' => 'btn btn-primary mr5 ml10',  'div' => false, 'label' =>false]);
                        echo $this->Form->end();
                    ?>  
                </div>
            </div><!-- media -->
        </div><!-- pageheader -->
        
        <div class="contentpanel">      
            <?php echo $this->Flash->render(); ?>
            <div class="panel panel-primary-head"> 
                <table id="basicTable" class="table table-striped table-bordered responsive">
                    <thead class="table-heading">
                         <tr>
                            <th>ID</th>
                            <th>State Code</th>
                            <th>State Name
                            <span class="sort-link">
                            <?php
                            echo $this->Paginator->sort('name', $this->Html->image('sort-arrow-top.png', array("alt" => "Ascending", "title" => "Ascending")), array('escape' => false, 'direction' => 'asc', 'lock' => true));
                            echo $this->Paginator->sort('name', $this->Html->image('sort-arrow-bottom.png', array("alt" => "Descending", "title" => "Descending")), array('escape' => false, 'direction' => 'desc', 'lock' => true));   
                            ?>
                            </span>
                            </th>
                            <th>State Tax</th>
                            <th>Country Name</th>
                            <th class="table-action" style="width: 10%">Action</th>
                        </tr>
                    </thead>                         
                    <tbody>                                    
                        <?php
                        $page = $this->request->query('page');
                        $i = 1;
                        if($page > '1'){
                            $i = (($page-1)*$limit) + 1;
                        }
                        if(count($states)){ // Change from countries to states
                            foreach ($states as $state): // Change from countries to states
                                ?>
                                <tr>
                                    <td><?php echo $state->id; ?>.</td> <!-- Change from country to state -->
                                    <td><?php echo h($state->code); ?></td> <!-- Assuming 'code' is the state code -->
                                    <td><?php echo h($state->name); ?></td> <!-- Assuming 'name' is the state name -->
                                    <td><?php echo number_format($state->state_tax, 2); ?></td>
                                    <td><?php echo h($state->country->name); ?></td>
                                    <td class="table-action" style="width: 10%;">
                                        <a href="<?php echo $this->Url->build(['controller' => 'Locations', 'action' => 'editState', $state->id]);?>" data-toggle="tooltip" title="Edit" class="tooltips"><i class="fa fa-pencil"></i></a>
                                        <a href="<?php echo $this->Url->build(['controller' => 'Locations', 'action' => 'deleteState', $state->id]);?>" data-toggle="tooltip" onclick="return confirm('Are you sure you want to delete this state?')" title="Delete" class="delete-row tooltips"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                                <?php
                                $i++;
                            endforeach;
                        }else {
                            echo "<tr><td colspan='5' class='error'>No Record Found...</td></tr>";
                        }
                        ?> 
                    </tbody>
                </table>
            </div><!-- panel -->                  
            <div class="paging-container">
                <?php
                //------Paging---------
                if($this->Paginator->counter(array('format' => __('{{count}}'))) !=0) {?> 
                    <p>
                        <?php
                        echo $this->Paginator->counter(array('format' => __('<p class="records-showing">Showing {{start}} - {{end}} of {{count}} States</p>'))); // Change from Countries to States
                        ?>
                    </p>
                    <?php if($this->Paginator->counter(array('format' => __('{{pages}}'))) > 1) {?>
                    <ul>
                    <?php       
                        echo $this->Paginator->prev(__('Previous'), array('tag' => 'li','escape' => false), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a','escape' => false));
                        echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'ellipsis'=>'','currentClass' => 'active','tag' => 'li','first' => 1));
                        echo $this->Paginator->next(__('Next'), array('tag' => 'li','escape' => false,'currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a','escape' => false));               
                    ?>
                    </ul>
                    <?php } ?>
                    <div class="cl"></div>    
                <?php 
                }
                ?>
            </div>
        </div><!-- contentpanel -->
    </div><!-- mainpanel -->
</div><!-- mainwrapper --> 