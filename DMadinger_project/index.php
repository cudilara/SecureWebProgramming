<?php
// Name     : index.php
// Purpose  : Provides Ingredients functionality.
// Author   : Dilara Madinger dilara.madinger@colorado.edu
// Version  : 1.0
// Date:    : 07/12/2016
include_once('header.php');
include_once('/var/www/html/project/project-lib.php');
connect($db);
switch($s) 
{ 
	case 0:
	default:
		echo "<table> <tr> <td>  <b>  Menu </b> </td></tr> \n ";
		$query = "select name, price from menu";
		$result=mysqli_query($db, $query);
		echo "<tr> <td><b> Item</b> </a></td>
        	<td><b> Price</b> </a></td></tr> \n
        	";
		while($row=mysqli_fetch_row($result)) 
		{ 
			echo "<tr> <td> $row[0] </a></td>
			<td> $row[1] </a></td></tr> \n
			";
		}

		echo "<tr> <td>  <b>  Address: Boulder, CO. </b> </td></tr> \n ";
		echo "</table>";
	break;


	case 1:
		echo "<table> <tr> <td>  <b>  Ingredients </b> </td></tr> \n ";
		$query = "select name, ingredients from menu";
                $result=mysqli_query($db, $query);
		while($row=mysqli_fetch_row($result))
                {
                	echo "<tr> <td> $row[0] </a></td>
                        <td> $row[1] </a></td></tr> \n
                        ";
                }
		echo "</table>";
	break;
}
include_once('footer.php');
?>
