<body>
<?php
echo '<pre>';
require __DIR__ . '/vendor/autoload.php';
if ($_POST ['search']) {
    $api = new \Yandex\Geo\Api();
    $api->setQuery($_POST ['adres']);
    $api->setLang(\Yandex\Geo\Api::LANG_RU)->load();
    $response = $api->getResponse();
    $collection = $response->getList();
    echo '<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript">
    </script>';
    ?>
        <div id="map" style="width: 700px; height: 500px"></div>
        <script type="text/javascript">
            ymaps.ready(init);
            var myMap;
            function init(){
                myMap = new ymaps.Map("map", {
                    center: [55.76, 37.64],
                    zoom: 2
                });
                <?
                foreach ($collection as $item) {
                    $adres = $item->getAddress(); // вернет адрес
                    $shirota = $item->getLatitude(); // широта
                    $dolgota = $item->getLongitude(); // долгота
                    ?>
                    myMap.geoObjects.add(new ymaps.Placemark([<?=$shirota?>, <?=$dolgota?>], {
                        hintContent: '<?=$adres?>',
                        balloonContent: '<?=$adres?>'
                    }));
                    <?
                }
                ?>
            }
        </script>
    <?
    foreach ($collection as $item) {
        $adres = $item->getAddress(); // вернет адрес
        $shirota = $item->getLatitude(); // широта
        $dolgota = $item->getLongitude(); // долгота
        $item->getData(); // необработанные данные

        echo '<h3>' . $adres . '</h3>';
        echo 'Долгота: '.$dolgota;
        echo '&nbsp;&nbsp;';
        echo 'Широта: '.$shirota;
        echo '<br><br>';
    }
};
echo '<form action="index.php" method="post">';
echo '<input type="text" name="adres" placeholder="Введите адрес">&nbsp;';
echo '<input type="submit" name="search" value="Найти"> ';
echo '</form>';

?>
</body>
