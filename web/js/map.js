let map = document.querySelector('#map');
let lat = document.querySelector('#lat').value;
let lng = document.querySelector('#lng').value;

ymaps.ready(init);
function init(){
    var myMap = new ymaps.Map(map, {
        center: [lat, lng],
        zoom: 17
    });
}
