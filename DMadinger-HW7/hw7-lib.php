<?php
// Name     : hw7-lib.php
// Purpose  : a PHP page that is connected to a database.
// Author   : Dilara Madinger dilara.madinger@colorado.edu
// Version  : 1.0
// Date:    : 06/20/2016

isset ( $_REQUEST['s'] ) ? $s = strip_tags($_REQUEST['s']) : $s = "";
isset ($_REQUEST['sid']) ? $sid = strip_tags($_REQUEST['sid']) : $sid = "";
isset ($_REQUEST['cid']) ? $cid = strip_tags($_REQUEST['cid']) : $cid = "";
isset ($_REQUEST['bid']) ? $bid = strip_tags($_REQUEST['bid']) : $bid = "";
isset ($_REQUEST['pid']) ? $pid = strip_tags($_REQUEST['pid']) : $pid = "";
isset ($_REQUEST['url']) ? $url = strip_tags($_REQUEST['url']) : $url = "";
isset ($_REQUEST['characterName']) ? $characterName = strip_tags($_REQUEST['characterName']) : $characterName = "";
isset ($_REQUEST['characterRace']) ? $characterRace = strip_tags($_REQUEST['characterRace']) : $characterRace = "";
isset ($_REQUEST['characterSide']) ? $characterSide = strip_tags($_REQUEST['characterSide']) : $characterSide = "";
isset ($_REQUEST['title']) ? $title = strip_tags($_REQUEST['title']) : $title = "";
isset ( $_REQUEST['postUser'] ) ? $postUser = strip_tags($_REQUEST['postUser']) : $postUser = "";
isset ( $_REQUEST['postPass'] ) ? $postPass = strip_tags($_REQUEST['postPass']) : $postPass = "";
isset ( $_REQUEST['postEmail'] ) ? $postEmail = strip_tags($_REQUEST['postEmail']) : $postEmail = "";


function connect(&$db){
        $mycnf="/etc/hw5-mysql.conf";
        if (!file_exists($mycnf)) { 
                echo "ERROR: DB Config file not found: $mycnf";
                exit;
        }
	 
        //Here we read the file /etc/hw5-mysql.conf
	$mysql_ini_array=parse_ini_file($mycnf);
        $db_host=$mysql_ini_array["host"];
        $db_user=$mysql_ini_array["user"];
        $db_pass=$mysql_ini_array["pass"];
        $db_port=$mysql_ini_array["port"];
        $db_name=$mysql_ini_array["dbName"];
	//Here we connect to the database using the information from /etc/hw5-mysql.conf
        $db = mysqli_connect($db_host, $db_user, $db_pass, $db_name, $db_port); 
        //Print an error message if we do not connect.
	if (!$db) { 
                print "<br> Error connecting to MySQL Database. <br> : " . mysqli_connect_error();
                exit; 
        }
}
//To check that numeric variables are numeric.
function icheck($input)
{
	if($input!=null)
	{ 
		if (!is_numeric($input))
		{	
			print "<b> ERROR: </b> Invalid Syntax. ";
               	 	exit;
		}
	}

}



function authenticate($db, $postUser, $postPass)
{
	if ( $postUser == NULL || $postPass == NULL)
	{
		echo "null variables";
		logout();
	}
	else
	{
		$query = "select userid, email, password, salt from users where username=?";
		if ($stmt = mysqli_prepare($db, $query))
		{
			mysqli_stmt_bind_param($stmt, "s", $postUser);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $userid, $email, $password, $salt);
			while(mysqli_stmt_fetch($stmt))
			{
				$userid=$userid;
				$password=$password;
				$salt=$salt;
				$email=$email;
			}
			mysqli_stmt_close($stmt);
			$epass=hash('sha256', $postPass.$salt);
			if ($epass == $password)
			{
				$_SESSION['userid']=$userid;
				$_SESSION['email']=$email;
				$_SESSION['authenticated']="yes";
				$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];
			}
			else
			{
				echo "Failed to Login";
				header("Location: /hw7/login.php");
				exit;
			}

		}

	}
}

function logout()
{
	session_destroy();
	header("Location: /hw7/login.php");	
}

function generate_salt()
{
	$number = rand (100, 50000);
	return $salt = hash('sha256', $number);	

}

function adminCheck()
{
	if ($_SESSION['userid'] != 1)
	{
		echo "Error: you do not have proper authentication.";
		exit;
	}

}
function authenticateCheck($db, $postUser, $postPass)
{
	if(!isset($_SESSION['authenticated']))
	{
		authenticate($db,$postUser, $postPass);
	}
}
?>

