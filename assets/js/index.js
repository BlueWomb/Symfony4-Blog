require("../js/app.js");

window.filter_by_category = function(category_id) {
    console.log("filter_by_category");
    $.ajax({
        url: "filter_by_category",
        data: { 'json' : '1', 'page': '1', 'category_id': category_id},
        dataType: 'json',
        complete: function (xhr, status) {
            console.log(xhr.responseText);
            if (status !== "error" && xhr.responseJSON) {
                input = JSON.parse(xhr.responseJSON);
                $("#div-site-content").html("");
                for (var i = 0; i < input.length; i++) {
                    console.log(input[i]);
                    output = '<div class="col-lg-4 mb-4">' + 
                                '<div class="entry2">' +
                                    '<a href="single.html"><img src="build/images/' + input[i].preview + '" alt="Image" class="img-fluid rounded"></a>' +
                                    '<div class="excerpt">';

                                        for(var j = 0; j < input[i].tags.length; j++)
                                            output += '<span class="post-category text-white bg-secondary mb-3" style="background-color:' + input[i].tags[j].color + '!important;">' + input[i].tags[j].name + '</span>';
                                        
                                            output += '<h2><a href="single.html">' + input[i].title + '</a></h2>'+
                                        '<div class="post-meta align-items-center text-left clearfix">' +
                                            '<figure class="author-figure mb-0 mr-3 float-left"><img src="build/images/' + input[i].author.image + '" alt="Image" class="img-fluid"></figure>' +
                                            '<span class="d-inline-block mt-1">By<a href="#">' + input[i].author.name + '</a></span>' +
                                            '<span>&nbsp;-&nbsp;' + input[i].createdAt.toString() + '</span>' +
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