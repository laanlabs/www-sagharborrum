<?php
// include config file
include_once './includes/config.inc.php';
include './includes/validate.php';

validate_request_add_store();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>Super Store Finder - Add Store</title>
	<?php include ROOT."themes/meta_others.php"; ?>
</head>
<body id="add_edit_body">
	<div id="wrapper">
		<div id="header">
			
			<?php include ROOT."themes/nav.inc.php"; ?>
		</div>

		<div id="main">
			<h2><?php echo $lang['ADMIN_ADD_STORE']; ?></h2>
			<p>
				<?php echo $lang['REQUEST_STORE_ADMIN_APPROVAL']; ?>
			</p>
			
			<?php echo notification(); ?>
			<?php if(isset($errors)): ?>
			<div class="alert alert-block alert-error fade in">
			<ul>
				<?php foreach($errors as $k=>$v): ?>
				<li><?php echo $v; ?></li>
				<?php endforeach; ?>
			</ul>
			</div>
			<?php endif; ?>

			<div id="map_canvas" class="newstore_map"></div>
			<div id="ajax_msg"></div>

			<div style="display:block; clear:both">
			<form method='post' id='form_new_store' enctype="multipart/form-data">
				<fieldset>
					<legend><?php echo $lang['ADMIN_ADD_STORE']; ?></legend>
					
						<label><?php echo $lang['ADMIN_NAME']; ?>: <span class='required'>*</span></label>
						<input type='text' name='name' id='name' value='<?php echo $fields['name']['value']; ?>' />
					
					
						<label><?php echo $lang['ADMIN_ADDRESS']; ?>: <span class='required'>*</span></label>
						<input type='text' name='address' id='address' value='<?php echo $fields['address']['value']; ?>' />
						<span><?php echo $lang['ADMIN_LAT_LANG_AUTO']; ?></span>
					
					
						<label><?php echo $lang['ADMIN_TELEPHONE']; ?>:</label>
						<input type='text' name='telephone' id='telephone' value='<?php echo $fields['telephone']['value']; ?>' />
					
					
						<label><?php echo $lang['ADMINISTRATOR_EMAIL']; ?>:</label>
						<input type='text' name='email' id='email' value='<?php echo $fields['email']['value']; ?>' />
					
					
						<label><?php echo $lang['ADMIN_WEBSITE']; ?>:</label>
						<input type='text' name='website' id='website' value='<?php echo $fields['website']['value']; ?>' />
					
						<label><?php echo $lang['ADMIN_DESCRIPTION']; ?>:</label>
						<textarea name='description' id='description' rows="5" cols="40"><?php echo $fields['description']['value']; ?></textarea>
					
						<label><?php echo $lang['ADMIN_STORE_IMAGE']; ?>:</label>
						<input type="file" name="file" id="file" />
						<span><?php echo $lang['ADMIN_THUMB_AUTO']; ?> </span>
					

					
					<?php if(!empty($images)): ?>
					<div class="input">
						<?php foreach($images as $k=>$v): ?>
						<div class="image">
							<img src="<?php echo $v; ?>" alt="Image" />
							<button type="submit" name="delete_image[<?php echo basename($v); ?>]" id="delete_image" class="btn btn-danger" value="<?php echo basename($v); ?>"><?php echo $lang['ADMIN_DELETE']; ?></button>
						</div>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>

					
					<div class='input first'>
						<label><?php echo $lang['ADMIN_LATITUDE']; ?>:</label>
						<input type='text' name='latitude' id='latitude' value='<?php echo $fields['latitude']['value']; ?>' />
					</div>
					<div class='input second'>
						<label><?php echo $lang['ADMIN_LONGITUDE']; ?>:</label>
						<input type='text' name='longitude' id='longitude' value='<?php echo $fields['longitude']['value']; ?>' />
					</div>
					
					
					<div class='input buttons'>
						<button type='submit' name='save' class="btn" id='save'><?php echo $lang['ADMIN_SAVE']; ?></button>
					</div>
				</fieldset>
			</form>
			</div>
			
		</div>
	</div>
<?php include ROOT."themes/footer.inc.php"; ?>
</body>
</html>