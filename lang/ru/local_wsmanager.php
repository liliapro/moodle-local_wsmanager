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
 * Strings for component 'local_wsmanager', language 'ru'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Менеджер Веб-служб';

$string['dashboard'] = 'Панель управления';
$string['protocols'] = 'Протоколы';
$string['protocol'] = 'Протокол';
$string['services'] = 'Службы';
$string['recommended'] = 'Рекомендуется';
$string['request'] = 'Запрос';
$string['fixit'] = 'Исправить';
$string['testing'] = 'Тестирование';
$string['test'] = 'Тест';
$string['webservice_test'] = 'Тестовый вебсервис';
$string['function_test'] = 'Тестовая функция';
$string['webservice_test_create'] = 'Создать тестовую Веб-Службу';
$string['webservice_test_delete'] = 'Удалить тестовую Веб-Службу';
$string['response_format'] = 'Формат ответа';
$string['updated'] = 'Обновлено';

$string['webservices_disable'] = 'Отключить веб-службы';
$string['webservices_protocol_disable'] = 'Отключить протокол веб-служб';

$string['errorwsdisabled'] = 'Веб-службы отключены';
$string['errorwsdisabled_help'] = 'Веб-службы должны быть включены';

$string['restprotocol'] = 'REST протокол';
$string['restprotocol_help'] =
    '<strong>Сервер Moodle REST принимает запросы GET/POST и возвращает значения в форматах <code>JSON/XML</code>. Не является RESTfull.</strong><br />REST (Representational state transfer) — это архитектурный стиль программного обеспечения, созданный для управления проектированием и разработкой архитектуры Всемирной паутины. REST определяет набор ограничений того, как должна вести себя архитектура распределенной гипермедийной системы масштаба Интернета.';
$string['soapprotocol'] = 'SOAP протокол';
$string['soapprotocol_help'] =
    '<strong>SOAP-сервер Moodle основан на SOAP-сервере Zend (который сам основан на SOAP-сервере PHP). Zend также поддерживает <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">клиент Zend SOAP</a>. Возвращает значения <code>XML</code></strong><br />SOAP (ранее аббревиатура от Simple Object Access Protocol) — это спецификация протокола обмена сообщениями для обмена структурированной информацией при реализации веб-служб. Он использует набор информации XML в качестве формата сообщения и полагается на протоколы прикладного уровня, чаще всего протокол передачи гипертекста (HTTP).';
$string['xmlrpcprotocol'] = 'XML-RPC протокол';
$string['xmlrpcprotocol_help'] =
    '<strong>XML-RPC сервер Moodle обрабатывает удаленные вызовы процедур через HTTP с использованием XML для кодирования вызовов и ответов. Возвращает значения в формате <code>XML</code></strong><br />XML-RPC (XML Remote Procedure Call) — это протокол удаленного вызова процедур, который использует XML для кодирования своих вызовов и HTTP в качестве транспортного механизма. XML-RPC разработан как можно проще, позволяя передавать, обрабатывать и возвращать сложные структуры данных.';
$string['webservicesready'] = 'Веб-службы готовы к работе';

$string['statusenabled'] = 'Включено';
$string['statusdisabled'] = 'Выключено';
$string['statuserror'] = 'Выключено';

$string['toolong'] = 'Значение поля слишком длинное; оно должно содержать не более {$a} символов.';
$string['deletetoken'] = 'Удалить токен';

$string['privacy:metadata'] = 'Плагин инструмента администрирования API Менеджер не хранит никаких персональных данных.';

$string['wsmanager:manage'] = 'Управление веб-сервисами';
$string['wsmanager:manage_desc'] =
    'Разрешить пользователю управлять конфигурацией веб-сервисов, включать/отключать протоколы и настраивать параметры веб-сервисов.';

$string['enabled'] = 'Включено';
$string['disabled'] = 'Отключено';
$string['opensinnewwindow'] = 'Открывается в новом окне';
$string['mobileapp'] = 'Мобильное приложение';
$string['displayerrorswarning_mobileapp'] =
    'Отображение ошибок включено. Это не рекомендуется для продакшн-сайтов, так как сообщения об ошибках могут раскрыть конфиденциальную информацию о вашем сайте.';