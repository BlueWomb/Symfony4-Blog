/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
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

window.filter_by_category = function(category_id) {
    console.log("filter_by_category");
    $.ajax({
        url: "filter_by_category",
        data: { 'page': '1', 'category_id': category_id},
        dataType: 'json',
        complete: function (xhr, status) {
            if (status !== "error" && xhr.responseJSON) {
                input = JSON.parse(xhr.responseJSON);
                $("#div-site-content").html("");
                for (var i = 0; i < input.length; i++) {
                    console.log(input[i]);
                    output = '<div class="col-lg-4 mb-4">' + 
                                '<div class="entry2">' +
                                    '<a href="single.html"><img src="{{ asset("build/images/img_1.jpg") }}" alt="Image" class="img-fluid rounded"></a>' +
                                    '<div class="excerpt">';

                                        for(var j = 0; j < input[i].tags.length; j++)
                                            output += '<span class="post-category text-white bg-secondary mb-3" style="background-color:' + input[i].tags[j].color + '!important;">' + input[i].tags[j].name + '</span>';
                                        
                                            output += '<h2><a href="single.html">' + input[i].title + '</a></h2>'+
                                        '<div class="post-meta align-items-center text-left clearfix">' +
                                            '<figure class="author-figure mb-0 mr-3 float-left"><img src="{{ asset("build/images/person_1.jpg") }}" alt="Image" class="img-fluid"></figure>' +
                                            '<span class="d-inline-block mt-1">By<a href="#">' + input[i].author.name + '</a></span>' +
                                            '<span>&nbsp;-&nbsp; {{ post.createdAt|date("d-m-Y") }}</span>' +
                                        '</div>' +
                                        '<p>' + truncate(input[i].description) + '</p>' +
                                        '<p><a href="#">Read More</a></p>' + 
                                    '</div>' +
                                '</div>' + 
                            '<div>';
                    $("#div-site-content").append(output);
                }
            }
        },     
    });
}

function truncate(str, length, ending) {
    if (length == null) length = 127;
    if (ending == null) ending = "...";
    if (str.length > length) return str.substring(0, length - ending.length) + ending;
    else return str;
}

require("../fonts/icomoon/style.css");
require("../fonts/flaticon/font/flaticon.css");

require("../css/bootstrap.min.css");
require("../css/magnific-popup.css");
require("../css/bootstrap-datepicker.css");
require("../fonts/flaticon/font/flaticon.css");
require("../css/aos.css");

require("../css/style.css");

console.log('Hello Webpack Encore!');