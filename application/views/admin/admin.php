<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php
    $this->load->view('admin/common/header');
?>

<?php
    $this->load->view('admin/common/sidenav');
?>

<!-- Page Content -->
        <div id="page-wrapper">
                <div class="row">
                    <div id="alert_parent" class="col-lg-12 alert_parent">
                        <h2 class='page-header'>
                            <?php
                                if($this->uri->segment(3)!='') {
                                    echo ucfirst(humanize_admin($this->uri->segment(2)));
                                } else if($this->uri->segment(2)!='') {
                                    echo ucfirst(humanize_admin($this->uri->segment(2)));
                                } else {
                                    echo 'Dashboard';
                                }
                            ?>
                        </h2> 
                        <ol class="breadcrumb">
                            <li class="active">
                                <i class="fa fa-dashboard"></i> Admin
                                    <?php if($this->uri->segment(1)==''){
                                        echo ' / Dashboard';

                                    }?>
                                    <?php if($this->uri->segment(2)!='' && !(is_numeric($this->uri->segment(2)))) echo '/ '.humanize_admin($this->uri->segment(2));?>
                                    <?php if($this->uri->segment(3)!='' && !(is_numeric($this->uri->segment(3)))) echo '/ '.humanize_admin($this->uri->segment(3));?>
                                    <?php if($this->uri->segment(4)!=''  && !(is_numeric($this->uri->segment(4)))) echo '/ '.humanize_admin($this->uri->segment(4));?>
                                    <?php if($this->uri->segment(5)!=''  && !(is_numeric($this->uri->segment(5)))) echo '/ '.humanize_admin($this->uri->segment(5));?>
                        </li>
                        </ol>
                        
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->

<?php
    $this->load->view('admin/common/alert');
?>

  <?php
        $this->load->view($main);
    ?>

     <!-- /.row -->
            </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<?php
    $this->load->view('admin/common/footer');
?>