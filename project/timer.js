var timeVar = setInterval(function() {
    updateTime()
}, 1000);
var t = 0;

function updateTime() {
    document.getElementById("timer").innerHTML = t++;
    console.log("updated timer: " + t);
}
