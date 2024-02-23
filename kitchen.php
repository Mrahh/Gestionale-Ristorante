<?php
  include("../functions.php");

  if((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])) ) 
    header("Location: login.php");

  if($_SESSION['user_level'] != "staff")
    header("Location: login.php");

  if($_SESSION['user_role'] != "chef") {
    echo ("<script>window.alert('Available for chef only!'); window.location.href='index.php';</script>");
    exit();
  }
  
?>

<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard - FOS Staff</title>

    <!-- Bootstrap core CSS-->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

  </head>

  <body id="page-top">

    <nav class="navbar navbar-expand navbar-dark bg-dark static-top">

      <a class="navbar-brand mr-1" href="index.php">Food Ordering System</a>

      <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
        <i class="fas fa-bars"></i>
      </button>

      <!-- Navbar -->
      <ul class="navbar-nav ml-auto ml-md-0">
        <li class="nav-item dropdown no-arrow">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-user-circle fa-fw"></i>
          </a>
        </li>
      </ul>

    </nav>

    <div id="wrapper">

      <!------------------ Sidebar ------------------->
      <ul class="sidebar navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
          </a>
        </li>

        
        <?php

          if ($_SESSION['user_role'] == "waiter") {
            echo '
            <li class="nav-item">
              <a class="nav-link" href="order.php">
                <i class="fas fa-fw fa-book"></i>
                <span>Order</span></a>
            </li>
          ';
          }

          if ($_SESSION['user_role'] == "chef") {
            echo '
            <li class="nav-item">
              <a class="nav-link" href="kitchen.php">
                <i class="fas fa-fw fa-utensils"></i>
                <span>Kitchen</span></a>
            </li>
            ';
          }

        ?>

        <li class="nav-item">
          <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-fw fa-power-off"></i>
            <span>Logout</span>
          </a>
        </li>

      </ul>

      <div id="content-wrapper">

        <div class="container-fluid">

          <!-- Breadcrumbs-->
          <ol class="breadcrumb">
            <li class="breadcrumb-item">
              <a href="index.php">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Kitchen</li>
          </ol>

          <!-- Page Content -->
          <h1>Kitchen Management</h1>
          <hr>
          <p>Manage order that been sent to here.</p>

          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-utensils"></i>
              Current Order List</div>
            <div class="card-body">
            	<table id="tblCurrentOrder" class="table table-bordered text-center" width="100%" cellspacing="0">
					<thead>
						<th>Order #</th>
						<th>Category</th>
						<th>Menu Name</th>
						<th class='text-center'>Quantity</th>
						<th class='text-center'>Status</th>
						<th class='text-center'>Options</th>
					</thead>
					
					<tbody id="tblBodyCurrentOrder">
						
					</tbody>
				</table>
            </div>
          </div>

        </div>
        <!-- /.container-fluid -->

        <!-- Sticky Footer -->
        <footer class="sticky-footer">
          <div class="container my-auto">
            <div class="copyright text-center my-auto">
              <span>Copyright © Food Ordering System 2018</span>
            </div>
          </div>
        </footer>

      </div>
      <!-- /.content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
      <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Sicuro di voler uscire?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Selezionare "Logout" se vuoi a terminare la tua sessione.</div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <a class="btn btn-primary" href="logout.php">Logout</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin.min.js"></script>

    <script type="text/javascript">

		$( document ).ready(function() {
	    	refreshTableOrder();
		});

		function refreshTableOrder() {
			$( "#tblBodyCurrentOrder" ).load( "displayorder.php?cmd=currentorder" );
		}


		function editStatus (objBtn,orderID) {
			var status = objBtn.value;

			$.ajax({
				url : "editstatus.php",
					type : 'POST',
					data : {
						orderID : orderID,
						status : status 
					},

					success : function(output) {
						refreshTableOrder();
					}
				});
		}

		//refresh order current list every 3 secs
		setInterval(function(){ refreshTableOrder(); }, 3000);

	</script>

  </body>

</html>