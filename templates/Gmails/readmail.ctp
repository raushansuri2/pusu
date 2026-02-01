<?php
use Cake\Core\Configure;

?>

<div class="add-charity-container">
    <h2>Message System</h2>
	<?php echo $this->Flash->render();?>
    <div class="white-bg">
        
        <div class="row" id="message_main_box_right">
                
                <ul>
                    <li><?php echo $this->Html->link('Inbox', array('controller'=>'gmails', 'action' => 'maillist'), array('escape' => false, 'class'=>'active')); ?></li>
                    <li><?php echo $this->Html->link('Write Message', array('controller'=>'gmails', 'action' => 'writemail'), array('escape' => false, 'class'=>'')); ?></li>
                    <li><?php echo $this->Html->link('Sent Message', array('controller'=>'gmails', 'action' => 'sentmails'), array('escape' => false, 'class'=>'')); ?></li>
                    <li><?php echo $this->Html->link('Draft', array('controller'=>'gmails', 'action' => 'draftmail'), array('escape' => false, 'class'=>'')); ?></li>
                </ul>
                
        </div>
        
        <div class="message_main_box_left_head">
				<a onClick="history.back();">Back</a>
            </div>
        
                <!--message box title-->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" class="bicky-table">
                   <tr>
                     <td width="15%" align="left" class="maillist maillist_new">
                        <b>From: </b>	  </td>
                      <td  width="85%" class="maillist maillist_content">
                        <?php echo $mails->user->firstname; ?></td>
                   </tr>
                   <tr>
                      <td width="15%" align="left" class="description maillist_new">
                        <b>Subject: </b>	  </td>
                     <td  width="85%" class="maillist maillist_content">
                        <?php echo $mails->subject; ?>  </td>
                   </tr>
                   <tr>
                     <td width="15%" align="left" class="description maillist_new">
                        <b>Date: </b>	  </td>
                      <td  width="85%" class="maillist maillist_content">
                        <?php echo date('F j, Y, g:i a',$mails->date); ?>  </td>
                   </tr>
                </table>
                <div class="message_comment">
                <table border="0" cellpadding="5" cellspacing="0" width="100%">
                    <tr>
                        <td class="description" style="padding:10px;">
                            
                            <?php echo $mails->message; ?>
                            
                        </td>
                    </tr>
                </table>
                </div>



      <div class="cl"></div>  
    
    </div>
</div>
