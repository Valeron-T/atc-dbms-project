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

    if(isset($_POST['flt_submit_btn'])) {
        // Validate if gate supports given aircraft type
        $ac_type = $_POST['aircraft_type'];
        $gate_no = $_POST['gate'];
        $origin = $_POST['origin'];
        $destination = $_POST['destination'];

        $supported_ac_for_gate = "SELECT aircraft_type from gates where stand_no = '$gate_no'";
        $gate_result = $conn->query($supported_ac_for_gate)->fetch_assoc();

        if(strpos($gate_result['aircraft_type'], $ac_type) !== false){
            // Validate origin and destination are not same
            if ($origin !== $destination) {
                if ($_POST['deparr'] === 'DEP') {
                    // Validate dep airport is BOM
                    if ($_POST['origin'] === 'BOM') {
                        $sql = "INSERT INTO `flights` (`airline_code`, `flight_no`, `time`, `deparr`, `origin`, `destination`, `gate`, `aircraft_type`, `status`) VALUES ('".$_POST['flight-no-selected']."', '".$_POST['flight_no']."', '".$_POST['time']."', '".$_POST['deparr']."', '".$_POST['origin']."', '".$_POST['destination']."', '".$_POST['gate']."', '".$_POST['aircraft_type']."','Scheduled');";
                        if ($conn->query($sql) === TRUE) {
                            echo "New record created successfully";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                    } else {
                        echo "Origin must be BOM for departures";
                    }
                }
                elseif ($_POST['deparr'] === 'ARR') {
                    // Validate arrival airport is BOM
                    if ($_POST['destination'] === 'BOM') {
                        $sql = "INSERT INTO `flights` (`airline_code`, `flight_no`, `time`, `deparr`, `origin`, `destination`, `gate`, `aircraft_type`, `status`) VALUES ('".$_POST['flight-no-selected']."', '".$_POST['flight_no']."', '".$_POST['time']."', '".$_POST['deparr']."', '".$_POST['origin']."', '".$_POST['destination']."', '".$_POST['gate']."', '".$_POST['aircraft_type']."','Expected');";
                        if ($conn->query($sql) === TRUE) {
                            echo "New record created successfully";
                        } else {
                            echo "Error: " . $sql . "<br>" . $conn->error;
                        }
                    } else {
                        echo "Destination must be BOM for arrivals";
                    }
                }
            } else {
                echo "Origin and Destination cannot be same";
            }
        } else{
            echo "Selected gate doesn't support aircraft type!<br>";
            // Display supported gates which are also vacant 
            $get_supported_gates = "SELECT stand_no from gates where aircraft_type like '%$ac_type%' and stand_status = 'Vacant'";
            $supported_gates_result = $conn->query($get_supported_gates);
            
            $sup_gates = '';

            if ($supported_gates_result->num_rows > 0) {
                while($row = $supported_gates_result->fetch_assoc()) {
                    $sup_gates = $sup_gates.$row['stand_no'].",";
                }
                echo "Choose any of these gates: ".$sup_gates;
            }
            else {
                echo "No gates available for specified A/C type.<br>";
            }
        }
        
    }
    
    if(isset($_POST['button2'])) {
        $select_flight = "SELECT * from flights where airline_code='SG' and flight_no=321 ";
        $result = $conn->query($select_flight)->fetch_assoc();
        echo $result['airline_code'];

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

        // while($row = $select_result->fetch_assoc()) {
        //     echo $row['airline_code'];
        // }

        $sql = "INSERT INTO `ground` (`airline_code`, `flight_no`, `airline_name`, `status`, `time`, `deparr`, `rname`, `origin`, `destination`, `gate`, `aircraft_type`, `clear_level`)
        VALUES ('$airline_code', '$flight_no', '$airline_name', 'Boarding' , '$time', '$deparr', '$runway', '$origin', '$destination', '$gate', '$aircraft_type', 'Requested PDC')";
        if ($conn->query($sql) === TRUE) {
            echo "New record in ground created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    if(isset($_POST['button3'])) {
        $select_flight = "UPDATE `ground` SET `clear_level` = 'Requested Pushback', 'status' = 'Gate Closed' WHERE `ground`.`airline_code` = 'SG' AND `ground`.`flight_no` = 321;";
        $result = $conn->query($select_flight);
    }

    if(isset($_POST['button4'])) {
        $select_flight = "UPDATE `ground` SET `clear_level` = 'Requested Taxi', 'status' = 'Departed' WHERE `ground`.`airline_code` = 'SG' AND `ground`.`flight_no` = 321;";
        $result = $conn->query($select_flight);
    }

//   echo "Connected successfully";
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

	<link rel="stylesheet" type="text/css" href="pilot.css">

	<title>Pilot UI</title>
</head>
<body id="body-of-pilot-ui">
	<div class="container-fluid" id="first-container-fluid">
		<div class="container">
			


			<!--this ---below--- div is used for the making two windows-->
			<div class="container-fluid">
				<div class="row pt-5">
					<div class="container p-4" id="ckpit-border">
						<div class="row">
							<div class="col p-5 bg-primary" id="ckpit-l-window">
								<!-- <img src="" alt="cockpit window" class="img-fluid float-start"> -->
							</div>
							<div class="col p-5 bg-dark" id="ckpit-r-window"></div>
						</div>
					</div>
				</div>
			</div>
			<!--this ---above--- div is used for the making two windows-->





			<form action="" method="POST">
				<!--This ---below---div--- is used for making boxes-->
				<div class="container-fluid">







					<!--this ---below--div-- is the -----1st----- row for user input-->
					<div class="row pt-3">


						<!--the ---below--- div is for ---------Airline Code------>
						<div class="col p-3 bg-primary">
							<div class="container text-center fs-5 fw-bold">
								<label class="text-uppercase">Airline Code</label>
							</div>
							<div class="container text-uppercase text-center">
                                <select id='flight-no-selected' name="flight-no-selected" class="text-uppercase text-center form-control">
                                    <?php
                                        $flights = "Select * from airline_codes";
                                        $result = $conn->query($flights);
                                    
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo "<option value='".$row['airline_code']."'>".$row['airline_code']." - ".$row['airline_name']."</option>";
                                            }
                                        }
                                    ?>
                                </select>
							</div>
						</div>
						<!--the ---above--- div is for ---------Airline Code------>


						<!--the ---below--- div is for ---------Flight Number------>
						<div class="col p-3 ms-2 bg-primary">
							<div class="container text-center fs-5 fw-bold">
								<label class="text-uppercase">Flight Number</label>
							</div>
							<div class="container text-uppercase text-center">
								<input type="textbox" name="flight_no" id="flight_no" class="text-center form-control">
							</div>
						</div>
						<!--the ---above--- div is for ---------Flight Number------>


						<!--the ---below--- div is for ---------Time------>
						<div class="col p-3 ms-2 bg-primary">
							<div class="container text-center fs-5 fw-bold">
								<label class="text-uppercase">Time</label>
							</div>
							<div class="container text-uppercase text-center">
								<input type="datetime-local" name="time" id="time" class="text-center form-control">
							</div>
						</div>
						<!--the ---above--- div is for ---------Time------>


						<!--the ---below--- div is for ---------Gate------>
						<div class="col p-3 ms-2 bg-primary">
							<div class="container text-center fs-5 fw-bold">
								<label class="text-uppercase">Available Gates</label>
							</div>
							<div class="container text-uppercase text-center">
								<select id='gate' name="gate" class="text-uppercase text-center form-control">
                                    <?php
                                        $flights = "Select stand_no from gates where stand_status='Vacant'";
                                        $result = $conn->query($flights);
                                    
                                        if ($result->num_rows > 0) {
                                            while($row = $result->fetch_assoc()) {
                                                echo "<option value='".$row['stand_no']."'>".$row['stand_no']."</option>";
                                            }
                                        }
                                    ?>
                                </select>
							</div>
						</div>
						<!--the ---above--- div is for ---------Gate------>


					</div>
					<!--this ---above--div-- is the -----1st----- row for user input-->











					<!--this ---below--div-- is the -----2nd----- row for user input-->
					<div class="row pt-3">


						<!--the ---below--- div is for ---------Origin------>
						<div class="col p-3 bg-primary">
							<div class="container text-center fs-5 fw-bold">
								<label class="text-uppercase">Origin</label>
							</div>
							<div class="container text-uppercase text-center">
                                <select id='origin' name="origin" class="text-uppercase text-center form-control">
                                    <?php
                                        $flights = "Select * from airport";
                                        $result = $conn->query($flights);
                                    
                                        if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<option value='".$row['iata_code']."'>".$row['iata_code']." - ".$row['name']."</option>";
                                        }
                                        }
                                    ?>
                                </select>
							</div>
						</div>
						<!--the ---above--- div is for ---------Origin------>


						<!--the ---below--- div is for ---------Destination------>
						<div class="col p-3 ms-2 bg-primary">
							<div class="container text-center fs-5 fw-bold">
								<label class="text-uppercase">Destination</label>
							</div>
							<div class="container text-uppercase text-center">
                                <select id='destination' name="destination" class="text-uppercase text-center form-control">
                                    <?php
                                        $flights = "Select * from airport";
                                        $result = $conn->query($flights);
                                    
                                        if ($result->num_rows > 0) {
                                        while($row = $result->fetch_assoc()) {
                                            echo "<option value='".$row['iata_code']."'>".$row['iata_code']." - ".$row['name']."</option>";
                                        }
                                        }
                                    ?>
                                </select>
							</div>
						</div>
						<!--the ---above--- div is for ---------Destination------>


						<!--the ---below--- div is for ---------Request------>
						<div class="col p-3 ms-2 bg-primary">
							<div class="container text-center fs-5 fw-bold">
								<label class="text-uppercase">A/C Type Code</label>
							</div>
							<div class="container text-uppercase text-center">
                                <input type="text" name="aircraft_type" id="aircraft_type" class="text-center form-control">
							</div>
						</div>
						<!--the ---above--- div is for ---------Request------>


						<!--the ---below--- div is for ---------Departure and Arrival------>
						<div class="col p-3 ms-2 bg-primary">
							<div class="container text-center fs-5 fw-bold  text-uppercase">
								<!-- <label class="text-uppercase">Gate</label> -->
								<input type="radio" name="deparr" value="DEP">
								<label>Departure</label>
							</div>
							<div class="container text-uppercase text-center  fw-bold  fs-5">
								<input type="radio" name="deparr" value="ARR">
								<label>Arrival</label>
							</div>
						</div>
						<!--the ---above--- div is for ---------Departure and Arrival------>


					</div>
					<!--this ---above--div-- is the -----2nd----- row for user input-->




					<div class="row pt-3">
						<div class="col p-3 bg-primary">
							<div class="container text-center fs-5 fw-bold  text-uppercase">
								<!-- <input type="submit" name="submit_button" value="SUBMIT TO ATC" class="form-control text-center fs-5 fw-bold  text-uppercase"> -->
                                <input type="submit" name="flt_submit_btn" class="button form-control text-center fs-5 fw-bold text-uppercase" value="Submit Flight Details" />
							</div>
						</div>

						<div class="col p-3  ms-2 bg-primary">
							<div class="container text-center fs-5 fw-bold  text-uppercase">
								<input type="submit" name="reset_button" value="RESET" class="form-control text-center fs-5 fw-bold  text-uppercase">
							</div>
						</div>
					</div>








				</div>
				<!--This ---above---div--- is used for making boxes-->
			</form>

		</div>
		<!--div---container----ends-->
	</div>
	<!--div---container-fluid---ends-->
</body>
</html>