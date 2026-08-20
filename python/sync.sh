#!/bin/bash

set -a
source /root/python/.env
set +a

cd /root/python/

if [[ $1 = "fixtures" ]]
then
    echo "Actualizando Partidos"
    /usr/local/bin/python /root/python/getFixtures.py
elif [[ $1 = "leagues" ]]
then
    echo "Actualizando Ligas"
    /usr/local/bin/python /root/python/getLeagues.py
else
    echo "Argumentos Incorrectos"
fi
