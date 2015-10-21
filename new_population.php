
<?php

// restore session variables from the previous PHP script to be
// used for the production of new population for every step of growth

if(!session_id())session_start();

 // Implementation for the rules of game of life

function liveordie($board, $size) {
  for($x = 0; $x < $size; $x++) {
    for($y = 0; $y < $size; $y++) {
    // find all dead cells of the population
     if ($board[$x][$y]==0){        $neighbours=0;
        for($i=-1;$i<2;$i++){        	$idx=$x+$i;
        	$idy=$y+$i;
        	if ($idx<0) $idx=0;
        	if ($idy<0) $idy=0;
        	if ($idx>$size-1) $idx=$size-1;
        	if ($idy>$size-1) $idy=$size-1;
        	if ($board[$idx][$idy]==1)
        	    $neighbours++;
      }
        for($j=-1;$j<2;$j+=2){            $idx=$x-$j;
        	$idy=$y+$j;
        	if ($idx<0) $idx=0;
        	if ($idy<0) $idy=0;
        	if ($idx>$size-1) $idx=$size-1;
        	if ($idy>$size-1) $idy=$size-1;
            if ($board[$idx][$idy]==1)
        	    $neighbours++;
      }
        // if the neighbouring cells are three then
        // a new cell is born in place of the dead one,
        //otherwise remains dead for the next stages of growth

        if ($neighbours==3) $newboard[$x][$y]=1;
        else $newboard[$x][$y]=0;
     }

       // find all living cells of the population

     elseif ($board[$x][$y]==1){
        $neighbours=0;
        for($i=-1;$i<2;$i++){
        	$idx=$x+$i;
        	$idy=$y+$i;
        	if ($idx<0) $idx=0;
        	if ($idy<0) $idy=0;
        	if ($idx>$size-1) $idx=$size-1;
        	if ($idy>$size-1) $idy=$size-1;
        	if ($board[$idx][$idy]==1)
        	    $neighbours++;
      }
        for($j=-1;$j<2;$j+=2){
            $idx=$x-$j;
        	$idy=$y+$j;
        	if ($idx<0) $idx=0;
        	if ($idy<0) $idy=0;
        	if ($idx>$size-1) $idx=$size-1;
        	if ($idy>$size-1) $idy=$size-1;
            if ($board[$idx][$idy]==1)
        	    $neighbours++;
      }

        // if the neighbouring cells are less than 2 then
        // the cell dies by underpopuation,
        // if the neighbouring cells are more than 3 then
        // the cell dies by overpopulation
        // if the neighbouring cells are two or three then
        // the cell remains alive for the next generation

        if ($neighbours<2) $newboard[$x][$y]=0;
        elseif ($neighbours>3) $newboard[$x][$y]=0;
        elseif (($neighbours==3)||($neighbours==2)) $newboard[$x][$y]=1;

     }
    }
  }
  //store the new generation of population into session variable
  //for later use (every stage of growth)

  $_SESSION['board'] = json_encode($newboard);
  return $newboard;
}

 // restore session variables into main script for production of the
 // next generation via the PHP function

$size = $_SESSION['size'];
$board = json_decode($_SESSION['board'],true);

$board = liveordie($board, $size);

//Use of PHP GD Graphics library by Thomas Boutell to produce the
//simulation for the game of life
// gray background, living cells with black and dead cells with red

$myImage = imagecreate(510, 510);
$myGray = imagecolorallocate($myImage, 204, 204, 204);
$myBlack = imagecolorallocate($myImage, 0, 0, 0);
$myRed = imagecolorallocate($myImage, 204, 0, 0);

//implementation of this animation

for($x = 0; $x < $size; $x++) {
   for($y = 0; $y < $size; $y++) {
    if($board[$x][$y] == 1) {
      imagesetpixel($myImage,$x,$y,$myBlack);
    } else {
     imagesetpixel($myImage,$x,$y,$myRed);
    }
  }
}

ob_start();
imagepng($myImage);
printf('<img src="data:image/png;base64,%s"/>',base64_encode(ob_get_clean()));
imagedestroy($myImage);

?>