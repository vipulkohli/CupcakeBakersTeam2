<html><body>
<?

session_start();

$con = mysql_connect("localhost", "martin", "195979");
if(!$con)
{
	die('Could not Connect: ' . mysql_error());
}



mysql_select_db("phptest", $con)
  	or die("Unable to select database: " . mysql_error());

$query = "select * from users where id = '";
$query = $query . $_POST['id'] . "'and pw = '" . $_POST['pw'] . " ' ";

$result = mysql_query($query);

if(mysql_num_rows($result) == 0)
	header ('Location: error.html');
else
	header ('Location: success.html');

mysql_close($con);

?>
</body></html>
	
