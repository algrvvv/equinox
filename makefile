php:
	docker exec -it equinox_app bash

pgsql:
	docker exec -it equinox_pgsql psql -U postgres

mysql:
	docker exec -it equinox_mysql mysql -u root -p

migrate:
	docker exec -it equinox_app php master migrate
