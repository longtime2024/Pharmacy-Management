<?php

  if(isset($_GET["action"]) && $_GET["action"] == "delete") {
    require "db_connection.php";
    $invoice_number = $_GET["invoice_number"];
    $query = "DELETE FROM invoices WHERE INVOICE_ID = $invoice_number";
    $result = mysqli_query($con, $query);
    if(!empty($result))
  		showInvoices();
  }

  if(isset($_GET["action"]) && $_GET["action"] == "refresh")
    showInvoices();

  if(isset($_GET["action"]) && $_GET["action"] == "search")
    searchInvoice(strtoupper($_GET["text"]), $_GET["tag"]);

  if(isset($_GET["action"]) && $_GET["action"] == "print_invoice")
    printInvoice($_GET["invoice_number"]);

  function showInvoices() {
    require "db_connection.php";
    if($con) {
      $seq_no = 0;
      $query = "SELECT * FROM invoices INNER JOIN customers ON invoices.CUSTOMER_ID = customers.ID";
      $result = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($result)) {
        $seq_no++;
        showInvoiceRow($seq_no, $row);
      }
    }
  }

  function showInvoiceRow($seq_no, $row) {
    ?>
    <tr>
      <td><?php echo $seq_no; ?></td>
      <td><?php echo $row['INVOICE_ID']; ?></td>
      <td><?php echo $row['NAME']; ?></td>
      <td><?php echo $row['INVOICE_DATE']; ?></td>
      <td><?php echo $row['TOTAL_AMOUNT']; ?></td>
      <td><?php echo $row['TOTAL_DISCOUNT']; ?></td>
      <td><?php echo $row['NET_TOTAL']; ?></td>
      <td>
        <button class="btn btn-warning btn-sm" onclick="printInvoice(<?php echo $row['INVOICE_ID']; ?>);">
          <i class="fa fa-fax"></i>
        </button>
        <button class="btn btn-danger btn-sm" onclick="deleteInvoice(<?php echo $row['INVOICE_ID']; ?>);">
          <i class="fa fa-trash"></i>
        </button>
      </td>
    </tr>
    <?php
  }

  function searchInvoice($text, $column) {
    require "db_connection.php";
    if($con) {
      $seq_no = 0;
      if($column == 'INVOICE_ID')
        $query = "SELECT * FROM invoices INNER JOIN customers ON invoices.CUSTOMER_ID = customers.ID WHERE CAST(invoices.$column AS VARCHAR(9)) LIKE '%$text%'";
      else if($column == "INVOICE_DATE")
        $query = "SELECT * FROM invoices INNER JOIN customers ON invoices.CUSTOMER_ID = customers.ID WHERE invoices.$column = '$text'";
      else
        $query = "SELECT * FROM invoices INNER JOIN customers ON invoices.CUSTOMER_ID = customers.ID WHERE UPPER(customers.$column) LIKE '%$text%'";

      $result = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($result)) {
        $seq_no++;
        showInvoiceRow($seq_no, $row);
      }
    }
  }

  function printInvoice($invoice_id) {
    require "db_connection.php";

    if ($con) {
        // Query untuk mendapatkan detail invoice dan customer
        $stmt = $con->prepare("
            SELECT invoices.*, customers.NAME, customers.ADDRESS, customers.CONTACT_NUMBER, customers.DOCTOR_NAME, customers.DOCTOR_ADDRESS
            FROM invoices
            INNER JOIN customers ON invoices.CUSTOMER_ID = customers.ID
            WHERE invoices.INVOICE_ID = ?
        ");
        if (!$stmt) {
            die("Prepare failed: " . $con->error);
        }
        $stmt->bind_param("i", $invoice_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die("Query failed: " . $stmt->error);
        }
        $row = $result->fetch_assoc();
        $customer_name = $row['NAME'];
        $address = $row['ADDRESS'];
        $contact_number = $row['CONTACT_NUMBER'];
        $doctor_name = $row['DOCTOR_NAME'];
        $doctor_address = $row['DOCTOR_ADDRESS'];
        $invoice_date = $row['INVOICE_DATE'];
        $total_amount = $row['TOTAL_AMOUNT'];
        $total_discount = $row['TOTAL_DISCOUNT'];
        $net_total = $row['NET_TOTAL'];

        // Ambil informasi admin
        $stmt = $con->prepare("SELECT * FROM admin_credentials");
        if (!$stmt) {
            die("Prepare failed: " . $con->error);
        }
        $stmt->execute();
        $result = $stmt->get_result();
        if (!$result) {
            die("Query failed: " . $stmt->error);
        }
        $row = $result->fetch_assoc();
        $p_name = $row['PHARMACY_NAME'];
        $p_address = $row['ADDRESS'];
        $p_email = $row['EMAIL'];
        $p_contact_number = $row['CONTACT_NUMBER'];

        // Query untuk mendapatkan detail penjualan
        $stmt = $con->prepare("
            SELECT * FROM sales
            WHERE INVOICE_NUMBER = ?
        ");
        if (!$stmt) {
            die("Prepare failed: " . $con->error);
        }
        $stmt->bind_param("i", $invoice_id);
        $stmt->execute();
        $sales_result = $stmt->get_result();
        if (!$sales_result) {
            die("Query failed: " . $stmt->error);
        }

        // Debugging: Cek jumlah baris yang ditemukan
        $num_rows = $sales_result->num_rows;
        if ($num_rows === 0) {
            echo "<p>No sales data found for this invoice.</p>";
        }

        ?>
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/sidenav.css">
        <link rel="stylesheet" href="css/home.css">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10 h3" style="color: #ff5252;">Customer Invoice<span class="float-right">Invoice Number : <?php echo htmlspecialchars($invoice_id); ?></span></div>
        </div>
        <div class="row font-weight-bold">
            <div class="col-md-1"></div>
            <div class="col-md-10"><span class="h4 float-right">Invoice Date : <?php echo htmlspecialchars($invoice_date); ?></span></div>
        </div>
        <div class="row text-center">
            <hr class="col-md-10" style="padding: 0px; border-top: 2px solid #ff5252;">
        </div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-4">
                <span class="h4">Customer Details : </span><br><br>
                <span class="font-weight-bold">Name : </span><?php echo htmlspecialchars($customer_name); ?><br>
                <span class="font-weight-bold">Address : </span><?php echo htmlspecialchars($address); ?><br>
                <span class="font-weight-bold">Contact Number : </span><?php echo htmlspecialchars($contact_number); ?><br>
                <span class="font-weight-bold">Doctor's Name : </span><?php echo htmlspecialchars($doctor_name); ?><br>
                <span class="font-weight-bold">Doctor's Address : </span><?php echo htmlspecialchars($doctor_address); ?><br>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-4">
                <span class="h4">Shop Details : </span><br><br>
                <span class="font-weight-bold"><?php echo htmlspecialchars($p_name); ?></span><br>
                <span class="font-weight-bold"><?php echo htmlspecialchars($p_address); ?></span><br>
                <span class="font-weight-bold"><?php echo htmlspecialchars($p_email); ?></span><br>
                <span class="font-weight-bold">Mob. No.: <?php echo htmlspecialchars($p_contact_number); ?></span>
            </div>
            <div class="col-md-1"></div>
        </div>
        <div class="row text-center">
            <hr class="col-md-10" style="padding: 0px; border-top: 2px solid #ff5252;">
        </div>

        <!-- Tabel untuk menampilkan detail penjualan -->
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10 table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Medicine Name</th>
                            <th>Expiry Date</th>
                            <th>Quantity</th>
                            <th>MRP</th>
                            <th>Discount</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sl = 1;
                        while ($sale = $sales_result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($sl++) . '</td>';
                            echo '<td>' . htmlspecialchars($sale['MEDICINE_NAME']) . '</td>';
                            echo '<td>' . htmlspecialchars($sale['EXPIRY_DATE']) . '</td>';
                            echo '<td>' . htmlspecialchars($sale['QUANTITY']) . '</td>';
                            echo '<td>' . htmlspecialchars($sale['MRP']) . '</td>';
                            echo '<td>' . htmlspecialchars($sale['DISCOUNT']) . '</td>';
                            echo '<td>' . htmlspecialchars($sale['TOTAL']) . '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                    <tfoot class="font-weight-bold">
                        <tr style="text-align: right; font-size: 18px;">
                            <td colspan="6">&nbsp;Total Amount</td>
                            <td><?php echo htmlspecialchars($total_amount); ?></td>
                        </tr>
                        <tr style="text-align: right; font-size: 18px;">
                            <td colspan="6">&nbsp;Total Discount</td>
                            <td><?php echo htmlspecialchars($total_discount); ?></td>
                        </tr>
                        <tr style="text-align: right; font-size: 22px;">
                            <td colspan="6" style="color: green;">&nbsp;Net Amount</td>
                            <td class="text-primary"><?php echo htmlspecialchars($net_total); ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="col-md-1"></div>
        </div>
        <div class="row text-center">
            <hr class="col-md-10" style="padding: 0px; border-top: 2px solid #ff5252;">
        </div>
        <?php
    }
}



?>
