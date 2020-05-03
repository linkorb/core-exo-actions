#!/bin/bash

INPUT=`cat /dev/stdin`

# echo $INPUT | jq ".name"

GREETING=`echo $INPUT | jq -r ".input.greeting"`
NAME=`echo $INPUT | jq -r ".input.name"`
COLOR=$EXO__EXAMPLE__COLOR
printf '{"status": "OK", "output": {"text": "%s, %s (%s)"}}' "$GREETING" "$NAME" "$COLOR"
