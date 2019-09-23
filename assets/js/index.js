require("../js/app.js");

$(document).ready(function() {
    console.log("Window cleared");

    window.page = 1;
    window.category_id = -1;
    window.search_key = null;
});

$('#s').on('keyup', function() {
    window.page = 1;
    window.search_key = this.value
    
    var generated_url = Routing.generate('index_with_params', { type: 'json', page: 1, category_id: category_id, search_key: search_key });
    console.log("filter_by_search_key");

    $.ajax({
        url: generated_url,
        dataType: 'json',
        complete: function (xhr, status) {
            if (status !== "error" && xhr.responseJSON) {
                input = JSON.parse(xhr.responseJSON.data);
                num_pages = Math.trunc(JSON.parse(xhr.responseJSON.pages));
                
                update_div(input);
                update_pagination(num_pages);
            }
        },
    });
});

window.filter_by_category = function (category_id) {
    window.page = 1;
    window.category_id = category_id;

    var generated_url = Routing.generate('index_with_params', { type: 'json', page: 1, category_id: category_id, search_key: search_key });
    console.log("filter_by_category");
    
    $.ajax({
        url: generated_url,
        dataType: 'json',
        complete: function (xhr, status) {
            if (status !== "error" && xhr.responseJSON) {
                input = JSON.parse(xhr.responseJSON.data);
                num_pages = Math.trunc(JSON.parse(xhr.responseJSON.pages));
                
                update_div(input);
                update_pagination(num_pages);
            }
        },
    });
}

window.filter_by_page = function (page) {
    window.page = page;

    var generated_url = Routing.generate('index_with_params', { type: 'json', page: page, category_id: category_id, search_key: search_key });
    console.log("filter_by_page");
    
    $.ajax({
        url: generated_url,
        dataType: 'json',
        complete: function (xhr, status) {
            if (status !== "error" && xhr.responseJSON) {
                input = JSON.parse(xhr.responseJSON.data);
                update_div(input);
            }
        },
    });
}

function update_pagination(num_pages) {
    $("#div-pagination").html("");
    for (var i = 1; i <= num_pages + 1; i++) {
        var generated_url = Routing.generate('index_with_params', { type: 'json', page: i, category_id: category_id, search_key: search_key });
        var output = '<a href="#" onclick="filter_by_page(' + i + ')">' + i + '</a>';
        $("#div-pagination").append(output);
    }
}

function update_div(input) {
    $("#div-site-content").html("");

    if(input.length == 0) {
        var output = '<div style><h2>No post found</h2></div>';
        $("#div-site-content").append(output);
        return;
    }

    for (var i = 0; i < input.length; i++) {
        var generate_url = Routing.generate('single', { id: input[i].id })
        output = '<div class="col-lg-4 mb-4">' +
            '<div class="entry2">' +
            '<a href="single.html"><img src="/build/images/' + input[i].preview + '" alt="Image" class="img-fluid rounded"></a>' +
            '<div class="excerpt">';

        for (var j = 0; j < input[i].tags.length; j++)
            output += '<span class="post-category text-white bg-secondary mb-3" style="background-color:' + input[i].tags[j].color + '!important;">' + input[i].tags[j].name + '</span>';

        output += '<h2><a href="single.html">' + input[i].title + '</a></h2>' +
            '<div class="post-meta align-items-center text-left clearfix">' +
            '<figure class="author-figure mb-0 mr-3 float-left"><img src="/build/images/' + input[i].author.image + '" alt="Image" class="img-fluid"></figure>' +
            '<span class="d-inline-block mt-1">By<a href="#">' + input[i].author.name + '</a></span>' +
            '<span>&nbsp;-&nbsp;' + input[i].createdAt.toString() + '</span>' +
            '</div>' +
            '<p>' + truncate(input[i].description) + '</p>' +
            '<p><a href="' + generate_url + '">Read More</a></p>' +
            '</div>' +
            '</div>' +
            '<div>';
        $("#div-site-content").append(output);
    }
}

function truncate(str, length, ending) {
    if (length == null) length = 127;
    if (ending == null) ending = "...";
    if (str.length > length) return str.substring(0, length - ending.length) + ending;
    else return str;
}

window.hide_search_form = function() {
    if ( $('.js-search-form').hasClass('active') ) {
        $('.js-search-form').removeClass('active');
    }
}