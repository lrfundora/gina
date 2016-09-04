function initial(){
    order = document.getElementById('order');
    order.addEventListener("change", setOrder, false);
    }

function setOrder(){
    location = order.value;
    }

window.addEventListener('load', initial, false);

