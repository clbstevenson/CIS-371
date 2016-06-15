<?php

// check if $_GET is set for the number of values to evaluate.
if(isset($_GET['num'])) {
    // set num to the $_GET value specified
    $num = $_GET['num'];
} else {
    // use a default value for num
    $num = 8;
}

$num_cols = 4;
$num_rows = $num / $num_cols;

 
?>

<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Average Evaluation</title>
    <style type="text/css">
        #blank_vals, #display_values {
            display: none;
        }

    </style>



</head>

<body>
<h1></h1>

<?php
?>

<h2>Test Data</h2>

<p id="blank_vals">Please enter a value for all the test squares.</p>
<button type="button" id="autofill">Autofill</button>

<fieldset>
<form id="avg_form" action="average.php" method="post">

<table>
<?php
    $num_vals = 0;
    $index = 0;
    $curr_row = 0;
    $curr_col = 0;
    echo "<tr>";
    while($num_vals < $num) {
        echo "<td><input type='text' name='box$num_vals' class='box_val' id='box$num_vals' value = ''</td>";

        $num_vals ++;
        $curr_col++;
        if($num_vals < $num && $curr_col >= $num_cols) {
            $curr_col = 0;
            $curr_row = 1;
            echo "</tr><tr>";
        }
    }
    // if didn't reach the end of the column, then end the table row
    if($curr_col < $num_cols) {
        echo "</tr>";
    }
?>
</table>

<input type="submit" name="postSubmit" value="Submit"/>

</form>

</fieldset>

<ul id="display_values">
    <li><p id="max_value"></p></li>
    <li><p id="min_value"></p></li>
    <li><p id="avg_value"></p></li>
</ul>
<p id="demo"></p>

<script type="text/javascript">

    function displayError(blank_nodes) {
        console.log("Displaying error");
        //_.each(document.getElementsByClassName("box_val"), function(item) {
        //    item.style.background-color = "white"; 
        //});
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
        document.getElementById("display_values").style.display = "none";
        //Then, get all of the box nodes and their values
        var value_nodes = document.getElementsByClassName("box_val");
        var num_values = value_nodes.length;
        var blank_nodes = [];
        var values = [];
        var sum = 0;
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
                sum += parseInt(submit_val,10);
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
        
        // Set the unordered list of values to be displayed
        document.getElementById("display_values").style.display = "block";
        // Find and calculate the maximum value
        var max_value = parseInt(Math.max.apply(Math, values),10);
        console.log("Maximum: " + max_value);
        // had to write my own function for finding the index
        // because array.indexOf was having issues with my dataset
        var max_index = get_index(values, max_value);
        console.log("Index of max: " + max_index);
        var max_node = value_nodes[max_index];
        max_node.style.background = "lightgreen";
        document.getElementById("max_value").innerHTML = "Maximum: " + max_value;

        // Find and calculate the minimum value
        var min_value = parseInt(Math.min.apply(Math, values),10);
        console.log("Minimum: " + min_value);
        // had to write my own function for finding the index
        // because array.indexOf was having issues with my dataset
        var min_index = get_index(values, min_value);
        console.log("Index of min: " + min_index);
        var min_node = value_nodes[min_index];
        min_node.style.background = "lightblue";
        document.getElementById("min_value").innerHTML = "Minimum: " + min_value;
        
        // Calculate the average value and display it
        console.log("Sum of Values: " + sum);
        var avg = sum / (values.length);
        console.log("Average: " + avg);
        document.getElementById("avg_value").innerHTML = "Average: " + avg;
        
    }

    function get_index(values, value) {
        for(i = 0; i < values.length; i++) {
            if(values[i] == value) {
                return i;        
            }
        }
        return -1;
    }

    function new_random() {
        return Math.floor((Math.random() * 100) + 1);
    }

    function autofill_tests(event) {
        var value_nodes = document.getElementsByClassName("box_val");
        for(i = 0; i < value_nodes.length; i++) {
            value_nodes[i].style.background = "white";
            //if(!value_nodes[i].value) {
                var new_val = new_random();
                value_nodes[i].value = new_val;
                console.log("adding value: " + new_val);
            //}
        }

        
    }

    document.getElementById("avg_form").addEventListener("submit",
        function(event){
            event.preventDefault();
            checkInput(event);
            document.getElementById("demo").innerHTML += "avg ";
        });
    document.getElementById("autofill").addEventListener("click",
        function(event) {
            console.log("Autofilling the data...");
            event.preventDefault();
            autofill_tests(event);
            document.getElementById("demo").innerHTML += "fill ";
        });

</script>


</body>
</html>
