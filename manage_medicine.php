<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Manage Medicines</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<script src="bootstrap/js/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="shortcut icon" href="images/icon.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/sidenav.css">
    <link rel="stylesheet" href="css/home.css">
    <script src="js/manage_medicine.js"></script>
    <script src="js/validateForm.js"></script>
    <script src="js/restrict.js"></script>
  </head>
  <body>
    <!-- including side navigations -->
    <?php include("sections/sidenav.html"); ?>

    <div class="container-fluid">
      <div class="container">

        <!-- header section -->
        <?php
          require "php/header.php";
          createHeader('shopping-bag', 'Kelola Obat', 'Pengelolaan Obat');
        ?>
        <!-- header section end -->

        <!-- form content -->
        <div class="row">

          <div class="col-md-12 form-group form-inline">
            <label class="font-weight-bold" for="">Mencari :&emsp;</label>
            <input type="text" class="form-control" id="by_name" placeholder="Nama Obat" onkeyup="searchMedicine(this.value, 'name');">
            &emsp;<input type="text" class="form-control" id="by_generic_name" placeholder="Nama Generik" onkeyup="searchMedicine(this.value, 'generic_name');">
            &emsp;<input type="text" class="form-control" id="by_suppliers_name" placeholder="Nama Pemasok" onkeyup="searchMedicine(this.value, 'suppliers_name');">
          </div>

          <div class="col col-md-12">
            <hr class="col-md-12" style="padding: 0px; border-top: 2px solid  #02b6ff;">
          </div>

          <div class="col col-md-12 table-responsive">
            <div class="table-responsive">
            	<table class="table table-bordered table-striped table-hover">
            		<thead>
            			<tr>
            				<th style="width: 5%;">No.</th>
            				<th style="width: 20%;">Nama Obat</th>
                    <th style="width: 10%;">Dikemas</th>
                    <th style="width: 30%;">Nama Generik</th>
            				<th style="width: 20%;">Pemasok</th>
                    <th style="width: 15%;">Tindakan</th>
            			</tr>
            		</thead>
            		<tbody id="medicines_div">
                  <?php
                    require 'php/manage_medicine.php';
                    showMedicines(0);
                  ?>
            		</tbody>
            	</table>
            </div>
          </div>

        </div>
        <!-- form content end -->
        <hr style="border-top: 2px solid #ff5252;">
      </div>
    </div>
  </body>
</html>
