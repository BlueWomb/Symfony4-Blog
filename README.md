# Symfony4-Blog

Crea file .env 
	-> DATABASE_URL=mysql://root:@127.0.0.1:3306/blog

composer install
yarn install
yarn encore dev
php bin/console assets:install --symlink public
php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json