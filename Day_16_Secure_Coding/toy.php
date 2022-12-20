<?php
	include "connection.php";

    // MODIFIED QUERY ADDED INTVAL
    $query="select * from toys where id=".intval($_GET['id']);

	$toys_rs=mysqli_query($db,$query);

	if(!$toys_rs)
	{
		echo "<font color=red size=10>Error: Invalid SQL Query</font>";
		die($query);
	}

	// Get the first result. There should be a single elf here.
	$toy=mysqli_fetch_assoc($toys_rs);

	//query info on the creator elf

    // MODIFIED QUERY ADDED INTVAL
	$query="select * from users where id=".intval($toy['creator_id']);
	$elves_rs=mysqli_query($db,$query);

	if(!$elves_rs)
	{
		echo "<font color=red size=10>Error: Invalid SQL Query</font>";
		die($query);
	}

	// Get the first result. There should be a single elf here.
	$elf=mysqli_fetch_assoc($elves_rs);
	
	//query info on planned deliveries
	$query="select * from kids where assigned_toy_id=".intval($_GET['id']);
	$kids_rs=mysqli_query($db,$query);

	if(!$kids_rs)
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
							<img src="imgs/toys/<?=$toy['name']?>.png" alt="Profile Pic" class="rounded-circle p-1" style="width:200px;height:200px;background-color:#5cb363">
								<div class="mt-3">
									<h4><?=$toy['name']?></h4>
									<p class="text-muted font-size-sm"><?=$toy['description']?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-8">
					<div class="card">
						<div class="card-body">
							<div class="row mb-3">
								<div class="col-sm-3 my-auto">
									<h6 class="mb-0">Assigned Elf</h6>
								</div>
								<div class="col-sm-9 text-secondary" style="border:1px solid;border-radius:5px;max-width:320px;">
									<img src="imgs/profile/60x60-<?=$elf['username']?>.png">
									<a href="elf.php?id=<?=$elf['id']?>"><?=$elf['full_name']?></a>
								</div>
							</div>
							<div class="row mb-3">
								<div class="col-sm-3 my-auto">
									<h6 class="mb-0">Available #</h6>
								</div>
								<div class="col-sm-9 text-secondary my-auto">
									<span><?=$toy['quantity']?></span>
								</div>
							</div>
							<div class="row mb-0">
								<div class="col-sm-3 my-auto">
									<h6 class="mb-0">Required Score</h6>
								</div>
								<div class="col-sm-9 text-secondary my-auto">
									<meter id="disk_c" value="<?=$toy['min_score']?>" min="0" max="10">2 out of 10</meter>
									<span><?=$toy['min_score']?>/10</span>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
							<div class="card">
								<div class="card-body">
									<h5 class="d-flex align-items-center mb-3">Planned Deliveries</h5>
									<table class="table">
									<tr><th>To</th><th>Score</th><th>Latitude</th><th>Longitude</th></tr>
									<?php
										while($kid=mysqli_fetch_assoc($kids_rs)){
											print("<tr>\n");
											print("<td>".$kid['first_name']." ".$kid['last_name']."</td>\n");
											print("<td><meter min=0 max=10 value=".$kid['score']."></meter><span>".$kid['score']."/10</span></td>\n");
											print("<td>".$kid['latitude']."</td>\n");
											print("<td>".$kid['longitude']."</td>\n");
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
