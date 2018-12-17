<?php

// Array to store price values 
$prices = array();
//Array to store income values
$income = array();
//required income var
$priceToPay;


function jsonDataValuesTaker(){
//Path of json file
$url = 'functions/datain.json';
//file marge into varable
$data = file_get_contents($url);
//decode json
$jsonDataValues = json_decode($data , true);
//loops for all taken array 
foreach ($jsonDataValues['sms_list'] as $jsonDataValue) {
//asign prices array values
    array_push($GLOBALS['prices'], $jsonDataValue['price']);
//asing income array values
	array_push($GLOBALS['income'], $jsonDataValue['income']);	
}
//asign req_income values
$GLOBALS['priceToPay']=$jsonDataValues['required_income'];

}

//method/function to sort(in reverse order) both arrays .Implementing that we have only two of them
function sortArrays()
{
	//sorting prices array
	rsort($GLOBALS['prices']);
	//sorting incomes array
	rsort($GLOBALS['income']);
	
}





//fucntion to test if array is not empty
function checkIfArrayNotEmpty(array $array)
{	// if for test and count for check if it's not eq for 0
	if(count($array))
	{
		//if it's not eq  to 0 it's return true
		return true;

	}
	//overwise it's return false if it's empty
	else
	{
		return false;
	}

}
//funciton to check if array is numeric 
function checkIfNumberic(array $array)
{//loop for all array to check
	foreach ($array as $key) {
		//loopig and checkiing if array is equal to integer or float data types.
		if(is_int($key) or is_float($key))
		{
			//doesnt return anything , this way because it's more clear to see what's going on
		}
		else
		{	//if it's finds not wanted data tybes it's returns false
			return false;
		}
	}
	//if everything okey to array it's return true
	return true;
}
//function to check if array has negative number
function checkIfNotNegative(array $array)
{//min() find smallest number and compire to >0
	if(min($array)>0)
	{
		//if it's more than 0 it's return true 
		return true;
	}
	else
	{//if not it's return false
		return false;
	}
}

//function that check every testing function and finds if array is good to use for code.
function fullTest(array $array){
//checks array content
if(checkIfArrayNotEmpty($array))
{	//checks array datatypes.
	if(checkIfNumberic($array))
	{//checks array it's negative or not
		if(checkIfNotNegative($array))
		{//if correct return true
			return true;
		}
		else
		{
			//error mess + return false if somethign is incorrect.
			echo "Negative number";
			return false;
		}
	}
	else
	{
		echo("Not num");
	}
}
else
{
	echo("Empty array");
}
};
//function to call all array
function testArray(array $array)
{	//calls and check if array is correct to use
	if(fullTest($GLOBALS['prices']))
	{	//do nothing
		echo "";
	}
	//array is wrong otherwise , error comes from testing methodds.
}


//Arrat if prices paid
$priceToPayList = array();

//function to get array length
function arrayLength(array $array)
{
	return (count($array)-1);
}

//Algorithm function of calc
function actionFunction(array $array1 , array $array2 , $value)
{
	//value of array looping
	$sk = 0;

	//$pricePaid = 2;

	$valuePrice = $value;
	//while loop to calculate everyting
	while($valuePrice>0)
	{	//second loop to jump between different numbers
			while($valuePrice>=$array1[$sk])
			{//print values on the screen
				echo $valuePrice . " - " . $array1[$sk] . " = ";
				//values asigning
				$valuePrice -= $array2[$sk];
				//more priting
				echo $valuePrice . "<br>";
				//setting values into array
				array_push($GLOBALS['priceToPayList'], $array1[$sk]);
				//Looper 'heloper' to just do actio if our price we are paying goes down zero
				if($valuePrice<$array1[arrayLength($array1)] && $valuePrice!=0)
				{
					//more priting
					echo $valuePrice . " - " . $array1[arrayLength($array1)] . " = ";
					//setting values
					$valuePrice -= $array2[$sk];
					//printing values on screen //FOR PROGRESS
					echo $valuePrice . "<br>";
					//putting values into array
					array_push($GLOBALS['priceToPayList'], $array1[arrayLength($array1)]);						
				}
			}
	//looper
		$sk++;
	}
	

}




//function to put data from calculation to json
function sendDataToJson(array $arry){

		//path of json file where we want to put data
		$url = "functions/dataout.json";
		//we make json data type
		$arrayJsonData = json_encode($arry);
		//checking if json file exist in path
		if(file_exists($url))
		{	//check if we can put data to json file
			if(file_put_contents($url, $arrayJsonData))
			{	//good
				print_r($arrayJsonData);
			}
			else
			{
				echo "Bug in putting data to json file";
			}
		}
		else
		{
			echo "File Doesnt exist needed for storing data";
		}

}


function printArray(array $arr)
{
	foreach ($arr as $key) {
		# code...
		echo $key . "<br>";
	}
}

//main method
function action()
{
	//takes array from json file
	jsonDataValuesTaker();
	//let test it
	testArray($GLOBALS['prices']);
	//sorting array
	sortArrays($GLOBALS['prices']);
	//sorting array
	sortArrays($GLOBALS['income']);
	echo $GLOBALS['priceToPay'] . "<br>";
	//function with calculate price and sms amount
	actionFunction($GLOBALS['prices'], $GLOBALS['income'],$GLOBALS['priceToPay']);
	//cast method to put array to json file
	sendDataToJson($GLOBALS['priceToPayList']);

}

action();

?>