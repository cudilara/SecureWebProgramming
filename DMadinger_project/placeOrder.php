<?php
session_start();//initializing session.
$_SESSION['userid']="1";
$_SESSION['authenticated']="yes";
// Name     : placeOrder.php
// Purpose  : Allows to place order and pay.
// Author   : Dilara Madinger dilara.madinger@colorado.edu
// Version  : 1.0
// Date:    : 07/12/2016
include_once('header.php');
include_once('/var/www/html/project/project-lib.php');

connect($db);

if (!isset($_SESSION['authenticated']))
{
	authenticate($db, $postUser, $postPass);
}

icheck($s);

switch($s) 
{ 
	case 1:
		echo "<form method = post action = placeOrder.php?s=2>
		<table> <tr> <td>  <b>Place Order for Easy Pick-Up </b> </td></tr> \n 
		<tr><td> Choose one or more items.</td><td>";
		$query = "select name, price from menu;";
		$result = mysqli_query($db, $query);
		while($row = mysqli_fetch_row($result))
		{
			echo"
			<tr> 
				<td>  $row[0] \$$row[1] </td>
                          	<td> <input type=radio name=foodChoice value='Choice'></td>
			</tr>\n";
                }
		echo " <td>
			<select name=\"pickTime\">
			<option value=\"\"> Time for Pickup</option>
			<option value=\"1\">1:00 </option>
			<option value=\"2\">2:00 </option>
			<option value=\"3\">3:00 </option>
			<option value=\"4\">4:00 </option>
			</select></td>
		<input type=hidden name=step value=2>\n
		<tr><td> <input type=submit name=submit value=submit></td><tr>\n
		<tr> <td>  <b>  Address: Boulder, CO. </b> </td></tr> \n 
		</table>";
	break;
	case 2:
		$foodChoice = mysqli_real_escape_string($db, $foodChoice);
		$pickTime = mysqli_real_escape_string($db, $pickTime);
		$userid = mysqli_real_escape_string($db, $userid);
		if ($stmt = mysqli_prepare($db, "insert into purchasedFood set purchasedFoodID = '', userID = ?, menuID = ?;")) 
		{
                        mysqli_stmt_bind_param($stmt, "ss", $userid, $foodChoice);
                        mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		echo "<table><tr><td> Your Picking Time </td></tr>
		<tr><td> At $pickTime:00.
                </table>
		</form>";


	break;
	case 10:
		echo "in case 10";
	break;

	case 50:
		$postUser=mysqli_real_escape_string($db, $postUser);
		$postPass=mysqli_real_escape_string($db, $postPass);
		$postEmail=mysqli_real_escape_string($db, $postEmail);

		$salt = generate_salt();
		$ePass = hash('sha256', ($postPass.$salt));
		
		if ($stmt = mysqli_prepare($db, "insert into user set userID='', userName=?, email=?, groupID=?, salt=?, password=?;"))
		{
			mysqli_stmt_bind_param($stmt, "sssss", $postUser, $postEmail,$postGroup, $salt, $ePass);
                        mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		
		else 
		{
			echo "Error with Query";
		}
	echo "Added new user $postUser";
	break;

	case 95:
		adminCheck();
		echo "<table> <tr> <td>  <b> <u> Users </b></u> </td></tr>\n";
		$query = "select userName from user";
		$result = mysqli_query($db, $query);
		while($row = mysqli_fetch_row($result))
		{
			echo "<tr> <td>$row[0] </td></tr>";

		}
		mysqli_stmt_close($query);
		 echo
	        "<br> <a href=placeOrder.php?s=96> Update Users' Passwords </a>";
	break;

	case 96:
		adminCheck();
		echo
		"
		<form method=post action=placeOrder.php?s=97>
		<tr><td> Provide User's Name and New Password </td></tr>
        	<table><tr> <td> Username: </td> <td> <input type=text name=postUser>  </td> </tr>
		<tr> <td> New Password: </td> <td> <input type=password name=postPass>  </td> </tr>
        	<tr> <td colspan=2> <input type=submit name=submit value=submit> </td></tr>
		</table>
        	</form>
		";
	break;	

	case 97:
		adminCheck();
		$postUser=mysqli_real_escape_string($db, $postUser);
		$postPass=mysqli_real_escape_string($db, $postPass);
		$salt = generate_salt();
		$ePass = hash('sha256', ($postPass.$salt));
		if ($stmt = mysqli_prepare($db, "update user set password=?, salt=? where userName=?;"))
		{
			mysqli_stmt_bind_param($stmt, "sss", $ePass, $salt, $postUser);
                        mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		} 
		
		if ($stmt = mysqli_prepare($db, "SELECT userID from user where password=? and salt=? and username=? order by userID desc limit 1"))
		{
                        mysqli_stmt_bind_param($stmt, "sss",$ePass, $salt, $postUser);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt,$ePass, $salt, $postUser);
                        while(mysqli_stmt_fetch($stmt)) 
			{
				$ePass=$ePass;
				$salt=$salt;
				$postUser=$postUser;
			}                        
                        mysqli_stmt_close($stmt);
                }
		
		else 
		{
			echo "Error with Query";
		}
		echo "Updated password for user $postUser";
	break;


	case 99:
		logout();
	break;

}

if ($_SESSION['userid'] == 1)
{
	echo
	"
 		<br> <a href=placeOrder.php?s=99> Logout </a> 
		<br> <a href=placeOrder.php?s=95> Display Users </a>
	"; 
}

else
{
	 echo
        "
                <br> <a href=placeOrder.php?s=99> Logout </a>
	";
}
include_once('footer.php');
?>
