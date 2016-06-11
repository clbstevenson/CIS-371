<script>
var name = "PHPSESSID=";
var ca = document.cookie.indexOf(name);
console.log(ca);
var ca_val = document.cookie.substring(ca);
console.log(ca_val);
var ca_sub = document.cookie.substring(ca, ca + name.length + 50);
var end = document.cookie.substring(ca).indexOf(' ');
var ca_sub2 = document.cookie.substring(ca, ca + end);
console.log(ca_sub);
//console.log(ca_sub2);
</script>
var all_cookies = document.cookie;
function setCookie(cname, cvalue, exdays) {
    var new_date = new Date();
    d.setTime(d.getTime() + (exdays *24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

console.log("all_cookies: " + all_cookies);

//var ca = document.cookie.split(';');
//for (var i = 0; i < ca.length; i++) {
 // console.log(i);
  
//}
</script>

// function get_phpsessid() {
//     var name = "PHPSESSID=";
//     var ca = document.cookie.split(';');
//     for (var i = 0; i < ca.length; i++) {
// 	console.log(i);
//         var c = ca[i];
//         while (c.charAt(0) == ' ') {
//             c = c.substring(1);
//         }
//         if(c.indexOf(name) == 0) {
//             return c.substring(name.length, c.length);
//         }
//     }
//     return "";
// }

//console.log("session id: " + get_phpsessid());

</script>