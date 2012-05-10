<?php require_once('header.php');
function getTodayMarkers(){
	$q = mysql_query("SELECT * FROM daily_goals NATURAL JOIN users NATURAL JOIN goals WHERE DATE(goal_date) = DATE(NOW())");
	$markers = array();
	while($r = mysql_fetch_assoc($q)){
		$goal = $r['goal'];
		$latlong = $r['loc_lat_long'];
		$ugid = $r['ugid'];
		$markers[] = array('latlong' => $latlong, 'goal' => $goal, 'ugid' => $ugid);
	}
	return $markers;
}

$markers = getTodayMarkers();
?>
<script type="text/javascript"
src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDqQ2DKa_aIVBicfjEGU89KYiLAAkbpdD4&sensor=true">
</script>
<script type="text/javascript">
function initialize() {
	var myOptions = {
		center: new google.maps.LatLng(0,0),
		zoom: 2,
		mapTypeId: google.maps.MapTypeId.SATELLITE
	};

	var map = new google.maps.Map(document.getElementById("map_canvas"),
	myOptions);

	<?php
	// echo "var $varname" . 'marker = new google.maps.Marker({
		//       position: '.$varname.',
		//       map: map,
		//       title:"'.$goal.'"});' . "\r";		
		// echo "$varname"."marker.setMap(map);\r";
		echo "var pins = [";
		foreach($markers as $marker){
			$latlong = $marker['latlong'];
			//$ugid = $marker['ugid'];
			//$varname = "m$ugid";
			$goal = addslashes($marker['goal']);
			if(!empty($latlong)){
				echo "['$goal', $latlong],";
				//echo "new google.maps.LatLng('$latlong')\r";
			}
		}
		echo "];";
		?>

		var marker, i;

		for (i = 0; i < pins.length; i++) {  
			marker = new google.maps.Marker({
				position: new google.maps.LatLng(pins[i][1], pins[i][2]),
				map: map,
				title: pins[i][0]
			});
		}
	}
	</script>
	<div  id="map_canvas" style="width:100%; height:100%"></div>
	<?php require_once('footer.php'); ?>