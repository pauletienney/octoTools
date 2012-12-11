<?php
 //Set the Content Type
  //header('Content-type: image/jpeg');


$file = 'marker.png';

  // Create Image From Existing File
  $jpg_image = imagecreatefrompng($file);


$info = getimagesize($file); 
$w = $info[0];
$h = $info[1];


  // Allocate A Color For The Text
  $white = imagecolorallocate($jpg_image, 255, 255, 255);

  // Set Path to Font File
  $font_path = 'champ.TTF';

  // Set Text to Be Printed On Image
  $text = "This is a sunset!";

  // Print Text On Image
  imagettftext($jpg_image, 25, 0, 75, 300, $white, $font_path, $text);















  // Send Image to Browser
  //imagepng($jpg_image);

  // Clear Memory
  imagedestroy($jpg_image);

?>