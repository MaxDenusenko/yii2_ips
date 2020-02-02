jQuery(document).ready(function($) {
    /*
     * Добавление товара в корзину с использованием AJAX
     */
    $('.add-to-basket').on('submit', function (event) {
        var action = $(this).attr('action');
        var method = $(this).attr('method');

        var data = $(this).serialize();
        $.ajax({
            url: action,
            type: method,
            data: data,
            dataType: 'html',
            success: function (response) {
                $('#basket-modal .modal-body').html(response);
                $('#basket-modal').modal();
            },
            error: function () {
                alert('Произошла ошибка при добавлении товара в корзину');
            }
        });

        event.preventDefault();
        event.stopImmediatePropagation();
        return false;
    });

    $('#basket-modal .modal-body').on('click', 'table a.remove-product', function (event) {
        var href = $(this).attr('href');

        $('#basket-modal .modal-body').load(href, function () {
            // если корзина пуста, скрываем кнопку «Оформить заказ»
            if ( ! $('#basket-modal .modal-body table').length) {
                $('#basket-modal .modal-footer .btn-warning').hide();
            }
        });

        event.preventDefault();
        event.stopImmediatePropagation();
        return false;
    });

    /*
     * Удаление всех товаров из корзины в модальном окне
     */
    $('#basket-modal .modal-body').on('click', 'p a.remove-all', function (event) {
        var href = $(this).attr('href');
        $('#basket-modal .modal-body').load(href);
        // корзина пуста, скрываем кнопку «Оформить заказ»
        $('#basket-modal .modal-footer .btn-warning').hide();

        event.preventDefault();
        event.stopImmediatePropagation();
        return false;
    });

    $('#basket-content').on('click', 'table a.remove-product', function (event) {
        var href = $(this).attr('href');
        $('#basket-content').load(href, function () {
            // если корзина пуста, скрываем кнопку «Оформить заказ»
            if ( ! $(this).find('table').length) {
                $('#basket-content').next('.btn-warning').hide();
            }
        });
        event.preventDefault();
    });

    /*
     * Удаление всех товаров из корзины на странице корзины
     */
    $('#basket-content').on('click', 'p a.remove-all', function (event) {
        var href = $(this).attr('href');
        $('#basket-content').load(href);
        // корзина пуста, скрываем кнопку «Оформить заказ»
        $('#basket-content').next('.btn-warning').hide();
        event.preventDefault();
    });


    /*
     * Обновление содержимого корзины в модальном окне
     */
    $('#basket-modal').on('submit', 'form', function (event) {
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        if (method == undefined) {
            method = 'get';
        }
        var data = $(this).serialize();
        $.ajax({
            url: action,
            type: method,
            data: data,
            dataType: 'html',
            success: function (response) {
                $('#basket-modal .modal-body').html(response);
                // если корзина пуста, скрываем кнопку «Оформить заказ»
                if ( ! $('#basket-modal .modal-body table').length) {
                    $('#basket-modal .modal-footer .btn-warning').hide();
                }
            },
            error: function () {
                alert('Произошла ошибка при обновлении корзины');
            }
        });

        event.preventDefault();
        event.stopImmediatePropagation();
        return false;
    });


    /*
     * Обновление содержимого корзины на странице корзины
     */
    $('#basket-content').on('submit', 'form', function (event) {
        var action = $(this).attr('action');
        var method = $(this).attr('method');
        if (method == undefined) {
            method = 'get';
        }
        var data = $(this).serialize();
        $.ajax({
            url: action,
            type: method,
            data: data,
            dataType: 'html',
            success: function (response) {
                $('#basket-content').html(response);
                // если корзина пуста, скрываем кнопку «Оформить заказ»
                if ( ! $('#basket-content table').length) {
                    $('#basket-content').next('.btn-warning').hide();
                }
            },
            error: function () {
                alert('Произошла ошибка при обновлении корзины');
            }
        });
        event.preventDefault();
    });

});
