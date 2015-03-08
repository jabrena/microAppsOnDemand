<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Client</title>
	<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/autosize.js/1.18.17/jquery.autosize.min.js"></script>

<script>

var usersTo = ["juanantoniobm", "isuriv", "DepressedDarth"];
var usersFrom = ["androides", "motorolaes"];

var TwitterConnection = {
  isFollowedBy: function(index,userFrom,userTo){
    var promise = $.Deferred();

    $.ajax('http://juanantonio.info/tests/twitter1/API/0.1/isFollowedBy/', {
      data: { userFrom: userFrom, userTo : userTo},
      indexValue: index,
      success: function(result){
      	//Append index to result
      	result["index"] = this.indexValue;
        promise.resolve(result);
      },
      error: function(){
        var error = 'invalid location';
        promise.reject(error);
      }
    });

    return promise;
  }
}

function getCombinations(param){

	return param * 2;
}

var flagShow = false;

var arrResult = Array();
var combinations = 0;

var arrMap = {};

$(function() {

      $("#btProcess").click( function(){
				var lines = $('#mytextarea').val().split(/\n/);
				var usersTo = []
				for (var i=0; i < lines.length; i++) {
				  // only push this line if it contains a non whitespace character.
				  if (/\S/.test(lines[i])) {
				    usersTo.push($.trim(lines[i]));
				    arrMap[$.trim(lines[i])] = { account1: null, account2: null, result: null};
				  }
				}

				combinations = getCombinations(usersTo.length);

				var k = 0;
				for(var i=0; i<usersFrom.length; i++){
					for(var j=0; j<usersTo.length; j++){

						TwitterConnection.isFollowedBy(k, usersFrom[i],usersTo[j]).done(function(result){
							arrResult[result.index] = {"index": result.index, "userFrom": result.userFrom, "userTo":result.userTo,"isFollowedBy": result.isFollowedBy};
						});

						//Detect last iteration
						if(k == combinations-1){
							flagShow = true;
						}

						k++;
					}
				}

           }
      );
});

function updateMap(){

	for(var z=0;z<arrResult.length;z++){
		try{
			var key = arrResult[z].userTo;
			var account = arrResult[z].userFrom;
			var result = arrResult[z].isFollowedBy;
			
			var value = arrMap[key];
			if(account == "androides"){
				value.account1 = result;
				arrMap[key] = value;
			}else{
				value.account2 = result;
				arrMap[key] = value;
			}
			//console.log(value.account1);
		}catch(err) {}
	}
}

function determinateResult(){
	for(key in arrMap) {
		var value = arrMap[key];
		
		if((value.account1 == true) && (value.account2 == true)){
			value.result = "11";
		}else if((value.account1 == false) && (value.account2 == false)){
			value.result = "00";
		}else if((value.account1 == true) && (value.account2 == false)){
			value.result = "10";
		}else if((value.account1 == false) && (value.account2 == true)){
			value.result = "01";
		}

		//console.log(value);
		arrMap[key] = value;
	}
}

function show2(){

	var htmlString = "";
	htmlString+="<table>";
	for(key in arrMap) {
		var value = arrMap[key];
		
		htmlString+="<tr>";
		htmlString += "<td>" + key + "</td><td> androides: " + value.account1 + "</td><td> motorolaes: " + value.account2 + "</td><td> result: " + value.result +"</td>";
		htmlString+="</tr>";

		
	}
	htmlString+="</table>";
	$('#results').html(htmlString);

}

$(document).ready(function(){
    $('textarea').autosize();

    var myVar = setInterval(function(){
    	if(flagShow){
    		if(arrResult.length == combinations){

    			updateMap();
    			determinateResult();
    			show2();
    			clearInterval(myVar);
    		}
    		//console.log(flagShow);
    	}
    	 
    }, 100);   
});



</script>

</head>
<body>
<form>
<textarea id="mytextarea">
juanantoniobm
isuriv
DepressedDarth
</textarea>
<button type="button" id="btProcess">Click Me!</button>
</form>
<div id="results"></div>
</body>
</html>