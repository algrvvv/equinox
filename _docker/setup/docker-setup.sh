#!/bin/bash

cd .. && cd ..

docker compose up -d

docker exec -it equinox_app composer install

docker exec -it equinox_app php master migrate
