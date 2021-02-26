/*
 --------------------------------
 Ajax Contact Form
 --------------------------------
 + https://github.com/mehedidb/Ajax_Contact_Form
 + A Simple Ajax Contact Form developed in PHP with HTML5 Form validation.
 + Has a fallback in jQuery for browsers that do not support HTML5 form validation.
 + version 1.0.1
 + Copyright 2016 Mehedi Hasan Nahid
 + Licensed under the MIT license
 + https://github.com/mehedidb/Ajax_Contact_Form
 */

(function ($, window, document, undefined) {
    'use strict';

    $('#contact-form').validate({
        rules: {
            name: 'required',
            phone: {
                required: true,
                minlength: 10,
                maxlength: 10,
                digits: true
            },
            email: {
                required: true,
                email: true
            },
            message: 'required'
        },
        messages: {
            name: 'Please enter your name',
            email: 'Enter valid email address',
            message: 'Please enter message'
        },
        errorClass: "text-danger",
        errorElement: "span",
        submitHandler: function (form) {
            var $form = $('#contact-form');

            $.ajax({
                type: "POST",
                url: $form.attr('action'),
                data: $form.serialize(),
                dataType: 'json',
                beforeSend: function () {
                    $("#btn_submit").attr("disabled", true);
                    $("#btn_submit").val("Submitting..");
                },
                success: function (res)
                {
                    if (res.status) {
                        $('#contact-form')[0].reset();
                        $('#contact_message').html('<h6 class="text-success">' + res.message + '</h6>');
                    } else {
                        $('#contact_message').html('<h6 class="text-danger">' + res.message + '</h6>');
                    }
                },
                error: function (xhr) {
                    alert(xhr.statusText + xhr.responseText);
                },
                complete: function () {
                    $("#btn_submit").val("Submit");
                    $("#btn_submit").attr("disabled", false);
                }
            });
        }
    });
}(jQuery, window, document));
