#! /bin/bash

# Check if QUERY_STRING has 'key' defined
echo "Query_String: $QUERY_STRING"
#QUERY_STRING="key=PINEAPPLE"
#echo "Query_String: $QUERY_STRING"

# store the entire query string into querystr
querystr=$QUERY_STRING
# split the query string around '=', and store pieces into an array
IFS='=' read -ra queryArr <<< "$querystr"
echo "0: ${queryArr[0]}"
echo "1: ${queryArr[1]}"
for i in "${queryArr[@]}"; do
    =$i*$i
    echo "$i * $i = $pow"
done

