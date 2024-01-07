php:
	docker exec -it ldv_db psql -U postgres

pgsql:
	docker exec -it equinox_pgsql psql -U postgres

mysql:
	docker exec -it equinox_mysql mysql -u root -p
