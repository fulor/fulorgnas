</div><!-- #main --><!---->

<!-- footer -->
<footer class="footer" id="contacts">
    <div class="container-wrapper">
        <div id="map">
            <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A6118c12a88bd0c1054a22905bc356260fc1ea15f43141e8acd5231b901f219f3&amp;width=100%25&amp;height=360&amp;lang=ru_RU&amp;scroll=false"></script>
        </div>
    <div class="contacts">
            <img src="<?= get_template_directory_uri() ?>/inc/img/logo_foot.png" alt="">
            <div class="contact-title">Контакты</div>
            <div class="phone-list">
                <a href="tel://+375445533900" class="phone">+375 44 55 33 900</a>
            </div>
            <div class="time-work">09:00 - 18:00</div>
            <div class="address">г. Гомель, Кирова 35</div>
        </div>
        <a href="https://askerweb.by/" class="copyring" target="_blank" rel="noopener">
            <img src="<?= get_template_directory_uri() ?>/inc/img/copyring.png" alt="">
            Разработка и создание сайтов в Гомеле, Минске, Бресте, Гродно, Могилеве, Витебске. Продвижение и поддержка.
        </a>
    </div>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter49816948 = new Ya.Metrika2({
                        id:49816948,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/tag.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks2");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/49816948" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-96153707-15"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-96153707-15');
    </script>

    <script src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script>
    <script src="//yastatic.net/share2/share.js"></script>
    <div class="ya-share2" style="position:fixed; top:25%; z-index: 999999"
         data-services="vkontakte,facebook,odnoklassniki,twitter,viber,skype,telegram" data-direction="vertical"
         data-limit="8"></div>
    <style>
        .ya-share2__title {
            display: none;
        }

    </style>

    <script data-skip-moving="true">
        (function(w,d,u){
            var s=d.createElement('script');s.async=1;s.src=u+'?'+(Date.now()/60000|0);
            var h=d.getElementsByTagName('script')[0];h.parentNode.insertBefore(s,h);
        })(window,document,'https://cdn.bitrix24.by/b7713491/crm/site_button/loader_1_8hs8i5.js');
    </script>

</footer>
<?php wp_footer(); ?>
</body>
</html>
