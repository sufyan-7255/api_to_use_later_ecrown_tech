<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!=true){
  header("location: index.php");
  exit;
}
$uid =  $_SESSION['ID'];
$role =  $_SESSION['ROLE'];
$uname =  $_SESSION['username'];
$vouchers = " ";
$transactions = " ";
$others = " ";
if($uname != 'admin'){
$vouchers = $_SESSION['voucher'];
$transactions = $_SESSION['transaction']; 
$others = $_SESSION['others'];

}

?>
<?php

include '../api/connection.php';

?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php include "../header.php" ?>
  <title>Hello, world!</title>

<!-- Additional Css -->
<style>
    .dropdown-menu li {
      position: relative;
    }

    .dropdown-menu .dropdown-submenu {
      display: none;
      position: absolute;
      left: 100%;
      top: -7px;
    }

    .dropdown-menu .dropdown-submenu-left {
      right: 100%;
      left: auto;
    }

    .dropdown-menu>li:hover>.dropdown-submenu {
      display: block;
    }
    table.dataTable tbody th, table.dataTable tbody td{
      padding-top: 15px !important;
      line-height:1rem;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none !important;
      margin: 0!important;
    }
    /* Firefox */
    input[type=number] {
      -moz-appearance: textfield !important;
    }
    table,thead,tr,th,tbody,tr,td{
      background-color:white !important;
    }
    /* table.table-bordered.dataTable tbody th, table.table-.table-striped>tbody>tr:nth-of-type(even)>* {
      background-color:white !important;
    } */
    
  </style>
   <!-- Additional Css -->

</head>

<body>
<?php include $_SERVER['DOCUMENT_ROOT']."/import/dashboard.php" ?><br>
<!-- Content Header (Page header) -->
   <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-md-7  text-right">
          <h1 class="animate-charcter"><u>Company List</u></h1>
        </div>
        <div class="col-md-5">
          <ol class="breadcrumb float-md-right">
            <!-- <li class="breadcrumb-item"><a class="btn btn-sm"  href = "web-api/logout/logout.php">LOGOUT</a></li> -->
            <li class="breadcrumb-item active">Company Setup</li>
            <li class="breadcrumb-item active"><button style="background-color:transparent;border:none" class="btn btn-sm" id="fm_company_list_breadcrumb" >Company List</button></li>
          </ol>
        </div>   
      </div>
    </div>
    <!-- /.container-fluid -->
  </section>
  <!-- /.content-header -->
  
  <div class="row response" style="display:none">
    <div class="col-12">
      <div style="line-height:0.7rem" class="container p-2">
        <div class="">
          <div class="alert alert-success alert-dismissible">
              <button type="button" class="close" data-bs-dismiss="alert" aria-hidden="true">&times;</button>
              <span class="vd_alert-icon"><i class="fas fa-check-circle vd_green"></i></span><strong>Success!</strong>
          </div>
        </div>
      </div>
    </div>    
  </div>
  
  <section class="content">
    <div style="line-height:0.7rem"class="card container">
      <div class="row">
        <div style="height:40px" class="card-header">
          <div class="card-title">
            <h3>Company List:</h3>
          </div>
        </div>
        <div class="card-body">
            <table id="example1" class=" table table-bordered table-responsive-lg ">
              <thead>
              <tr>
                <th>Sno</th>
                <th>Code</th>
                <th>Company name</th>
                <th>Address</th>
                <th>NTN N0.</th>
                <th>ST_REG_NBR</th>
                <th>Action</th>              
              </tr>
              </thead>
            </table>
              
        </div>
      </div>
    </div>
  </section>
  <!-- Edit  form -->
  <div class="modal fade" id="EditFormModel" tabindex="-1" aria-labelledby="exampleModalLabel" aria-modal="true"
      role="dialog">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Edit Company</h5>
                  <button type="button" class="close" aria-label="Close" data-bs-dismiss="modal">
                      <span aria-hidden="true">×</span>
                  </button>
              </div>
              <div class="modal-body">
                <form id="EditForm">
                    <div class="row">
                      <div class="col-md-6 form-group">
                          <label for="inputCompanyCode">Company Code :</label><span style="color:red;">*</span>
                          <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-arrow-up"></i></span>
                              </div>
                              <input pattern="[a-zA-Z0-9 ,._-]{1,}" min="0" maxlength="30" type="number" name="company_code" id="company_code" class="form-control form-control-sm" placeholder="Company Code" readonly > 
                          </div>
                      </div>
                      <div class="col-md-6 form-group">
                          <label for="inputCompanyName">Company Name :</label><span style="color:red;">*</span>
                          <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-pen"></i></span>
                              </div>
                              <input pattern="[a-zA-Z0-9 ,._-]{1,}" maxlength="30" type="text" name="company_name" id="company_name" class="form-control form-control-sm" placeholder="Company Name">
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 form-group text-center">
                        <span id="msg1" style="color: red;font-size: 13px;"></span>
                      </div>
                      <div class="col-md-6 form-group text-center">
                        <span id="msg2" style="color: red;font-size: 13px;"></span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 form-group">
                          <label for="inputNTNNumber">NTN Number :</label>
                          <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-sort-numeric-asc"></i></span>
                              </div>
                              <input maxlength="30" type="text" name="ntn_number" id="ntn_number" class="form-control form-control-sm" placeholder="NTN Number 1-9" >
                          </div>
                      </div>
                      <div class="col-md-6 form-group">
                          <label for="inputSTRegNbrr">ST Reg Nbrr :</label>
                          <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="fas fa-sort-numeric-asc"></i></span>
                              </div>
                              <input pattern="[a-zA-Z0-9 ,._-]{1,}" maxlength="30" type="text" name="stregno" id="stregno" class="form-control form-control-sm" placeholder="ST Reg No." >
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6 form-group text-center">
                        <span id="msg3" style="color: red;font-size: 13px;"></span>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12 form-group">
                          <label for="inputAddress">Address :</label>
                          <div class="input-group">
                              <div class="input-group-prepend">
                                  <span class="input-group-text"><i class="far fa-calendar"></i></span>
                              </div>
                              <textarea rows="4" name="address" id="address" class="form-control form-control-sm" placeholder="Address" ></textarea>
                          </div>
                      </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                      <button type="button" class="btn btn-default" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                      <button type="button" id="update_data" class="btn btn-primary toastrDefaultSuccess">Submit</button>
                    </div>
                </form>
              </div>
          </div>
      </div>
  </div>

  <script>
    $(document).ready(function () {
            var uname = "<?php echo $uname;?>";
            if(uname != 'admin'){
            var transactions = "<?php echo $vouchers;?>";
            var vouchers = "<?php echo $transactions;?>";
            var others= "<?php echo $others;?>";
            
            var transaction1 = transactions.split(",");
            var vouchers1 = vouchers.split(",");
            var others1 = others.split(",");
            if(transaction1!= ''){
            $.each(transaction1, function(key, value){
              document.getElementById(value).style.display = "";
            });
          } 
          if(vouchers1!= ''){
            $.each(vouchers1, function(key, value){
              document.getElementById(value).style.display = "";
            });
          } 
          if(others1!= ''){
            $.each(others1, function(key, value){
              document.getElementById(value).style.display = "";
            });
          } 
        }else{
          var rights = document.getElementsByClassName("show");
          $.each(rights, function(key, value){
            $(value).css("display", "");
         
            });
         
        }
        
    });
        // function new(){
    $(document).ready(function(){
    // aaaa();
    });
      
        $("#example1").ready(function(){
          let table = $('#example1').DataTable({
          dom: 'Bfrtip',
          orderCellsTop: true,
          fixedHeader: true,
          
                  buttons: [
                      'copy', 'csv', 'excel', 'pdf', 'print',
                  ]
          });
          // Setup - add a text input to each footer cell
          $('#example1 thead tr').clone(true).appendTo( '#example1 thead' );
          $('#example1 thead tr:eq(1) th').each( function (i) {
              var title = $(this).text();
              $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
              $( 'input', this ).on( 'keyup change', function () {
                  if ( table.column(i).search() !== this.value ) {
                      table
                          .column(i)
                          .search( this.value )
                          .draw();
                    }
              });
          });
          
            $.ajax({
              url: '/import/api/company_setup/company_ajax.php',
              type:'POST',
              data: {action:'view'},
              success: function(data) {
                console.log(data);
                // Empty datatable previouse records
                table.clear().draw();
                // append data in datatable
                var sno='0';
                for (var i = 0; i < data.length; i++) {
                  sno++;
                  
                    btn_edit = '<button class="btn btn-sm btn-select 702a1b_2" data-id="'+data[i].CO_CODE+'" ><i class="far fa-edit text-info fa-fw"></i></button>';
                  // btn_dlt = '<button class="btn btn-sm btn-delete" data-id="'+data[i].ID+'"><i class="far fa-trash-alt text-danger fa-fw"></i></button>';
                  btn = btn_edit; 
                  table.row.add([sno,data[i].CO_CODE,data[i].CO_NAME,data[i].CO_ADD,data[i].NTN_NO,data[i].ST_NO,btn]);
                }
                table.draw();
              },
              error: function(e){
                  console.log(e);
                  alert("Contact IT Department");
              }
         
        });
    
      // new();    
      //edit
      $("#example1").on('click','.btn-select',function(){
        var company_code = $(this).attr("data-id");
        console.log(company_code);
        $.ajax({
          url: '/import/api/company_setup/company_ajax.php',
            type : "post",
            data : {action:'edit',company_code:company_code},
            success: function(response)
            {
              console.log(response);
              $('#company_code').val(response.CO_CODE);
              $('#company_name').val(response.CO_NAME);
              $('#address').val(response.CO_ADD);
              $('#ntn_number').val(response.NTN_NO);
              $('#stregno').val(response.ST_NO);
          
              $('#EditFormModel').modal('show');
            },
            error: function(e) 
            {
              console.log(e);
              alert("Contact IT Department");
            }
        });
      });
        //update
        $("#EditForm").on('click','#update_data',function(){
            // console.log(id);
            var company_name = $('#company_name').val();
            var company_address = $('#address').val();
            var company_ntn = $('#ntn_number').val();
            var company_code = $('#company_code').val();
            var company_st_reg = $('#stregno').val();
            $.ajax({
                url: '../api/company_setup/company_ajax.php',
                type:'POST',
                data :  {action:'update',company_name:company_name,company_address:company_address, company_ntn:company_ntn,  company_code:company_code,company_st_reg:company_st_reg},
                success: function(response)
                {
                  toastr.remove();
                  var message = response.message
                  var status = response.status
                  console.log(message);
                  if(status == 0){
                    $("#msg").html(message);
                  }else{
                    
                      var table = $('#example1').DataTable();
                    $.ajax({
                      url: '/import/api/company_setup/company_ajax.php',
                      type:'POST',
                      data: {action:'view'},
                      success: function(data) {
                        console.log("dkjfdk");
                        // Empty datatable previouse records
                        table.clear().draw();
                        // append data in datatable
                        var sno='0';
                        for (var i = 0; i < data.length; i++) {
                          sno++;
                          
                            btn_edit = '<button class="btn btn-sm btn-select 702a1b_2" data-id="'+data[i].CO_CODE+'" ><i class="far fa-edit text-info fa-fw"></i></button>';
                          // btn_dlt = '<button class="btn btn-sm btn-delete" data-id="'+data[i].ID+'"><i class="far fa-trash-alt text-danger fa-fw"></i></button>';
                          btn = btn_edit; 
                          table.row.add([sno,data[i].CO_CODE,data[i].CO_NAME,data[i].CO_ADD,data[i].NTN_NO,data[i].ST_NO,btn]);
                        }
                        table.draw();
                      },
                      error: function(e){
                          console.log(e);
                          alert("Contact IT Department");
                      }
                    });
                      $('#EditFormModel').modal('hide');
                      $('#EditFormModel input').trigger("reset");
                      $('.alert-dismissible strong').html("Success!"+message);
                      $('.response').css('display','');
                      toastr.success(message);
                  // });
                  }
                },
                error: function(e) 
                {
                  console.log(e);
                  alert("Contact IT Department");
                }
            });
        });
      });
      //breadcumb 
    $('#fm_company_list_breadcrumb').click(function(){
      $('#fm_company_list_breadcrumb').css('border-color','none');
      window.location.href='/import/company setup/company_list.php';
    });
  </script>
  <?php include"../footer.php"?>
    <script href="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="/import/dist/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="/import/dist/libs/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="/import/dist/libs/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="/import/dist/libs/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="/import/dist/libs/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="/import/dist/libs/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="/import/dist/libs/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="/import/dist/libs/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="/import/dist/libs/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="/import/dist/libs/jszip/jszip.min.js"></script>
    <script src="/import/dist/libs/pdfmake/pdfmake.min.js"></script>
    <script src="/import/dist/libs/pdfmake/vfs_fonts.js"></script>
</body>
</html>