var timeVar = setInterval(function() {
    updateTime()
}, 1000);
var t = 0;

var austDay = new Date();
austDay = new Date(austDay.getFullYear(), 6 - 1, 20, 16, 57);
endTime();
var jstimer = document.getElementById("jstimer");
var newEndTime = jstimer.innerHTML;
console.log("jstimer: jstimer");
var newEndDay = new Date(newEndTime);
console.log("endTime: " + newEndTime);
// The timer is set upon success of retrieving sql DB time from php file
// shown later in the file.
//
//console.log("endDay: " + newEndDay);
//$('#defaultCountdown').countdown({until: austDay, onExpiry: liftOff,
//    serverSync: serverTime});
//$('#year').text(austDay.getFullYear());

function updateTime() {
    //document.getElementById("timer").innerHTML = t++;
    //console.log("updated timer: " + t);
}

function liftOff() {
    alert("We have lift off!");
}
function serverTime() {
    var time = null;
    $.ajax({url: 'serverTime.php',
        async: false, dataType: 'text',
        success: function(text){
            time = new Date(text);
            console.log("success: " + text);
        }, error: function(http, message, exc) {
            time = new Date();
        }});
    return time;
}

function get_endtime(storyID, eventID) {

}

function endTime() {
    var time = null;
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            //document.getElementById("history").innerHTML = xmlhttp.responseText;
            var text = xmlhttp.responseText;
            console.log("response text: " + text);
            time = new Date(text);
            console.log("response time: " + time);
            document.getElementById("jstimer").innerHTML = time;
            var newEndDay = time;
            //$('#defaultCountdown').countdown({until: newEndDay, onExpiry: liftOff,
            //    serverSync: serverTime});
            $('#defaultCountdown').countdown({until: newEndDay, onExpiry: liftOff, serverSync: serverTime});
            $('#year').text(newEndDay.getFullYear());
            return time;
        }
    };
    xmlhttp.open("GET", "getEndtime.php?story_id=9&event_id=30", true); 
    xmlhttp.send();
    //document.getElementById("history_btn").innerHTML = "Hide Full Story";
    //document.getElementById("history").style.display = "block";
}
