<?php
$xmlDoc = new DomDocument();
$xmlDoc->load("input1.html");
$root = $xmlDoc->documentElement; 
$xp = new DOMXPath($xmlDoc);
//echo $xmlDoc->saveXML($xp->evaluate("p[id='allcaps']")[0]);
// NOTE: should have just used getElementsByTagName("span");
$p_elems = $root->getElementsByTagName("p");

//$allcaps_elems = $root->getElementsByClassName("allcaps");
//$upcase_elems = $root->getElementsByClassName("upcase");

// These two arrays store all of the allcaps/upcase text
$list_allcaps = array();
$list_upcase = array();

function get_html($node) {
    $innerHTML = '';
    $children = $node->childNodes;
    foreach($children as $child) {
        $innerHTML .= $child->ownerDocument->saveXML($child);
    }
    return $innerHTML;
}

function check_attributes($node) {
    if($node->hasAttributes()) {
        $child_attr = $node->attributes;
        //$length = $child_attr->length;
        // get the class attribute of the node
        $child_class = $child_attr->getNamedItem("class")->value;
        return $child_class;
    } else {
        return false;
    }
}

function get_attribute_value($node, $attr) {
    if($node->hasAttributes()) {
        if($node->attributes->getNamedItem("class")->value == $attr) {
            $text_value = $node->firstChild->wholeText;
            //echo "attr text value: $text_value</br>";
            return $text_value; 
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function display_array($values, $class) {
    foreach($values as $value) {
        echo "<li class='$class'>$value</li>";

?>
    <script type="text/javascript">
    console.log("testing a nested javascript function");
    var display_val = '<?php echo $value; ?>';
    console.log("display_val: " + display_val);
    //var display_val = <?php echo $value ?>;
    //console.log("display_val: " + display_val);
    </script>
<?php
    }
}

?>
    

<html>
<head>


</head>

<body>
<h1>Special Words</h1>

<?php
    foreach ($p_elems as $p_elem) {
        //echo $p_elem->firstChild->wholeText;
        //$children = $p_elem->childNodes;
        //foreach($children as $child) {
        $children = $p_elem -> childNodes;
        foreach($children as $child) {
            //echo $child->nodeName;
            //$result = check_and_load_attributes($child);
            //check_and_load_attributes($child);
            
            $attr_type = check_attributes($child);
            if($attr_type == "allcaps") {
                $text_value = get_attribute_value($child, $attr_type);
                //echo "$text_value</br>";
                array_push($list_allcaps, $text_value);
            } else if($attr_type == "upcase") {
                array_push($list_upcase, get_attribute_value($child, $attr_type));
            }
        }
    }
?>

<h2>All Caps</h2>

<ul>
<?php
display_array($list_allcaps, 'display_allcaps');
?>

</ul>

<h2>Upcase</h2>
<ul>
<?php
display_array($list_upcase, 'display_upcase');
?>
</ul>

<script type="text/javascript">
    var elems_all = document.getElementsByClassName('display_allcaps');
    for(j = 0; j < elems_all.length; j++) {
        elems_all[j].style.textTransform = "uppercase";          
    }

    var elems_up = document.getElementsByClassName('display_upcase');
    for(j = 0; j < elems_up.length; j++) {
        elems_up[j].style.textTransform = "capitalize";          
    }

</script>


</body>

</html>
