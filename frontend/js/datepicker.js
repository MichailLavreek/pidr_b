(function () {

    var fakeDates = { //if month/day not in the data, this means its free
        '2017': {
            '11': { //month
                '17': { //day
                    '1': 'hold', //queue (free or hold)
                    '2': 'free',
                    '3': 'free'
                },
                '18': {
                    '1': 'half-hold',
                    '2': 'free',
                    '3': 'free'
                },
                '19': {
                    '1': 'hold',
                    '2': 'hold',
                    '3': 'hold'
                },
                '20': {
                    '1': 'hold',
                    '2': 'hold',
                    '3': 'half-hold'
                },
            },
            '12': {
                '1': {
                    '1': 'hold',
                    '2': 'hold',
                    '3': 'hold'
                },
                '2': {
                    '1': 'free',
                    '2': 'half-hold',
                    '3': 'hold'
                },
                '18': {
                    '1': 'free',
                    '2': 'hold',
                    '3': 'hold'
                },
                '22': {
                    '1': 'hold',
                    '2': 'hold',
                    '3': 'hold'
                },
                '31': {
                    '1': 'free',
                    '2': 'free',
                    '3': 'half-hold'
                }
            }
        }
    };

    var legend = $('#js_date-picker-legend');
    var queues = $('#js_date-picker-queues');
    var queuesHeaderDate = $('#js_queues-header-date');
    var locale = 'uk'; //TODO: брать с бэкенда

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
            var selectedItem = $('.popup-order__queues-item-button.active');
            if (!item.hasClass('active')) {
                selectedItem.text($.datepicker.regional[locale].customQueues['free']).removeClass('active');
                item.addClass('active').text($.datepicker.regional[locale].selectedQueueButtonText);
            } else {
                item.text($.datepicker.regional[locale].customQueues['free']).removeClass('active');
            }
        });
    }

    $.ajax('urlToServer', {
        type: 'GET',
        data: {someParam: 'some value'},

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

})();

