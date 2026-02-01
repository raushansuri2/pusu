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
                        <li>Manage Countries</li>
                    </ul>
                    <h4>Manage Countries</h4>
                </div>
                <div class="search-body" style="width: 39%;">
                    <a href="<?php echo $this->Url->build(['controller' => 'Locations', 'action' => 'addCountry']);?>" class="btn btn-primary mr5 ml10" style="float: right;" onclick="return false;" aria-disabled="true">Add Country</a>
                    <?php 
                        echo $this->Form->create('Countries',  ['type' => 'get', 'novalidate' => 'novalidate']);
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
                            <th>Country Code</th>
                            <th>Country Name
                            <span class="sort-link">
                            <?php
                            echo $this->Paginator->sort('name', $this->Html->image('sort-arrow-top.png', array("alt" => "Ascending", "title" => "Ascending")), array('escape' => false, 'direction' => 'asc', 'lock' => true));
                            echo $this->Paginator->sort('name', $this->Html->image('sort-arrow-bottom.png', array("alt" => "Descending", "title" => "Descending")), array('escape' => false, 'direction' => 'desc', 'lock' => true));   
                            ?>
                            </span>
                            </th>
                            <th>Status
                            <span class="sort-link">
                            <?php
                            echo $this->Paginator->sort('status', $this->Html->image('sort-arrow-top.png', array("alt" => "Ascending", "title" => "Ascending")), array('escape' => false, 'direction' => 'asc', 'lock' => true));
                            echo $this->Paginator->sort('status', $this->Html->image('sort-arrow-bottom.png', array("alt" => "Descending", "title" => "Descending")), array('escape' => false, 'direction' => 'desc', 'lock' => true));   
                            ?>
                            </span>
                            </th>
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
                        if(count($countries)){
                            foreach ($countries as $country):
                                ?>
                                <tr>
                                    <td><?php echo $country->id; ?>.</td>
                                    <td><?php echo h($country->sortname); ?></td>
                                    <td><?php echo h($country->name); ?></td>
                                    <td>
                                        <?php if($country->status == '1'){ ?>
                                            <a href="<?php echo $this->Url->build(['controller' => 'Locations', 'action' => 'countryStatus', $country->id]);?>" onclick="return confirm('Are you sure want to inactivate this country?')">Active</a>
                                        <?php }else{ ?>
                                            <a href="<?php echo $this->Url->build(['controller' => 'Locations', 'action' => 'countryStatus', $country->id]);?>" onclick="return confirm('Are you sure want to activate this country?')">Inactive</a>
                                        <?php } ?> 
                                    </td>                                    
                                    <td class="table-action" style="width: 10%;">
                                        <!--<a href="<?php echo $this->Url->build(['controller' => 'Locations', 'action' => 'editCountry', $country->id]);?>" data-toggle="tooltip" title="Edit" class="tooltips"><i class="fa fa-pencil"></i></a>-->
                                        <!--<a href="<?php echo $this->Url->build(['controller' => 'Locations', 'action' => 'deleteCountry', $country->id]);?>" data-toggle="tooltip" onclick="return confirm('Are you sure you want to delete this country?')" title="Delete" class="delete-row tooltips"><i class="fa fa-trash-o"></i></a>-->
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
                        echo $this->Paginator->counter(array('format' => __('<p class="records-showing">Showing {{start}} - {{end}} of {{count}} Countries</p>')));
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