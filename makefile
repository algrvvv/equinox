php:
	docker-compose exec nginx nginx -s reload

pgsql:
	docker exec -it equinox_pgsql psql -U postgres

mysql:
	docker exec -it equinox_mysql mysql -u root -p

migrate:
	docker exec -it equinox_app php master migrate

nginx_reload:
	docker exec equinox_nginx nginx -s reload
