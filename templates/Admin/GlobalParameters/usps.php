<div class="mainwrapper">
    <div class="leftpanel">
        <?php echo $this->element('admin/sidebar'); ?>             
    </div><!-- leftpanel -->
    
    <div class="mainpanel">
        <div class="pageheader">
            <div class="media">
                <div class="pageicon pull-left">
                    <i class="fa fa-shield"></i>
                </div>
                <div class="media-body">
                    <ul class="breadcrumb">
                        <li><a href="<?php echo $this->Url->build(['controller' => 'admins', 'action' => 'dashboard']); ?>"><i class="glyphicon glyphicon-home"></i> Dashboard</a></li>
                        <li>Manage USPS</li>
                    </ul>
                    <h4>Manage USPS</h4>
                </div>
                <div class="search-body" style="width: 39%;">
                    <a href="<?php echo $this->Url->build(['controller' => 'GlobalParameters', 'action' => 'uspsAdd']); ?>" class="btn btn-primary mr5 ml10" style="float: right;">Add</a>
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
                            <th>Environment</th>
                            <th>Grant Type</th>
                            <th>Client ID</th>
                            <th>Client Secret</th>
                            <th>Access Token</th>
                            <th>Issued At</th>
                            <th>Expires In</th>
                            <th class="table-action" style="width: 10%">Action</th>
                        </tr>
                    </thead>                         
                    <tbody>									
                        <?php
                        if (count($uspsData)) {
                            foreach ($uspsData as $usps):
                                ?>
                                <tr>
                                    <td><?php echo $usps->id; ?>.</td>
                                    <td><?php echo h($usps->environment); ?></td>
                                    <td><?php echo h($usps->grant_type); ?></td>
                                    <td style="word-wrap: break-word; max-width: 200px;"><?php echo h($usps->client_id); ?></td>
                                    <td style="word-wrap: break-word; max-width: 200px;"><?php echo h($usps->client_secret); ?></td>
                                    <td style="word-wrap: break-word; max-width: 200px;"><?php echo h($usps->access_token); ?></td>
                                    <td><?php echo date('Y-m-d H:i:s', strtotime($usps->issued_at)); ?></td>
                                    <td><?php echo date("Y-m-d H:i:s", strtotime($usps->expires_in)); ?></td>
                                    <td class="table-action" style="width: 10%;">
                                        <a href="<?php echo $this->Url->build(['controller' => 'GlobalParameters', 'action' => 'renew', $usps->id]); ?>" data-toggle="tooltip" title="Renew" class="tooltips"><i class="fa fa-refresh"></i> Renew</a>
                                    </td>
                                </tr>
                                <?php
                            endforeach;
                        } else {
                            echo "<tr><td colspan='9' class='error'>No Record Found...</td></tr>";
                        }
                        ?> 
                    </tbody>
                </table>
            </div><!-- panel -->                  
        </div><!-- contentpanel -->
        <?php echo $this->Form->end(); ?>      
    </div><!-- mainpanel -->
</div><!-- mainwrapper -->

<style>
    /* Add this CSS to your stylesheet or within a <style> tag */
    table {
        table-layout: auto; /* Allow the table to adjust based on content */
        width: 100%; /* Full width */
    }
    td {
        word-wrap: break-word; /* Allow long words to break */
        overflow-wrap: break-word; /* For better compatibility */
        max-width: 200px; /* Set a max width for the cell */
    }
</style>