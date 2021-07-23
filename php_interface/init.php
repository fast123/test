<?php

use \Bitrix\Main\EventManager;

\Bitrix\Main\Loader::registerAutoLoadClasses(
    null,
    [
        '\LibSite\Event\Iblock' => '/local/php_interface/LibSite/Event/Iblock.php'
    ]
);

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementUpdate', ['\LibSite\Event\Iblock', 'OnBeforeIBlockElementUpdateHandler']);
$eventManager->addEventHandler('iblock', 'OnBeforeIBlockElementDelete', ['\LibSite\Event\Iblock', 'OnBeforeIBlockElementDeleteHandler']);