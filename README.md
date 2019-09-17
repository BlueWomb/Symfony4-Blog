# Symfony4-Blog

To run the software locally, you should install:
- php 7. 
- composer 
- any mysql server.

I suggest you to in stall EasyPhpDevServer: https://www.easyphp.org/

Completed the previous step, create a file inside the main folder called <b>.env</b> (without extension) containing the following line:
- DATABASE_URL=mysql://root:@127.0.0.1:3306/blog

Finally, run the following commands from the terminal:
- composer install
- yarn install
- yarn encore dev
- php bin/console assets:install --symlink public
- php bin/console fos:js-routing:dump --format=json --target=public/js/fos_js_routes.json
