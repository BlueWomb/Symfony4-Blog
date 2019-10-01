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
- php bin/console doctrine:fixtures:load


I'm using a template (that I have customized) with a CC BY 3.0 license (as you can see from the page footer). So, if you want to use the code contained in this repository in a commercial way, make sure you buy a commercial license from https://colorlib.com/wp/template/miniblog/.

Used bundle:
- jsrountinbundle: to generate path for ajax request.
- fosuserbundle: to handle basic author login.
