require('../js/app.js');
require('jquery-validation');
var dateformat = require('dateformat');

$(document).ready(function() {
    var validator = $('#comment_form').validate();
    $('#submit_input').click(function() {
        update_comments();
        var errors = null;

        if (!$("#name_input").val()) errors = { "name_input": "Please enter a valid name" };
        if (!$("#email_input").val()) errors = { "email_input": "Please enter a valid email" };
        if (!$("#message_input").val()) errors = { "message_input": "Please enter a valid message" };

        if (errors !== null)
            validator.showErrors(errors);
        else {
            var generated_url = Routing.generate("post_comment");
            data = {"name" : $("#name_input").val(), "email": $("#email_input").val(), 
                        "website" : $("#website_input").val(), "message" : $("#message_input").val(),
                            "post_id" : $("#comment_form").attr("whatsend")};
            $.post(generated_url, data, function(data, status) {
                if (status !== "error" && data) {
                    update_comments();
                    $("#name_input").val("");
                    $("#website_input").val("");
                    $("#email_input").val("");
                    $("#message_input").val("");
                }
            });
        }
    });

    var update_comments = function() {
        var generated_url = Routing.generate("get_comments_json", {"id" : $("#comment_form").attr("whatsend")});

        $.ajax({
            url: generated_url,
            dataType: 'json',
            complete: function (xhr, status) {
                if (status !== "error" && xhr.responseJSON) {
                    input = JSON.parse(xhr.responseJSON);
                    $("#comment-list").html("");
                    output = "";
                    for(var i = 0; i < input.length; i++) {
                        var date_string = dateformat(new Date(input[i].createdAt.timestamp + input[i].createdAt.offset), "dd-mm-yyyy");
                        console.log(input[i].createdAt);
                        console.log(input[i].createdAt.timestamp); 
                        output += '<li class="comment">' +
                                    '<h3>' + input[i].name + '</h3>' +
                                    '<div class="meta">' + date_string + '</div>' +
                                    '<p>' + input[i].message + '</p>' +
                                    '</li>';
                    }
                    $("#comment-list").html(output);
                }
            },
        });
    }
});