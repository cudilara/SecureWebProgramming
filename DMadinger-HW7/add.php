<?php
session_start();
// Name     : add.php
// Purpose  : a PHP page that handles adding to the database.
// Author   : Dilara Madinger dilara.madinger@colorado.edu
// Version  : 3.0
// Date:    : 06/29/2016
include_once('header.php');
include_once('/var/www/html/hw7/hw7-lib.php');
connect($db);

if (!isset($_SESSION['authenticated']))
{
	authenticate($db, $postUser, $postPass);
}

icheck($s);
icheck($sid);
icheck($cid);
icheck($bid);
icheck($pid);

switch($s) {
	 
	case 4:
		echo "
		<form method=post action=add.php>
		     <table> 
			<tr> 
				<td colspan=2> Add Character to Books </td> 
			</tr>
			<tr> 
				<td> Character Name </td>
		          	<td> <input type=text name=characterName value=\"\"></td>
			</tr>
			<tr> 
				<td> Race </td>
                          	<td> <input type=text name=characterRace value=\"\"></td>
			</tr>
			<tr> 
				<td> Side </td>
                          	<td> <input type=radio name=characterSide value='Good'>Good
				<input type=radio name=characterSide value='Evil'>Evil</td>
			</tr>	
 			<tr> 
				<td colspan=2> <input type=hidden name=s value=5> 
				<input type=submit name=submit value=submit> </td>
			</tr>	 
		     </table> 
		</form> ";
        break;
	case 5:
        $characterName=mysqli_real_escape_string($db, $characterName);
		$characterRace=mysqli_real_escape_string($db, $characterRace);
		$characterSide=mysqli_real_escape_string($db, $characterSide);
                if ($stmt = mysqli_prepare($db, "INSERT INTO characters set characterid='', name=?, race=?, side=?;")) {
                        mysqli_stmt_bind_param($stmt, "sss", $characterName, $characterRace, $characterSide);
                        mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		if ($stmt = mysqli_prepare($db, "SELECT characterid from characters where name=? and race=? and side=? order by characterid desc limit 1")){
                        mysqli_stmt_bind_param($stmt, "sss", $characterName, $characterRace, $characterSide);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $cid);
                        while(mysqli_stmt_fetch($stmt)) {
				$cid=$cid;
			}                        
                        mysqli_stmt_close($stmt);
                }
		else {
			echo "Error with Query";
		}
		
		echo"
		<form method=post action=add.php>
		<table> 
			<tr> 
				<td colspan=2> Add Picture to Character $characterName </td> 
			</tr>
			
			<tr>
				<td> Character Picture URL </td>
				<td> <input type=text name=url value=\"\"> </td>
			</tr>
			
			<tr>
				<td colspan=2>
				<input type=hidden name=s value=7>
				<input type=hidden name=cid value=$cid>
				<input type=hidden name=characterName value=$characterName>
				<input type=submit name=submit value=submit> </td>			
			</tr>
		</table>
		</form><br> <br> <a href=add.php?s=7> Logout </a> <br>";
        break;
	case 7:
		$url=mysqli_real_escape_string($db, $url);
		$cid=mysqli_real_escape_string($db, $cid);	 	
if ($stmt = mysqli_prepare($db, "INSERT INTO pictures set pictureid='', url=?, characterid=?")) {
                        mysqli_stmt_bind_param($stmt, "ss", $url, $cid);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_close($stmt);
                }
                else {
                        echo "Error with Query";
                }
		 echo"

			 <form method=post action=add.php>
                <table>
                        <tr>
                                <td> Added Picture for $characterName </td>
                        </tr>
                        <tr>
                                <td>
                                <input type=hidden name=s value=8>
                                <input type=hidden name=cid value=$cid>
                                <input type=hidden name=characterName value=$characterName>
                                <input type=submit name=submit value=\"Add Character to Books\">
                                </td>
                        </tr>
                </table>
		</form><br> <br> <a href=add.php?s=8> Logout </a> <br>";
	break;	
	case 8:
		echo"
		<form method=post action=add.php>
                <table>
                <tr>
                        <td colspan=2> Add  to Books </td>
                <tr>
                        <td> Select Book</td>
		<td>";
		if ($bid != 0)
		{
 			$bid=mysqli_real_escape_string($db, $bid);
                	$cid=mysqli_real_escape_string($db, $cid);
			if ($stmt = mysqli_prepare($db, "INSERT INTO appears set appearsid='', bookid=?, characterid=?;")) 
			{
                        	mysqli_stmt_bind_param($stmt, "ss", $bid, $cid);
                        	mysqli_stmt_execute($stmt);
                       		mysqli_stmt_close($stmt);
                	}
		}


		if ($stmt = mysqli_prepare($db, "SELECT distinct(a.bookid), b.title FROM books b, appears a WHERE a.bookid NOT IN (SELECT bookid FROM appears WHERE characterid=?) AND b.bookid=a.bookid"))
		{
			mysqli_stmt_bind_param($stmt, "s", $cid);
                	mysqli_stmt_execute($stmt);
                	mysqli_stmt_bind_result($stmt, $bid, $title);
			echo"	<select name=\"bid\">";
                	while(mysqli_stmt_fetch($stmt)) 
			{
				echo "<option value=$title>$title</option>
					<a href=add.php?s=8>";
			}
			if ($bid != 0)
			{
				echo"<td><a href=index.php?s=3> Done </a></td>"; 
			}
 
		}
               	else 
		{
                	echo "Error with Query";
                }

                        mysqli_stmt_close($stmt);
		echo
		"
		</td>
		</tr>
				</select> 
		<tr> 
			<td><input type=hidden name=s value=9>
			<input type=hidden name=cid value=$cid>
			<input type=hidden name=characterName value=$characterName>
			<input type=submit name=submit value=\"Add to Book\">
			</td>
		</tr> 
		</table> 
		</form><br> <br> <a href=index.php?s=0> Logout </a> <br>
		";
	break;
	case 90:
		adminCheck();
		echo
		"
		<form method=post action=add.php?s=91>
		<tr><td> Add Users to Tolkien App </td></tr>
        	<table><tr> <td> Username: </td> <td> <input type=text name=postUser>  </td> </tr>
        	<tr> <td> Password: </td> <td> <input type=password name=postPass>  </td> </tr>
		<tr> <td> Email: </td> <td> <input type=password name=postEmail>  </td> </tr>
        	<tr> <td colspan=2> <input type=submit name=submit value=submit> </td></tr>
		</table>
        	</form>
		";
	break;
	
	case 91:
		adminCheck();
		$postUser=mysqli_real_escape_string($db, $postUser);
		$postPass=mysqli_real_escape_string($db, $postPass);
		$postEmail=mysqli_real_escape_string($db, $postEmail);
		

		$salt = generate_salt();
		$ePass = hash('sha256', ($postPass.$salt)); 	

		if ($stmt = mysqli_prepare($db, "INSERT INTO users set userid='', username=?, password=?, salt=?, email=?;"))
		{
			mysqli_stmt_bind_param($stmt, "ssss", $postUser, $ePass, $salt,  $postEmail);
                        mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		}
		
		if ($stmt = mysqli_prepare($db, "SELECT userid from users where username=? and password=? and salt=? and email=? order by userid desc limit 1"))
		{
                        mysqli_stmt_bind_param($stmt, "ssss",  $postUser, $ePass, $salt, $postEmail);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $postUser, $ePass, $salt, $postEmail);
                        while(mysqli_stmt_fetch($stmt)) 
			{
				$postUser=$postUser;
				$ePass=$ePass;
				$salt=$salt;
				$postEmail=$postEmail;
			}                        
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
		$query = "select username from users";
		$result = mysqli_query($db, $query);
		while($row = mysqli_fetch_row($result))
		{
			echo "<tr> <td>$row[0] </td></tr>";

		}
		mysqli_stmt_close($query);
		 echo
	        "<br> <a href=add.php?s=96> Update Users' Passwords </a>";
	break;

	case 96:
		adminCheck();
		echo
		"
		<form method=post action=add.php?s=97>
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

		if ($stmt = mysqli_prepare($db, "update users set password=?, salt=? where username=?;"))
		{
			mysqli_stmt_bind_param($stmt, "sss", $ePass, $salt, $postUser);
                        mysqli_stmt_execute($stmt);
			mysqli_stmt_close($stmt);
		} 
		
		if ($stmt = mysqli_prepare($db, "SELECT userid from users where password=? and salt=? and username=? order by userid desc limit 1"))
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
 		<br> <a href=add.php?s=99> Logout </a> 
		<br> <a href=add.php?s=90> Add New Users </a>
		<br> <a href=add.php?s=95> Display Users </a>
	"; 
}
else
{
	 echo
        "
                <br> <a href=add.php?s=99> Logout </a>
	";
}

include_once('footer.php');
?>

