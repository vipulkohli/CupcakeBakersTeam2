
function loadFavorites() {
    
    var favorites_menu = $('#favoritesMenu')[0];


    var request = new XMLHttpRequest();
    request.open("GET","http://localhost/cupcakes/api/index.php/users/1/favorites",true);
    request.onreadystatechange = function(e) {
        if (request.readyState === 4) {
           
            var favorites_data = JSON.parse(request.responseText);

            favorites_menu.innerHTML += request.responseText;

        }
    }
    request.send();
}


function clickFlavor() {
    resetFlavors();
    $(this).addClass('selected');
}

function resetFlavors() {
    $('.flavor').removeClass('selected');
}


function clickFilling() {
    resetFillings();
    $(this).addClass('selected');
}

function resetFillings() {
    $('.filling').removeClass('selected');
}

function clickIcing() {
    resetIcings();
    $(this).addClass('selected');
}

function resetIcings() {
    $('.icing').removeClass('selected');
}

function resetToppings() {
    $("input[name='toppings']").prop('checked', false);
}

function resetCupcake(e) {
    resetFlavors();
    resetFillings();
    resetIcings();
    resetToppings();

    e.preventDefault();
}



$(document).ready(function () {
    loadFavorites();
    
    $('.flavor').click(clickFlavor);
    $('.filling').click(clickFilling);
    $('.icing').click(clickIcing);
    $('#resetCupcakeButton').click(resetCupcake);
    $('#resetToppingButton').click(resetToppings);

});