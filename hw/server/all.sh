#! /bin/bash

bash part1
bash part2
bash part3

# display part 1 to stdout 
echo "PART 1"
echo "<br>"
echo "<p>"
sort -t',' -k2 part1data
echo "</p>"
echo "<br><hr><br>"

# display part 2 to stdout
echo "PART 2"
echo "<br>"
echo "<p>"
gawk -F, '{printf "%s\t%s\t\t%s\n", $3, $2, $1}' out_1  
echo "</p>"
echo "<br><hr><br>"

# display part 3 to stdout
echo "PART 3"
echo "<br>"
echo "<p>"
sed -n -e s/Fall\ /F/p -e s/Winter\ /W/p out_2 
echo "</p>"
echo "<br><hr><br>"
