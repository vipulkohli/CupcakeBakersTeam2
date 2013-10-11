

//globals that will be used

var userId = -1;

var flavors;
var icings;
var fillings;
var toppings;

var currentFlavor = -1;
var currentIcing = -1;
var currentFilling = -1;
var currentToppings = [];
var currentQuantity = 1;

var totalPrice = 0.0;

var favorites_data = [];
var order_data = [];

var selectedOrder = -1;

function loadCupcakeData() {

    // Load Flavors from JSON using an XMLHttp Request
    var flavorRequest = new XMLHttpRequest();
    flavorRequest.open("GET","./api/index.php/flavors",true);
    flavorRequest.onreadystatechange = function(e) {
        if (flavorRequest.readyState === 4) {

            flavors = JSON.parse(flavorRequest.responseText);
        }
    }
    flavorRequest.send();

    // Load Icings from JSON
    var icingRequest = new XMLHttpRequest();
    icingRequest.open("GET","./api/index.php/icings",true);
    icingRequest.onreadystatechange = function(e) {
        if (icingRequest.readyState === 4) {

            icings = JSON.parse(icingRequest.responseText);
        }
    }
    icingRequest.send();

    // Load Fillings from JSON
    var fillingRequest = new XMLHttpRequest();
    fillingRequest.open("GET","./api/index.php/fillings",true);
    fillingRequest.onreadystatechange = function(e) {
        if (fillingRequest.readyState === 4) {

            fillings = JSON.parse(fillingRequest.responseText);
        }
    }
    fillingRequest.send();

    // Load Toppings from JSON
    var toppingRequest = new XMLHttpRequest();
    toppingRequest.open("GET","./api/index.php/toppings",true);
    toppingRequest.onreadystatechange = function(e) {
        if (toppingRequest.readyState === 4) {

            toppings = JSON.parse(toppingRequest.responseText);
        }
    }
    toppingRequest.send();
}


// Load all favorites using REST API
function loadFavorites() {

    var favorites_menu = $('#favoritesMenu');

    var request = new XMLHttpRequest();
    request.open("GET","./api/index.php/users/" + userId + "/favorites",true);
    request.onreadystatechange = function(e) {
        if (request.readyState === 4) {

            favorites_data = JSON.parse(request.responseText);

            // Insert each favorite into the menu
            for (var i = 0; i < favorites_data.length; i++) {
                var favorite_data = favorites_data[i];

                var favorite_element = $(document.createElement('div'));
                favorite_element.addClass('favoriteItem');
                favorite_element.append("<img src='resources/artwork/cupcake_icon.png' />");
                
                var label = $(document.createElement('label'));

                if (favorite_data['name']) {

                    // Use the name given to the cupcake design
                    label.append(favorite_data['name']);
                } else {

                    // If the name does not exists, then use the name of the cupcake flavor
                    // Search the cupcake flavors to find the one with the matching id.
                    for (var j = 0; j < flavors.length; j++) {
                        if (favorite_data['cupcake'].flavor_id == flavors[j].id) {

                            // Use the cupcake flavor name as the name of the favorite cupcake design
                            label.append(flavors[j].name);
                        }
                    }
                }

                favorite_element.append(label);
                favorite_element.click(loadFromFavorites);
                favorites_menu.append(favorite_element);
                
                //console.log(favorite_element);
                //favorites_menu.append(favorite_element);

            }
        }
    }

	//send the XMLHttp request 
    request.send();
}


// Select a flavor
function clickFlavor() {
    resetFlavors();
    $(this).addClass('selected');
    currentFlavor = $(this).index();
}

// Deselect all flavors
function resetFlavors() {
    $('.flavor').removeClass('selected');
    currentFlavor = -1;
}

// Select a filling
function clickFilling() {
    resetFillings();
    $(this).addClass('selected');
    currentFilling = $(this).index();
}

// Deselect all fillings
function resetFillings() {
    $('.filling').removeClass('selected');
    currentFilling = -1;
}

// Select an icing
function clickIcing() {
    resetIcings();
    $(this).addClass('selected');
    currentIcing = $(this).index();
}

// Deselect all icings
function resetIcings() {
    $('.icing').removeClass('selected');
    currentIcing = -1;
}

// Select a topping
function clickTopping() {

    var topping_id = $(this).attr( "id" );
    
    if ($(this).is(':checked')) {
        // Add toppings
        currentToppings.push(topping_id);
    } else {
        // Remove topping

        var toppingIndex = currentToppings.indexOf(topping_id);

        if (toppingIndex > -1) {
            currentToppings.splice(toppingIndex, 1);
        }
    }
    
    //console.log(currentToppings);
}

// Deselect all toppings
function resetToppings() {
    $("input[name='toppings']").prop('checked', false);
    currentToppings = [];
}

// Reset all cupcake defaults
function resetCupcake(e) {
    resetFlavors();
    resetFillings();
    resetIcings();
    resetToppings();

    if (e) {
        e.preventDefault();
    }
}

// Load a favorite cupcake into the editor from the favorites menu
function loadFromFavorites() {
    
    var index = $(this).index('.favoriteItem');
    
    var cupcake_data = favorites_data[index]['cupcake'];

    // Reset the selections
    resetCupcake(undefined);


    // Select flavor according to the favorite data
    var flavor_index = -1;

    // Get the index of the desired flavor
    for (var i = 0; i < flavors.length; i++) {
        if (flavors[i].id == cupcake_data['flavor_id']) {
            flavor_index = i;
        }
    }
    //console.log(flavor_index);
    $( '.flavor' )[flavor_index].click();




    // Select according to the favorite data
    var icing_index = -1;

    // Get the index of the desired icing
    for (var i = 0; i < icings.length; i++) {
        if (icings[i].id == cupcake_data['icing_id']) {
            icing_index = i;
        }
    }

    //console.log(icing_index);
    $( '.icing' )[icing_index].click();





    // Select according to the favorite data
    var filling_index = -1;

    // Get the index of the desired filling
    for (var i = 0; i < fillings.length; i++) {
        if (fillings[i].id == cupcake_data['filling_id']) {
            filling_index = i;
        }
    }

    //console.log(filling_index);
    $( '.filling' )[filling_index].click();



    // Select toppings according to favorite data
    for (var i = 0; i < cupcake_data['toppings'].length; i++) {

        for (var j = 0; j < toppings.length; j++) {

            if (toppings[j].id == cupcake_data['toppings'][i].id) {
                $('input[name="toppings"][value="' + cupcake_data['toppings'][i].id + '"]').click();
            }
        }
   }

    //console.log('Load from favorites:' + index);
}

function  openFavoriteDialog() {

    if (currentFlavor > -1 && currentIcing > -1 && currentFilling > -1) {

        var design_name = '';

        // Assign the dialog labels
        $('#flavorLabel')[0].innerHTML = "Flavor: " + flavors[currentFlavor].name;
        $('#fillingLabel')[0].innerHTML = "Filling: " + fillings[currentFilling].name;
        $('#icingLabel')[0].innerHTML = "Icing: " + icings[currentIcing].name;

        var toppingsString = "";

        for (var i = 0; i < currentToppings.length; i++) {
            for (var j = 0; j < toppings.length; j++) {
                if (currentToppings[i] == toppings[j].id) {

                    // Create the toppings string
                    toppingsString += toppings[j].name + ", ";
                }
            }
        }

        // Remove the last comma and space
        toppingsString = toppingsString.slice(0,-2);

        // Assign the topping dialog label
        $('#toppingsLabel')[0].innerHTML = "Toppings: " + toppingsString;

        // Create the dialog for input
        $( "#dialog-modal" ).dialog({
              autoOpen: true,
              height: 400,
              width: 500,
              modal: true,
              resizable: false,
              buttons: {
                Okay: function() {

                    if ($('#favoriteName').val().length > 0) {
                        
                        // Get the favorite name
                        design_name = $('#favoriteName').val();
                        
                        // Close the dialog
                        $( this ).dialog( "close" );
                        
                        // Add the cupcake to favorites
                        addToFavorites(design_name);

                    } else {
                        console.log('Error: Favorite name is empty');
                    }
                    
                  },
                Cancel: function() {

                    // Close the dialog
                  $( this ).dialog( "close" );
                }
              }
          });
    }
}

// Add a cupcake to the favorites menu
function addToFavorites(design_name) {

    // INSERT INTO DB VIA REST API!

    var favorite_menu = $('#favoritesMenu');

    if (currentFlavor > -1 && currentIcing > -1 && currentFilling > -1) {

        
       toppings_array = [];

        for (var i = 0; i < currentToppings.length; i++) {
            toppings_array.push({ "id": currentToppings[i] });
        }

        //console.log(toppings_array);

        // Create the favorites data
        var favorite_data = {
            "name": design_name,
            "cupcake": {
                "flavor_id": flavors[currentFlavor].id,
                "icing_id": icings[currentIcing].id,
                "filling_id": fillings[currentFilling].id,
                "toppings": toppings_array,
                "quantity": 1
            }
        };

        favorites_data.push(favorite_data);

        // Create the element in the DOM
        var favorite_element = $(document.createElement('div'));
        favorite_element.addClass('favoriteItem');
        favorite_element.append("<img src='resources/artwork/cupcake_icon.png' />");

        var label = $(document.createElement('label'));
        label.append(design_name);

        favorite_element.append(label);

        // Add the edit order item event trigger
        favorite_element.click(loadFromFavorites);

        // Add the item into the order
        favorite_menu.append(favorite_element);

        // SEND TO REST API
        var request = new XMLHttpRequest();
            request.open("POST", "./api/index.php/users/" + userId + "/favorites");
            request.setRequestHeader('Content-Type', 'application/json');
            request.onreadystatechange = function () {
                if (request.readyState == 4 && request.status == 200) {

                    var response = JSON.parse(request.responseText);

                    if (response['success'] == true) {
                        //console.log('Successfully added favorites to Database');
                    } else {
                        console.log('Error' + request.responseText);
                    }
                }
            }

        request.send(JSON.stringify(favorite_data));

    } else {
        console.log('Something is missing from your design');
    }
}

function removeFromOrder() {
    // Remove from internal array of orders
    var index = $(this).parent().index($('.orderItem'));

    //console.log('Order Item Index: ' + index);


    order_data.splice(index, 1);

    // Remove from UI
    $(this).parent().remove();

    updateTotalPrice();
}

function addToOrder() {


    var order_menu = $('#orderMenu');

    if (currentFlavor > -1 && currentIcing > -1 && currentFilling > -1 && currentQuantity > 0) {
        
        //console.log(currentToppings);
        //console.log(flavors[currentFlavor].name);
        //console.log(icings[currentIcing].name);
        //console.log(fillings[currentFilling].name);


        order_data.push(
        {
            "flavor_id": flavors[currentFlavor].id,
            "icing_id": icings[currentIcing].id,
            "filling_id": fillings[currentFilling].id,
            "toppings": currentToppings,
            "quantity": currentQuantity
        });

        var order_element = $(document.createElement('div'));
        order_element.addClass('orderItem');
        order_element.append("<img src='resources/artwork/cupcake_icon.png' />");

        var label = $(document.createElement('label'));
        label.append(flavors[currentFlavor].name + ":  " + currentQuantity);

        order_element.append(label);

        var remove_button = $(document.createElement('input'));
        remove_button.attr('type', 'button');
        remove_button.addClass('removeOrderItem');
        remove_button.attr('value', 'X');

        // Add the remove from order event trigger
        remove_button.click(removeFromOrder);

        order_element.append(remove_button);

        // Add the edit order item event trigger
        order_element.click(selectOrderItem);

        // Add the item into the order
        order_menu.append(order_element);

        // Reset the selection so we can create a new cupcake
        resetCupcake(undefined);

        updateTotalPrice();

    } else {
        console.log('Something is missing from your order');
    }
    
}

function updateQuantity() {
    currentQuantity = $('#cupcakeQuantity')[0].value;
}

function updateTotalPrice() {

    totalPrice = 0.0;

    for (var i = 0; i < order_data.length; i++) {

        var item = order_data[i];

        var itemPrice = 0.0;
        var quantity = item.quantity;
        
        for(var j = 0; j < flavors.length; j++) {
            if (flavors[j].id == item.flavor_id)
            {
                itemPrice += flavors[j].price;
            }
        }

        for(var j = 0; j < fillings.length; j++) {
            if (fillings[j].id == item.filling_id)
            {
                itemPrice += fillings[j].price;
            }
        }

        for(var j = 0; j < icings.length; j++) {
            if (icings[j].id == item.icing_id)
            {
                itemPrice += icings[j].price;
            }
        }

        for(var j = 0; j < item.toppings.length; j++) {

            var topping = item.toppings[j];

            for(var k = 0; k < toppings.length; k++) {
                if (toppings[k].id == topping)
                {
                    itemPrice += toppings[k].price;
                }
            }
        }
        
        totalPrice += (itemPrice * quantity);
    }

    $('#totalCost')[0].innerHTML = 'Total Cost: $' + totalPrice.toFixed(2);
    //console.log("Total Cost: " + totalPrice.toFixed(2));
}

function submitOrder() {

    var final_order_data = {
        "user_id": userId,
        "total_price": totalPrice,
        "cupcakes": order_data
    }

    // SEND TO REST API
    var request = new XMLHttpRequest();
        request.open("POST", "./api/index.php/orders");
        request.setRequestHeader('Content-Type', 'application/json');
        request.onreadystatechange = function () {
            if (request.readyState == 4 && request.status == 200) {

                var response = JSON.parse(request.responseText);

                if (response['success'] == true) {
                    console.log('Successfully added order to Database');
                    window.location.href = "employee.php";
                } else {
                    console.log('Error' + request.responseText);
                }
            }
        }

    request.send(JSON.stringify(final_order_data));

    
}

function selectOrderItem(e) {
    
    deselectOrderItem();

    // Show the item as selected
    $(this).addClass('orderItemSelected');

    // Get the index of the item
    selectedOrder = $(this).index('.orderItem');

    // Don't let the deselect event trigger
    e.stopPropagation();

    // SHOW UPDATE BUTTON
    $('#updateCupcakeButton').show();


    // Reset the selections
    resetCupcake(undefined);

    ////
    // Update the UI to show the choices of the order
    ////

    var currentOrderItem = order_data[selectedOrder];

    // Select flavor according to the favorite data
    var flavor_index = -1;

    // Get the index of the desired flavor
    for (var i = 0; i < flavors.length; i++) {
        if (flavors[i].id == currentOrderItem['flavor_id']) {
            flavor_index = i;
        }
    }
    //console.log(flavor_index);
    $( '.flavor' )[flavor_index].click();




    // Select according to the favorite data
    var icing_index = -1;

    // Get the index of the desired icing
    for (var i = 0; i < icings.length; i++) {
        if (icings[i].id == currentOrderItem['icing_id']) {
            icing_index = i;
        }
    }

    //console.log(icing_index);
    $( '.icing' )[icing_index].click();



    // Select according to the favorite data
    var filling_index = -1;

    // Get the index of the desired filling
    for (var i = 0; i < fillings.length; i++) {
        if (fillings[i].id == currentOrderItem['filling_id']) {
            filling_index = i;
        }
    }

    //console.log(filling_index);
    $( '.filling' )[filling_index].click();



    // Select toppings according to favorite data
    for (var i = 0; i < currentOrderItem['toppings'].length; i++) {

        for (var j = 0; j < toppings.length; j++) {

            if (toppings[j].id == currentOrderItem['toppings'][i]) {
                $('input[name="toppings"][value="' + currentOrderItem['toppings'][i] + '"]').click();
            }
        }
   }
}

function  deselectOrderItem() {

    // Deselect the item
    $('.orderItem').removeClass('orderItemSelected');
    selectedOrder = -1;

    // HIDE UPDATE BUTTON
    $('#updateCupcakeButton').hide();

}

function updateOrderItem() {

    console.log('update itme');

    if (selectedOrder > -1 && selectedOrder < order_data.length) {
        console.log('update the order item');

        order_data[selectedOrder] =
        {
            "flavor_id": flavors[currentFlavor].id,
            "icing_id": icings[currentIcing].id,
            "filling_id": fillings[currentFilling].id,
            "toppings": currentToppings,
            "quantity": currentQuantity
        };

        var label = $('.orderItem label')[selectedOrder];
        label.innerHTML = flavors[currentFlavor].name + ":  " + currentQuantity;

        updateTotalPrice();
    }
}

$(document).ready(function () {

    userId = $('#user-id')[0].innerHTML;

    $('#dialog-modal').submit( function(e) {
        // Prevent the page from submitting within the dialog
         e.preventDefault();
    });
        

    loadCupcakeData();

    loadFavorites();
    
    $('.flavor').click(clickFlavor); 
    $('.filling').click(clickFilling);
    $('.icing').click(clickIcing);
    
    $('#resetCupcakeButton').click(resetCupcake);
    $('#addCupcakeButton').click(addToOrder);
    $('#resetToppingButton').click(resetToppings);

    $('#saveFavoriteButton').click(openFavoriteDialog);
    $('#cupcakeQuantity').change(updateQuantity);

    $('#submitOrder').click(submitOrder);
    $('input[name="toppings"]').change(clickTopping);

    $('#orderMenu').click(deselectOrderItem);

    $('#updateCupcakeButton').hide();
    $('#updateCupcakeButton').click(updateOrderItem);
    updateTotalPrice();

});
