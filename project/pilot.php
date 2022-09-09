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

	function Message($conn)
	{
		$error_tag = "<div class='bg-color-deep-yellow'><p class='m-0 text-center text-uppercase fw-bold'>Invalid Command</p></div>";
		$success_tag = "<div class='bg-color-deep-yellow'><p class='m-0 text-center text-uppercase fw-bold'>Data Updated successfully</p></div>";
		if(!(mysqli_affected_rows($conn) >0 )) {
			echo $error_tag;
		} else {
			echo $success_tag;
		}
	}

    if(isset($_POST['btn-pdc'])) {
        $joined_code  = $_POST['flight_number'];
        $joined_code_split = explode("-", $joined_code);

		$select_flight = "SELECT * from flights where airline_code='".$joined_code_split[0]."' and flight_no=".$joined_code_split[1]."";
		$result = $conn->query($select_flight)->fetch_assoc();

		$airline_code = $result['airline_code'];
		$flight_no = $result['flight_no'];
		$time = $result['time'];
		$deparr = $result['deparr'];
		$origin = $result['origin'];
		$destination = $result['destination'];
		$gate = $result['gate'];
		$aircraft_type = $result['aircraft_type'];
		
		$select_airline_name = "SELECT airline_name from airline_codes where airline_code = '$airline_code' ";
		$result = $conn->query($select_airline_name)->fetch_assoc();
		$airline_name = $result['airline_name'];
		
        $get_active_runway = "Select rname from runways where inUse='Y'";
        $result = $conn->query($get_active_runway)->fetch_assoc();
        $runway = $result['rname'];

		$insert_into_gnd = "INSERT INTO `ground` (`airline_code`, `flight_no`, `airline_name`, `status`, `time`, `deparr`, `rname`, `origin`, `destination`, `gate`, `aircraft_type`, `clear_level`)
		VALUES ('$airline_code', '$flight_no', '$airline_name', 'Boarding' , '$time', '$deparr', '$runway', '$origin', '$destination', '$gate', '$aircraft_type', 'PDC Requested')";
        if ($conn->query($insert_into_gnd) === TRUE) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $insert_into_gnd . "<br>" . $conn->error;
        }
	}

    if(isset($_POST['btn-pushback'])) {
        $joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$select_flight = "UPDATE `ground` SET `clear_level` = 'Pushback requested',`status` = 'Gate Closed'  WHERE `ground`.`airline_code` = '".$joined_code_split[0]."' AND `ground`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($select_flight);
		Message($conn);
    }

    if(isset($_POST['btn-taxi'])) {
        $joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$select_flight = "UPDATE `ground` SET `clear_level` = 'Taxi requested'  WHERE `ground`.`airline_code` = '".$joined_code_split[0]."' AND `ground`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($select_flight);
		Message($conn);
    }

    if(isset($_POST['btn-hs'])) {
        $joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$select_flight = "UPDATE `ground` SET `clear_level` = 'Hold Short'  WHERE `ground`.`airline_code` = '".$joined_code_split[0]."' AND `ground`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($select_flight);
		Message($conn);

        $drop_from_gnd = "DELETE from `ground` WHERE `ground`.`airline_code` = '".$joined_code_split[0]."' AND `ground`.`flight_no` = ".$joined_code_split[1].";";
        $del_result = $conn->query($drop_from_gnd);
    }

    if(isset($_POST['btn-takeoff'])) {
        $joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$select_flight = "UPDATE `departures` SET `clear_level` = 'Requested T/O'  WHERE `departures`.`airline_code` = '".$joined_code_split[0]."' AND `departures`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($select_flight);
		Message($conn);
    }

    if(isset($_POST['btn-handoff'])) {
        $joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$select_flight = "UPDATE `departures` SET `clear_level` = 'Requested Handoff'  WHERE `departures`.`airline_code` = '".$joined_code_split[0]."' AND `departures`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($select_flight);
		Message($conn);
    }

    if(isset($_POST['btn-land'])) {
        $joined_code  = $_POST['flight_number'];
        $joined_code_split = explode("-", $joined_code);
		$select_flight = "SELECT * from flights where airline_code='".$joined_code_split[0]."' and flight_no=".$joined_code_split[1]."";
		$result = $conn->query($select_flight)->fetch_assoc();

		$airline_code = $result['airline_code'];
		$flight_no = $result['flight_no'];
		$time = $result['time'];
		$deparr = $result['deparr'];
		$origin = $result['origin'];
		$destination = $result['destination'];
		$gate = $result['gate'];
		$aircraft_type = $result['aircraft_type'];
		
		$select_airline_name = "SELECT airline_name from airline_codes where airline_code = '$airline_code' ";
		$result = $conn->query($select_airline_name)->fetch_assoc();
		$airline_name = $result['airline_name'];
		
        $get_active_runway = "Select rname from runways where inUse='Y'";
        $result = $conn->query($get_active_runway)->fetch_assoc();
        $runway = $result['rname'];

		$insert_into_arr = "INSERT INTO `arrivals` (`airline_code`, `flight_no`, `airline_name`, `status`, `time`, `rname`, `origin`, `destination`, `gate`, `aircraft_type`, `clear_level`)
		VALUES ('$airline_code', '$flight_no', '$airline_name', 'Confirmed' , '$time', '$runway', '$origin', '$destination', '$gate', '$aircraft_type', 'Requested Landing')";
        if ($conn->query($insert_into_arr) === TRUE) {
            echo "<div class='bg-color-deep-yellow'><p class='m-0 text-center text-uppercase fw-bold'>Data Updated successfully</p></div>";
        } else {
            echo "Error: " . $insert_into_arr . "<br>" . $conn->error;
        }
	}

    if(isset($_POST['btn-vac'])) {
        $joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$select_flight = "UPDATE `arrivals` SET `clear_level` = 'Runway vacated',`status` = 'Landed'  WHERE `arrivals`.`airline_code` = '".$joined_code_split[0]."' AND `arrivals`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($select_flight);
		Message($conn);

        $drop_from_arr = "DELETE from `arrivals` WHERE `arrivals`.`airline_code` = '".$joined_code_split[0]."' AND `arrivals`.`flight_no` = ".$joined_code_split[1].";";
        $del_result = $conn->query($drop_from_arr);
    }

    if(isset($_POST['btn-cfp'])) {
        $joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$delete_flight = "DELETE from `ground` WHERE `ground`.`airline_code` = '".$joined_code_split[0]."' AND `ground`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($delete_flight);
		Message($conn);
    }
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

	<link rel="stylesheet" type="text/css" href="pilot.css">

	<title>PILOT - Updating</title>
</head>
<body class="main-bg-color">
	<form class="form-control main-bg-color" action="" method="POST">
		<!-- The below div tag is the main container-fluid -->
		<div class="container-fluid p-1 main-bg-color">


			<!-- The below div tag is the main container -->
			<!-- <div class="container p-1"> -->


				<!-- The below div tag is the main row for heading -->
				<div class="row justify-content-center m-4">
					<h1 class="text-center text-uppercase fs-1 fw-bold ">Pilot</h1>
				</div>
				<!-- The above div tag is the main row for heading -->


				<!-- The below div tag is the main row from where the contents will start displaying -->
				<div class="row mt-2">


					<div class="col p-3">
						<div class="main-buttons ">
							<ul class="list-unstyled nav justify-content-center">


								<li class="float-start border m-2 style2">
									<!-- <button class="btn"> -->
										<figure class="container text-center my-5">
											<label class="fs-5 text-uppercase fw-bold text-center py-0 mt-4">Flight Number</label>
											<figcaption class="py-4 my-1">
												<select id='flight_number' name="flight_number" class="text-uppercase text-center form-control border-of-drop">
                                                    <?php
                                                        $flights = "Select * from flights";
                                                        $result = $conn->query($flights);
                                                    
                                                        if ($result->num_rows > 0) {
                                                            while($row = $result->fetch_assoc()) {
                                                                echo "<option value='".$row['airline_code']."-".$row['flight_no']."'>".$row['airline_code']." ".$row['flight_no']."</option>";
                                                            }
                                                        }
                                                    ?>
                                                </select>
											</figcaption>
										</figure>
									<!-- </button> -->
								</li>

								<li class="float-start border m-2 btn-bg-color">
									<button class="btn" name="btn-pdc">
										<figure class="container">
											<img src="https://cdn1.vectorstock.com/i/thumb-large/89/10/tower-station-with-dishes-internet-or-phone-vector-30488910.jpg" alt="Tim Cook" class="img-thumbnail max-width-img mt-3 style3">
											<figcaption>
												<p class="fs-5 mt-3 text-uppercase fw-bold style1">Request PDC</p>
											</figcaption>
										</figure>
									</button>
								</li>

								<li class="float-start border m-2 btn-bg-color">
									<button class="btn" name="btn-pushback">
										<figure class="container">
											<img src="https://media.istockphoto.com/vectors/the-aircraft-tractor-is-towing-the-aircraftpreparing-for-drawing-vector-id1250137989?k=20&m=1250137989&s=612x612&w=0&h=PK51jjXn-OrKYZvqmSiq3emxXz5nOmcK4t9HaUUwarM=" alt="Tim Cook" class="img-thumbnail max-width-img mt-4 style3">
											<figcaption>
												<p class="fs-5 mt-3 text-uppercase fw-bold style1">Request pushback</p>
											</figcaption>
										</figure>
									</button>
								</li>

								<li class="float-start border m-2  btn-bg-color">
									<button class="btn" name="btn-taxi">
										<figure class="container">
											<img src="https://media.gettyimages.com/vectors/airplane-isometric-icon-vector-id620710880?k=20&m=620710880&s=612x612&w=0&h=L8BVpRnMyckCohZ0qvO4ErqDivKRBV76-2CTp0k1iXM=" alt="Tim Cook" class="img-thumbnail max-width-img mt-4 style3">
											<figcaption>
												<p class="fs-5 mt-3 text-uppercase fw-bold style1">Request taxi</p>
											</figcaption>
										</figure>
									</button>
								</li>

								<li class="float-start border m-2  btn-bg-color">
									<button class="btn" name="btn-hs">
										<figure class="container">
											<img src="https://cdn5.vectorstock.com/i/1000x1000/18/54/passenger-airplanes-waiting-for-take-off-from-airp-vector-27081854.jpg" alt="Tim Cook" class="img-thumbnail max-width-img mt-3 style3">
											<figcaption>
												<p class="fs-5 mt-3 text-uppercase fw-bold max-width-img-200 style1">Holding Short of runway</p>
											</figcaption>
										</figure>
									</button>
								</li>

								<li class="float-start border m-2  btn-bg-color">
									<button class="btn" name="btn-takeoff">
										<figure class="container">
											<img src="https://img.freepik.com/premium-vector/plane-blue-sky-with-vapor-trail-illustration_567746-2595.jpg?w=150" alt="Tim Cook" class="img-thumbnail max-width-img mt-3 style3">
											<figcaption>
												<p class="fs-5 mt-3 text-uppercase fw-bold style1">Request takeoff</p>
											</figcaption>
										</figure>
									</button>
								</li>

                                <li class="float-start border m-2 btn-bg-color">
									<button class="btn" name="btn-handoff">
										<figure class="container">
											<img src="https://png.pngtree.com/png-vector/20191121/ourlarge/pngtree-clipart-of-pilot-with-the-headset-and-controlling-the-airplane-png-image_2013691.jpg" alt="Tim Cook" class="img-thumbnail max-width-img mt-3 style3">
											<figcaption>
												<p class="fs-5 mt-3 text-uppercase fw-bold style1">Request Handoff</p>
											</figcaption>
										</figure>
									</button>
								</li>

								<li class="float-start border m-2  btn-bg-color">
									<button class="btn" name="btn-land">
										<figure class="container">
											<img src="https://media.gettyimages.com/vectors/airplane-landing-vector-id165813488?s=2048x2048" alt="Tim Cook" class="img-thumbnail max-width-img mt-3 style3">
											<figcaption>
												<p class="fs-5 mt-3 text-uppercase fw-bold style1">Request Landing</p>
											</figcaption>
										</figure>
									</button>
								</li>

								<li class="float-start border m-2 btn-bg-color">
									<button class="btn" name="btn-vac">
										<figure class="container">
											<img src="https://img.freepik.com/free-vector/landing-strip-airplanes-near-terminal-control-room-tower-empty-asphalt-runway_1441-2177.jpg" alt="Tim Cook" class="img-thumbnail max-width-img-250 mt-4 style3">
											<figcaption>
												<p class="fs-5 mt-3 text-uppercase fw-bold style1">Runway vacated</p>
											</figcaption>
										</figure>
									</button>
								</li>

								<li class="float-start border m-2 btn-bg-color">
									<button class="btn" name="btn-cfp">
										<figure class="container">
											<img src="https://img.freepik.com/premium-vector/concept-closing-airspace-civil-aircraft-canceling-flights-vector-illustration_357257-970.jpg?w=360" alt="Tim Cook" class="img-thumbnail max-width-img-200 mt-3 style3">
											<figcaption>
												<p class="fs-5 mt-3 text-uppercase fw-bold style1">Close flight Plan</p>
											</figcaption>
										</figure>
									</button>
								</li>

							</ul>
						</div>
					</div>


				</div>
				<!-- The above div tag is the main row  from where the contents will start displaying -->






			<!-- </div> -->
			<!-- The above div tag is the main container -->


		</div>
		<!-- The above div tag is the main container-fluid -->
	</form>
</body>
</html>