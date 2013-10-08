
function loadFavorites() {
    
    var favorites_menu = $('#favoritesMenu')[0];


    var request = new XMLHttpRequest();
    request.open("GET","http://localhost/cupcakes/api/index.php/users/1/favorites",true);
    request.onreadystatechange = function(e) {
        if (request.readyState === 4) {
           
            var favorites_data = JSON.parse(request.responseText);

            console.log(request.responseText);
            favorites_menu.innerHTML += request.responseText;

        }
    }
    request.send();
}








$(document).ready(function () {
    loadFavorites();
});