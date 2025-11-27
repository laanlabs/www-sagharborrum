<?php
// include Config File
include_once './includes/config.inc.php';
// Authenticate user login
auth();


if(isset($_GET['action']) && $_GET['action']=='approve') {
	
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_INVALID_ID']);
		redirect(ROOT_URL.'stores.php');
	}

	
	$db = db_connect();
	if($db->update('stores',array('approved'=>1),$_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'good','msg'=>$lang['ADMIN_STORE_APPROVED']);
	} else {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_APPROVE_FAILED']);
	}
redirect(ROOT_URL.'stores.php');
}


if(isset($_GET['action']) && $_GET['action']=='delete') {
	
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_INVALID_ID']);
		redirect(ROOT_URL.'stores.php');
	}

	
	$db = db_connect();
	if($db->update('stores',array('status'=>0),$_GET['id'])) {
		$_SESSION['notification'] = array('type'=>'good','msg'=>$lang['ADMIN_STORE_DELETED']);
	} else {
		$_SESSION['notification'] = array('type'=>'bad','msg'=>$lang['ADMIN_DELETE_STORE_FAILED']);
	}
redirect(ROOT_URL.'stores.php');
}


$db = db_connect();

$limit = 50; 

if (isset($_GET["page"])) { $page  = $_GET["page"]; } else { $page=1; };  
$start_from = ($page-1) * $limit;  

$storefilter = "";

if(isset($_REQUEST['search'])){
  $storefilter = " AND ( lower(stores.name) LIKE lower('%".$_REQUEST['search']."%')
   OR lower(stores.address) LIKE lower('%".$_REQUEST['search']."%')
   OR lower(stores.website) LIKE lower('%".$_REQUEST['search']."%') 
   OR lower(stores.name) LIKE lower('%".$_REQUEST['search']."%') 
   OR lower(stores.telephone) LIKE lower('%".$_REQUEST['search']."%') )";
}

mysql_query("SET NAMES utf8"); 
$sqlstmt = "SELECT stores.* FROM stores WHERE stores.status=1 $storefilter ORDER BY stores.name  ASC LIMIT $start_from, $limit";
//die();
$stores = $db->get_rows($sqlstmt);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php echo $lang['STORE_FINDER']; ?> - <?php echo $lang['ADMIN_STORE_LIST']; ?></title>
	<?php include 'header.php'; ?>
</head>
<body id="stores">
	<div id="wrapper">
		<div id="header">
			
			<?php include 'nav.php'; ?>
		</div>
		<div id="main">
		
			<h2><?php echo $lang['ADMIN_STORE_LIST']; ?></h2>
			<?php echo notification(); ?>
			<div class="searchbar"><form method="POST" action="stores.php"><input type=text placeholder="Search" class="search-query span3" value="<?php if(isset($_REQUEST['search'])) { echo $_REQUEST['search']; } ?>" name="search" id="search"> <div class="icon-search"></div></form></div>
			<table class="table table-bordered" style="width:100%;">
				<thead>
				<tr>
					<th><?php echo $lang['ADMIN_NAME']; ?></th><th><?php echo $lang['ADMIN_ADDRESS']; ?></th><th><?php echo $lang['ADMIN_TELEPHONE']; ?></th><th><?php echo $lang['ADMINISTRATOR_EMAIL']; ?></th><th><?php echo $lang['ADMIN_WEBSITE']; ?></th><th class="acenter"><?php echo $lang['ADMIN_APPROVED']; ?></th><th class="actions"><?php echo $lang['ADMIN_ACTION']; ?></th>
				</tr>
				</thead>
				<tbody>
				<?php if(!empty($stores)): ?>
					<?php foreach($stores as $k=>$v): ?>
					<tr class='<?php echo ($k%2==0) ? 'odd':'even'; ?>'>
						<td><?php echo $v['name']; ?></td>
						<td><?php echo $v['address']; ?></td>
						<td><?php echo $v['telephone']; ?></td>
						<td><?php echo $v['email']; ?></td>
						<td><?php echo $v['website']; ?></td>
						<td class="acenter"><?php echo ($v['approved']) ? 'Yes' : 'No' ; ?></td>
						<td class="actions">
							<a href='./stores_edit.php?id=<?php echo $v['id']; ?>'><i class="icon-pencil"></i></a>
							<a href='javascript:delItem(<?php echo $v['id']; ?>)' class="confirm_delete"><i class="icon-trash"></i></a>
							<?php if(!$v['approved']) : ?>
							<a href='?action=approve&amp;id=<?php echo $v['id']; ?>'><?php echo $lang['ADMIN_APPROVE']; ?></a>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				<?php else: ?>
					<tr>
						<td colspan="7"><?php echo $lang['ADMIN_NO_STORES']; ?></td>
					</tr>
				<?php endif; ?>
				</tbody>
			</table>

			
		<?php  
			$sql = "SELECT COUNT(id) FROM stores WHERE status=1 $storefilter";  
			$rs_result = mysql_query($sql);  
			$row = mysql_fetch_row($rs_result);  
			$total_records = $row[0];  
			$total_pages = ceil($total_records / $limit);  
			$active = "";

			$pagLink = "<div class='pagination'><ul>";  
			for ($i=1; $i<=$total_pages; $i++) { 
					if(isset($_GET['page'])){
						if($i==$_GET["page"]){
						  $active="class='active'";
						} else {
						   $active="";
						}
					}
						$rf = "";
						if(isset($_REQUEST['search'])) { $rf = "&search=".$_REQUEST['search']; } 
						 $pagLink .= "<li ".$active."><a href='stores.php?page=".$i.$rf."'>".$i."</a></li>";   
			};  
			echo $pagLink . "</ul></div>";  
		?>  
	
		</div>
	</div>
	
	<script>
	function delItem(id){

	var a = confirm("<?php echo $lang['ADMIN_DELETE_CONFIRM']; ?>");
		if(a){
		document.location.href='?action=delete&id='+id;
		}
	
	}
	</script>
	<?php include '../themes/footer.inc.php'; ?>
</body>
</html>