<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Kelola Pelanggan</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
		<script src="bootstrap/js/jquery.min.js"></script>
		<script src="bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="shortcut icon" href="" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/sidenav.css">
    <link rel="stylesheet" href="css/home.css">
    <script src="js/manage_customer.js"></script>
    <script src="js/validateForm.js"></script>
    <script src="js/restrict.js"></script>
  </head>
  <body style="max-height: 100%;">
    <!-- including side navigations -->
    <?php include("sections/sidenav.html"); ?>

    <div class="container-fluid">
      <div class="container">

        <!-- header section -->
        <?php
          require "php/header.php";
          createHeader('handshake', 'Kelola Pelanggan', 'Pengelolaan Pelanggan');
        ?>
        <!-- header section end -->

        <!-- form content -->
        <div class="row">

          <div class="col-md-12 form-group form-inline">
            <label class="font-weight-bold" for="">Mencari :&emsp;</label>
            <input type="text" class="form-control" id="" placeholder="Pelanggan" onkeyup="searchCustomer(this.value);">
          </div>

          <div class="col col-md-12">
            <hr class="col-md-12" style="padding: 0px; border-top: 2px solid  #06D001;">
          </div>

          <div class="col col-md-12 table-responsive">
            <div class="table-responsive">
            	<table class="table table-bordered table-striped table-hover">
            		<thead>
            			<tr>
            				<th style="width: 2%;">No.</th>
                    <th style="width: 10%;">Id Pelanggan</th>
            				<th style="width: 13%;">Nama Pelanggan</th>
                    <th style="width: 13%;">Nomor Kontak</th>
                    <th style="width: 17%;">Alamat</th>
                    <th style="width: 13%;">Nama Dokter</th>
                    <th style="width: 17%;">Alamat Dokter</th>
                    <th style="width: 15%;">Tindakan</th>
            			</tr>
            		</thead>
            		<tbody id="customers_div">
                  <?php
                    require 'php/manage_customer.php';
                    showCustomers(0);
                  ?>
            		</tbody>
            	</table>
            </div>
          </div>

        </div>
        <!-- form content end -->
        <hr style="border-top: 2px solid #06D001;">
      </div>
    </div>
  </body>
</html>
