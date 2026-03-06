<div class="page-content">
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="quote-request.html">Quote Requests</a></li>
            <li class="breadcrumb-item active" aria-current="page">Add Quote Requests</li>
        </ol>
    </nav>


    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Choose Program to Quote</h4>
                    <?php echo $this->Flash->render(); ?>
                    <p class="text-muted mb-3">Please select from the available programs below</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="container1">
                                <div class="row">
                                    
                                    
                                    <?php foreach($program_list as $program){ ?>
                                    <div class="col-md-4 stretch-card grid-margin grid-margin-md-0">
                                        <div class="card">
                                            <div class="card-body">
                                                <i data-feather="file-text" class="text-primary icon-xxl d-block mx-auto my-3"></i>
                                                <h4 class="text-center mb-2"><?php echo $program->name;?></h4>
                                                <p class="text-muted text-center mb-0 fw-light" style="line-height:30px;"><span class="badge bg-success"><?php echo $program->networks;?></span> available networks/repricing<br>
                                                    <span class="badge bg-danger"><?php echo $program->benifit_plans;?></span> available employee benefit plans
                                                </p>
                                                <div class="d-grid">
                                                    <a href="<?php echo $this->Url->build(['controller'=>'Users','action'=>'addquotingRequest?programid='.$program->id]);?>" class="btn btn-lg  btn-danger mt-4">Choose Program</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
