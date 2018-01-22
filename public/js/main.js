document.addEventListener('DOMContentLoaded', initMain);

function initMain() {

    (function () {
        var pageWrapper = document.getElementsByClassName('main-side')[0];
        var sidebarHeight = document.getElementsByClassName('left-side')[0].offsetHeight;
        var pageWrapperHeight = pageWrapper.offsetHeight;

        if (sidebarHeight > pageWrapperHeight) {
            pageWrapper.style.height = sidebarHeight + 'px';
        }
    })();

    /** Функционал мобильного меню */
    (function () {
        var mobBtn = document.getElementById('js_mobile-btn');
        var mobNav = document.getElementById('js_mobile-nav');
        var mobNavContent = document.getElementById('js_mobile-nav-content');
        var nav = document.getElementById('js_nav-sidebar');
        var screen = document.getElementById('js_screen');

        var mobFilter = document.getElementById('js_mobile-filter');
        var mobFilterCloseBtn = document.getElementById('js_mobile-filter-close');
        var mobFilterContent = document.getElementById('js_mobile-filter-content');
        var filter = document.getElementById('js_filter-sidebar');

        setupMobileMenu();

        function setupMobileMenu() {
            mobBtn.addEventListener('click', function (e) {
                e.preventDefault();

                if (mobNavContent.classList.contains('empty')) {
                    copyNavContentToMobile();
                }

                if (mobNav.classList.contains('active')) {
                    mobileMenu(mobNav, 'close');
                } else {
                    mobileMenu(mobNav, 'open');
                }
            });
        }

        if (filter) {
            setupMobileFilter();
        }

        function setupMobileFilter() {
            mobFilter.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                if (mobFilterContent.classList.contains('empty')) {
                    copyFilterContentToMobile();
                    setupFilters(mobFilter);
                }

                if (!mobFilter.classList.contains('active')) {
                    mobileMenu(mobFilter, 'open');
                }
            });

            mobFilterCloseBtn.addEventListener('click', function (e) {
                if (mobFilter.classList.contains('active')) {
                    e.stopPropagation();
                    mobileMenu(mobFilter, 'close');
                }
            })
        }

        function copyNavContentToMobile() {
            var navClone = nav.cloneNode(true);
            navClone.querySelector('.logo').remove();
            navClone.querySelector('.nav').style.display = 'block';
            var filter;
            if (filter = navClone.querySelector('.filter')) {
                filter.remove();
            }
            mobNavContent.innerHTML = '';
            mobNavContent.appendChild(navClone);
            mobNavContent.classList.remove('empty');
        }

        function copyFilterContentToMobile() {
            var filterClone = filter.cloneNode(true);
            filterClone.querySelector('#js_mobile-products-count').style.display = 'block';

            mobFilterContent.innerHTML = '';
            mobFilterContent.appendChild(filterClone);
            mobFilterContent.classList.remove('empty');
            reattachEvents();
        }

        function reattachEvents() {

        }
        
        var screenListenerHandler, navListenerHandler;
        function mobileMenu(element, action) {
            if (action === 'open') {
                element.classList.add('active');
                screen.classList.add('blur');

                navListenerHandler = element.addEventListener('click', function (e) {
                    e.stopPropagation();
                });

                screenListenerHandler = screen.addEventListener('click', function (e) {
                    mobileMenu(element, 'close');
                });

            } else if (action === 'close') {
                element.classList.remove('active');
                screen.classList.remove('blur');

                screen.removeEventListener('click', screenListenerHandler);
                element.removeEventListener('click', navListenerHandler);
            }
        }

        var ads = document.getElementsByClassName('cbalink');
        if (ads && ads.length > 0) {
            ads[0].remove()
        }
    })();


    /** Функционал по фильтрам */
    var filterContainer = document.getElementsByClassName('filter')[0];
    if (filterContainer) {
        setupFilters(document.getElementById('js_nav-sidebar'));
    }

    function setupFilters(container) {
        var navContainer = container.querySelector('.nav');
        var navTab = document.querySelector('#js_menu-tab-nav');
        var filterTab = document.querySelector('#js_menu-tab-filter');

        var sidebar = document.querySelector('.left-side');
        var checkboxes = container.querySelectorAll('.filter__button');
        var resetButton = container.querySelector('#js_filter-reset');
        var submitButton = container.querySelector('#js_filter-submit');
        var showProductsButton = document.querySelector('.show-products');

        var priceFromJs = container.querySelector('#js_price-from');
        var priceFromJq = $(priceFromJs);
        var priceToJs = container.querySelector('#js_price-to');
        var priceToJq = $(priceToJs);
        var sliderJs = container.querySelector('#slider-range');
        var sliderJq = $(sliderJs);

        function initPriceSlider() {
            var updateShowProductButtonTimeout;

            sliderJq.slider({
                range: true,
                min: priceFromJq.data('min'),
                max: priceToJq.data('max'),
                values: [priceFromJq.data('min'), priceToJq.data('max')],
                slide: function (event, ui) {
                    clearTimeout(updateShowProductButtonTimeout);
                    showRangeValues();
                    updateShowProductButtonTimeout = setTimeout(refreshShowProductsButton, 400);
                }
            });
        }

        function showRangeValues(ui) {
            var fromValue;
            var toValue;

            if (ui) {
                fromValue = ui.values[0];
                toValue = ui.values[1];
            } else {
                fromValue = sliderJq.slider("values", 0);
                toValue = sliderJq.slider("values", 1);
            }

            priceFromJq.val(fromValue);
            priceToJq.val(toValue);
        }

        initPriceSlider();
        showRangeValues();

        if (navContainer) {
            switchMenu('filter');
            navTab.addEventListener('click', function () {
                switchMenu('nav');
            });
            filterTab.addEventListener('click', function () {
                switchMenu('filter');
            });

            $(showProductsButton).find('.show-products__text').on('click', function (e) {
                var query = buildFilterQuery();
                if (query) {
                    window.location.href = query;
                }
            });
        }

        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].addEventListener('click', function () {
                if (this.classList.contains('active')) {
                    this.classList.remove('active');
                } else {
                    this.classList.add('active');
                }
                refreshShowProductsButton(this);
            });
        }

        resetButton.addEventListener('click', function () {
            for (var i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].classList.contains('active')) {
                    checkboxes[i].classList.remove('active');
                }
            }

            window.location.href = buildFilterQuery(true);
        });

        submitButton.addEventListener('click', function () {
            var query = buildFilterQuery();

            if (query) {
                window.location.href = query;
            }
        });

        function switchMenu(element) {
            switch (element) {
                case 'filter':
                    navContainer.style = 'display: none;';
                    filterContainer.style = 'display: block;';
                    filterTab.classList.add('active');
                    navTab.classList.remove('active');
                    sidebar.classList.add('left-side--filter');
                    if (buildFilterQuery()) {
                        refreshShowProductsButton();
                        showProductsButton.style = 'display: block;';
                    }
                    break;
                case 'nav':
                    navContainer.style = 'display: block;';
                    filterContainer.style = 'display: none;';
                    navTab.classList.add('active');
                    filterTab.classList.remove('active');
                    sidebar.classList.remove('left-side--filter');
                    showProductsButton.style = 'display: none;';
                    break;
            }

            document.getElementById('js_hide-show-products-btn').addEventListener('click', function () {
                showProductsButton.style.display = 'none';
            });
        }

        function refreshShowProductsButton(button) {
            button = button || $('.filter__button.active .filter__checkbox').first()[0] || $('.filter__item--price')[0];

            var bodyRect = document.body.getBoundingClientRect(),
                elemRect = button.getBoundingClientRect(),
                offset   = elemRect.top - bodyRect.top,
                query = buildFilterQuery();

            if (query) {
                $.ajax(query + '&onlyCount=true', {
                    type: 'post',
                    complete: (data) => {
                        var count = JSON.parse(data.responseText).count;
                        $(showProductsButton).find('.show-products__count').text(count);
                        $('.mobile-products-count-value').text(count);
                        showProductsButton.style = 'display: block; top: ' + (offset - 8) + 'px;';
                    }
                });
            } else {
                showProductsButton.style = 'display: none';
            }
        }
    }

    function buildFilterQuery(returnAnyway) {
        var searchParams = new URLSearchParams();
        var filterItems = $('.filter__item');
        var priceFrom = $('#js_price-from');
        var priceTo = $('#js_price-to');
        var queryExists = false;

        filterItems.each(function (fi, fItem) {
            var code = $(fItem).data('code');
            $(fItem).find('.filter__button.active').each(function (bi, bItem) {
                var value = $(bItem).data('value');
                var performedValue = searchParams.has(code) ? searchParams.get(code) + '-' + value : value;
                queryExists = true;
                searchParams.set(code, performedValue);
            })
        });

        if (+priceFrom.val() !== +priceFrom.data('min') || +priceTo.val() !== +priceTo.data('max')) {
            searchParams.set('price', priceFrom.val() + '-' + priceTo.val());
            queryExists = true;
        }

        var query = window.location.origin + window.location.pathname + '?' + searchParams.toString();

        return (returnAnyway || queryExists) ? query : false;
    }

    /** Product */
    if (typeof $ === 'function') {

        var slickSliderFor = $('.slick-slider-for');
        var slickSliderNav = $('.slick-slider-nav');

        if (slickSliderFor.length && slickSliderNav.length) {
            slickSliderFor.slick({
                slidesToShow: 1,
                slidesToScroll: 1,
                arrows: false,
                fade: true,
                asNavFor: '.slick-slider-nav'
            });
            slickSliderNav.slick({
                slidesToShow: 3,
                // initialSlide: 1,
                variableWidth: true,
                // centerMode: true,
                infinite: false,
                slidesToScroll: 1,
                asNavFor: '.slick-slider-for',
                focusOnSelect: true
            });
        }


        function setupTabs() {
            var tabsBtn = $('.product-content__tabs-btn');
            var tabsContentElements = $('.product-content__tabs-content-item');
            var tabsContentBlock = $('.product-content__tabs-content-block');

            tabsBtn.map(function (index, item) {
                var element = $(item);
                var content = $('#js_tabs-element-' + element.data('id'));
                var contentCopy = content.clone(true);
                element.append(contentCopy);
                contentCopy.on('click', function (e) {
                    e.stopPropagation();
                });

                element.on('click', function (e) {
                    console.log('click');
                    if (!element.hasClass('active')) {
                        tabsBtn.removeClass('active');
                        tabsContentElements.removeClass('active');
                        element.addClass('active');
                        content.addClass('active');

                        $('html, body').animate({
                            scrollTop: element.offset().top
                        }, 400);

                        if (element.data('id') !== 1 && !tabsContentBlock.hasClass('product-content__tabs-content-block--border-fix')) {
                            tabsContentBlock.addClass('product-content__tabs-content-block--border-fix');
                        } else if (element.data('id') === 1 && tabsContentBlock.hasClass('product-content__tabs-content-block--border-fix')) {
                            tabsContentBlock.removeClass('product-content__tabs-content-block--border-fix');
                        }
                    } else {
                        if (window.innerWidth <= 480) {
                            tabsBtn.removeClass('active');
                            tabsContentElements.removeClass('active');
                        }
                    }
                });
            });
        }
        setupTabs();

        $('.product-content__register').magnificPopup({
            items: {
                type: 'ajax',
                src: 'popup-order.html'
            }
        });

        $('.simple-popup').magnificPopup({
            type: 'image',
            image: {
                titleSrc: function (item) {
                    return item.el.data().popupTitle;
                }
            },
            gallery: {
                enabled: true
            }
        });


        /** Category */
        var categorySlider = $('#js_category-slider');

        if (categorySlider.length) {
            categorySlider.slick({
                arrows: false,
                infinite: false,
                dots: true
            });
        }
    }
}
