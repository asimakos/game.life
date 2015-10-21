
<?php

//if session not created, create a new session to store
//settings regarding simulation for game of life

if(!session_id())session_start();

// Initialize population for game of life by producing random data into
// a squared grid:
// 0: for dead cells and 1: for live cells

$size =500;
   for($x = 0; $x < $size; $x++) {
     for($y = 0; $y < $size; $y++) {
       $board[$x][$y] = rand(0,1);
     }
   }

 // Store simulation settings (size and grid data)
 // for next stages of simulation into session variables

 $_SESSION['size'] = $size;
 $_SESSION['board'] = json_encode($board);

?>


<html>

<head>
  <title>Game of Life Simulation</title>
  <script type="text/javascript" src="js/jquery.min.js"></script>
 </head>

<body>

<div id="simulation"> </div>

<script type="text/javascript">

// produce new population after 1 second approximately for every step
//of growth using jQuery

  $(document).ready(function() {
     var intervalID = setInterval("production_data()",1000);
});

// the new population is produced using PHP code according to the rules
// for game of life mentioned in the calling script and displayed
// into the simulation frame of the Web page produced

 function production_data() {
     $.ajax({url:"new_population.php",success:function(result){
     $("#simulation").html(result);
   }});
  }
</script>

<?php

echo "<br/>";
echo "Alive cells: black <br/>";
echo "Dead cells: red <br/>";
echo "Background: gray";

?>

</body>

</html>