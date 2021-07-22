 <!DOCTYPE html>
 <html>
  <head>
    <title>Simple OS Command Injection</title>
    <h1>Hello world! </h1>
	<h2>Enter IP andress to ping :</h2>
  </head>

  <body>
  		<form action="" method="POST">
			<div class="input-group">
				<input type="text" placeholder="Ip" name="ip" value="<?php echo $_POST['ip']; ?>" required>
			</div>
			<div class="input-group">
				<button name="submit" class="btn">Ping</button>
			</div>
		</form>
   
  </body>
</html>
<?php
session_start();

error_reporting(0);

if(isset($_POST["submit"]))
{
	$input = $_POST["ip"];

	$blacklist_symbol = array(" ","!","@","#","|","%","^","&","*","~","\\","/","<",">");
	$input = str_replace($blacklist_symbol,"",$input);

	$blacklist_command = array("ls","cat","less","tail","more","whoami","pwd","echo");
	$input = str_replace($blacklist_command,"",$input);

	$output = shell_exec("ping -c 1 ".$input);

	if(isset($_GET["debug"])==true)
		echo "<div>ping -c 1 ".$input."</div>";

	echo "<pre>".$output."</pre>" ;
}

?>