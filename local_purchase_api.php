<?php
session_start();
header("Content-Type: application/json");
include '../../connection.php';
if(isset($_POST['action']) && $_POST['action'] == 'insert'){
    // print_r($_POST); die();
    // print_r("hii");
    $doc_date=$_POST['voucher_date'];
    $year=$_POST['year'];
    $ref_no=$_POST['ref_no'];
    $company_code=$_POST['company_code'];
    $company_name=$_POST['company_name'];
    $party=$_POST['party'];
    $title=$_POST['title'];
    $address=$_POST['address'];
    $phone_no=$_POST['phone_no'];
    $gst_no=$_POST['gst_no'];
    $lot_no=$_POST['lot_no'];
    $lot_name=$_POST['lot_name'];
    $location_code=$_POST['location_code'];
    $location_name=$_POST['location_name'];
    $cr_days=$_POST['cr_days'];
    $narration=$_POST['narration'];
    $acc_desc=$_POST['acc_desc'];
    $detail=$_POST['detail'];
    // $um=$_POST['um'];
    $rate=$_POST['rate'];
    $excl_amt=$_POST['excl_amt'];
    $rent_rt=$_POST['rent_rt'];
    $stx_per=$_POST['stx'];
    $stx_amt=$_POST['stx_amt'];
    $incl_amt=$_POST['incl_amt'];
    $trade_disc=$_POST['trade_disc'];
    $amount=$_POST['amount'];

    $excl_total=$_POST['excl_total'];
    $excl_totals = str_replace( ',', '', $excl_total );
    if( is_numeric( $excl_totals ) ) {
      $excl_total = $excl_totals;
    }
    $stx_total=$_POST['stx_total'];
    $stx_totals = str_replace( ',', '', $stx_total );
    if( is_numeric( $stx_totals) ) {
      $stx_total = $stx_totals;
    }
    $net_total=$_POST['net_total'];
    $net_totals = str_replace( ',', '', $net_total );
    if( is_numeric( $net_totals ) ) {
      $net_total = $net_totals;
    }
      // print_r($total); die();
    $select_q="SELECT (case when MAX(DOC_NO) is null then 1 else MAX(DOC_NO)+1 end) DOC_NO 
              FROM stktran_mst  WHERE CO_CODE = '$company_code' AND DOC_YEAR = '$year' AND DOC_TYPE = 'LP'";
    $select_r = $conn->query($select_q);
    $show = mysqli_fetch_assoc($select_r);
    $doc_no=$show['DOC_NO'];
    $master_q = "insert into stktran_mst(co_code,doc_no,doc_type,doc_year,doc_date,refnum,party_code,lot_code,wh_code,crdays,total_excise,total_stax,total_net_amt,remarks)value ('$company_code','$doc_no','LP','$year','$doc_date','$ref_no','$party','$lot_no','$location_code','$cr_days','$excl_total','$stx_total','$net_total','$narration')";
    $master_r = $conn->query($master_q);
    // print_r("hi"); 
    if($master_r){
        for($i=0;$i< count($_POST['acc_desc']); $i++){
          $rate=$_POST['rate'][$i];
          $rates = str_replace( ',', '', $rate );
          if( is_numeric( $rates ) ) {
            $rate = $rates;
          }
          $excl_amt=$_POST['excl_amt'][$i];
          $excl_amts = str_replace( ',', '', $excl_amt );
          if( is_numeric( $excl_amts ) ) {
            $excl_amt = $excl_amts;
          }
          $stx_per=$_POST['stx'][$i];
          $stx_pers = str_replace( ',', '', $stx_per );
          if( is_numeric( $stx_pers ) ) {
            $stx_per = $stx_pers;
          }
          $stx_amt=$_POST['stx_amt'][$i];
          $stx_amts = str_replace( ',', '', $stx_amt );
          if( is_numeric( $stx_amts ) ) {
            $stx_amt = $stx_amts;
          }
          $incl_amt=$_POST['incl_amt'][$i];
          $incl_amts = str_replace( ',', '', $incl_amt );
          if( is_numeric( $incl_amts ) ) {
            $incl_amt = $incl_amts;
          }
          $trade_disc=$_POST['trade_disc'][$i];
          $trade_discs = str_replace( ',', '', $trade_disc);
          if( is_numeric( $trade_discs ) ) {
            $trade_disc= $trade_discs;
          }
          $amount=$_POST['amount'][$i];
          $amounts = str_replace( ',', '', $amount );
          if( is_numeric( $amounts ) ) {
            $amount = $amounts;
          }
          $quantity=$excl_amt/$rate;
          // $quantity=intval(preg_replace('/[^\d]/', '', $quantity));
            $detail_q = "insert into stktran_dtl(co_code,doc_no,doc_type,doc_year,doc_date,party_code,item_code,qty,rate,amt,staxrate,sales_tax,amt2,net_amt2,net_amt)
            value
             ('$company_code','$doc_no','LP','$year','$doc_date','$party','$acc_desc[$i]','$quantity','$rate','$excl_amt','$stx_per','$stx_amt','$incl_amt','$trade_disc','$amount')"; 
            $detail_r = $conn->query($detail_q);
            if($detail_r){
              $return_data = array(
                  "status" => 1,
                  "doc_no" => $doc_no,
                  "message" => "Local Purchase data has been Inserted"
              );  
            }else{
              $return_data = array(
              "status" => 0,
              "message" => "Local Purchase data has not been Inserted"
              );
          }
        }
      
    }
    
}elseif(isset($_POST['action']) && $_POST['action'] == 'edit'){
  // print_r("jfsksk");
  $co_code=$_POST['co_code'];
  $doc_year=$_POST['doc_year'];
  $doc_type=$_POST['doc_type'];
  $doc_no=$_POST['doc_no'];
  $party_code=$_POST['party_code'];
    $query = "SELECT a.*,b.co_name FROM stktran_mst a left join company b on a.co_code=b.co_code  WHERE a.CO_CODE='$co_code' AND a.DOC_YEAR='$doc_year' 
              AND a.DOC_NO='$doc_no' AND a.DOC_TYPE='$doc_type' ";
    // PRINT_R($query);
    $result = $conn->query($query);
    $count = mysqli_num_rows($result);
    $show = mysqli_fetch_assoc($result);
    $return_data = $show;
}elseif(isset($_POST['action']) && $_POST['action'] == 'edit_table'){
  // print_r($_POST);die();
  // print_r("jfsksk");
  $co_code=$_POST['co_code'];
  $doc_year=$_POST['doc_year'];
  $doc_type=$_POST['doc_type'];
  $doc_no=$_POST['doc_no'];
    $query = "SELECT * FROM stktran_dtl WHERE co_code='$co_code' AND doc_year='$doc_year' 
              AND doc_no='$doc_no' AND doc_type='$doc_type' ";
    // PRINT_R($query); die();
    $result = $conn->query($query);
    $count = mysqli_num_rows($result);
    $return_data=[];
    while($show = mysqli_fetch_assoc($result)){
        $return_data[] = $show;
    }
}elseif(isset($_POST['action']) && $_POST['action'] == 'update'){
  // print_r($_POST);
  //   die();
    $document_no=$_POST['document_no'];
    $doc_date=$_POST['voucher_date'];
    $year=$_POST['year'];
    $ref_no=$_POST['ref_no'];
    $company_code=$_POST['company_code'];
    $company_name=$_POST['company_name'];
    $party=$_POST['party'];
    $title=$_POST['title'];
    $address=$_POST['address'];
    $phone_no=$_POST['phone_no'];
    $gst_no=$_POST['gst_no'];
    $lot_no=$_POST['lot_no'];
    $lot_name=$_POST['lot_name'];
    $location_code=$_POST['location_code'];
    $location_name=$_POST['location_name'];
    $cr_days=$_POST['cr_days'];
    $narration=$_POST['narration'];
    $acc_desc=$_POST['acc_desc'];
    $detail=$_POST['detail'];
    // $um=$_POST['um'];
    $rate=$_POST['rate'];
    $excl_amt=$_POST['excl_amt'];
    $rent_rt=$_POST['rent_rt'];
    $stx_per=$_POST['stx'];
    $stx_amt=$_POST['stx_amt'];
    $incl_amt=$_POST['incl_amt'];
    $trade_disc=$_POST['trade_disc'];
    $amount=$_POST['amount'];

    $excl_total=$_POST['excl_total'];
    $excl_totals = str_replace( ',', '', $excl_total );
    if( is_numeric( $excl_totals ) ) {
      $excl_total = $excl_totals;
    }
    $stx_total=$_POST['stx_total'];
    $stx_totals = str_replace( ',', '', $stx_total );
    if( is_numeric( $stx_totals) ) {
      $stx_total = $stx_totals;
    }
    $net_total=$_POST['net_total'];
    $net_totals = str_replace( ',', '', $net_total );
    if( is_numeric( $net_totals ) ) {
      $net_total = $net_totals;
    }
  $upd_mst_q="UPDATE stktran_mst SET DOC_DATE='$doc_date', DOC_YEAR='$year' , PARTY_CODE='$party',
              REMARKS='$narration',   REFNUM='$ref_no'
              WHERE CO_CODE = '$company_code' AND DOC_TYPE = 'LP' AND DOC_NO='$document_no'";
  // print_r($upd_mst_q); die();
  $upd_mst_r = $conn->query($upd_mst_q);
  if($upd_mst_r){
    $del_dtl_q="DELETE FROM stktran_dtl  WHERE CO_CODE = '$company_code' AND DOC_TYPE = 'LP' AND DOC_NO='$document_no'";
    // print_r($del_dtl_q); die();
    $del_dtl_r = $conn->query($del_dtl_q);
    if($del_dtl_r){
        for($i=0;$i< count($_POST['acc_desc']); $i++){
          $rate=$_POST['rate'][$i];
          $rates = str_replace( ',', '', $rate );
          if( is_numeric( $rates ) ) {
            $rate = $rates;
          }
          $excl_amt=$_POST['excl_amt'][$i];
          $excl_amts = str_replace( ',', '', $excl_amt );
          if( is_numeric( $excl_amts ) ) {
            $excl_amt = $excl_amts;
          }
          $stx_per=$_POST['stx'][$i];
          $stx_pers = str_replace( ',', '', $stx_per );
          if( is_numeric( $stx_pers ) ) {
            $stx_per = $stx_pers;
          }
          $stx_amt=$_POST['stx_amt'][$i];
          $stx_amts = str_replace( ',', '', $stx_amt );
          if( is_numeric( $stx_amts ) ) {
            $stx_amt = $stx_amts;
          }
          $incl_amt=$_POST['incl_amt'][$i];
          $incl_amts = str_replace( ',', '', $incl_amt );
          if( is_numeric( $incl_amts ) ) {
            $incl_amt = $incl_amts;
          }
          $trade_disc=$_POST['trade_disc'][$i];
          $trade_discs = str_replace( ',', '', $trade_disc);
          if( is_numeric( $trade_discs ) ) {
            $trade_disc= $trade_discs;
          }
          $amount=$_POST['amount'][$i];
          $amounts = str_replace( ',', '', $amount );
          if( is_numeric( $amounts ) ) {
            $amount = $amounts;
          }
          $quantity=$excl_amt/$rate;
          // $quantity=intval(preg_replace('/[^\d]/', '', $quantity));
            $detail_q = "insert into stktran_dtl(co_code,doc_no,doc_type,doc_year,doc_date,party_code,item_code,qty,rate,amt,staxrate,sales_tax,amt2,net_amt2,net_amt)
            value
             ('$company_code','$document_no','LP','$year','$doc_date','$party','$acc_desc[$i]','$quantity','$rate','$excl_amt','$stx_per','$stx_amt','$incl_amt','$trade_disc','$amount')"; 
            $detail_r = $conn->query($detail_q);
            if($detail_r){
              $return_data = array(
                  "status" => 1,
                  "document_no" => $document_no,
                  "message" => "Local Purchase data has been Updated"
              );
            }else{
              $return_data = array(
              "status" => 0,
              "message" => "Local Purchase data not been Updated"
              );
            }
        }
      
    }
  } 
}elseif(isset($_POST['action']) && $_POST['action'] == 'party_accounts_spec'){
  // print_r("jfsksk");
    $company_code=$_POST['company_code'];
    $query = "SELECT ACCOUNT_CODE,DESCR FROM party_chart WHERE CONTROL_CODE='610' AND CO_CODE='$company_code' AND SUB_LEVEL2 !='000'";
    // PRINT_R($query ); DIE();  
    $result = $conn->query($query);
    $count = mysqli_num_rows($result);
    $return_data=[];
      while($show = mysqli_fetch_assoc($result)){
          $return_data[] = $show;
      }
}elseif(isset($_POST['action']) && $_POST['action'] == 'lot_code'){
  // print_r("jfsksk");
    $company_code=$_POST['company_code'];
    $query = "SELECT LOT_CODE,LOT_NAME FROM lot WHERE  CO_CODE='$company_code' ";
    // PRINT_R($query ); DIE();  
    $result = $conn->query($query);
    $count = mysqli_num_rows($result);
    $return_data=[];
      while($show = mysqli_fetch_assoc($result)){
          $return_data[] = $show;
      }
}elseif(isset($_POST['action']) && $_POST['action'] == 'location_code'){
  // print_r("jfsksk");
    $company_code=$_POST['company_code'];
    $query = "SELECT WH_CODE,WH_NAME FROM warehouse WHERE CO_CODE='$company_code' ";
    // PRINT_R($query ); DIE();  
    $result = $conn->query($query);
    $count = mysqli_num_rows($result);
    $return_data=[];
      while($show = mysqli_fetch_assoc($result)){
          $return_data[] = $show;
      }
}elseif(isset($_POST['action']) && $_POST['action'] == 'item_code'){
  // print_r("jfsksk");
    $company_code=$_POST['company_code'];
    $query = "SELECT ACCOUNT_CODE,DESCR FROM items WHERE CONTROL_CODE='401' AND CO_CODE='$company_code' and sub_level1!='0000' AND SUB_LEVEL2!='000'";
    // PRINT_R($query); DIE();  
    $result = $conn->query($query);
    $return_data=[];
      while($show = mysqli_fetch_assoc($result)){
          $return_data[] = $show;
      }
}elseif(isset($_POST['action']) && $_POST['action'] == 'party_detail'){
    // print_r("jfsksk");
      $party_code=$_POST['party_code'];
      $company_code=$_POST['company_code'];
      $query = "SELECT * FROM party_chart WHERE ACCOUNT_CODE='$party_code' AND CONTROL_CODE='610' AND CO_CODE='$company_code' AND SUB_LEVEL2 !='000'";
      // PRINT_R($query); DIE();  
      $result = $conn->query($query);
      $count = mysqli_num_rows($result);
      $show = mysqli_fetch_assoc($result);
      $return_data = $show;
  }else{
    $return_data = array(
        "status" => 0,
        "message" => "Action Not Matched"
    );
}

print(json_encode($return_data, JSON_PRETTY_PRINT));

?>



