/* ---------------------------------------------
 Contact form
 --------------------------------------------- */
$(document).ready(function () {
    $("#submit_btn").click(function (e) {
        e.preventDefault();

        var $btn = $(this);
        var $btnText = $btn.find("span");
        var originalText = $btnText.text();

        var $name = $("input[name=name]");
        var $email = $("input[name=email]");
        var $message = $("textarea[name=message]");
        var user_name = $name.val().trim();
        var user_email = $email.val().trim();
        var user_message = $message.val().trim();

        let proceed = true;

        // Clear previous borders
        $name.css("border-color", "");
        $email.css("border-color", "");
        $message.css("border-color", "");
        $("#result").slideUp();

        if (user_name === "") {
            $name.css("border-color", "#e41919");
            proceed = false;
        }
        if (user_email === "") {
            $email.css("border-color", "#e41919");
            proceed = false;
        }
        if (user_message === "") {
            $message.css("border-color", "#e41919");
            proceed = false;
        }

        if (proceed) {
            // Show "Sending..." on button
            $btn.prop("disabled", true);
            $btnText.text("Sending...");

            grecaptcha.ready(function () {
                grecaptcha
                    .execute("6Ldj4ZsrAAAAALHUsIYZ2zc4vmvwdqYCJ02M4TgH", {
                        action: "contact_form",
                    })
                    .then(function (token) {
                        const postData = {
                            name: user_name,
                            email: user_email,
                            message: user_message,
                            recaptcha_token: token,
                        };

                        $.ajax({
                            type: "POST",
                            url: "/contact",
                            data: postData,
                            dataType: "json",
                            headers: {
                                "X-CSRF-TOKEN": $(
                                    'meta[name="csrf-token"]'
                                ).attr("content"),
                            },
                            success: function (response) {
                                if (response.message) {
                                    $("#result")
                                        .hide()
                                        .html(
                                            '<div class="success">' +
                                                response.message +
                                                "</div>"
                                        )
                                        .slideDown();
                                    $("#contact_form")[0].reset();
                                } else {
                                    $("#result")
                                        .hide()
                                        .html(
                                            '<div class="error">Unexpected server response.</div>'
                                        )
                                        .slideDown();
                                }

                                // Restore button state
                                $btn.prop("disabled", false);
                                $btnText.text(originalText);
                            },
                            error: function (xhr) {
                                const errorMsg =
                                    xhr.responseJSON?.message ||
                                    "Something went wrong. Please try again.";
                                $("#result")
                                    .hide()
                                    .html(
                                        '<div class="error">' +
                                            errorMsg +
                                            "</div>"
                                    )
                                    .slideDown();

                                // Restore button state
                                $btn.prop("disabled", false);
                                $btnText.text(originalText);
                            },
                        });
                    });
            });
        }
    });

    $("#contact_form input, #contact_form textarea").keyup(function () {
        $(this).css("border-color", "");
        $("#result").slideUp();
    });
});
