/**
 * Created by kurmasz on 4/20/15.
 */


window.onload = function () {

    /* 
     Put your submit event handler here.  Remember, the submit listener goes on the <form> element, not the
     submit button (i.e., the <input type="submit"/> 

     You event handler should iterate over all the "fillin" items and replace the text
     with the value of the corresponding input field.
     */
    document.getElementById("theForm").addEventListener("submit",
        function(event) {
            event.preventDefault();
            checkInput(event);
        });

    function displayError(blank_nodes) {
        console.log("Displaying error");
        var error_node = document.getElementById("blank_vals");
        error_node.style.display = "block";
        error_node.style.background = "red";
        for(i = 0; i < blank_nodes.length; i++) {
            var curr_node = blank_nodes[i];
            curr_node.style.background = "red";
            //blank_nodes[i].style.background-color = "red";
        }
    }

    function checkInput(event) {
        console.log("Checking input...");
        // First, reset displays back to normal
        document.getElementById("blank_vals").style.display = "none";
        document.getElementById("story").style.display = "none";
        // Then, get all of the form box nodes and their values
        var value_nodes = document.getElementsByClassName("box_val");
        var num_values = value_nodes.length;
        console.log("There are " + num_values + " nodes.");
        var blank_nodes = [];
        var values = [];
        // Note: the following code is from ../PracticeFinal/average.php
        for(i = 0; i < num_values; i++) {
            var submit_val = value_nodes[i].value;
            value_nodes[i].style.background = "white";
            console.log("node " + i + ": " + submit_val);
            if(!submit_val) {
                console.log("no value found for node " + i);
                blank_nodes.push(value_nodes[i]);
            } else {
                console.log("adding value: " + submit_val);
                values.push(submit_val);
                //sum += parseInt(submit_val,10);
            }
        }
        if(blank_nodes.length > 0) {
            console.log("Found " + blank_nodes.length + " nodes with no value set: " );
            displayError(blank_nodes);
            return;
        }
        console.log("nodes : " + value_nodes);
        console.log("values: " + values);

        console.log("Evaluating Data...");

        var fillins = document.getElementsByClassName("fillin");
        for(i = 0; i < fillins.length; i++) {
            fillins[i].innerHTML = values[i];
            fillins[i].style.color = "blue";
            fillins[i].style.fontWeight = "bold";
        }
        document.getElementById("story").style.display = "block";

    }


};
