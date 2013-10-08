


var flavors;
var icings;
var fillings;
var toppings;

var currentFlavor = -1;
var currentIcing = -1;
var currentFilling = -1;
var currentToppings = [];

var favorites_data = [];
var order_data = [];


function loadCupcakeData() {
    
    // Load Flavors
    var flavorRequest = new XMLHttpRequest();
    flavorRequest.open("GET","http://localhost/cupcakes/api/index.php/flavors",true);
    flavorRequest.onreadystatechange = function(e) {
        if (flavorRequest.readyState === 4) {
           
            flavors = JSON.parse(flavorRequest.responseText);
        }
    }
    flavorRequest.send();

    // Load Icings
    var icingRequest = new XMLHttpRequest();
    icingRequest.open("GET","http://localhost/cupcakes/api/index.php/icings",true);
    icingRequest.onreadystatechange = function(e) {
        if (icingRequest.readyState === 4) {
           
            icings = JSON.parse(icingRequest.responseText);
        }
    }
    icingRequest.send();

    var fillingRequest = new XMLHttpRequest();
    fillingRequest.open("GET","http://localhost/cupcakes/api/index.php/fillings",true);
    fillingRequest.onreadystatechange = function(e) {
        if (fillingRequest.readyState === 4) {
           
            fillings = JSON.parse(fillingRequest.responseText);
        }
    }
    fillingRequest.send();

    var toppingRequest = new XMLHttpRequest();
    toppingRequest.open("GET","http://localhost/cupcakes/api/index.php/toppings",true);
    toppingRequest.onreadystatechange = function(e) {
        if (toppingRequest.readyState === 4) {
           
            toppings = JSON.parse(toppingRequest.responseText);
        }
    }
    toppingRequest.send();
}


function loadFavorites() {
    
    var favorites_menu = $('#favoritesMenu');

    var request = new XMLHttpRequest();
    request.open("GET","http://localhost/cupcakes/api/index.php/users/1/favorites",true);
    request.onreadystatechange = function(e) {
        if (request.readyState === 4) {
           
            favorites_data = JSON.parse(request.responseText);

            for (var i = 0; i < favorites_data.length; i++) {
                var favorite_data = favorites_data[i];

                var favorite_element = $(document.createElement('div'));
                favorite_element.addClass('favoriteItem');
                favorite_element.append("<img src='resources/artwork/cupcake_icon.png' />");
                
                var label = $(document.createElement('label'));

                if (favorites_data['name']) {
                    label.append(favorites_data['name']);
                } else {
                    label.append('Favorite Cupcake');
                }

                favorite_element.append(label);

                favorite_element.click(loadFromFavorites);

                favorites_menu.append(favorite_element);
                //console.log(favorite_element);
                //favorites_menu.append(favorite_element);

            }
        }
    }
    request.send();
}


function clickFlavor() {
    resetFlavors();
    $(this).addClass('selected');
    currentFlavor = $(this).index();
}

function resetFlavors() {
    $('.flavor').removeClass('selected');
    currentFlavor = -1;
}


function clickFilling() {
    resetFillings();
    $(this).addClass('selected');
    currentFilling = $(this).index();
}

function resetFillings() {
    $('.filling').removeClass('selected');
    currentFilling = -1;
}

function clickIcing() {
    resetIcings();
    $(this).addClass('selected');
    currentIcing = $(this).index();
}

function resetIcings() {
    $('.icing').removeClass('selected');
    currentIcing = -1;
}


function clickTopping() {

    var topping_name = $(this).attr( "id" );
    
    console.log(topping_name);
    
    if ($(this).is(':checked')) {
        // Add toppings
        currentToppings.push(topping_name);
    } else {
        // Remove topping

        var toppingIndex = currentToppings.indexOf(topping_name);

        if (toppingIndex > -1) {
            currentToppings.splice(toppingIndex, 1);
        }
    }
    console.log(currentToppings);
}
function resetToppings() {
    $("input[name='toppings']").prop('checked', false);
    currentToppings = [];
}

function resetCupcake(e) {
    resetFlavors();
    resetFillings();
    resetIcings();
    resetToppings();

    if (e) {
        e.preventDefault();
    }
}

function loadFromFavorites() {
    var index = $(this).index();

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

        var topping_name = cupcake_data['toppings'][i].name;

        //console.log(topping_name);
        $('input[name="toppings"][value="' + topping_name + '"]').click();
    }

    //console.log(favorites_data[index]['cupcake']);
    //console.log('Load from favorites:' + index);
}

function addToFavorites() {
    // TODO
}

function removeFromOrder() {
    // Remove from internal array of orders
    var index = $(this).parent().index();

    //console.log('Order Item Index: ' + index);


    order_data.splice(index, 1);

    // Remove from UI
    $(this).parent().remove();
}

function addToOrder() {


    var order_menu = $('#orderMenu');

    if (currentFlavor > -1 && currentIcing > -1 && currentFilling > -1 && currentToppings.length > 0) {
        console.log(currentToppings);
        console.log(flavors[currentFlavor].name);
        console.log(icings[currentIcing].name);
        console.log(fillings[currentFilling].name);


        order_data.push(
        {
            "flavor_id": flavors[currentFlavor].id,
            "icing_id": icings[currentIcing].id,
            "filling_id": fillings[currentFilling].id,
            "toppings": currentToppings
        });

        var order_element = $(document.createElement('div'));
        order_element.addClass('orderItem');
        order_element.append("<img src='resources/artwork/cupcake_icon.png' />");

        var label = $(document.createElement('label'));
        label.append(flavors[currentFlavor].name);

        order_element.append(label);

        var remove_button = $(document.createElement('input'));
        remove_button.attr('type', 'button');
        remove_button.addClass('removeOrderItem');
        remove_button.attr('value', 'X');

        // Add the remove from order event trigger
        remove_button.click(removeFromOrder);

        order_element.append(remove_button);

        // Add the edit order item event trigger
        //order_element.click(editOrderItem);

        // Add the item into the order
        order_menu.append(order_element);

        // Reset the selection so we can create a new cupcake
        resetCupcake(undefined);

    } else {
        console.log('Something is missing from your order');
    }
    
}



$(document).ready(function () {
    
    loadCupcakeData();

    loadFavorites();
    
    $('.flavor').click(clickFlavor); 
    $('.filling').click(clickFilling);
    $('.icing').click(clickIcing);
    
    $('#resetCupcakeButton').click(resetCupcake);
    $('#addCupcakeButton').click(addToOrder);
    $('#resetToppingButton').click(resetToppings);
    $('#saveFavoriteButton').click(addToFavorites);
    
    $('input[name="toppings"]').change(clickTopping);

});