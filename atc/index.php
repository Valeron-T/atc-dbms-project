<?php
  $servername = "h1.host.filess.io";
  $username = "user_atc_8f575f94d2";
  $password = "a822fece4943ccf3b2838eb99dd70d8ba84759d6";
  $db_name = "atc_34dfe95cfa";

  // Create connection
  $conn = new mysqli($servername, $username, $password, $db_name);

  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // // Select airports
  // $airports = "Select * from airport";
  // $result = $conn->query($airports);

  // if ($result->num_rows > 0) {
  //   // output data of each row
  //   while($row = $result->fetch_assoc()) {
  //     echo "IATA Code: " . $row["iata_code"]. " , Name: " . $row["name"]. ", Country: " . $row["iso_country"]. "<br>";
  //   }
  // } else {
  //   echo "0 results";
  // }

  echo "Connected successfully";
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <meta http-equiv="refresh" content="10" >  -->

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

	<link rel="stylesheet" type="text/css" href="index.css">

	<title>Timetable</title>
</head>
<body>
	<div class="container-fluid">
		<div class="container" id="t-t-main-area">
			<div class="row t-t-border-bottom">
				<h3 class="text-center t-t-color-white" id="t-t-main-text">Flight Schedule</h3>
			</div>

			<div class="container">

				<div class="row">
					<div class="col p-3">
						<h3 class="text-center t-t-color-white t-t-border-bottom" id="arrival">Arrivals</h3>

            <?php
              $flights = "Select * from flights where deparr = 'ARR' order by time asc";
              $result = $conn->query($flights);
            
              if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                $s = $row['time'];
                $dt = new DateTime($s);

                $date = $dt->format('d-m-Y');
                $time = $dt->format('h:i:s A');
                echo"
                <div class='container t-t-border t-t-border-bottom t-t-border-left-and-right' id='arr-row-1'>
                  <div class='row'>
                    <div class='col p-1 text-uppercase fw-bold t-t-color-moccasin'>$time</div>
                    <div class='col p-1 text-uppercase fw-bold t-t-color-springgreen'>".$row['status']."</div>
                    <div class='col p-1 text-uppercase fw-bold t-t-color-greenyellow'>".$row['aircraft_type']."</div>
                    <div class='col p-1 text-uppercase'></div>
                  </div>
                  <div class='row'>
                    <div class='col p-1 text-uppercase t-t-font-size-smaller t-t-color-yellow'>From</div>
                    <div class='col p-1 text-uppercase'></div>
                    <div class='col p-1 text-uppercase  fw-bold t-t-color-white'>".$row['airline_code']." ".$row['flight_no']."</div>
                    <div class='col p-1 text-uppercase fw-bold t-t-color-white'>
                      <img class='airline-logo' src='https://daisycon.io/images/airline/?width=100&height=50&color=000000&iata=".$row['airline_code']."'></img>
                    </div>
                  </div>
                  <div class='row'>
                    <div class='col p-1 text-uppercase t-t-font-size-smaller t-t-color-yellow'>".$row['origin']."</div>
                    <div class='col p-1 text-uppercase'></div>
                    <div class='col p-1 text-uppercase'></div>
                    <div class='col p-1 text-uppercase'></div>
                  </div>
                </div>";
                }
              } else {
                echo "0 results";
              }
            ?>
          </div>

					<div class="col p-3">
						<h3 class="text-center t-t-color-white t-t-border-bottom"  id="departure">Departures</h3>


						<!--Departure-----Row---1----STARTS-->
						<?php
              $flights = "Select * from flights where deparr = 'DEP' order by time asc";
              $result = $conn->query($flights);
            
              if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                $s = $row['time'];
                $dt = new DateTime($s);

                $date = $dt->format('d-m-Y');
                $time = $dt->format('h:i:s A');
                echo"
                <div class='container t-t-border t-t-border-bottom t-t-border-left-and-right' id='arr-row-1'>
                  <div class='row'>
                    <div class='col p-1 text-uppercase fw-bold t-t-color-moccasin'>$time</div>
                    <div class='col p-1 text-uppercase fw-bold t-t-color-springgreen'>".$row['status']."</div>
                    <div class='col p-1 text-uppercase fw-bold t-t-color-greenyellow'>".$row['aircraft_type']."</div>
                    <div class='col p-1 text-uppercase fw-bold t-t-color-white'>
                      <img class='airline-logo' src='https://daisycon.io/images/airline/?width=100&height=50&color=000000&iata=".$row['airline_code']."'></img>
                    </div>
                  </div>
                  <div class='row'>
                    <div class='col p-1 text-uppercase t-t-font-size-smaller t-t-color-yellow'>To</div>
                    <div class='col p-1 text-uppercase t-t-color-white'>Gate ".$row['gate']."</div>
                    <div class='col p-1 text-uppercase  fw-bold t-t-color-white'>".$row['airline_code']." ".$row['flight_no']."</div>
                    <div class='col p-1 text-uppercase'></div>
                  </div>
                  <div class='row'>
                    <div class='col p-1 text-uppercase t-t-font-size-smaller t-t-color-yellow'>".$row['destination']."</div>
                    <div class='col p-1 text-uppercase'></div>
                    <div class='col p-1 text-uppercase'></div>
                    <div class='col p-1 text-uppercase'></div>                    
                  </div>
                </div>";
                }
              } else {
                echo "0 results";
              }
            ?>
						<!--Departure-----Row---1----ENDS-->

					</div>

				</div><!--from-here-actually-tw0 columns are made---which is the arrivals and departures--->

			</div><!--container---ends-->
		</div><!--t-t-main-area-->
	</div><!--container-fluid----ends-->
</body>
</html>