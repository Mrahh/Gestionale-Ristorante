<?php
  include("../functions.php");

  if((!isset($_SESSION['uid']) && !isset($_SESSION['username']) && isset($_SESSION['user_level'])) ) 
    header("Location: login.php");

  if($_SESSION['user_level'] != "admin")
    header("Location: login.php");

  if (!empty($_POST['role'])) {
    $role = $sqlconnection->real_escape_string($_POST['role']);
    $staffID = $sqlconnection->real_escape_string($_POST['staffID']);

    $updateRoleQuery = "UPDATE tbl_staff SET role = '{$role}'  WHERE staffID = {$staffID}  ";

      if ($sqlconnection->query($updateRoleQuery) === TRUE) {
        echo "";
      } 

      else {
        //handle
        echo "someting wong";
        echo $sqlconnection->error;
      }
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

    <title>Staff Management - FOS Admin</title>

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

        
        <li class="nav-item">
          <a class="nav-link" href="menu.php">
            <i class="fas fa-fw fa-utensils"></i>
            <span>Menu</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="sales.php">
            <i class="fas fa-fw fa-chart-area"></i>
            <span>Sales</span></a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="staff.php">
            <i class="fas fa-fw fa-user-circle"></i>
            <span>Staff</span>
          </a>
        </li>

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
              <a href="index.html">Dashboard</a>
            </li>
            <li class="breadcrumb-item active">Staff</li>
          </ol>

          <!-- Page Content -->
          <h1>Staff Management</h1>
          <hr>
          <p>Manage current staff that avalaible.</p>

          <div class="row">
            <div class="col-lg-8">
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fas fa-user-circle"></i>
                  Current Staff List</div>
                <div class="card-body">
                  <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                    <tr>
                      <th>#</th>
                      <th>Username</th>
                      <th>Status</th>
                      <th>Role</th>
                      <th class="text-center">Option</th>
                    </tr>
                    
                      <?php 

                        $displayStaffQuery = "SELECT * FROM tbl_staff";

                        if ($result = $sqlconnection->query($displayStaffQuery)) {

                          if ($result->num_rows == 0) {
                            echo "<td colspan='4'>There are currently no staff.</td>";
                          }

                          $staffno = 1;
                          while($staff = $result->fetch_array(MYSQLI_ASSOC)) {
                          ?>  
                          	<tr class="text-center">
                            	<td><?php echo $staffno++; ?></td>
                            	<td><?php echo $staff['username']; ?></td>

                              <?php

                            	if ($staff['status'] == "Online") {
                                echo "<td><span class=\"badge badge-success\">Online</span></td>";
                              }

                              if ($staff['status'] == "Offline") {
                                echo "<td><span class=\"badge badge-secondary\">Offline</span></td>";
                              }

                              ?>

                              <td>
                                <form method="POST">
                                <input type="hidden" name="staffID" value="<?php echo $staff['staffID']; ?>"/>
                                <select name="role" class="form-control" onchange="this.form.submit()">
                                  <?php

                                  $roleQuery = "SELECT role FROM tbl_role";

                                  if ($res = $sqlconnection->query($roleQuery)) {
                                    
                                    if ($res->num_rows == 0) {
                                      echo "no role";
                                    }

                                    while ($role = $res->fetch_array(MYSQLI_ASSOC)) {

                                      if ($role['role'] == $staff['role']) 
                                        echo "<option selected='selected' value='".$staff['role']."'>".ucfirst($staff['role'])."</option>";

                                      else
                                        echo "<option value='".$role['role']."'>".ucfirst($role['role'])."</option>";
                                    }
                                  }

                                  ?>
                                </select>
                                <noscript><input type="submit" value="Submit"></noscript>
                                </form>
                              </td>

                            	<td class="text-center"><a href="deletestaff.php?staffID=<?php echo $staff['staffID']; ?>" class="btn btn-sm btn-danger">Delete</a></td>
                        	</tr>

                          <?php 
                          }

                        }
                        else {
                          echo $sqlconnection->error;
                          echo "Something wrong.";
                        }

                      ?>

                  </table>
                </div>
                <div class="card-footer small text-muted"><i>Default password for new user : Abc123</i></div>
              </div>
            </div>

            <div class="col-lg-4">
              <div class="card mb-3">
                <div class="card-header">
                  <i class="fas fa-chart-bar""></i>
                  Add new staff</div>
                <div class="card-body">
                  <form action="addstaff.php" method="POST" class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
                    <div class="input-group">
                      <select name="staffrole" class="form-control">
                      <?php

                      $roleQuery = "SELECT role FROM tbl_role";

                      if ($res = $sqlconnection->query($roleQuery)) {
                        
                        if ($res->num_rows == 0) {
                          echo "no role";
                        }

                        while ($role = $res->fetch_array(MYSQLI_ASSOC)) {
                            echo "<option value='".$role['role']."'>".ucfirst($role['role'])."</option>";
                        }
                      }

                      ?>
                      </select>
                      <input type="text" required="required" name="staffname" class="form-control" placeholder="Add new user" aria-label="Add" aria-describedby="basic-addon2">
                      <div class="input-group-append">
                        <button type="submit" name="addstaff" class="btn btn-primary">
                          <i class="fas fa-plus"></i>
                        </button> 
                      </div>
                    </div>
                  </form>
                </div>
              </div>
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
            <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
            <button class="close" type="button" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
            </button>
          </div>
          <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
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

  </body>

</html>