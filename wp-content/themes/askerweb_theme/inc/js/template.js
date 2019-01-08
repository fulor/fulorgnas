(function ($) {
    var App = {
        createSlider: function () {
            $('.class_wrapper_slider').slick({
                autoplay: true,
                autoplaySpeed: 4000,
                speed: 1500,
                arrow: false,
            });

        },

        switchCategoryCatalog: function(){
            //реализация табов на сайте
            $('.tabs-category').on('click', 'div:not(.active)', function() {
                $(this)
                    .addClass('active').siblings().removeClass('active')
                    .closest('.catalog').find('div.products-list').removeClass('active').eq($(this).index()).addClass('active');
            });
        },
        showCharacterInModelCatalog: function(){
            //показ модального окна
            var showForm= function () {                                                    //функция на появление всплывающией формы
                var thisEl = $('#name_form');
                $('.modal-wrapper').addClass('open');
                thisEl.css({'opacity':'1', 'visibility':'visible'});
                thisEl.addClass('open');
            }
            $('.close__modal, .modal-wrapper').click(function (){                           //событие на закрытие формы
                $('.hidden-character').css({'opacity':'0', 'visibility':'hidden'});
                $('.hidden-character,.modal-block').removeClass('open');
            });
            $('.class_button').click(showForm);//показать форму
        },

        showForms: function(){
            var title,nameCategory,nameModel

            var closeModal = function () {
                $('.hidden-character,.modal-wrapper').removeClass('open');
                window.setTimeout(function () {
                    $('.modal-block').show().css({"visibility": "hidden"});
                }, 500);
            }
            $(document).on('click', function (event) {                                      //Закрытие формы подложке при клике на подложку
                if ($('.modal-wrapper').hasClass('open')) {
                    var item = $(event.target);
                    if (item.closest('.modal-wrapper').length != 0 && item.closest('.modal-block').length == 0) {
                        closeModal();
                    }
                }
            })

            $('.close__modal').click(closeModal);                                         //Закрытие формы при клике на крестик

        },
        sendForms: function(){

            var showThank = function () {
                window.setTimeout(function () {
                    $('#thank').show().css({"visibility": "visible"});
                    $('.modal-wrapper').addClass('open');
                }, 1500);
                window.setTimeout(function () {
                    $('#thank').show().css({"visibility": "hidden"});
                    $('.modal-wrapper').removeClass('open ');
                }, 6000);
            }
            var sendPost = function () {
                var $form = $(this);
                var dataForm = $form.serialize();
                $.ajax({
                    type: "POST",
                    url: "/wp-admin/admin-ajax.php?action=sendPost",
                    data: dataForm,
                    beforeSend: function (data) {
                        $form.find('button[type="submit"]').prop("disabled", true);
                    },
                    success: function (data) {
                        if (data.status == 'success') {
                            $form.trigger('reset');
                            $form.find('input[type=hidden]').val('');
                            closeModal();
                            showThank();
                        } else {
                            alert('Произошла ошибка обработки запроса');
                        }

                    },
                    error: function () {
                        alert('Произошла ошибка соединения');
                    },
                    complete: function (data) {
                        $form.find('button[type="submit"]').prop("disabled", false);
                    },
                });
                return false;
            }
            $('#popup__form .modal-form').submit(sendPost);
        },
        showMobileMenu: function() {
            $('.mobile-menu').click(function () {
                $(this).toggleClass('active');
                $('.primary-menu').slideToggle();
            })
        },
        scrollTo:function() {
            $('.menu-item-5').click(function () {
                $.scrollTo('.about', {offset: -100, duration: 750});
            })
            $('.menu-item-6').click(function () {
                $.scrollTo('.catalog', {offset: -100, duration: 750});
            })
            $('.menu-item-7').click(function () {
                $.scrollTo('.stock', {offset: -100, duration: 750});
            })
            $('.menu-item-8').click(function () {
                $.scrollTo('.service', {offset: -100, duration: 750});
            })
            $('.menu-item-9').click(function () {
                $.scrollTo('#contacts', {offset: -100, duration: 750});
            })
        },
        init: function () {
            this.createSlider();
            this.switchCategoryCatalog();
            this.showCharacterInModelCatalog();
            this.showForms();
            this.sendForms();
            this.showMobileMenu();
            this.scrollTo();
        }
    }
    $(document).ready(function () {
        App.init();
    })


})(jQuery)
