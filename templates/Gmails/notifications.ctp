
<div class="main">
    <?php echo $this->Flash->render();?>
    <!--<h2 class="fl" style="text-align:center; width:100%">Notifications List</h2>-->
    
               
 

    <div class="cl"></div>
    <div class="table-white-bg add-charity-container">
        <div class="scroll-div">
            <?php 
                echo $this->Form->create('Employers',  ['url' => ['controller' => 'Employers', 'action'=>'deleteemployeeall'], 'type' =>'post','id'=>'bulkdelete', 'novalidate' => 'novalidate']);
                ?>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                
               <tr><td><h2 class="fl" style="text-align:center; width:100%">Notifications List</h2></td></tr>
                
                <?php
                //pr($workreports);
                if(!empty($notifications)){
                    foreach($notifications as $notfi){
                        ?>
                        
                <div class="cl"></div>
                        <tr  style="<?php if($notfi->readMessage == '0') { ?> background-color: rgb(242, 242, 242); <?php } ?>">
                            
                            <td class="emp-dep"><?php echo $notfi->message;?></td>
                        </tr>                    
                        <?php
                    }
                }
                else {
                    echo "<tr><td style='color : #ff0000;'>No Record Found...</td></tr>";
                }
                ?>
                
                 
            </table>
            <?php echo $this->Form->end(); ?>
        </div>
        <?php if(!empty($notifications)){ ?>
            <div class="paging">
                <?php
                //------Paging---------
                if($this->Paginator->counter(array('format' => __('{{count}}'))) !=0) {                
                    if($this->Paginator->counter(array('format' => __('{{pages}}'))) > 1) {?> 
                    <ul>
                    <?php		
                        echo $this->Paginator->prev(__('Previous'), array('tag' => 'li','escape' => false), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a','escape' => false));
                        echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'ellipsis'=>'','currentClass' => 'active','tag' => 'li','first' => 1));
                        echo $this->Paginator->next(__('Next'), array('tag' => 'li','escape' => false,'currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a','escape' => false));			   
                    ?>
                    </ul>
                    <?php }
                }
                ?>
            </div>
        <?php } ?>

    </div>
</div>
