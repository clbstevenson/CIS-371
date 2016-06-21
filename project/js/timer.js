var timeVar = setInterval(function() {
    updateTime()
}, 1000);
var t = 0;

// Get the current story_id

// Collect all of the data_story_id node informations
// also set a timer for each of the different sets.
var story_id_nodes = document.getElementsByClassName("data_story_id");
var event_id_nodes = document.getElementsByClassName("data_event_id");
console.log("There are " + story_id_nodes.length + " id node pairs.");
for(i = 0; i < story_id_nodes.length; i++) {
    var curr_id = story_id_nodes.id; 
    // May need to selectively remove nodes from story.php..TBD
    if(curr_id) {
        console.log("curr_id (" + curr_id + ")");
        //if(curr_id == "pagedata_story_id") 
        //console.log(" 
    }
    var curr_story_node = story_id_nodes[i];
    var curr_event_node = event_id_nodes[i];
    console.log("curr_story_node: " + curr_story_node);
    console.log("curr_event_node: " + curr_event_node);

    var story_value = curr_story_node.innerHTML;
    var event_value = curr_event_node.innerHTML;
    //var story_value = story_id_nodes[i].value;
    //var event_value = event_id_nodes[i].value;
    console.log("Starting timer. (storyID, eventID) ("+story_value+","+event_value+")");

    textTimer_withID(story_value, event_value);
    
}
console.log("class data_story_id: " + document.getElementsByClassName("data_story_id"));
console.log("class data_event_id: " + document.getElementsByClassName("data_event_id"));
console.log("data_story obj: " + document.getElementById("data_story_id"));
var story_id = document.getElementById("data_story_id").innerHTML;
// Get the current event_id
console.log("data_event obj: " + document.getElementById("data_event_id"));
var event_id = document.getElementById("data_event_id").innerHTML;

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

function general_timer(classID, myFormat, myLayout, s_id, e_id) {
    var time = null;
    var xmlhttp = new XMLHttpRequest();
    if(!s_id) {
        s_id = 9;
    }
    if(!e_id) {
        e_id = 30;
    }
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
    xmlhttp.open("GET", "getEndtime.php?story_id="+s_id+"&event_id="+e_id, true); 
    xmlhttp.send();
    //document.getElementById("history_btn").innerHTML = "Hide Full Story";
    //document.getElementById("history").style.display = "block";
}

function textTimer() {
    general_timer("textTimer", "HMS", '{hnn}{sep}{mnn}{sep}{snn}', false, false);
    //general_timer("textTimer", "HMS",false);// '{sn} {sl}, {mn} {ml}, {hn} {hl}, and {dn} {dl}');
    console.log("using text timer");
}

function textTimer_withID(s_id, e_id) {
    console.log("using text timer with IDs");
    general_timer("textTimer", "HMS", '{hnn}{sep}{mnn}{sep}{snn}', s_id, e_id);
    //general_timer("textTimer", "HMS",false);// '{sn} {sl}, {mn} {ml}, {hn} {hl}, and {dn} {dl}');
}

function defaultTimer() {
    general_timer("defaultCountdown", "HMS", false, false, false);
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
