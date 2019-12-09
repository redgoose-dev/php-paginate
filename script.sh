#!/bin/bash

# set port
[[ -z "$2" ]] && port=9000 || port=$2

# func / start server
start() {
  php -S 0.0.0.0:$port -t ./tests
}

case "$1" in
  start)
    start
    ;;

  *)
    echo "Usage: ./action.sh {start}" >&2
    exit 3
    ;;
esac
