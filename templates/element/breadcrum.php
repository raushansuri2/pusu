<div class="banner-inner dark-opacity" style="background-image:url(<?php echo $this->Url->build('/');?>assets/img/home-banner.jpg);" data-overlay="8">  
    <div class="container">
        <div class="title-content">
            <h1><?php echo $breadcum['Title'];?></h1>
            <div class="breadcrumbs">
              <?php foreach($breadcum['URL'] as $breadKey=>$breadValue){
                 if(end($breadcum['URL']) == $breadValue){	
              ?>
                <span class="current"><?php echo $breadKey;?></span>
              <?php }else{ ?>
                <a href="<?php echo $breadValue;?>"><?php echo $breadKey;?></a>
             <?php }
             echo '<span class="gt3_breadcrumb_divider"></span>';
              }?>
          
            </div>
        </div>
    </div>
</div>


