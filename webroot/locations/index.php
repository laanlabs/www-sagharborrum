<?php
// include config file
include_once './includes/config.inc.php';

// list of available distances
$distances = array(
	100=>'100 Miles',
    50=>'50 Miles',
	10=>'10 Miles',
);


if(isset($_REQUEST['ajax'])) {
	
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='get_nearby_stores') {
		
		if(!isset($_REQUEST['lat']) || !isset($_REQUEST['lng'])) {
			
			echo json_encode(array('success'=>0,'msg'=>'Coordinate not found'));
		exit;
		}
		
		// support unicode
		mysql_query("SET NAMES utf8");

		// category filter
		if(!isset($_REQUEST['products']) || $_REQUEST['products']==""){
			$category_filter = "";
		} else {
			$category_filter = " AND cat_id='".$_REQUEST['products']."'";
		}
		
		$sql = "SELECT *, ( 3959 * ACOS( COS( RADIANS(".$_REQUEST['lat'].") ) * COS( RADIANS( latitude ) ) * COS( RADIANS( longitude ) - RADIANS(".$_REQUEST['lng'].") ) + SIN( RADIANS(".$_REQUEST['lat'].") ) * SIN( RADIANS( latitude ) ) ) ) AS distance FROM stores WHERE status=1 AND approved=1 ".$category_filter." HAVING distance <= ".$_REQUEST['distance']." ORDER BY distance ASC LIMIT 0,100";
	
		
		
		echo json_stores_list($sql);
	}
exit;
}


$errors = array();

if($_REQUEST) {
	if(isset($_REQUEST['address']) && empty($_REQUEST['address'])) {
		$errors[] = 'Please enter your address';
	} else {

			
		$google_api_key = '';

		$region = 'us';

		
		
		$xml = convertXMLtoArray($tmp);
		
		if($xml['Response']['Status']['code']=='200') {
			
			$coords = explode(',', $xml['Response']['Placemark']['Point']['coordinates']);
			
			if(isset($coords[0]) && isset($coords[1])) {
				
				$data = array(
					'name'=>$v['name'],
					'address'=>$v['address'],
					'latitude'=>$coords[1],
					'longitude'=>$coords[0]
				);

				
				$sql = "SELECT *, ( 3959 * ACOS( COS( RADIANS(".$coords[1].") ) * COS( RADIANS( latitude ) ) * COS( RADIANS( longitude ) - RADIANS(".$coords[0].") ) + SIN( RADIANS(".$coords[1].") ) * SIN( RADIANS( latitude ) ) ) ) AS distance FROM stores WHERE status=1 HAVING distance <= ".$db->escape($_REQUEST['distance'])." ORDER BY distance ASC  LIMIT 0,60";
				
				$stores = $db->get_rows($sql);

				
				if(empty($stores)) {
					$errors[] = 'Stores with address '.$_REQUEST['address'].' not found.';
				}
			} else {
				$errors[] = 'Address not valid';
			}
		} else {
			$errors[] = 'Entered address'.$_REQUEST['address'].' not found.';
		}
	}
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Sag Harbor Rum Locations</title>
	 <meta name="keywords" content="Sag Harbor Rum Locations" />
     <meta name="description" content="Sag Harbor Rum" />
	 <link rel="shortcut icon" href="/images/favicon.ico" />
	 
	  <?php include ROOT."themes/meta_mobile.php"; ?>
	  

	
	
	 
	
	
	<script>
	function changeLang(v){
	document.location.href="?langset="+v;
	}
	</script>

</head>
<body id="super-store-finder">


<!-- Start Head Container -->
		
<div class=" text-center">
				<h1 class="" style="padding-left:30px; padding-top:10px"><a href="/" class=''>&#8592 Back to Sag Harbor Rum</a></h1>

</div>
		

<!-- Head Container END -->
		
		<div class="clear"></div><!-- CLEAR -->
		
		<!-- Start Header Break Line -->
		<div class="container_12">
			<hr class="grid_12"></hr>
		</div>
		<!-- Header Break Line END -->
		
		<div class="clear"></div><!-- CLEAR -->
	

	
	<div class="clear"></div>
		
	<!-- Start Container 12 -->
	<div id="main_content" class="container_12">
	
		<div id="main">
		<div class="width-container">

			<div id="container-sidebar">
				
				
				
				
				
				<div class="content-boxed">
				
					
					
					
					
	
					
					<div id="map-container">

						<div id="clinic-finder" class="clear-block">
						<div class="links"></div>
			
						<form method="post" action="./index.php" accept-charset="UTF-8" method="post" id="clinic-finder-form" class="clear-block" class="clear-block">
							<table style="width:100%">
							<tr><td width="95%" style="padding-right:20px;">
							<div class="form-item" id="edit-gmap-address-wrapper">
							 <label for="edit-gmap-address"><?php echo $lang['PLEASE_ENTER_YOUR_LOCATION']; ?>: </label>
							 <input type="text" maxlength="128" name="address" id="address" size="60" value="" class="form-text" autocomplete="off" />
							</div>
							</td>
							</tr>
							<tr>
							
							<td  width="95%">
							<?php 
							// support unicode
							mysql_query("SET NAMES utf8");
							$cats = $db->get_rows("SELECT categories.* FROM categories WHERE categories.id!='' ORDER BY categories.cat_name ASC");

							?>
							<div class="form-item" id="edit-products-wrapper">
							 
							 <select name="products" class="form-select" id="edit-products" ><option value=""><?php echo $lang['SSF_ALL_CATEGORY']; ?></option>
							 <?php if(!empty($cats)): ?>
								<?php foreach($cats as $k=>$v): ?>
								<option value="<?php echo $v['id']; ?>"><?php echo $v['cat_name']; ?></option>
								<?php endforeach; ?>
								<?php endif; ?>
							 </select>
							 
							
							
							</div>
							
							</tr>
							<tr><td align="center" nowrap><input type="submit" name="op" id="edit-submit" value="<?php echo $lang['FIND_STORE']; ?>" class="btn btn-dark" />
							<input type="hidden" name="form_build_id" id="form-0168068fce35cf80f346d6c1dbd7344e" value="form-0168068fce35cf80f346d6c1dbd7344e"  />
							<input type="hidden" name="form_id" id="edit-clinic-finder-form" value="clinic_finder_form"  />
							
				
							
							</td></tr>
							</table>
							


					  <div id="map_canvas"><?php echo $lang['JAVASCRIPT_ENABLED']; ?></div>
					  <div id="results">        
						<h2 class="title-bg" style="padding-bottom:10px !important; "><input type="radio" id="distance" name="distance" value="50" > 50 m <input type="radio" id="distance" name="distance" value="100" checked> 100 m</h2>
						<p class="distance-units">
						  <label class="km unchecked" units="km">
							<input type="radio" name="distance-units" value="kms" checked="unchecked" /><?php echo $lang['KM']; ?>
						  </label>
						  <label class="miles" units="miles">
							<input type="radio"  name="distance-units" value="miles" /><?php echo $lang['MILES']; ?>
						  </label>
						</p>
						<ol style="display: block; " id="list"></ol>
					  </div>
					  
					  
					    </form>
						
					  
					  <div id="direction">
					  <h2 class="title-bg" style="padding-bottom:10px !important; ">Directions</h2>
					  <form method="post" id="direction-form">
					  
					  <p>
					  <table><tr>
					  <td>Origin:</td><td><input id="origin-direction" name="origin-direction" class="orides-txt" type=text /></td>
					  </tr>
					  <tr>
					  <td>Destination:</td><td><input id="dest-direction" name="dest-direction" class="orides-txt" type=text readonly /></td>
					  </tr>
					  </table>
					  <div id="get-dir-button" class="get-dir-button"><input type=submit id="get-direction" class="btn" value="Get Direction"> <a href="javascript:directionBack()">Back</a></div></p>
					  </form>
					  </div>
					  
	</div>


	<script>
			$('#address').val(geoip_city()+", "+geoip_country_name());
	</script>
					</div>
					
						
						<div class="clearfix"></div>

					</div>
					

			</div><!-- close #container-sidebar -->
			

			
		<div class="clearfix"></div>
		</div><!-- close .width-container -->
	</div><!-- close #main -->
  
   <center>


		
		

		<div class="clear"></div><!-- CLEAR -->
				
  <br><br>

<!-- IE fix -->
<script type="text/javascript"> Cufon.now(); </script>	



<?php include ROOT."themes/footer.inc.php"; ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-48709338-1', 'sagharborrum.com');
  ga('send', 'pageview');

</script> 

</body>
</html>