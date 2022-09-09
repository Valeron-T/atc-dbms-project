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

	
  // Insert flight
//   $flight = $_POST['ins_plane']
//   $result = $conn->query($airports);

	
	if(isset($_POST['btn-pdc'])) {
		$joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$select_flight = "UPDATE `ground` SET `clear_level` = 'PDC Filed',`status` = 'Final Call'  WHERE `ground`.`airline_code` = '".$joined_code_split[0]."' AND `ground`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($select_flight);
		Message($conn);
	}

	if(isset($_POST['btn-pushback'])) {
		$joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$select_flight = "UPDATE `ground` SET `clear_level` = 'Pushback Approved',`status` = 'Departure'  WHERE `ground`.`airline_code` = '".$joined_code_split[0]."' AND `ground`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($select_flight);
		Message($conn);
	}

	if(isset($_POST['btn-taxi'])) {
		$joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$select_flight = "UPDATE `ground` SET `clear_level` = 'Taxi Approved' WHERE `ground`.`airline_code` = '".$joined_code_split[0]."' AND `ground`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($select_flight);
		Message($conn);
	}

	if(isset($_POST['btn-takeoff'])) {
		$joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$select_flight = "UPDATE `departures` SET `clear_level` = 'T/O Approved' WHERE `departures`.`airline_code` = '".$joined_code_split[0]."' AND `departures`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($select_flight);
		Message($conn);
	}

	if(isset($_POST['btn-landing'])) {
		// Validate active runway is vacant
		$get_active_runway = "SELECT * from runways where inUse='Y'";
		$result = $conn->query($get_active_runway)->fetch_assoc();
		$runway_status = $result['status'];
		if ($runway_status == 'Vacant') {
			$joined_code  = $_POST['flight_number'];
			$joined_code_split = explode("-", $joined_code);
			$select_flight = "UPDATE `arrivals` SET `clear_level` = 'Cleared to Land' WHERE `arrivals`.`airline_code` = '".$joined_code_split[0]."' AND `arrivals`.`flight_no` = ".$joined_code_split[1].";";
			$result = $conn->query($select_flight);
			Message($conn);
		}
		else {
			Message("Runway not vacant");
		}
	}

	if(isset($_POST['btn-handoff'])) {
		$joined_code  = $_POST['flight_number'];
		$joined_code_split = explode("-", $joined_code);
		$select_flight = "UPDATE `departures` SET `clear_level` = 'Handoff' WHERE `departures`.`airline_code` = '".$joined_code_split[0]."' AND `departures`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($select_flight);
		Message($conn);
		$delete_flight = "DELETE from `departures` WHERE `departures`.`airline_code` = '".$joined_code_split[0]."' AND `departures`.`flight_no` = ".$joined_code_split[1].";";
		$result = $conn->query($delete_flight);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

	<link rel="stylesheet" type="text/css" href="new-atc--CSS.css">

	<title>ATC</title>
</head>
<body>
	<div class="container-fluid">
		<div class="row">

			<!-- This below div is used for 3 nav buttons -->
			<div class="col-2 p-5 bg-color">

				<div class="row mt-2 pt-3 bg-color-deep-yellow" id="ground_btn">
					<a href="parked.php" class="txt-decoration-none container" target="open-area">
						<figure class="container text-center">
							<img src="https://cdn-icons-png.flaticon.com/512/7434/7434239.png" alt="Landing Icon" class="img-thumbnail" style="width: 70px;">
							<figcaption>
								<p class="mb-0 text-center fw-bold fs-6 text-uppercase text-nowrap txt-black">Parked</p>
							</figcaption>
						</figure>
					</a>
				</div>

				<div class="row mt-2 pt-3 bg-color-deep-yellow" id="ground_btn">
					<a href="ground.php" class="txt-decoration-none container" target="open-area">
						<figure class="container text-center">
							<img src="https://cdn-icons-png.flaticon.com/512/0/614.png" alt="Landing Icon" class="img-thumbnail" style="width: 70px;">
							<figcaption>
								<p class="mb-0 text-center fw-bold fs-6 text-uppercase text-nowrap txt-black">Ground</p>
							</figcaption>
						</figure>
					</a>
				</div>

				<div class="row mt-2 pt-3 bg-color-deep-yellow" id="depart_btn">
					<a href="departure.php" class="txt-decoration-none container" target="open-area">
						<figure class="container text-center">
							<img src="https://cdn-icons-png.flaticon.com/512/68/68380.png" alt="Landing Icon" class="img-thumbnail" style="width: 70px;">
							<figcaption>
								<p class="mb-0 text-center fw-bold fs-6 text-uppercase text-nowrap txt-black">Departures</p>
							</figcaption>
						</figure>
					</a>
				</div>

				<div class="row mt-2 pt-3 bg-color-deep-yellow" id="arrival_btn">
					<a href="arrivals.php" class="txt-decoration-none container" target="open-area">
						<figure class="container text-center">
							<img src="https://cdn-icons-png.flaticon.com/512/68/68542.png" alt="Landing Icon" class="img-thumbnail" style="width: 70px;">
							<figcaption>
								<p class="text-center fw-bold fs-6 text-uppercase text-nowrap txt-black">Arrivals</p>
							</figcaption>
						</figure>
					</a>
				</div>


			</div>
			<!-- This above div is used for 3 nav buttons -->


			<!-- This below div tah is used for iframe -->
			<div class="col p-2 bg-color">
				<iframe src="ground.php" class="" style="width: 100%; height: 100%; border:none;" name="open-area">
					
				</iframe>
			</div>
			<!-- This above div tah is used for iframe -->

		</div>






		<!-- the below div is the form tags in the footer area -->
		<div class="container-fluid mt-1 bg-color-form">
			<div class="container-fluid p-3">

				<form action="" method='POST'>

					<div class="row">



						<!-- The below div tag is used for the 5icons downs -->
						<div class="col p-0 ms-3">
							<div class="row justify-content-center">

								<div class="col p-3">
									<div class="row p-3 my-2 border bg-color-form-drop mt-4">
										<div class="container text-center fs-6 fw-bold">
											<label class="text-uppercase txt-white" for="flight_number">Flight Number</label>
										</div>
										<div class="container text-uppercase text-center">
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
										</div>
									</div>
								</div>


								<div class="col p-3">
									<div class="container p-3">
										<div class="row justify-content-center">
											<button class="container btn text-nowrap btn-lg btns_5" name="btn-pdc">
												<figure>
													<img src="https://activelearningps.com/wp-content/uploads/2020/08/checklist.jpg" alt="PushBack Icon" class="img-thumbnail max-width-img">
													<figcaption>
														<h6 class="mt-3 text-uppercase">PDC</h6>
													</figcaption>
												</figure>
											</button>
										</div>
									</div>
								</div>


								<div class="col p-3">
									<div class="container p-3">
										<div class="row justify-content-center">
											<button class="container btn text-nowrap btn-sm btns_5" name="btn-pushback">
												<figure>
													<img src="https://media.istockphoto.com/vectors/the-aircraft-tractor-is-towing-the-aircraftpreparing-for-drawing-vector-id1250137989?k=20&m=1250137989&s=612x612&w=0&h=PK51jjXn-OrKYZvqmSiq3emxXz5nOmcK4t9HaUUwarM=" alt="PushBack Icon" class="img-thumbnail max-width-img">
													<figcaption>
														<h6 class="mt-3 text-uppercase">PushBack</h6>
													</figcaption>
												</figure>
											</button>
										</div>
									</div>
								</div>

								<div class="col p-3">
									<div class="container p-3">
										<div class="row justify-content-center">
											<button class="container btn text-nowrap btn-sm btns_5" name="btn-taxi">
												<figure>
													<img src="https://media.gettyimages.com/vectors/airplane-isometric-icon-vector-id620710880?k=20&m=620710880&s=612x612&w=0&h=L8BVpRnMyckCohZ0qvO4ErqDivKRBV76-2CTp0k1iXM=" alt="PushBack Icon" class="img-thumbnail max-width-img">
													<figcaption>
														<h6 class="mt-3 text-uppercase">Taxi</h6>
													</figcaption>
												</figure>
											</button>
										</div>
									</div>
								</div>

								<div class="col p-3">
									<div class="container p-3">
										<div class="row justify-content-center">
											<button class="container btn text-nowrap btn-sm btns_5" name="btn-takeoff">
												<figure>
													<img src="https://img.freepik.com/premium-vector/plane-blue-sky-with-vapor-trail-illustration_567746-2595.jpg?w=150" alt="PushBack Icon" class="img-thumbnail max-width-img">
													<figcaption>
														<h6 class="mt-3 text-uppercase">TakeOff</h6>
													</figcaption>
												</figure>
											</button>
										</div>
									</div>
								</div>

								<div class="col p-3">
									<div class="container p-3">
										<div class="row justify-content-center">
											<button class="container btn text-nowrap btn-sm btns_5" name="btn-landing">
												<figure>
													<img src="https://media.gettyimages.com/vectors/airplane-landing-vector-id165813488?s=2048x2048" alt="PushBack Icon" class="img-thumbnail max-width-img">
													<figcaption>
														<h6 class="mt-3 text-uppercase">Landing</h6>
													</figcaption>
												</figure>
											</button>
										</div>
									</div>
								</div>


								<div class="col p-3">
									<div class="container p-3">
										<div class="row justify-content-center">
											<button class="container btn text-nowrap btn-lg btns_5" name="btn-handoff">
												<figure>
													<img src="https://cdn1.vectorstock.com/i/thumb-large/89/10/tower-station-with-dishes-internet-or-phone-vector-30488910.jpg" alt="PushBack Icon" class="img-thumbnail max-width-img">
													<figcaption>
														<h6 class="mt-3 text-uppercase">Hand Off</h6>
													</figcaption>
												</figure>
											</button>
										</div>
									</div>
								</div>


								
							</div>
						</div>
						<!-- The above div tag is used for the 5icons downs -->




					</div>

				</form>
			</div>
		</div>
		<!-- the above div is the form tags of footer area -->


	</div>
</body>
</html>