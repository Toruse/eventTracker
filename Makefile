docker-up: memory
	docker-compose up -d

docker-down:
	docker-compose down

docker-build: memory
	docker-compose up --build -d

assets-install:
	docker-compose exec node npm install

assets-build:
	docker-compose exec node npm run build

assets-dev:
	docker-compose exec node npm run dev

assets-watch:
	docker-compose exec node npm run watch