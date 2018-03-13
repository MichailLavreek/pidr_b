(function () {

    var legend = $('#js_date-picker-legend');
    var queues = $('#js_date-picker-queues');
    var queuesHeaderDate = $('#js_queues-header-date');
    var locale = document.documentElement.lang;
    if (locale === 'ua') locale = 'uk'; // в библиотеке "ua" локаль проходит как uk
    $.datepicker.setDefaults($.datepicker.regional[locale]);

    var selectedDateInstance = null;

    function performDayCell(date, dates) {
        var isSelectable = true;
        var additionalClass = '';

        var now = new Date();

        var year = date.getFullYear();
        var month = date.getMonth() + 1;
        var day = date.getDate();

        if (dates[year] && dates[year][month] && dates[year][month][day]) {
            var countHoldDays = 0;

            $.each(dates[year][month][day], function (key, queue) {
                if (queue === 'hold') {
                    countHoldDays++;
                }
            });

            if (countHoldDays === 3) {
                isSelectable = false;
                additionalClass = 'hold-day';
            } else {
                additionalClass = 'half-hold-day';
            }
        }

        now.setHours(0,0,0,0);
        if (now >= date) {
            isSelectable = false;
            additionalClass = 'past-days';
        }

        return [isSelectable, additionalClass];
    }



    function onSelectCallback(instance, dates) {
        selectedDateInstance = instance;

        legend.css('display', 'none');
        queues.css('display', 'block');

        var headerDate = (instance.selectedDay < 10) ? '0' + instance.selectedDay : instance.selectedDay;
        headerDate += ' ' + $.datepicker.regional[locale].monthNamesCustom[instance.selectedMonth].toLowerCase();
        headerDate += ' ' + instance.selectedYear + ' ' + $.datepicker.regional[locale].yearNameCustom.toLowerCase() + ':';

        queuesHeaderDate.text(headerDate);

        if (
            dates[instance.selectedYear] &&
            dates[instance.selectedYear][instance.selectedMonth + 1] &&
            dates[instance.selectedYear][instance.selectedMonth + 1][instance.selectedDay]
        ) {
            $('.popup-order__queues-item-button').each(function (index, item) {
                var queuesStatuses = dates[instance.selectedYear][instance.selectedMonth + 1][instance.selectedDay];
                $(item).removeClass()
                    .addClass('popup-order__queues-item-button')
                    .addClass('popup-order__queues-item-button--' +
                        queuesStatuses[index + 1])
                    .text($.datepicker.regional[locale].customQueues[queuesStatuses[index + 1]]);

            });

        } else {
            $('.popup-order__queues-item-button').each(function (index, item) {
                $(item).removeClass()
                    .addClass('popup-order__queues-item-button')
                    .addClass('popup-order__queues-item-button--free')
                    .text($.datepicker.regional[locale].customQueues['free']);
            });
        }
    }

    function setupEvents() {
        $('#js_queues-close').click(function () {
             queues.css('display', 'none');
             legend.css('display', 'block');
        });

        var queueButtons = $('.popup-order__queues-item-button');

        queueButtons.click(function (e) {
            var item = $(this);
            if (item.hasClass('popup-order__queues-item-button--hold') || item.hasClass('popup-order__queues-item-button--half-hold')) {
                return;
            }

            var selectedItem = $('.popup-order__queues-item-button.active');
            if (!item.hasClass('active')) {
                selectedItem.text($.datepicker.regional[locale].customQueues['free']).removeClass('active');
                item.addClass('active').text($.datepicker.regional[locale].selectedQueueButtonText);
            } else {
                item.text($.datepicker.regional[locale].customQueues['free']).removeClass('active');
            }
        });


    }

    $.ajax('/order/getDatesForCalendar', {
        type: 'GET',

        complete: function (data) {
            var dates = (data.status === 404) ? fakeDates : JSON.parse(data.responseText);

            $( "#datepicker" ).datepicker({
                showOtherMonths: true,
                beforeShowDay: function (date) {
                    return performDayCell(date, dates);
                },
                onSelect: function (date, instance) {
                    onSelectCallback(instance, dates);
                }
            });

            setupEvents();
        }
    });

    /** Обработка сабмита формы заказа монтажа */
    var form = $('#js_order-submit-handler');

    form.on('submit', (e) => {
        e.preventDefault();

        var agreedCheckbox = $('#required-checkbox');
        console.log(!agreedCheckbox.is(":checked"));
        if (!agreedCheckbox.is(":checked")) {
            agreedCheckbox.parent().addClass('error');
            agreedCheckbox.change(function() {
                if(this.checked) {
                    agreedCheckbox.parent().removeClass('error');
                }
            });
            return;
        }

        var name = $('#js_input-name');
        var phone = $('#js_input-phone');
        var address = $('#js_input-address');
        var quadrature = $('#js_input-quadrature');
        var product = $('#js_input-product');
        var productId = $('#js_input-product-id');

        var date = selectedDateInstance;

        if (!date) {
            var messages = {
                uk: 'Необхідно вибрати бажану дату монтажу!',
                ru: 'Необходимо выбрать желаемую дату монтажа!',
                en: 'You must select the desired installation date!',
            };

            console.log(locale, messages[locale], messages);
            var message = messages[locale];
            $('.popup-order__item--form').append($('<h4 class="error-message">'+message+'</h4>'));
            return;
        } else {
            $('.popup-order__item--form .error-message').remove();
        }

        var dateString = date.selectedYear + '-' + (1 + date.selectedMonth) + '-' + date.selectedDay;
        var selectedQueue = $('.popup-order__queues-item-button--free.active').data('queue');

        var data = {
            'name': name.val(),
            'phone': phone.val(),
            'address': address.val(),
            'quadrature': quadrature.val(),
            'product': product.val(),
            'productId': productId.val(),
            'orderDate': dateString,
            'orderQueue': selectedQueue,
        };

        $.ajax(form.attr('action'), {
            method: 'post',
            data: data,

            complete: function (res) {
                console.log(res);
                var state = JSON.parse(res.responseText);

                if (state.success) {
                    name.val('');
                    phone.val('');
                    address.val('');
                    quadrature.val('');
                    product.val('');
                    productId.val('');
                    form.fadeOut(300);

                    var successMessages = {
                        uk: 'Ваше повідомлення успішно відправлено. Ми зв\'яжемось з Вами!',
                        ru: 'Ваше сообщение успешно отправлено. Мы свяжемся с вами!',
                        en: 'Your message has been sent successfully. We will contact you!',
                    };

                    var successMessage = successMessages[locale];

                    $('.popup-order').append($('<h4 class="submit-success">'+successMessage+'</h4>'));
                }
            }
        });

        // console.log(data);
    });

})();

