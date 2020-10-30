function nameValidation (value,colname) {
    if (/^[a-z,A-Z,\s,',-]+$/.test(value)) 
    {
      	return [true, ""];   
    } 
    else 
    {
      	return [false, "The "+colname+" "+value+" is wrong.<br/>Please enter the correct "+colname];
    }
}


function phoneValidation (value) {
	if (/^[0-9,\s,(),-,+]+$/.test(value)) 
	{
	       	return [true, ""];   
	} 
	else 
	{
	   	return [false, "The phone number "+value+" is wrong.<br/>Please enter the correct phone number"];
	}
}


function emailValidation(value) {
   	if (/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/.test(value)) 
   	{
   		return [true, ""];   
   	} 
   	else 
   	{
   		return [false, "The email "+value+" is wrong.<br/>Please enter the correct email"];
    }
}

function weightValidation(value){
  if (/^[0-9,.]+$/.test(value)) 
    {
        return [true, ""];   
    } 
    else 
    {
        return [false, "The weight "+value+" is wrong.<br/>Please enter the correct weight"];
    }
}

function waistValidation(value){
  if (/^[0-9,.]+$/.test(value)) 
    {
        return [true, ""];   
    } 
    else 
    {
        return [false, "The waist measure "+value+" is wrong.<br/>Please enter the correct one"];
    }
}

function hipsValidation(value){
  if (/^[0-9,.]+$/.test(value)) 
    {
        return [true, ""];   
    } 
    else 
    {
        return [false, "The hip measure "+value+" is wrong.<br/>Please enter the correct one"];
    }
}

function armsValidation(value){
  if (/^[0-9,.]+$/.test(value)) 
    {
        return [true, ""];   
    } 
    else 
    {
        return [false, "The arm measure "+value+" is wrong.<br/>Please enter the correct one"];
    }
}