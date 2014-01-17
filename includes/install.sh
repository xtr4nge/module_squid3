#!/bin/bash

echo "installing squid3..."
apt-get -y install squid3

update-rc.d -f squid3 remove

echo "..DONE.."
exit
