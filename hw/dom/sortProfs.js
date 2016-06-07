/**
 * Created by kurmasz on 5/18/15.
 */

function compare_rows(row_a, row_b) {
    var td_a = row_a.getElementsByTagName("td");
    var lname_a = td_a[1].innerHTML;
    console.log("lname_a: " + td_a);
    console.log("\t1: " + lname_a);

    var td_b = row_b.getElementsByTagName("td");
    var lname_b = td_b[1].innerHTML;
    console.log("lname_b: " + td_b);
    console.log("\t1: " + lname_b);

    // Compare the last names in the two rows.
    // A positive value means lastname B is AFTER lastname A alphabetically.
    // A negative value meanst lastname B is BEFORE lastname A.
    // A zero value means lastname B and lastname A are the same.
    var comp_val = lname_a.localeCompare(lname_b);
    console.log("\'" + lname_a + "\'.localCompare(\'" + lname_b+"\') = " + comp_val);
    return comp_val;
}

function testing2(text) {
    document.getElementById("demo2").innerHTML += text + "<br/>";
}

function testing() {
    document.getElementById("demo").innerHTML = "Functional";
    console.log("functional log");
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


