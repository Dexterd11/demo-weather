# demo-weather
Demo Weather
Steps to follow :
1. `docker-compose up -d --build`
2. `docker exec -it demo-fpm /var/www/bin/console predictions:update`

Example url :
`http://demo.local/weather?city=Amsterdam&scale=celsius`
`http://demo.local/weather?city=Amsterdam&scale=fahrenheit`
