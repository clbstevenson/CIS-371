import re
[re.findall(r'a{1}', line)
for line in open('lotsOfLinks.html')]:
    print line
