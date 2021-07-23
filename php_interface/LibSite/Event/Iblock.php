<?php


namespace LibSite\Event;

use Bitrix\Main\Mail\Event;

class Iblock
{
    //первое задание
    function OnBeforeIBlockElementUpdateHandler(&$arFields)
    {
        $iblockId = 2;
        if($arFields["IBLOCK_ID"]==$iblockId)
        {
            $res = \CIBlockElement::GetList(
                [],
                [
                    'IBLOCK_ID'=>$arFields['IBLOCK_ID'],
                    'ID'=>$arFields['ID']
                ],
                false,
                false,
                ['IBLOCK_ID', 'ID', 'DATE_CREATE']
            );
            if($obj = $res->GetNext(true, false)){
                $dateCreate = $obj['DATE_CREATE'];
            }
            if(!empty($dateCreate)){
                $dateCreate = MakeTimeStamp($dateCreate, \CSite::GetDateFormat("FULL"));
                $curentDate = time();
                $seconds = abs($curentDate - $dateCreate);
                $day = floor($seconds / 86400);
            }
            if($day<7){
                global $APPLICATION;
                $APPLICATION->throwException("Товар ". $arFields['NAME'] ." был создан менееодной недели назад и не может быть изменен.");
                return false;
            }
        }
    }

    //второе задание
    function OnBeforeIBlockElementDeleteHandler($ID)
    {
        $iblockId = 2;
        $EVENT_NAME = 'test_emaiil_message';
        $res = \CIBlockElement::GetByID($ID);
        if($obj = $res->GetNext(true, false)){
            $iblockIdCur = $obj['IBLOCK_ID'];
            $showCounter = $obj['SHOW_COUNTER'];
            $arItem = $obj;
        }
        if($iblockId == $iblockIdCur && $showCounter>10000){
            global $APPLICATION;
            global $USER;
            $APPLICATION->throwException("Нельзя удалить данный товар, так как он очень популярный на сайте");
            $ev = new Event;
            $arPost = [
                'LOGIN'=>$USER->GetLogin(),
                'ID_USER'=>$USER->GetID(),
                'NAME_PROD'=>$arItem['NAME'],
                'SHOW_COUNTER'=>$showCounter
            ];
            $idEv = $ev::send(
                array(
                    "EVENT_NAME" => $EVENT_NAME,
                    "LID" => "s1",
                    "C_FIELDS" => $arPost,
                )
            );

            return false;
        }
    }

}