<?php
// Name: program2.php
// Purpose: PHP practice. Show day of the week or print a file in the drop-down menu.
// Author: Dilara Madinger dilara.madinger@colorado.edu
// Version: 1.1
// Date 6/6/2016
isset ($_REQUEST['files']) ? $files = $_REQUEST['files'] : $files = "";
isset ($_REQUEST['SubmitForm']) ? $SubmitForm = $_REQUEST['SubmitForm'] : $SubmitForm = "";
//Header
echo"
<html>
   <head> <title> TLEN5839 HW2: Dilara Madinger </title> </head>";
//Variables
$day_number = 0;
$line_count = 0;
$first_char = '';
//Array
$days = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
//function
function print_file($file)
{
	
	if (file_exists($file))
	{
		$given_file = file($file);
		foreach ($given_file as $line)
		{	
			$first_char = substr($line, 0, 1);
			if ($line_count < 101)
			{
				$line_count++;
				if ($first_char != "#")
				{
					echo "$line<br>";
				}
			}
		}
	}
	else
	{
		echo "Error: the file is not valid.";
	}
}
echo "<body>"; 
switch($files)
{
	default:
	echo"
		<form  action=program2.php method=post>
			<select name=\"files\">
			<option value=\"\"> Select...</option>
			<option value=\"1\">File 1 </option>
			<option value=\"2\">File 2 </option>
			<option value=\"3\">File 3 </option>
			<option value=\"4\">File 4 </option>
			<option value=\"days_of_week\"> Days of Week </option>
			</select>		
		<input type=\"submit\"  value=\"submit\" name=\"SubmitForm\">
		</form>";
	break;
	
	case 1:
		if (is_numeric($files))
		{
			print_file("file1.txt");
		}
		else
		{
			echo "Error. The post variavle for the file is not a digit.";
		}
	break;
	
	case 2:
		if (is_numeric($files))
		{
			print_file("file2.txt");
		}
		else
		{
			echo "Error. The post variavle for the file is not a digit.";
		}
	break;
	
	case 3:
		if (is_numeric($files))
		{
			print_file("file3.txt");
		}
		else
		{
			echo "Error. The post variavle for the file is not a digit.";
		}
	break;
	
	case 4:
		if (is_numeric($files))
		{
			print_file("file4.txt");
		}
		else
		{
			echo "Error. The post variavle for the file is not a digit.";
		}
	break;
	
	case "days_of_week":
		for ($i=0; $i < 7; $i++)
		{
			$day_number = $i + 1;
			echo"Day $day_number of the week is $days[$i]<br />\n";
		}
	break;
}
echo " </body></html>";
?>