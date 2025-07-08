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
 * Strings for component 'local_wsmanager', language 'pl'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Menedżer usług sieciowych';

$string['dashboard'] = 'Pulpit nawigacyjny';
$string['protocols'] = 'Protokoły';
$string['protocol'] = 'Protokół';
$string['services'] = 'Usługi';
$string['recommended'] = 'Zalecane';
$string['request'] = 'Żądanie';
$string['fixit'] = 'Napraw to';
$string['testing'] = 'Testowanie';
$string['test'] = 'Test';
$string['webservice_test'] = 'Testuj usługę sieciową';
$string['function_test'] = 'Testuj funkcję';
$string['webservice_test_create'] = 'Utwórz testową usługę sieciową';
$string['webservice_test_delete'] = 'Usuń testową usługę sieciową';
$string['response_format'] = 'Format odpowiedzi';
$string['updated'] = 'Zaktualizowano';

$string['webservices_disable'] = 'Wyłącz usługi sieciowe';
$string['webservices_protocol_disable'] = 'Wyłącz protokół usługi sieciowej';

$string['errorwsdisabled'] = 'Usługi sieciowe wyłączone';
$string['errorwsdisabled_help'] = 'Usługi sieciowe muszą być włączone';

$string['restprotocol'] = 'Protokół REST';
$string['restprotocol_help'] =
    '<strong>Serwer Moodle REST akceptuje parametry GET/POST i zwraca wartości <code>JSON/XML</code>. Ten serwer nie jest w pełni RESTful.</strong><br />REST (Representational State Transfer) to styl architektoniczny oprogramowania, który został stworzony w celu ukierunkowania projektowania i rozwoju architektury dla World Wide Web. REST definiuje zestaw ograniczeń dotyczących zachowania architektury rozproszonego systemu hipermedialnego na skalę internetową, takiego jak sieć WWW.';
$string['soapprotocol'] = 'Protokół SOAP';
$string['soapprotocol_help'] =
    '<strong>Serwer SOAP Moodle opiera się na serwerze Zend SOAP (który sam opiera się na serwerze PHP SOAP). Zend publikuje <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">klienta Zend SOAP</a>. Zwraca wartości <code>XML</code></strong><br />SOAP (dawniej skrót od Simple Object Access Protocol) to specyfikacja protokołu przesyłania wiadomości służąca do wymiany ustrukturyzowanych informacji w implementacji usług sieciowych. Wykorzystuje on zestaw informacji XML do formatu wiadomości i opiera się na protokołach warstwy aplikacji, najczęściej na protokole Hypertext Transfer Protocol (HTTP).';
$string['xmlrpcprotocol'] = 'Protokół XML-RPC';
$string['xmlrpcprotocol_help'] =
    '<strong>Serwer XML-RPC Moodle obsługuje zdalne wywołania procedur przez HTTP, używając XML do kodowania wywołań i odpowiedzi. Zwraca wartości w formacie <code>XML</code></strong><br />XML-RPC (XML Remote Procedure Call) to protokół zdalnego wywołania procedur, który używa XML do kodowania swoich wywołań i HTTP jako mechanizmu transportu. XML-RPC został zaprojektowany tak, aby był jak najprostszy, jednocześnie umożliwiając przesyłanie, przetwarzanie i zwracanie złożonych struktur danych.';
$string['webservicesready'] = 'Usługi sieciowe są gotowe do użycia';

$string['statusenabled'] = 'Włączone';
$string['statusdisabled'] = 'Wyłączone';
$string['statuserror'] = 'Wyłączone';

$string['toolong'] = 'Wartość pola jest zbyt długa; powinna zawierać {$a} liter lub mniej.';
$string['deletetoken'] = 'Usuń token';

$string['privacy:metadata'] = 'Wtyczka Menedżer usług sieciowych nie przechowuje żadnych danych osobowych.';
