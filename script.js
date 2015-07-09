/**************************************
	 INITIAL SETTINGS AND VARIABLES
**************************************/

TempObject = {};

/*********************************
			SETUP
*********************************/


$(document).ready(function() {

	//get json from database
	$.ajax({
		url: 'ajax.php',
		async: false,
		success: function(data) {
			users = data;
		}
	});

	if (users !== "")
		users = $.parseJSON(users);
	else
		users = {};

	// assigns a count for items in list
	var counter = 0;

	// creates the debt table
	$('#debt-wrapper').append('<table id="debt"></table>');

	// format price field value when it loses focus
	$('#priceData').change(function() {
		var string = $(this).val();
		string = Number(string).toFixed(2);

		$(this).val(string);
	});

	// provides instant validation for owersData feild 
	$('#owersData').keyup(function() {
		var input = $(this).val().toLowerCase();
		var currentOwersArray = input.split(" ");
		
		for (i in currentOwersArray) {
			for (k in users) {
				if(k == currentOwersArray[i]) {

				}
			}
		}
	})

	$('#add').click(function() {

		// sets data from input fields to global variables
		getFieldData();

		// basic form validation
		if (buyer == "" || owers == "") {
			alert("Looks like you missed something. Make sure all the fields are complete.");
			return false;
		}
		if(allNumeric(price) == false){
			return false;
		}

		// validates that owers and buyer are users
		itemOwers = owers.split(" ");

		if (validateOwers() == false || validateBuyer() == false) {
			return false;
		}
		
		// validates price is within normal range
		if(price > 40) {
			var answer = confirm("Woah there, Millionaire Bob. $" + price + " is a lot. Are you sure you didn't add an extra zero?");
			if(answer == false) {
				return;
			}
		}

		// updates list totals
		updateTempObj();

		// adds items to the list
	    $('#add_form').before('<tr class="item' + counter + '"><td class="item' + counter + 
	    		' item-buyer">' + capitalizeFirst(buyer) + '</td><td class="item' + counter + 
	    		' item-name">' + capitalizeFirst(itemName) + '</td><td class="item' + counter + 
	    		' item-price">$' + parseFloat(price).toFixed(2) + '</td><td class="item' + counter + 
	    		' item-owers">' + newArray() + '</td><td class="item' + counter + 
	    		' item-delete"><button type="button" class="item' + counter + 
	    		' trashcan' + counter + ' delete">Can</button></td></tr>');

	    // add a corresponding object for the added list element, in order to easily extract data later if removed
		window["item" + counter] = new ListItem(buyer, price, itemOwers);
		
	    // removes an item
		$('.trashcan' + counter).click(function() {
			var className = $(this).attr("class").split(' ')[0];
			$('.' + className).remove();

			rebalanceTempObj(className);
		});
  		
  		counter++;

  		// clears html form values on add
		$('input[name=item]').val("");
		$('input[name=price]').val("");
		$('input[name=item]').val("");
		$('input[name=owers]').val("");

  		$('#itemData').focus();

  	});

	// updates the users object with temp object data
	$('#divvy-it').click(function() {
			
		// updates users object with TempObj data
		writeToUsers();

		// refreshes data in debt div
		displayDebt();

		$("[class^='item']").remove();
		TempObject = {};
 
		// write users object to database
		var jsonData = JSON.stringify(users);
		$.post('update.php', {json: jsonData});

	});
	
	$('#addUser').click(function() {
		/*$('#debt-wrapper').append('<form><input type="text" name="new-user" id="new-user" autocomplete="off" placeholder="New User Name" maxlength="20" /><input type="button" name="new-user-submit" id="new-user-submit" value="Add User" /></form>');
		$('#new-user').focus();*/
		createNewUser("newName");

		var jsonData = JSON.stringify(users);
		$.post('update.php', {json: jsonData});
	});

	displayDebt();

	// creates options for the buyers datalist
	for (i in users){
		$('#datalist').append('<option value=' + capitalizeFirst(i) + '>');
	}



});

/*************************
	REFERENCE FUNCTIONS
**************************/

function getFieldData() {
	buyer = $('input[name=buyer]').val().toString().toLowerCase().trim();
	itemName = $('input[name=item]').val();
	price = $('input[name=price]').val();
	owers = $('input[name=owers]').val().toString().toLowerCase().trim();
}

function updateTempObj() {
	var pricePerOwer = price / itemOwers.length;

	for(i = 0; i < itemOwers.length; i++){
		var ower = itemOwers[i];

		if(TempObject[ower] == undefined) {
			TempObject[ower] = {};
		}

		if (ower != buyer){
			if(TempObject[ower][buyer] == undefined) {
				TempObject[ower][buyer] = pricePerOwer;
			} else {
				TempObject[ower][buyer] += pricePerOwer;
			}
		}

	}
}

function writeToUsers() {
	for(i in TempObject) {
		for(k in TempObject[i]) {
			if(users[i][k] == undefined) {
				users[i][k] = TempObject[i][k];
			} else {
				users[i][k] += TempObject[i][k];
			}
		}
	}
}

function validateOwers() {
	for(i in itemOwers) {
		var userFound = false;

		for(k in users){
			if(itemOwers[i] == k) {
				userFound = true;
			}
		}

		if(userFound == false) {
			var answer = confirm("User " + capitalizeFirst(itemOwers[i]) + " was not found. \nWould you like to add " 
				+ capitalizeFirst(itemOwers[i]) + " as a user?");

			if (answer) {
				createNewUser(capitalizeFirst(itemOwers[i]));
			} else {
				return false;
			}
		}
	}
}

function validateBuyer() {
	var userFound = false;

	for (i in users) {
		if(buyer == i) {
			userFound = true;
		}
	}

	if(userFound == false) {
		var answer = confirm("User " + capitalizeFirst(buyer) + " was not found. \nWould you like to add " 
			+ capitalizeFirst(buyer) + " as a user?");

		if (answer) {
			createNewUser(capitalizeFirst(buyer));
		} else {
			return false;
		}
	}
}

function allNumeric(price){
	if (price.trim() === "") {
        alert("Please enter a price");
        price.focus();
        return false;
    }
    if (price.trim() !== "") {
        if (! (/^\d*(?:\.\d{0,2})?$/.test(price))) {
            alert("Please enter a valid price");
            price.focus();
            return false;
        }
    }       
}

function newArray() {
	var newArray = itemOwers.slice(0);
	newArray.sort();

	for (i in newArray) {
		newArray[i] = capitalizeFirst(newArray[i]);
	}
	return newArray.join(", ");
}

function displayDebt() {

	// removes user tr exists, delete it 
	if($('#debt tr').has('td')) {
		$('#debt tr td').remove();
  	} 

    // displays users and their cumulative debt
	for(i in users) {
		var x = capitalizeFirst(i);
		var amount = calculateDebt(i, "users");
		var posOrNeg = (amount >= 0) ? 'positive' : 'negative';
		$('#debt').append('<tr><td class="users">' + x + '</td><td class="status"><span class="' 
					+ posOrNeg + '">' + returnAmount(amount)  + '</span></td></tr>');

	}

}

function capitalizeFirst(str) {
	return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
}

function createNewUser(name) {
	if (name == "newName") {
		var newUser = prompt("Please enter user's name: ");
	} else {
		var newUser = name;
	}

	if (newUser !== "") { 
		newUser = newUser.toLowerCase();
	} else {
		alert("Please enter the name of a user you wish to add");
		createNewUser("newName");
		return;
	}

	for (i in users){
		if (newUser == i) {
			alert("It looks like the user " + capitalizeFirst(newUser) + " already exists. Please choose another name.");
			createNewUser("newName");
			return;
		}
	} 

	users[newUser.toLowerCase()] = {};
	displayDebt();
}

function returnAmount(amount) {
	if (amount >= 0){
		return "$" + amount.toFixed(2);
	} else if (amount < 0) {
		return "\u2212" + "$" + Math.abs(amount).toFixed(2);
	}
}

function calculateDebt(user, object) {

	var debt = getDebt(user, object);
	var counterDebt = getCounterDebt(user, object);

	return Number(Math.round((debt-counterDebt) * 100) / 100);
} 

function getDebt(user, object) {
	var total = 0;

	for (i in window[object][user]) {
		total -= window[object][user][i];
	}

	return Number(total);
} 

function getCounterDebt(user, object) {
	var total = 0;

	for(i in window[object]) {
		for(k in window[object][i]) {
			if (k == user){
				total -= window[object][i][k];
			}
		}
	}

	return Number(total);
}

function rebalanceTempObj(className) {
  var pricePerOwer = window[className].iPrice / window[className].iOwers.length;
  var buy = window[className].iBuyer;
  
  for(var i = 0; i < window[className].iOwers.length; i++) {
    var owe = window[className].iOwers[i];
    if (owe != buy) {
      TempObject[owe][buy] -= pricePerOwer;
    }
  }
}

/***************************
		CONSTRUCTORS
****************************/

// for objects made to hold temporary data for list items
function ListItem(iBuyer,iPrice, iOwers) {
	this.iBuyer = iBuyer;
	this.iPrice = iPrice;
	this.iOwers= iOwers;
}
