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

  // echo "Connected successfully";
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

	<link rel="stylesheet" type="text/css" href="arrivals--CSS.css">

	<title>Arrivals - Table</title>
</head>
<body class="bg-color-day-sky-blue">
	<!-- below---is the first container-fluid -->
	<div class="container-fluid">

		<!-- below---is the first container -->
		<div class="container pt-5">

			<!-- below---is the first container-for text -->
			<div class="container">
				<h3 class="p-3 text-center fs-1 style1">Arrivals</h3>
			</div>
			<!-- above---is the first container-for text -->

			<br>


			<!-- below---is the first container-where the table will start -->
			<div class="container justify-content-center">


				<!-- below---is the first container-which is used for table headings -->
				<div class="container fw-bold text-uppercase mb-2" id="table-heading">
					<div class="row">
						<div class="col p-1 style5">
							<p class="mb-0 text-center">Flight Number</p>
						</div>
						<div class="col p-1 ms-1 style6">
							<p class="mb-0 text-center">Airline Name</p>
						</div>
						<div class="col p-1 ms-1 style5">
							<p class="mb-0 text-center">Status</p>
						</div>
						<div class="col p-1 ms-1 style6">
							<p class="mb-0 text-center">Time</p>
						</div>
						<div class="col p-1 ms-1 style5">
							<p class="mb-0 text-center">Runway</p>
						</div>
						<div class="col p-1 ms-1 style6">
							<p class="mb-0 text-center">Origin</p>
						</div>
						<div class="col p-1 ms-1 style5">
							<p class="mb-0 text-center">Destination</p>
						</div>
						<div class="col p-1 ms-1 style6">
							<p class="mb-0 text-center">Gate</p>
						</div>
						<div class="col p-1 ms-1 style5">
							<p class="mb-0 text-center">A/C</p>
						</div>
						<div class="col p-1 ms-1 style6">
							<p class="mb-0 text-center">Clearence level/Status</p>
						</div>
					</div>
				</div>
				<!-- above---is the first container-which is used for table headings -->







				<div class="pt-1 pb-1">
					<!-- below---is the second container-which is used for 1st row -->
					<div class="container border-of-row fw-bold text-uppercase text-center" id="row-data">
					<?php
						$flights = "Select * from arrivals order by time asc";
						$result = $conn->query($flights);
						
						if ($result->num_rows > 0) {
							// output data of each row
							while($row = $result->fetch_assoc()) {
								$s = $row['time'];
								$dt = new DateTime($s);

								$date = $dt->format('d-m-Y');
								$time = $dt->format('h:i:s A');
								echo "<div class='row'>
								<div class='col p-1 style4'>
									<p class='mb-0 text-center'>".$row['airline_code'].$row['flight_no']."</p>
								</div>
								<div class='col p-1 ms-1 style4'>
									<p class='mb-0 text-center'>".$row['airline_name']."</p>
								</div>
								<div class='col p-1 ms-1 style4'>
									<p class='mb-0 text-center'>".$row['status']."</p>
								</div>
								<div class='col p-1 ms-1 style4'>
									<p class='mb-0 text-center'>".$time."</p>
								</div>
								<div class='col p-1 ms-1 style4'>
									<p class='mb-0 text-center'>".$row['rname']."</p>
								</div>
								<div class='col p-1 ms-1 style4'>
									<p class='mb-0 text-center'>".$row['origin']."</p>
								</div>
								<div class='col p-1 ms-1 style4'>
									<p class='mb-0 text-center'>".$row['destination']."</p>
								</div>
								<div class='col p-1 ms-1 style4'>
									<p class='mb-0 text-center'>".$row['gate']."</p>
								</div>
								<div class='col p-1 ms-1 style4'>
									<p class='mb-0 text-center'>".$row['aircraft_type']."</p>
								</div>
								<div class='col p-1 ms-1 style4'>
									<p class='mb-0 text-center'>".$row['clear_level']."</p>
								</div>
							</div>";
							}}
					?>
					</div>
					<!-- below---is the second container-which is used for 1st row -->
				</div>




				

			</div>
			<!-- above---is the first container-where the table will start -->




		</div>
		<!-- above---is the first container -->

	</div>
	<!-- above---is the first container-fluid -->
</body>
</html>