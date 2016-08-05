<?php
// Name : program1.php
// Purpose : PHP practice. Drop-down menu and summary.
// Author : Dilara Madinger dilara.madinger@colorado.edu
// Version : 1.0
// Date : 6/3/2016
isset ($_REQUEST['step']) ? $step = $_REQUEST['step'] : $step = "";
isset ($_REQUEST['first']) ? $first = $_REQUEST['first'] : $first = "";
isset ($_REQUEST['last']) ? $last = $_REQUEST['last'] : $last = "";
isset ($_REQUEST['email']) ? $email = $_REQUEST['email'] : $email = "";
isset ($_REQUEST['major']) ? $major = $_REQUEST['major'] : $major = "";
isset ($_REQUEST['graduation_date']) ? $graduation_date = $_REQUEST['graduation_date'] : $graduation_date = "";
//Setting the title of the page.
echo"
<html>
  <head> <title> TLEN5839 HW2: Dilara Madinger </title> </head>
</html>";
switch($step)
{
	case 0:
	default:
		echo "What is your first name?
		     <form method=post action=program1.php>
 		     <input type=text name=\"first\">
		     <input type=hidden name=step value=1>
		     <input type=submit name=\"submit\" value=\"submit\">
		     </form>";
	break;
	case "1":
		echo "What is your last name?
		     <form method=post action=program1.php>
 		     <input type=text name=\"last\">
		     <input type=hidden name=\"first\" value=\"$first\">
		     <input type=hidden name=step value=2>
		     <input type=submit name=\"submit\" value=\"submit\">
		     </form>";
	break;
	case "2":
		echo "Hello $first $last, what is your email?
		     <form method=post action=program1.php>
 		     <input type=text name=\"email\">
		     <input type=hidden name=\"first\" value=\"$first\">
		     <input type=hidden name=\"last\" value=\"$last\">
		     <input type=hidden name=step value=3>
		     <input type=submit name=\"submit\" value=\"submit\">
		     </form>";
	break;
	case "3":
		echo "What is your major?
		     <form method=post action=program1.php>
 		     <input type=text name=\"major\">
		     <input type=hidden name=\"first\" value=\"$first\">
		     <input type=hidden name=\"last\" value=\"$last\">
		     <input type=hidden name=\"email\" value=\"$email\">
		     <input type=hidden name=step value=4>
		     <input type=submit name=\"submit\" value=\"submit\">
		     </form>";
	break;
	case "4":
		echo "When do you graduate?
		     <form method=post action=program1.php>
 		     <input type=text name=\"graduation_date\">
		     <input type=hidden name=\"first\" value=\"$first\">
		     <input type=hidden name=\"last\" value=\"$last\">
		     <input type=hidden name=\"email\" value=\"$email\">
		     <input type=hidden name=\"email\" value=\"$major\">
		     <input type=hidden name=step value=5>
		     <input type=submit name=\"submit\" value=\"submit\">
		     </form>";
	break;
	case "5":
		print_summary($first, $last, $email, $graduation_date);
	break;
}
function print_summary($first, $last, $email, $graduation_date)
{
	echo "Summary<br />\n
	      Name: $first $last<br />\n
	      Email: $email<br />\n
	      Graduation: $graduation_date<br />\n";
}
?>