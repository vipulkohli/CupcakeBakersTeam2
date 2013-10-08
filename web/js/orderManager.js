
function loadFavorites() {
    
    var favorites_menu = $('#favoritesMenu');


/*

        <div class="orderItem">
        <img src="resources/artwork/cupcake_icon.png" />
        <label>
        Cranberry [3]
        </label>
        <input type="button" value="X" />
        </div>
        */

    var request = new XMLHttpRequest();
    request.open("GET","http://localhost/cupcakes/api/index.php/users/1/favorites",true);
    request.onreadystatechange = function(e) {
        if (request.readyState === 4) {
           
            var favorites_data = JSON.parse(request.responseText);

            for (var i = 0; i < favorites_data.length; i++) {
                var favorite_data = favorites_data[i];

                var favorite_element = $(document.createElement('div'));
                favorite_element.addClass('favoriteItem');
                favorite_element.append("<img src='resources/artwork/cupcake_icon.png' />");
                
                var label = $(document.createElement('label'));
                //label.append(favorites_data['name']);
                label.append('FAVORITE CHOCOLATE');

                favorite_element.append(label);

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