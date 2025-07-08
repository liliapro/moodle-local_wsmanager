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
 * Strings for component 'local_wsmanager', language 'hu'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Webszolgáltatás-kezelő';

$string['dashboard'] = 'Irányítópult';
$string['protocols'] = 'Protokollok';
$string['protocol'] = 'Protokoll';
$string['services'] = 'Szolgáltatások';
$string['recommended'] = 'Ajánlott';
$string['request'] = 'Kérés';
$string['fixit'] = 'Javítás';
$string['testing'] = 'Tesztelés';
$string['test'] = 'Teszt';
$string['webservice_test'] = 'Webszolgáltatás tesztelése';
$string['function_test'] = 'Függvény tesztelése';
$string['webservice_test_create'] = 'Teszwebszolgáltatás létrehozása';
$string['webservice_test_delete'] = 'Teszwebszolgáltatás törlése';
$string['response_format'] = 'Válasz formátuma';
$string['updated'] = 'Frissítve';

$string['webservices_disable'] = 'Webszolgáltatások letiltása';
$string['webservices_protocol_disable'] = 'Webszolgáltatási protokoll letiltása';

$string['errorwsdisabled'] = 'A webszolgáltatások le vannak tiltva';
$string['errorwsdisabled_help'] = 'A webszolgáltatásokat engedélyezni kell';

$string['restprotocol'] = 'REST protokoll';
$string['restprotocol_help'] =
    '<strong>A Moodle REST szerver GET/POST paramétereket fogad és `JSON/XML` értékeket ad vissza. Ez a szerver nem RESTfull.</strong><br />A REST (Representational state transfer) egy szoftverarchitektúra-stílus, amelyet a világháló architektúrájának tervezéséhez és fejlesztéséhez hoztak létre. A REST meghatározza azokat a korlátokat, amelyek szerint egy internetes méretű, elosztott hipermédia-rendszernek, például a webnek, viselkednie kell.';
$string['soapprotocol'] = 'SOAP protokoll';
$string['soapprotocol_help'] =
    '<strong>A Moodle SOAP szerver a Zend SOAP szerveren alapul (amely maga is a PHP SOAP szerveren alapul). A Zend közzétesz egy <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">Zend SOAP klienst</a>. `XML` értékeket ad vissza.</strong><br />A SOAP (korábban a Simple Object Access Protocol rövidítése) egy üzenetküldési protokoll specifikáció a strukturált információk cseréjére a webszolgáltatások megvalósítása során. Üzenetformátumához az XML Information Set-et használja, és alkalmazási réteg protokollokra, leggyakrabban a Hypertext Transfer Protocol-ra (HTTP) támaszkodik.';
$string['xmlrpcprotocol'] = 'XML-RPC protokoll';
$string['xmlrpcprotocol_help'] =
    '<strong>A Moodle XML-RPC szerver távoli eljáráshívásokat kezel HTTP-n keresztül, XML-t használva a hívások és válaszok kódolásához. <code>XML</code> formátumban ad vissza értékeket</strong><br />Az XML-RPC (XML Remote Procedure Call) egy távoli eljáráshívási protokoll, amely XML-t használ a hívások kódolásához és HTTP-t szállítási mechanizmusként. Az XML-RPC-t a lehető legegyszerűbbnek tervezték, miközben lehetővé teszi összetett adatszerkezetek továbbítását, feldolgozását és visszaadását.';
$string['webservicesready'] = 'A webszolgáltatások használatra készek';

$string['statusenabled'] = 'Engedélyezve';
$string['statusdisabled'] = 'Letiltva';
$string['statuserror'] = 'Letiltva';

$string['toolong'] = 'A mező értéke túl hosszú; legfeljebb {$a} betűt tartalmazhat.';
$string['deletetoken'] = 'Token törlése';

$string['privacy:metadata'] = 'A Webszolgáltatás-kezelő bővítmény nem tárol személyes adatokat.';
