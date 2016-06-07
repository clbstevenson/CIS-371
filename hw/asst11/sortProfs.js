/**
 * Created by kurmasz on 5/18/15.
 */

var col_num;
var debug = 0;

function compare_rows(row_a, row_b) {
    //console.log("row_a: " + row_a);
    //console.log("row_b: " + row_b);
    var td_a = row_a.getElementsByTagName("td");
    //console.log("len of a: " + td_a.length);
    //console.log("td_a: " + td_a);
    //console.log("td_a["+col_num+"]: " + td_a[col_num]);
    var lname_a = td_a[col_num].innerHTML;
    if(debug)
        console.log("lname_a: " + td_a + "\t= " + lname_a);

    var td_b = row_b.getElementsByTagName("td");
    var lname_b = td_b[col_num].innerHTML;
    if(debug)
        console.log("lname_b: " + td_b + "\t= " + lname_b);

    // Compare the last names in the two rows.
    // A positive value means lastname B is AFTER lastname A alphabetically.
    // A negative value meanst lastname B is BEFORE lastname A.
    // A zero value means lastname B and lastname A are the same.
    var comp_val = lname_a.localeCompare(lname_b);
    if(debug)
        console.log("\'" + lname_a + "\'.localCompare(\'" + lname_b+"\') = " + comp_val);
    return comp_val;
}

function sort(tableID, tableColName, tableCol) {
    console.log("Sorting table: " + tableID + " by column " + tableCol
        + ":" + tableColName);
    col_num = tableCol; // set global variable based on parameter
    var html_rows = [];
    var rows = [];
    html_rows = document.getElementById(tableID).getElementsByTagName("tr");
    for(j = 0; j < html_rows.length; j++) {
        rows.push(html_rows[j]);
    }
    var num_rows = rows.length;
    console.log("number of rows: " + num_rows);
    // Rows[0] is the header text
    var row_zero = rows[0]; // store row 0 because it will not be sorted
    rows.shift();   // shift so the header row is removed from the array
    console.log("rows[0] = " + rows[0]);
    console.log("\t= " + rows[0].innerHTML);
    // Rows[1] is the first prof
    //console.log("rows[1] = " + rows[1]);
    //console.log("\t= " + rows[1].innerHTML);
    //var r1 = rows[1].getElementsByTagName("td");
    //console.log("r1[1]: " + r1[1].innerHTML);

    console.log("Preparing removal of rows...");
    var row_parent = rows[1].parentNode;
    // as long as the parent of the rows has more rows, remove them
    while (row_parent.hasChildNodes()) {
        row_parent.removeChild(row_parent.firstChild);
    }
    console.log("Rows have been removed from table");


    // Call the Array sort method using the compare_rows(a,b) function
    rows.sort(compare_rows);
    console.log("Rows have been sorted.");

    console.log("Adding header row back to table.");
    row_parent.appendChild(row_zero);

    console.log("Adding " + rows.length + " rows back to table...");
    while(rows.length > 0) {
        row_parent.appendChild(rows[0]); // append next element to table
        rows.shift(); // shift to remove the 'top' element from array
    }

    console.log("Rows have been added.");


}

function testing2(text) {
    document.getElementById("demo2").innerHTML += text + "<br/>";
}

function testing(tableID, tableColName, tableCol) {
    document.getElementById("demo").innerHTML = "Functional";
    console.log("functional log");
    sort(tableID, tableColName, tableCol);

}

function findLinks() {
    var answer = [];
    // TODO:  Add code here
    var links = document.links;
    var num_links = links.length;
    console.log("num_links: " + num_links);
    for (i = 0; i < num_links; i++) {
        var link_href = links[i].href;
        console.log(i + ": " + link_href);
        var index_val = link_href.indexOf("#");
        if(index_val != -1) {
            var link_href_substring = link_href.substring(index_val);
            console.log("\tsubtring: " + link_href_substring);
            answer.push(link_href_substring);
        } else {
            //answer.add(link_href);
            var link_href_short = link_href.substring(0, link_href.length - 1);
            answer.push(link_href_short);
        }
    }
    //for (i = 0; i < links.length; i++) {
    //    console.log("i: " + i + links.elements[i]); 
    //}
    console.log("doc.links: " + document.links);
    //return document.links;
    return answer;
}

/**
 * Compare two arrays and display a message at the bottom of the document indicating success or failure.
 * @param observed
 * @param expected
 */
function verifyResult(observed, expected) {

    // If observed is undefined, then do nothing.
    if (typeof(observed) == "undefined") {
        console.log("findLinks doesn't appear to be ready yet.");
        return;
    }

    console.log(observed);
    console.log(expected);

    var message = "Success";
    var detail = "";
    if (observed.length != expected.length) {
        message = "Fail:  Lengths differ.";
    } else {
        expected.forEach(function (element, index) {
            if (element != observed[index]) {
                message = "Fail";
                detail += "<br/>Element " + index + " differs.";
                console.log("Element " + index + " differs: ");
                console.log(element);
                console.log(observed[index]);
            }
        });
    }

    // This code demonstrates how one can add a completely new element to the DOM.
    // It shows how to add both content and styling to the new element.
    //
    // I chose this technique to keep the .html files as simple as possible.  However, in practice, 
    // it is more common to add a hidden, styled "placeholding" element to the DOM, and only use JavaScript to 
    // add the content an "unhide" the element when desired.
    var newItem = document.createElement("div");
    newItem.innerHTML = message + detail;
    newItem.style.display = "inline-block";
    newItem.style.backgroundColor = message === "Success" ? "lightgreen" : "red";
    newItem.style.padding = "15px";
    document.getElementsByTagName("body")[0].appendChild(newItem);
}


