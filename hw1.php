<?php
#author : Dilara Madinger
#name : hw1.php
#purpose : A guessing number game.
#date : 5/31/2016
#version : 1.0

isset ($_REQUEST['guess']) ? $guess = $_REQUEST['guess'] : $guess = "";
echo"
<html>
 <head> <title> TLEN5839 HW1: Dilara Madinger </title> </head>
 <body>
";

$answer=rand(0,20);

function take_guess()
{
	echo "
	<form method=post action=hw1.php>
	Write a number between 0 and 20:
	<input type=\"text\" name=\"guess\">
	<input type=\"submit\" value=\"Submit\" />
	</form>";
}

if($guess == $answer)
{
	echo "You got it! Your guess is $guess and the answer is $answer.";
}
else
{
	if($guess == Null)
	{
		take_guess();	
	} 
	elseif ($guess < 0 or $guess > 20)
	{
		echo "Error: Your guess is outside of the range. Numbers must be between 0 and 20.";
		take_guess();
	} 
	elseif ($guess < $answer and $guess >= 0)
	{
		echo "The number is too small! Your guess is $guess and the answer is $answer. Try again!";
		take_guess();

	} 
	elseif ($guess > $answer and $guess <= 20)
	{
		echo "The number is too large! Your guess is $guess and the answer is $answer. Try again!";
		take_guess();
	}
} 
?>
