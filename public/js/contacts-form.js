
(function () {
    var form = $('#js_contacts-form');

    form.on('submit', function (e) {
        e.preventDefault();

        var name = $('#js_input-name');
        var email = $('#js_input-email');
        var message = $('#js_input-message');

        var data = {
            'name': name.val(),
            'email': email.val(),
            'message': message.val(),
        };

        $.ajax(form.attr('action'), {
            method: 'post',
            data: data,

            complete: function (res) {
                var state = JSON.parse(res.responseText);

                if (state.success) {
                    name.val('');
                    email.val('');
                    message.val('');
                    form.fadeOut(300);
                }
            }
        });

        console.log(form.attr('action'));
    });
})();
