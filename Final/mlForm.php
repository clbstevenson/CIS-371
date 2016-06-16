<html>
<head>
    <title>MadLibs for CS 371 Final</title>
    <script type="text/javascript" src="ml.js"></script>
    <style type="text/css">
        table {
            border: 1px solid gray;
            text-align: center;
        }
        #blank_values, #display_values, #story {
            display: none;
        }

    </style>
</head>
<body>

<h1>My Mad Libs</h1>

<p id="blank_vals">Please enter a value for all the entries.</p>
<fieldset>
<legend>MadLib</legend>
<form id="theForm">

    <?php
    $xmlDoc = new DomDocument();
    $xmlDoc->load("story2.html");
    $root = $xmlDoc->documentElement;

    $spans = $root->getElementsByTagName("span");

    /* Insert code to generate <input> fields here
       Remember:
        * The $xmlDoc object describes an XML DOM, not an HTML DOM.  Therefore, there is no getElementsByClassName method.
          "class" and "id" are just "ordinary" attributes.
        * If you put an id on the <input> field, it must be distinct from the id on the corresponding <span>
     */


    function get_node_id($node) {
        if($node->hasAttributes()) {
            $item = $node->attributes->getNamedItem("id");
            if($item)
                $id_value = $node->attributes->getNamedItem("id")->value;
            else
                $id_value = false;
            //echo "get_node_id: $id_value</br>";
            return $id_value;
        }
        return false;
    }

    function get_node_class($node) {
        if($node->hasAttributes()) {
            $item = $node->attributes->getNamedItem("class");
            if($item)
                $class_value = $item->value;
            else
                $class_value = false;
            //echo "get_node_class: $class_value</br>";
            return $class_value;
            
        }
        return false;
    }

    function display_array($values, $class) {
        foreach($values as $value) {
            echo "<li class='$class'>$value</li>";
        }
    }
        
    function get_attribute_value($node, $attr) {
        if($node->hasAttributes()) {
            if($node->attributes->getNamedItem("class")->value == $attr) {
                $text_value = $node->firstChilde->wholeText;
            }
        }
    }
     
    // Gather all of the data from the DOM for all objects
    //  with class="fillin" and their connected IDs
    $arr_fillin_text = array();
    $arr_fillin_ids = array();
    foreach ($spans as $span) {
        //echo $span->firstChild->wholeText;
        //echo "</br>";
        $span_class = get_node_class($span);
        $span_id = get_node_id($span);
        if($span_id && $span_class) {
            array_push($arr_fillin_ids, $span_id);
            array_push($arr_fillin_text, $span->firstChild->wholeText);
        }
        //echo "span: class=$span_class id=$span_id</br>";
    }

    // Display a form for each of the fillin blanks
    echo "<table>";
    $num = count($arr_fillin_text);
    $num_boxes = 0;
    while($num_boxes < $num) {
        $box_id = "box".$arr_fillin_ids[$num_boxes];
        $box_text = $arr_fillin_text[$num_boxes];
        $fixed_box_text = str_replace("'", "\'", $box_text); 
        
        //$box_tr =  "<tr><td><input type='text' name=$box_id class='box_fillin' id=$box_id value='" . $box_text . "'</td></tr>";
        echo "<tr><td>$box_text</td>";
        echo "<td><input type='text' name=$box_id class='box_val' id=$box_id></td></tr>";
        //echo $box_tr;
        $num_boxes++;

    }
    echo "</table>";
     
    
    // (Your code should work for any input file.  Don't simply hard-code the five textboxes for story1.html)

    ?>
    <input type="submit" name="postSubmit" value="Let's MadLib!"/>

</form>
</fieldset>


<!--
    This will insert "story1.html" into the document. You can add JavaScript to ml.js (included above)
    to insert the values from the form. If you choose to use this PHP/JavaScript combination
    be sure to hide this div until the user submits the form.
-->
<!--<div id="story">-->
    <?php echo $xmlDoc->saveHTML(); ?>
<!--</div>-->

<ul>
<?php
//display_array($arr_fillin_ids, 'fillin');
?>
</ul>
<ul>
<?php
//display_array($arr_fillin_text, 'fillin');
?>
</ul>

<p id="demo"></p>

<script type="text/javascript" src="ml.js">
</script>

</body>
</html>
