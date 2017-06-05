<html>
<head>
	<title>Add watermark over image on upload - trinity tuts canyon</title>
</head>

<body>
	<form action="" method="post" enctype="multipart/form-data">
		<label>Image: </label>
		<input type="file" name="image" value="">
		<select name="option">
			<option value="">Select option</option>
			<option value="textW">Text Water Mark</option>
			<option value="imageW">Image watermark</option>
		</select>
		<input type="submit" value="Upload">
	</form>
<?php
	if(isset($_FILES['image']['name'])){
		// Validating Type of image
		switch($_FILES['image']['type']){
			case 'image/jpeg':
			case 'image/png':
			case 'image/jpg':
				// Add more validation if you like
				if(getimagesize($_FILES['image']['tmp_name']) < (1024*1024*1024*1024)){
					echo 'Image size is greater than 2MB';
				}
				elseif(empty($_POST['option'])){
					echo 'Please select option';
				}else{
					// Create new name for your image
					list($txt, $ext) = explode(".", $_FILES['image']['name']);
					$newName = rand(0, 9999).'.'.$ext;
					$up = copy($_FILES['image']['tmp_name'], $newName);
						if($up == true){
							
							// Check which type of water mark is requested 
							if($_POST['option'] == 'textW'){
								// Add text watermark over image
								$watermark = "WATERMARK IN PHP SINGLE FILE";
								textwatermark($newName, $watermark, $newName);							
							}elseif($_POST['option'] == 'imageW'){
								// Add image watermark 
								$WaterMark = 'watermark.png';
								watermarkImage ($newName, $WaterMark, $newName, 50);
							}
							echo '<img src="'.$newName.'" class="preview" width="500">';
						}else{
							echo 'Error uploading image';
						}
					}
			break;
			default:
				echo 'Please select valid file for upload';
		}
	}
	
// Function to add text water mark over image
function textwatermark($src, $watermark, $save=NULL) { 
 list($width, $height) = getimagesize($src);
 $image_p = imagecreatetruecolor($width, $height);
 $image = imagecreatefromjpeg($src);
 imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width, $height); 
 $txtcolor = imagecolorallocate($image_p, 255, 255, 255);
 $font = 'monofont.ttf';
 $font_size = 50;
 imagettftext($image_p, $font_size, 0, 50, 150, $txtcolor, $font, $watermark);
 if ($save<>'') {
 imagejpeg ($image_p, $save, 100); 
 } else {
 header('Content-Type: image/jpeg');
 imagejpeg($image_p, null, 100);
 }
 imagedestroy($image); 
 imagedestroy($image_p); 
}

// Function to add image watermark
function watermarkImage($SourceFile, $WaterMark, $DestinationFile=NULL, $opacity) { 
 
 $main_img = $SourceFile; 
 $watermark_img = $WaterMark; 
 $padding = 3; 
 $opacity = $opacity; 
 
 $watermark = imagecreatefrompng($watermark_img); // create watermark
 $image = imagecreatefromjpeg($main_img); // create main graphic
 
 if(!$image || !$watermark) die("Error: main image or watermark could not be loaded!");
 
 $watermark_size = getimagesize($watermark_img);
 $watermark_width = $watermark_size[0]; 
 $watermark_height = $watermark_size[1]; 
 
 $image_size = getimagesize($main_img); 
 $dest_x = $image_size[0] - $watermark_width - $padding; 
 $dest_y = $image_size[1] - $watermark_height - $padding;
 
 
 // copy watermark on main image
 imagecopymerge($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, $opacity);
 if ($DestinationFile<>'') {
	imagejpeg($image, $DestinationFile, 100); 
 } 
 else {
	 header('Content-Type: image/jpeg');
	 imagejpeg($image);
 }
 imagedestroy($image); 
 imagedestroy($watermark); 
}
?>
</body>
</html>