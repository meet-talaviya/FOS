<?php
session_start();
error_reporting(0);
include_once('includes/dbconnection.php');
$userid=base64_decode($_REQUEST['userid']);
$amount=base64_decode($_REQUEST['am']);
$orderid=mt_rand(100000000, 999999999);

$fnaobno=$_POST['flatbldgnumber'];
$street=$_POST['streename'];
$area=$_POST['area'];
$lndmark=$_POST['landmark'];
$city=$_POST['city'];

// if(isset($_POST['checkvalue']))
// {

// $query = "update tblorders set OrderNumber='$orderid',IsOrderPlaced='null' where UserId='$userid' and IsOrderPlaced is null;";

// $query.="insert into tblorderaddresses(UserId,Ordernumber,Flatnobuldngno,StreetName,Area,Landmark,City) values('$userid','$orderid','$fnaobno','$street','$area','$lndmark','$city');";
// mysqli_multi_query($con, $query);
// }


?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <title>Food Ordering System | Pay Now</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <link rel="stylesheet" href="assets/css/icons.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/red-color.css">
    <link rel="stylesheet" href="assets/css/yellow-color.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
</head>
<body itemscope>
<?php include_once('includes/header.php');?>


        <section>
            <div class="block">
				<div class="fixed-bg" style="background-image: url(assets/images/topbg.jpg);"></div>
                <div class="page-title-wrapper text-center">
					<div class="col-md-12 col-sm-12 col-lg-12">
						<div class="page-title-inner">
							<h1 itemprop="headline">Confirm Payment</h1>
						
				
						</div>
					</div>
                </div>
            </div>
        </section>

        <div class="bread-crumbs-wrapper">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php" title="" itemprop="url">Home</a></li>
                    <li class="breadcrumb-item"><a href="cart.php" title="" itemprop="url">My cart</a></li><li class="breadcrumb-item active">Confirm</li>
                </ol>
            </div>
        </div>

        <section>
            <div class="block gray-bg bottom-padd210 top-padd30">
                
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                            <div class="sec-box">
    							<div class="sec-wrapper">

    							



    <div class="col-md-12 col-sm-12 col-lg-12">

<div class="booking-table">
<table>
<thead>
<tr>
    <th>#</th>
    <th>Food Item</th>
    <th>Qty</th>
    <th>Per Unit Price</th>
       <th>Total</th>
          <th>Action</th>
</tr>
</thead>
<tbody>
    <?php 
$userid= $_SESSION['fosuid'];
$query=mysqli_query($con,"select tblorders.ID as frid,tblfood.Image,tblfood.ItemName,tblfood.ItemDes,tblfood.ItemPrice,tblfood.ItemQty,tblorders.FoodId,tblorders.FoodQty from tblorders join tblfood on tblfood.ID=tblorders.FoodId where tblorders.UserId='$userid' and tblorders.IsOrderPlaced is null");
$num=mysqli_num_rows($query);
if($num>0){
while ($row=mysqli_fetch_array($query)) {
?>

<tr>
    <td><img src="admin/itemimages/<?php echo $row['Image']?>" width="100" height="80" alt="<?php echo $row['ItemName']?>"></td>
<td>
    <a href="food-detail.php?fid=<?php echo $row['FoodId'];?>" title="" itemprop="url"><?php echo $row['ItemName']?></a>
</td>
<td><?php echo $qty=$row['FoodQty']?></td>
<td><?php echo $ppu=$row['ItemPrice']?></td>
<td><?php echo $total=$qty*$ppu;?></td>
<td><a href="cart.php?delid=<?php echo $row['frid'];?>" onclick="return confirm('Do you really want to delete?');";><i class="fa fa-trash" aria-hidden="true" title="Delete this food item"></i><a/></span></td>
</tr>

<?php $grandtotal+=$total;}?>
<thead>
<tr>
    <th colspan="4" style="text-align:center;">Grand Total</th>
<th style="text-align:center;"><?php echo $grandtotal;?></th>
<th></th>
</tr>
</thead>

<tr>
<td>Customer ID : </td>
<td><?php  echo "$userid" ?></td>
</tr>
<tr>
    <td colspan="3">
        <form method="post" action="PaytmKit/pgRedirect.php">
               
            <input type="hidden" id="ORDER_ID" tabindex="1" maxlength="20" size="20"
            name="ORDER_ID" autocomplete="off"
            value="<?php echo $orderid;  ?>">

           <input type="hidden" id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="<?php echo $userid;  ?>">

           <input type="hidden" id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="Retail">

           <input type="hidden" id="CHANNEL_ID" tabindex="4" maxlength="12"
            size="12" name="CHANNEL_ID" autocomplete="off" value="WEB">

            <input type="hidden" title="TXN_AMOUNT" tabindex="10"
            type="text" name="TXN_AMOUNT"
            value="<?php  echo $grandtotal;?>">

            <input type="hidden" id="flatbldgnumber" tabindex="4" maxlength="12"
            size="12" name="flatbldgnumber" autocomplete="off" value="<?php  echo $fnaobno;?>">

            <input type="hidden" id="streename" tabindex="4" maxlength="12"
            size="12" name="streename" autocomplete="off" value="<?php  echo $street;?>">  

            <input type="hidden" id="area" tabindex="4" maxlength="12"
            size="12" name="area" autocomplete="off" value="<?php  echo $area;?>">

            <input type="hidden" id="landmark" tabindex="4" maxlength="12"
            size="12" name="landmark" autocomplete="off" value="<?php  echo $lndmark;?>">

            <input type="hidden" id="city" tabindex="4" maxlength="12"
            size="12" name="city" autocomplete="off" value="<?php  echo $city;?>">

            <input type="submit" value="CheckOut" class="btn btn-danger" class="btn" name="checkvalue">
          </form>
    </td> 
</tr>   
</form>

<?php } else {?>
    <tr>
        <td colspan="6" style="color:red">You cart is empty</td>
    </tr>
<?php } ?>
</tbody>
</table>
</div>
                                    </div>

    							</div>
                            </div>
                        </div>
                    </div>
                </div><!-- Section Box -->
            </div>
        </section>

    <!-- red section -->
    <?php include_once('includes/footer.php');
include_once('includes/signin.php');
include_once('includes/signup.php');
      ?>
    </main><!-- Main Wrapper -->

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
    <script src="assets/js/google-map-int2.js"></script>
    <script src="assets/js/plugins.js"></script>
    <script src="assets/js/main.js"></script>
</body>	

</html>

