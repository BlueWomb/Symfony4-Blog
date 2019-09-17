/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../public/bundles/fosjsrouting/js/router.min.js';
Routing.setRoutingData(routes);
window.Routing = Routing;

const $ = require("jquery");
require("jquery-migrate");
require("webpack-jquery-ui");
require("bootstrap");
require('bootstrap-sass');
require("../js/owl.carousel.min.js");
require("../js/jquery.stellar.min.js");
require("../js/jquery.countdown.min.js");
require("../js/jquery.magnific-popup.min.js");
require("aos");

require("../js/main.js");

require("../fonts/icomoon/style.css");
require("../fonts/flaticon/font/flaticon.css");

require("../css/bootstrap.min.css");
require("../css/magnific-popup.css");
require("../css/bootstrap-datepicker.css");
require("../fonts/flaticon/font/flaticon.css");
require("../css/aos.css");

require("../css/style.css");

console.log('Hello Webpack Encore!');