/*
(function() {

})();
*/
/* When the user clicks on the button,
toggle between hiding and showing the dropdown content */

nr = 0;
function dropMenu(x,n) {
nr = n;
    if(x == "navSec"){
        document.getElementById("myDropdown").classList.toggle("show");
    } else {

        for(var i=0; i<nr; i++){
            if(x == i){
                document.getElementById("myDropdown"+x).classList.toggle("show1");
            } else {
                document.getElementById("myDropdown"+i).classList.remove("show1");
            }

        }

    }

}

// Close the dropdown if the user clicks outside of it
window.onclick = function(event) {


    //close nav dropdown
    if (!event.target.matches('.dropbtn')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }

    //close event details
    //if the user didn't clicked one of the folowing elements the tab will be closed
    if (!event.target.matches('h4') && !event.target.matches('.timing') && !event.target.matches('.wrp') && !event.target.matches('.details') && !event.target.matches('.eventbtn') && !event.target.matches('.joining') ) {

        var dropdowns = document.getElementsByClassName("event-details");
        var i;
        for (i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show1')) {
                openDropdown.classList.remove('show1');
            }
        }
    }


}


