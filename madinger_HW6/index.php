<?php
// Name     : hw6.php
// Purpose  : a PHP page that is connected to a database.
// Author   : Dilara Madinger dilara.madinger@colorado.edu
// Version  : 2.0
// Date:    : 06/22/2016
//Resources: I took the query for case 3 from Eric Lobatto's GitHub page.
//https://github.com/elobato92/Secure-Web-Programming/blob/master/hw5/index.php
include_once('header.php');
include_once('/var/www/html/hw6/hw6-lib.php');
connect($db);
icheck($s);
icheck($sid);
icheck($cid);
icheck($bid);
icheck($pid);

switch($s) { 
	case 0:
	default:
		echo "<table> <tr> <td>  <b> <u> Stories </b></u> </td></tr> \n ";
		$query="SELECT storyid, story from stories";
		$result=mysqli_query($db, $query);
		//The variable row is an array holding the output of the query.
		while($row=mysqli_fetch_row($result)) { 
			//Dynamically generates a link. 
			echo "<tr> <td> <a href=index.php?sid=$row[0]&s=1> $row[1] </a></td></tr> \n";
		}
		echo "</table>";
	break;
	case 1:
		echo "<table> <tr> <td> <b> <u> Books </b></u> </td></tr> \n ";
		//Function escapes special characters in a string for use in an SQL statement.
		$sid=mysqli_real_escape_string($db, $sid);
		if ($stmt = mysqli_prepare($db, "SELECT bookid, title FROM books WHERE storyid = ?")) {
			mysqli_stmt_bind_param($stmt, "s", $sid);//binds variables to a prepared statement as parameters. 
			mysqli_stmt_execute($stmt);//returns a boolean. 
			mysqli_stmt_bind_result($stmt, $bid, $title);//binds variables to a prepared statement for result storage.
			//fetch results from a prepared statement into the bound variables.
			while(mysqli_stmt_fetch($stmt)) {
				$bid=htmlspecialchars($bid);
				$title=htmlspecialchars($title);
				echo "<tr> <td> <a href=index.php?bid=$bid&s=2> $title </a></td></tr> \n";
			}
			mysqli_stmt_close($stmt);
        	}
		echo "</table>";
	break;

	case 2: 
		echo "<table> <tr> <td> <b> <u> Characters </b></u> </td></tr> \n ";
                $bid=mysqli_real_escape_string($db, $bid);
                if ($stmt = mysqli_prepare($db, "select characters.characterid,name from books, characters, appears where appears.bookid=books.bookid and appears.characterid=characters.characterid and books.bookid=?;")) {
                        mysqli_stmt_bind_param($stmt, "s", $bid);
                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $cid, $name);
                        while(mysqli_stmt_fetch($stmt)) {
                                $cid=htmlspecialchars($cid);
                                $name=htmlspecialchars($name);
                                echo "<tr> <td> <a href=index.php?cid=$cid&s=3> $name </a></td></tr> \n";
                        }
                        mysqli_stmt_close($stmt);
                }
                echo "</table>";
        break;
	case 3:
               echo "<table> <tr> <td> <b> <u> Appearances </b></u> </td></tr> \n ";
               $cid=mysqli_real_escape_string($db, $cid);
               if ($stmt = mysqli_prepare($db, 
		"SELECT name, title, story
                        FROM (SELECT A.name, A.characterid, A.title, B.story
                              FROM (SELECT A.name, A.characterid, B.title, B.storyid
                                    FROM (SELECT A.appearsid, A.bookid, A.characterid, B.name
                                          FROM appears A
                                          INNER JOIN characters B
                                          ON A.characterid = B.characterid) A
                                    INNER JOIN books B
                                    ON A.bookid=B.bookid) A
                              INNER JOIN stories B
                              ON A.storyid=B.storyid) A
                        WHERE A.characterid=?;"))
		{
                       mysqli_stmt_bind_param($stmt, "s", $cid);
                       mysqli_stmt_execute($stmt);
                       mysqli_stmt_bind_result($stmt, $name, $title, $story);
                       while(mysqli_stmt_fetch($stmt)) {
                               $bid=htmlspecialchars($name);
                               $title=htmlspecialchars($title);
			       $story=htmlspecialchars($story);
                               echo "<tr> 
					<td><a href=index.php?>$name </a></td>
					<td><a href=index.php?>$title </a></td>
					<td><a href=index.php?>$story </a></td>
				    </tr> \n";
                       }
                       mysqli_stmt_close($stmt);
                }
                echo "</table>";
        break;

	case 4:
		echo "
		<form method=post action=index.php>
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
		</form>
		<br> <br> <a href=index.php?s=5> Logout </a> <br> ";
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
		<form method=post action=index.php>
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
		</form><br> <br> <a href=index.php?s=7> Logout </a> <br>";
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

			 <form method=post action=index.php>
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
		<form method=post action=index.php>
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
					<a href=index.php?s=8>";
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
		echo"
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
		</form><br> <br> <a href=index.php?s=0> Logout </a> <br>";
	break;
}
include_once('footer.php');
?>
