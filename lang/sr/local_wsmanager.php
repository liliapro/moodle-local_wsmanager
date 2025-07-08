<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Strings for component 'local_wsmanager', language 'sr'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Менаџер веб сервиса';

$string['dashboard'] = 'Контролна табла';
$string['protocols'] = 'Протоколи';
$string['protocol'] = 'Протокол';
$string['services'] = 'Сервиси';
$string['recommended'] = 'Препоручено';
$string['request'] = 'Захтев';
$string['fixit'] = 'Поправи';
$string['testing'] = 'Тестирање';
$string['test'] = 'Тест';
$string['webservice_test'] = 'Тест веб сервиса';
$string['function_test'] = 'Тест функције';
$string['webservice_test_create'] = 'Направи тест веб сервис';
$string['webservice_test_delete'] = 'Обриши тест веб сервис';
$string['response_format'] = 'Формат одговора';
$string['updated'] = 'Ажурирано';

$string['webservices_disable'] = 'Онемогући веб сервисе';
$string['webservices_protocol_disable'] = 'Онемогући протокол веб сервиса';

$string['errorwsdisabled'] = 'Веб сервиси су онемогућени';
$string['errorwsdisabled_help'] = 'Веб сервиси морају бити омогућени';

$string['restprotocol'] = 'REST протокол';
$string['restprotocol_help'] =
    '<strong>Moodle REST сервер прихвата GET/POST параметре и враћа <code>JSON/XML</code> вредности. Овај сервер није RESTful.</strong><br />REST (Representational State Transfer) је архитектурални стил за софтверску архитектуру дистрибуираних хипермедијских система као што је World Wide Web. REST дефинише скуп ограничења која дефинишу како архитектура великих дистрибуираних хипермедијских система као што је веб треба да функционише.';
$string['soapprotocol'] = 'SOAP протокол';
$string['soapprotocol_help'] =
    '<strong>Moodle SOAP сервер је заснован на Zend SOAP серверу (који је сам заснован на PHP SOAP серверу). Zend је објавио <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">Zend SOAP клијент</a>. Враћа <code>XML</code> вредности.</strong><br />SOAP (изворно скраћеница за Simple Object Access Protocol) је спецификација протокола за размену поруке за размену структурираних информација у имплементацији веб сервиса. Користи XML Information Set за формат поруке и ослања се на протоколе апликационог слоја, најчешће Hypertext Transfer Protocol (HTTP).';
$string['xmlrpcprotocol'] = 'XML-RPC протокол';
$string['xmlrpcprotocol_help'] =
    '<strong>Moodle XML-RPC сервер обрађује удаљене позиве процедура преко HTTP-а користећи XML за кодирање позива и одговора. Враћа вредности у <code>XML</code> формату</strong><br />XML-RPC (XML Remote Procedure Call) је протокол за удаљене позиве процедура који користи XML за кодирање својих позива и HTTP као транспортни механизам. XML-RPC је дизајниран да буде што једноставнији, истовремено омогућавајући пренос, обраду и враћање сложених структура података.';
$string['webservicesready'] = 'Веб сервиси су спремни';

$string['statusenabled'] = 'Омогућено';
$string['statusdisabled'] = 'Онемогућено';
$string['statuserror'] = 'Онемогућено';

$string['toolong'] = 'Вредност поља је предугачка, мора бити мања од {$a} карактера.';
$string['deletetoken'] = 'Обриши токен';

$string['privacy:metadata'] = 'Менаџер веб сервиса додатак не чува никакве личне податке.';
