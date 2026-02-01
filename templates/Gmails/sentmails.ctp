<?php
use Cake\Core\Configure;

?>

<div class="add-charity-container">
    <h2>Message System</h2>
    <?php echo $this->Flash->render();?>
    <div class="white-bg">
        
        <div class="row" id="message_main_box_right">
                
                <ul>
                    <li><?php echo $this->Html->link('Inbox', array('controller'=>'gmails', 'action' => 'maillist'), array('escape' => false, 'class'=>'')); ?></li>
                    <li><?php echo $this->Html->link('Write Message', array('controller'=>'gmails', 'action' => 'writemail'), array('escape' => false, 'class'=>'')); ?></li>
                    <li><?php echo $this->Html->link('Sent Message', array('controller'=>'gmails', 'action' => 'sentmails'), array('escape' => false, 'class'=>'active')); ?></li>
                    <li><?php echo $this->Html->link('Draft', array('controller'=>'gmails', 'action' => 'draftmail'), array('escape' => false, 'class'=>'')); ?></li>
                </ul>
                
        </div>      
                <!--message box title-->
                <div id="message_box_title" class="bicky_message_box_title">
                    <?php echo $this->Form->create('Gmail', array('name' => 'maillist','controller' => 'gmails','action' => 'maillist','onsubmit'=>'return validate();'));?>
                    <table width="100%" border="0" align="left" cellpadding="0" cellspacing="0" style="margin-left:0px;" class="bicky-table">
                        <tr>
                            <td width="25%" align="left" valign="top" class="message_title message_title_new">From</td>
                            <td width="40%" align="left" valign="top" class="message_title message_title_new">Subject</td>
                            <td width="24%" align="left" valign="top" class="message_title message_title_new">Date </td>
                        </tr>
                        
                        <?php
                        if(count($maillists) != '0')
                        {
                        foreach($maillists as $maillist)
                        {
                            $firstname=$maillist->user->firstname;
                            $subject=$maillist->subject;
                            $mail_id=$maillist->id;
                            $message=$maillist->message;
                            $date=$maillist->date;
                    ?>
                        <tr>
                          <td align="left" valign="top" class="message_text message_text_new"><label>
                            <?php echo $this->Html->link(__($firstname,true), array('controller'=>'gmails', 'action' => 'readmail', $maillist->id), array('escape' => false, 'class'=>'link_txt')); ?>
                          </label>
                          </td>
                          <td align="left" valign="top" class="message_text message_text_new">
                                <?php echo $this->Html->link(__($subject,true), array('controller'=>'gmails', 'action' => 'readmail', $maillist->id), array('escape' => false, 'class'=>'link_txt')); ?>
                          </td>
                          <td align="left" valign="top" class="message_text message_text_new">
                            <?php $dt=date('F d, Y H:i:s',$date); ?>
                                <?php echo $this->Html->link(__($dt,true), array('controller'=>'gmails', 'action' => 'readmail', $maillist->id), array('escape' => false, 'class'=>'link_txt')); ?>
                          </td>
                        </tr>
                        <?php
                        }
                        }else{?>
                         <tr>
                            
                          <td align="left" valign="top" colspan="4" aline="center" class="message_text message_text_new">
                            No Message Found !	
                          </td>
                         
                        </tr>   
                            
                        <?php }
                        ?>
                           
                    </table>
                    <?php echo $this->Form->end(); ?>
                </div>
                              <!--message box title closed-->
    
    
        <?php if(!empty($maillists)){ ?>
            <p>&nbsp;</p>
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
      <div class="cl"></div>  
    
    </div>
</div>
