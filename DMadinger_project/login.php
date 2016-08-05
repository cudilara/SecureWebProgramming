<?php
// Name     : login.php
// Purpose  : Allows to login or register.
// Author   : Dilara Madinger dilara.madinger@colorado.edu
// Version  : 1.0
// Date:    : 07/12/2016
include_once('header.php');
include_once('project-lib.php');
connect($db);
icheck($s);

switch($s)

{
	default:
	case 1:
		echo "
			<table> <tr> <td>  <b> Login </b> </td></tr> \n
			<form method=post action=placeOrder.php?s=1>
			<tr> <td> Username: </td> <td> <input type=text name=postUser>  </td> </tr>
			<tr> <td> Password: </td> <td> <input type=password name=postPass>  </td> </tr>
			<tr> <td colspan=2> <input type=submit name=submit value=Login> </td></tr>

			</table>
			</form>";
	break;

	case 10:
		echo "
			<table>	
			<tr> <td>  <b> Register </b> </td></tr> \n
			<form method=post action=placeOrder.php?s=50>
        		<tr> <td> Name: </td> <td> <input type=text name=postName>  </td> </tr>
        		<tr> <td> Email: </td> <td> <input type=text name=postEmail>  </td> </tr>
			<tr> <td> Username: </td> <td> <input type=text name=postUser>  </td> </tr>
        		<tr> <td> Password: </td> <td> <input type=password name=postPass>  </td> </tr>
			<tr> <td colspan=2> <input type=submit name=submit value=Login> </td></tr>

			</table>
			</form>";
	break;

	case 20:
		logout();
	break;
}
?>

