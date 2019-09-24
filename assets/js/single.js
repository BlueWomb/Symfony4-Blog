require('../js/app.js');
require('jquery-validation');

$(document).ready(function() {
    var validator = $('#comment_form').validate();
    $('#submit_input').click(function() {
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
                            "post_id" : 357};
            $.post(generated_url, data, function(data, status) {
                console.log("Data: " + data + "\nStatus: " + status);
            });
        }
    });
});