#! /bin/bash

# Check if QUERY_STRING has 'key' defined
#echo "Query_String: $QUERY_STRING"
#QUERY_STRING="key=PINEAPPLE"
#echo "Query_String: $QUERY_STRING"

# store the entire query string into querystr
querystr=$QUERY_STRING
# split the query string around '=', and store pieces into an array
oldIFS=$IFS
IFS='=&'
#echo ":::"
# store query string into queryArr to be referenced as queryArr[index]
# where each index is a value separated by equal signs.
# Every odd index is the value, even indeces are the keys.
query=($QUERY_STRING)
# Create an associative array to pair keys and values
declare -A queryArr
for ((i=0; i<${#query[@]}; i+=2)); do
    # Connect key at index i to value at index (i+1)
    queryArr[${query[i]}]=${query[i+1]}
done
#echo "queryArr at a: ${queryArr[a]}"
a=${queryArr[a]}
b=${queryArr[b]}
c=${queryArr[c]}
# if a is defined, use the value from the query string
if [ ! $a ]; then
    #echo "Undefined a; use DEFAULT"
    a=9 # do nothing
#else
    #echo "a is defined"
fi
# if b is defined, use the value from the query string
if [ ! $b ]; then
    #echo "Undefined b; use DEFAULT"
    b=$((a/3*2)) # arbitrarily assign b to be 2/3rds of a
#else
    #echo "b is defined"
fi
# if c is defined, use the value from the query string
if [ ! $c ]; then
    #echo "Undefined c; use DEFAULT"
    c=$((b/3*2)) # arbitrarily assign c to be 2/3rds of b
#else
    #echo "c is defined"
fi

echo "<html><body><h1>Does It Triangle?</h1>"
echo "<table><tr><th>Side A</th><th>Side B</th><th>Side C</th></tr>"
echo "<tr><td>$a</td><td>$b</td><td>$c</td></tr></table>"

#echo "$a:$b:$c:"

# Determine if the 3 values form a valid triangle.
# Valid if: (a+b) > c AND (a+c) > b AND (b+c) > a
# Invalid otherwise.
ab=$((a+b))
ac=$((a+c))
bc=$((b+c))
#echo "(ab:$ab)"
#echo "(ac:$ac)"
#echo "(bc:$bc)"
# I use -gt for greater than and -a for AND because for some
# reason the && and > operators were not working properly for all cases.
if [ $ab -gt $c -a $ac -gt $b -a $bc -gt $a ]; then
    #echo "Yes those sides can form a triangle.:"
    echo "<h2>Yup! Those sides can form a triangle!</h2>"

    echo "<table><tr><th>Hypotenuse</th><th>Large Leg</th><th>Lil Leg</th></tr>"
    $hype = -1 
    $large = -2
    $lil = -3
    if [ $a -lt $b ]; then
        if [ $b -lt $c ]; then
            #echo "Hypotenuse is $c:Large Leg is $b:Lil Leg is $a:"   
            hype=$c
            large=$b
            lil=$a
        else
            #echo "Hypotenuse is $b:Large Leg is $c:Lil Leg s $a:"   
            hype=$b
            large=$c
            lil=$a
        fi
    else # a > b
        if [ $a -lt $c ]; then
            #echo "Hypotenuse is $c:Large Leg is $a:Lil Leg is $b:"   
            hype=$c
            large=$a
            lil=$b
        else
            #echo "Hypotenuse is $a:Large Leg is $c:Lil Leg is $b:"   
            hype=$a
            large=$c
            lil=$b
        fi
    fi
    echo "<tr><td>$hype</td><td>$large</td><td>$lil</td></tr></table>"

    # Use SideSideSide to find the angles of the triangle
    # 1. Law of Cosines
    cosA=$(((b*b+c*c-a*a)/(2*b*c)))
    #echo "cosA=$cosA:"
    #acosA=`perl -E 'use Math::Trig; say acos(${cosA})'`
    #echo $acosA
    # Based on those angles, determine the type of triangle it is.
     
else
    #echo "No those sides don't quite make a triangle.:" 
    echo "<h2>Oh No! those sides don't quite make a triangle.</h2>"
fi

echo "</body></html>"

#echo "DONE"

IFS=$oldIFS
