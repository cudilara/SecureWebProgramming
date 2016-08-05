<?php
// Name     : login.php
// Purpose  : a PHP page that accepts usernames and passwords.
// Author   : Dilara Madinger dilara.madinger@colorado.edu
// Version  : 1.0
// Date:    : 06/30/2016
include_once('header.php');
echo "
 <form method=post action=add.php>
	<table><tr> <td> Username: </td> <td> <input type=text name=postUser>  </td> </tr>
	<tr> <td> Password: </td> <td> <input type=password name=postPass>  </td> </tr>
	<tr> <td colspan=2> <input type=submit name=submit value=Login> </td></tr> 
	</table>
	</form>";
?>
