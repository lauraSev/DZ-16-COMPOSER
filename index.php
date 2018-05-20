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
    //print_r($response);
    $min_shirota = 1000;
    $min_dolgota = 1000;
    $max_shirota = 0;
    $max_dolgota = 0;
    foreach ($collection as $item) {
        $shirota = $item->getLatitude(); // широта
        $dolgota = $item->getLongitude(); // долгота
        if($shirota>$max_shirota)$max_shirota=$shirota;
        if($dolgota>$max_dolgota)$max_dolgota=$dolgota;

        if($shirota<$min_shirota)$min_shirota=$shirota;
        if($dolgota<$min_dolgota)$min_dolgota=$dolgota;
    }
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
                myMap.setBounds([[<?=$min_shirota?>,<?=$min_dolgota?>], [<?=$max_shirota?>,<?=$max_dolgota?>]]);
                <?php
                foreach ($collection as $item) {
                    $adres = $item->getAddress(); // вернет адрес
                    $shirota = $item->getLatitude(); // широта
                    $dolgota = $item->getLongitude(); // долгота
                    ?>
                    myMap.geoObjects.add(new ymaps.Placemark([<?=$shirota?>, <?=$dolgota?>], {
                        hintContent: '<?=$adres?>',
                        balloonContent: '<?=$adres?>'
                    }));
                    <?php
                }
                ?>
            }
        </script>
    <?php
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
?>
<form action="index.php" method="post">
<input type="text" name="adres" placeholder="Введите адрес"><br>
<input type="submit" name="search" value="Найти">
</form>
</body>