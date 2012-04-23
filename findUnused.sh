#!/bin/bash
for line in  `find . -name '*.js' -o -name '*.php' -printf "%f\n"`
  do
     if [ `grep -R $line . | wc -l` -lt 2 ]
     then
      echo $line
     fi
  done 
