<?php
session_start();
header("Pragma: no-cache");
header("Cache-Control: no-cache");
header("Expires: 0");

// following files need to be included
require_once("./lib/config_paytm.php");
require_once("./lib/encdec_paytm.php");
include_once("./../includes/dbconnection.php");




// include("./../my-account.php");
// $custid=$_POST['CUST_ID'];


// $order_id = NULL;
// $user_id = NULL;
// $payment_mode = NULL;
// $transaction_id = NULL;
// $amount = NULL;
// $banktransaction_id = NULL;
// $transaction_date = NULL;
// $status = NULL;
// $bank_name = NULL;

$paytmChecksum = "";
$paramList = array();
$isValidChecksum = "FALSE";

$paramList = $_POST;
$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application’s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


if($isValidChecksum == "TRUE") {
	echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
	if ($_POST["STATUS"] == "TXN_SUCCESS") {
		echo "<b>Transaction status is success</b>" . "<br/>";

		
		$qu = NULL;
		// echo "$ORDERID";
		// echo "$fnaobno";
		// echo "$street";
		// echo "$area";
		// echo "$lndmark";
		// echo "$city";

		// echo "$custid";
	}
	else {
		echo "<b>Transaction status is failure</b>" . "<br/>";
	}

	if (isset($_POST) && count($_POST)>0 )
	{ 

		 $sql = "INSERT INTO tbleusertransaction(ORDERID,STATUS,TXNID, PAYMENTMODE, BANKTXNID, TXNAMOUNT,BANKNAME,TXNDATE) VALUES('".$_POST['ORDERID']."','".$_POST['STATUS']."','".$_POST['TXNID']."','".$_POST['PAYMENTMODE']."', '".$_POST['BANKTXNID']."','".$_POST['TXNAMOUNT']."','".$_POST['BANKNAME']."', '".$_POST['TXNDATE']."')";  
       $res = mysqli_query($con,$sql);  

		foreach($_POST as $paramName => $paramValue) {

				if($paramName=='ORDERID')
				{
					$query = "update tblorders set IsOrderPlaced='1' where OrderNumber='$paramValue' and IsOrderPlaced='null' ";
					$result = mysqli_query($con,$query);

					if($result)
					{
						$toemail=$_SESSION['uemail'];
				$subj="FOS Order Confirmation";       
				$heade .= "MIME-Version: 1.0"."\r\n";
				$heade .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
				$heade .= 'From:FOS<mrshreyas24@gmail.com>'."\r\n";    // Put your sender email here
				$msgec.="<html></body><div><div>Hello,</div></br></br>";
				$msgec.="<div style='padding-top:8px;'> Your order has been placed successfully <br />
				<strong> Order Number: </strong> $paramValue </br>
				</div><div></div></body></html>";
				mail($toemail,$subj,$msgec,$heade);
				echo '<script>alert("Your order placed successfully. Order number is "+"'.$paramValue.'")</script>';
				echo "<script>window.location.href='./../my-account.php'</script>";
					}

					// $qu = "insert into tbleusertransaction(order_id) values('$paramValue')";
				}



				// else if($paramName=='TXNID')
				// {
				// 	$qu1 = "insert into tbleusertransaction(transaction_id) values('$paramValue')";
				// }
				// else if($paramName=='TXNAMOUNT')
				// {
				// 	$qu2 = "insert into tbleusertransaction(amount) values('$paramValue')";
				// }
				// else if($paramName=='PAYMENTMODE')
				// {
				// 	$qu3 = "insert into tbleusertransaction(payment_mode) values('$paramValue')";
				// }
				// else if($paramName=='TXNDATE')
				// {
				// 	$qu4 = "insert into tbleusertransaction(transaction_date) values('$paramValue')";
				// }
				// else if($paramName=='STATUS')
				// {
				// 	$qu5 = "insert into tbleusertransaction(status) values('$paramValue')";
				// }
				// else if($paramName=='BANKTXNID')
				// {
				// 	$qu6 = "insert into tbleusertransaction(banktransaction_id) values('$paramValue')";
				// }
				// else if($paramName=='BANKNAME')
				// {
				// 	$qu7 = "insert into tbleusertransaction(bank_name) values('$paramValue')";
				// }

				// mysqli_query($con, $qu);
				
		}
	}
	

}
else {
	echo "<b>Checksum mismatched.</b>";
	//Process transaction as suspicious.
}

?>