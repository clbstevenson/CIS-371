#! /bin/tcsh
# Check the number of arguments provided
echo "Query_String: $QUERY_STRING"
QUERY_STRING="a=4"
echo "Query_String: $QUERY_STRING"
saveIFS=$IFS
IFS='=&'
vals=($QUERY_STRING)
IFS=$saveIFS
echo "0: ${vals[0]}"
echo "1: ${vals[1]}"
echo "2: ${vals[2]}"
echo "3: ${vals[3]}"
if ( $key == 0 ); then
    echo "Undefined key, use default key GVSUDEFAULT"
else
    echo "Key is defined as $key"
endif

echo "After key is set"
#if ( $# == 0 || $#argv > 6); then
#    echo "enter between 1 and 7 arguments"
#else
#    echo "correct args number"
#    set sum = 0
#    set count = 0
#    foreach i ($argv[*])
#        @ count ++
#        @ sum = $sum + $i
#        echo "i = $i"
#        echo "new sum = $sum"
#    end
#    set avg = 0
#    @ avg  = $sum / $count
#    echo "avg = $avg "
#endif
echo "After comments"
