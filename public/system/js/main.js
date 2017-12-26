/** Скрыть селекты, в которых лежат служебные элементы для связки корневых элементов с их языковыми элементами */
$('.forced-hidden-data-field').parent().hide();
$('.field-collection-action a').click(() => {
    $('.forced-hidden-data-field').parent().hide();
});