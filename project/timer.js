var timeVar = setInterval(function() {
    updateTime()
}, 1000);
var t = 0;

// The timer is set upon success of retrieving sql DB time from php file
// shown later in the file.
var austDay = new Date();
austDay = new Date(austDay.getFullYear(), 6 - 1, 20, 16, 57);
//endTime();
defaultTimer();
textTimer();
//var jstimer = document.getElementById("jstimer");
//var newEndTime = jstimer.innerHTML;
//console.log("jstimer: jstimer");
//var newEndDay = new Date(newEndTime);
//console.log("endTime: " + newEndTime);

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

function general_timer(classID, myFormat, myLayout) {
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
            var class_selector = "#" + classID;
            if(myFormat) {
                if(myLayout) {
                    $(class_selector).countdown({until: newEndDay, onExpiry: liftOff, serverSync: serverTime, format: myFormat, layout: myLayout});
                } else {
                    $(class_selector).countdown({until: newEndDay, onExpiry: liftOff, serverSync: serverTime, format: myFormat});
                }
            } else {
                if(myLayout) {
                    $(class_selector).countdown({until: newEndDay, onExpiry: liftOff, serverSync: serverTime, layout: myLayout});
                } else {
                    $(class_selector).countdown({until: newEndDay, onExpiry: liftOff, serverSync: serverTime});
                }
            }
            return time;
        }
    };
    xmlhttp.open("GET", "getEndtime.php?story_id=9&event_id=30", true); 
    xmlhttp.send();
    //document.getElementById("history_btn").innerHTML = "Hide Full Story";
    //document.getElementById("history").style.display = "block";
}

function textTimer() {
    general_timer("textTimer", "MS", '{hnn}{sep}{mnn}{sep}{snn}');
    //general_timer("textTimer", "HMS",false);// '{sn} {sl}, {mn} {ml}, {hn} {hl}, and {dn} {dl}');
    console.log("using text timer");
}

function defaultTimer() {
    general_timer("defaultCountdown", "HMS", false);
    console.log("using default timer");
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
            //document.getElementById("jstimer").innerHTML = time;
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
