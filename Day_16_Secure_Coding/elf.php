<?php
	include "connection.php";

	// MODIFIED QUERY ADDED INTVAL
	$query="select * from users where id=".intval($_GET['id']);

	$elves_rs=mysqli_query($db,$query);

	if(!$elves_rs)
	{
		echo "<font color=red size=10>Error: Invalid SQL Query</font>";
		die($query);
	}

	// Get the first result. There should be a single elf here.
	$elf=mysqli_fetch_assoc($elves_rs);

	//Now get the toys associated to this elf
	//MODIFIED QUERY ADDED INTVAL
	$query="select * from toys where creator_id=".intval($_GET['id']);

	$toys_rs=mysqli_query($db,$query);

	if(!$toys_rs)
	{
		echo "<font color=red size=10>Error: Invalid SQL Query</font>";
		die($query);
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>Elf profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php require_once("menu.php"); ?>
<div class="container">
		<div class="main-body">
			<div class="row">
				<div class="col-lg-4">
					<div class="card">
						<div class="card-body">
							<div class="d-flex flex-column align-items-center text-center">
							<img src="imgs/profile/<?=$elf['username']?>.png" alt="Profile Pic" class="rounded-circle p-1" style="width:200px;height:200px;background-color:#5cb363">
								<div class="mt-3">
								<h4><?=$elf['full_name']?></h4>
									<p class="text-secondary mb-1">Full Stack Elf</p>
									<p class="text-muted font-size-sm">North Pole</p>
								</div>
							</div>
						</div>
					</div>
					<div class="card">
						<div class="card-body">
							<h5 class="d-flex align-items-center mb-3">Skills</h5>
							<p>Toy Making</p>
							<div class="progress mb-3" style="height: 5px">
								<div class="progress-bar bg-primary" role="progressbar" style="width: 80%" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<p>Christmas Spirit</p>
							<div class="progress mb-3" style="height: 5px">
								<div class="progress-bar bg-danger" role="progressbar" style="width: 72%" aria-valuenow="72" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<p>Cybersecurity</p>
							<div class="progress mb-3" style="height: 5px">
								<div class="progress-bar bg-success" role="progressbar" style="width: 89%" aria-valuenow="89" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-8">
					<div class="card">
						<div class="card-body">
							<div class="row mb-3">
								<div class="col-sm-3 my-auto">
									<h6 class="mb-0">Full Name</h6>
								</div>
								<div class="col-sm-9 text-secondary my-auto">
									<span><?=$elf['full_name']?></span>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-3 my-auto">
									<h6 class="mb-0">Email</h6>
								</div>
								<div class="col-sm-9 text-secondary my-auto">
									<span><?=$elf['email']?></span>
								</div>
							</div>
							<div class="row mb-0">
								<div class="col-sm-3 my-auto">
									<h6 class="mb-0">Phone</h6>
								</div>
								<div class="col-sm-9 text-secondary my-auto">
									<span>Not Disclosed</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-body">
									<h5 class="d-flex align-items-center mb-3">Latest Toys</h5>
									<table class="table mb-0">
									<tr><th>&nbsp;</th><th>Toy</th><th>Description</th></tr>
									<?php
										while($toy=mysqli_fetch_assoc($toys_rs)){
											print("<tr>\n");
											print("<td class=\"align-middle\"><img height=\"45px\" src=\"imgs/toys/".$toy['name'].".png\"/></td>\n");
											print("<td class=\"align-middle\"><a href=\"toy.php?id=".$toy['id']."\">".$toy['name']."</a></td>\n");
											print("<td class=\"align-middle\">".$toy['description']."</td></tr>\n");
											print("</tr>\n");
										}
									?>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
