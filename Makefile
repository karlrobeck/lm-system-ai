dev/frontend:
	npm run dev
dev/backend:
	php artisan serve

dev/mysql:
	docker compose up mysql -d

dev: 
	make -j3 dev/frontend dev/backend dev/mysql