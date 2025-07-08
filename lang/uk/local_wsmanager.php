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
 * Strings for component 'local_wsmanager', language 'uk'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Менеджер веб-сервісів';

$string['dashboard'] = 'Панель керування';
$string['protocols'] = 'Протоколи';
$string['protocol'] = 'Протокол';
$string['services'] = 'Сервіси';
$string['recommended'] = 'Рекомендовано';
$string['request'] = 'Запит';
$string['fixit'] = 'Виправити';
$string['testing'] = 'Тестування';
$string['test'] = 'Тест';
$string['webservice_test'] = 'Тест веб-сервісу';
$string['function_test'] = 'Тест функції';
$string['webservice_test_create'] = 'Створити тест веб-сервісу';
$string['webservice_test_delete'] = 'Видалити тест веб-сервісу';
$string['response_format'] = 'Формат відповіді';
$string['updated'] = 'Оновлено';

$string['webservices_disable'] = 'Вимкнути веб-сервіси';
$string['webservices_protocol_disable'] = 'Вимкнути протокол веб-сервісів';

$string['errorwsdisabled'] = 'Веб-сервіси вимкнено';
$string['errorwsdisabled_help'] = 'Веб-сервіси мають бути увімкнені';

$string['restprotocol'] = 'Протокол REST';
$string['restprotocol_help'] =
    '<strong>Сервер Moodle REST приймає параметри GET/POST і повертає значення <code>JSON/XML</code>. Цей сервер не є RESTful.</strong><br />REST (Representational State Transfer) — це архітектурний стиль програмного забезпечення для розподілених гіпермедіа систем, таких як World Wide Web. REST визначає набір обмежень, які визначають, як повинні працювати архітектури великих розподілених гіпермедіа систем, таких як веб.';
$string['soapprotocol'] = 'Протокол SOAP';
$string['soapprotocol_help'] =
    '<strong>Сервер Moodle SOAP базується на сервері Zend SOAP (який, у свою чергу, базується на сервері PHP SOAP). Zend опублікував <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">клієнт Zend SOAP</a>. Повертає значення <code>XML</code>.</strong><br />SOAP (спочатку абревіатура від Simple Object Access Protocol) — це специфікація протоколу обміну повідомленнями для обміну структурованою інформацією при реалізації веб-сервісів. Використовує XML Information Set для формату повідомлень і покладається на протоколи прикладного рівня, найчастіше Hypertext Transfer Protocol (HTTP).';
$string['xmlrpcprotocol'] = 'XML-RPC протокол';
$string['xmlrpcprotocol_help'] =
    '<strong>XML-RPC сервер Moodle обробляє віддалені виклики процедур через HTTP, використовуючи XML для кодування викликів та відповідей. Повертає значення у форматі <code>XML</code></strong><br />XML-RPC (XML Remote Procedure Call) — це протокол віддаленого виклику процедур, який використовує XML для кодування своїх викликів та HTTP як транспортний механізм. XML-RPC розроблений якомога простішим, водночас дозволяючи передавати, обробляти та повертати складні структури даних.';
$string['webservicesready'] = 'Веб-сервіси готові';

$string['statusenabled'] = 'Увімкнено';
$string['statusdisabled'] = 'Вимкнено';
$string['statuserror'] = 'Вимкнено';

$string['toolong'] = 'Значення поля занадто довге, має бути менше ніж {$a} символів.';
$string['deletetoken'] = 'Видалити токен';

$string['privacy:metadata'] = 'Додаток Менеджер веб-сервісів не зберігає персональні дані.';