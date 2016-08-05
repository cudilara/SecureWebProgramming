<?php
// Name     : index.php
// Purpose  : a PHP page that is connected to a database.
// Author   : Dilara Madinger dilara.madinger@colorado.edu
// Version  : 2.0
// Date:    : 06/29/2016
//Resources: I took the query for case 3 from Eric Lobatto's GitHub page.
//https://github.com/elobato92/Secure-Web-Programming/blob/master/hw5/index.php
include_once('header.php');
include_once('/var/www/html/hw7/hw7-lib.php');
//include_once('add.php');
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

	case 50:
		echo "<table> <tr> <td colspan=2> Characters </td></tr> \n ";
                $query = " select name, pictures.url from characters, pictures where pictures.characterid=characters.characterid;";
                $result = mysqli_query($db, $query);
                while($row = mysqli_fetch_row($result))
                {
                        echo "<tr> 
			<td><a href=index.php?>$row[0] </a></td>
			<td><mg src=$row[1]></td>
			</tr> \n";
                }
		  mysqli_stmt_close($stmt);

                echo "</table>";	
	break;
}
include_once('footer.php');
?>

