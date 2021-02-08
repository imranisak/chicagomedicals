<?php
require "../../includes/database.php";
require "../../includes/flashMessages.php";
//require "../../includes/database.php";
//Gets the number of approved clinics
$SQLnumberOfClinics="SELECT COUNT(*) FROM clinics WHERE approved = true";
$numberOfclinics=$databaseConnection->query($SQLnumberOfClinics);
$numberOfclinics=mysqli_fetch_row($numberOfclinics);
$numberOfclinics=$numberOfclinics[0];
//How many clinics to show
$clinicsPerPage=3;
$totalPages=ceil($numberOfclinics / $clinicsPerPage);
//echo "total pages: ".$totalPages;
//Get the current page or set a default
if(isset($_GET['currentpage']) && is_numeric($_GET['currentpage'])) $currentpage=(int)$_GET['currentpage'];//Uses int, so some fool can't access page like 2.3
else $currentpage = 1;
if($currentpage>$totalPages || $currentpage<1) $currentpage=1;
$offset = ($currentpage - 1) * $clinicsPerPage;
//Loads the clinics. If no services are selected, load all clinics
if(isset($_GET['services'])) $services=strtolower($_GET['services']);
else $services=false;
if($services){
	$services=filter_var($services, FILTER_SANITIZE_STRING);
	$services=strtolower($services);
	//$services=ucfirst($services);
	$SQLselectClinics="SELECT * FROM clinics WHERE services like '%$services%' AND approved = true ORDER BY featured DESC, name ASC LIMIT $offset, $clinicsPerPage";
}
else {
	$SQLselectClinics="SELECT * FROM clinics WHERE approved = true  ORDER BY featured DESC, name ASC LIMIT $offset, $clinicsPerPage";
	$services=false;
}
$clinics=$databaseConnection->query($SQLselectClinics);
echo $databaseConnection->error;
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
	<?php require '../../includes/header.php'; ?>

</head>
<body>
	<?php require '../../includes/navbar.php'; ?>
	<?php 
	if($msg->hasMessages()) $msg->display();
	foreach($clinics as $clinic){
		if($clinic['approved']){
			$featured=$clinic['featured'];
			$featuredImage=unserialize($clinic['images']);
			//$featuredImage=explode(",", $featuredImage);
			$featuredImage=$featuredImage[0];
			//$featuredImage=str_replace(['[', ']', '"'], "", $featuredImage);
			if ($featured) echo "
			<div class='container'>
			    <div class='row clinicBox featured'>
			        <div class='col-md-3'><img src='".$featuredImage."' class='featuredImage'></div>
			        <div class='col-md-9'>
			        	<h3 class='clinicNameInBox'>FEATURED: ".$clinic['name']."</h3>
			        	<p class='ownerInBox'>Owner: ".$clinic['owner']."</p>
			        	<p class='ratingInBox'>User rating: ".$clinic['rating']." / 5 (based on ".$clinic['numberOfReviews']." reviews)</p>
                        <div class='stars' style='--rating: ".$clinic['rating'].";' aria-label='Rating of this product is ".$clinic['rating']." out of 5'></div><br>
			        	<a href='/pages/clinics/clinic.php?ID=".$clinic['ID']."'><button class='btn btn-primary'><span class='glyphicon glyphicon-menu-right' aria-hidden='true'></span>Read more</button></a>
			        </div>
			    </div>
			</div>";
			else echo "
			<div class='container'>
			    <div class='row clinicBox'>
			        <div class='col-md-3'><img src='".$featuredImage."' class='featuredImage'></div>
			        <div class='col-md-9'>
			        	<h3 class='clinicNameInBox'>".$clinic['name']."</h3>
			        	<p class='ownerInBox'>Owner: ".$clinic['owner']."</p>
			        	<p class='adrressInBox'>Address: ".$clinic['address']."</p>
			        	<p class='ratingInBox'>User rating: ".$clinic['rating']." / 5 (based on ".$clinic['numberOfReviews']." reviews)</p>
                        <div class='stars' style='--rating: ".$clinic['rating'].";' aria-label='Rating of this product is ".$clinic['rating']." out of 5'></div><br>
			        	<a href='/pages/clinics/clinic.php?ID=".$clinic['ID']."'><button class='btn btn-primary'><span class='glyphicon glyphicon-menu-right' aria-hidden='true'></span>Read more</button></a>
			        </div>
			    </div>
			</div>";
		}
	}
	?>
	<?php 
	if ($currentpage > 1) {
   // show << link to go back to page 1
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=1&services=$services'><<</a> ";
   // get previous page num
   $prevpage = $currentpage - 1;
   // show < link to go back to 1 page
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$prevpage&services=$services'><</a> ";
} // end if
// range of num links to show
$range = 2;

// loop to show links to range of pages around current page
for ($x = ($currentpage - $range); $x < (($currentpage + $range)  + 1); $x++) {
   // if it's a valid page number...
   if (($x > 0) && ($x <= $totalPages)) {
      // if we're on current page...
      if ($x == $currentpage) {
         // 'highlight' it but don't make a link
         echo " [<b>$x</b>] ";
      // if not current page...
      } else {
         // make it a link
         echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$x&services=$services'>$x</a> ";
      } // end else
   } // end if 
} // end for

// if not on last page, show forward and last page links        
if ($currentpage != $totalPages) {
   // get next page
   $nextpage = $currentpage + 1;
    // echo forward link for next page 
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$nextpage&services=$services'>></a> ";
   // echo forward link for lastpage
   echo " <a href='{$_SERVER['PHP_SELF']}?currentpage=$totalPages&services=$services'>>></a> ";
} // end if
/****** end build pagination links ******/
?>
	<?php require '../../includes/footer.php';
	$databaseConnection->close();?>
</body>
</html>