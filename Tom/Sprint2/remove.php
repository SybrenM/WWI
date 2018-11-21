<?php
	require 'connection.php';
	if(isset($_POST['removeuser'])) {
		$errMsg = '';
		// Get username from FORM
		$naam = $_POST['gebruiker'];
		if($naam == '')
			$errMsg = 'Enter username/ id to remove';
		if($errMsg == '') {
			try{
				$stmt = $conn->prepare('DELETE FROM CustomerLogin WHERE achternaam = :achternaam LIMIT 1');
				$stmt->execute([':achternaam' => $naam
					]);
				$errMsg = 'User ' . $achternaam. ' successfully removed.';
			}
			catch(PDOException $e) {
				$errMsg = $e->getMessage();
			}
		}
	}
?>

<html>
<head><title>Remove User</title></head>
	<style>
	html, body {
		margin: 1px;
		border: 0;
	}
	</style>
<body>
	<div align="center">
		<div style=" border: solid 1px #006D9C; " align="left">
			<?php
				if(isset($errMsg)){
					echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
				}
			?>
			<div style="background-color:#006D9C; color:#FFFFFF; padding:10px;"><b>Remove User</b></div>
			<div style="margin: 15px">
				<form action="" method="post">
				Enter Username / ID <br>
					<input type="text" name="gebruiker" autocomplete="off" class="box"/><br /><br />
					<input type="submit" name='removeuser' value="Remove" class='submit'/><br />
				</form>
			</div>
		</div>
	</div>
</body>
</html>