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
 * Strings for component 'local_wsmanager', language 'sv'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Webbtjänsthanterare';

$string['dashboard'] = 'Kontrollpanel';
$string['protocols'] = 'Protokoll';
$string['protocol'] = 'Protokoll';
$string['services'] = 'Tjänster';
$string['recommended'] = 'Rekommenderad';
$string['request'] = 'Förfrågan';
$string['fixit'] = 'Fixa det';
$string['testing'] = 'Testning';
$string['test'] = 'Test';
$string['webservice_test'] = 'Webbtjänsttest';
$string['function_test'] = 'Funktionstest';
$string['webservice_test_create'] = 'Skapa webbtjänsttest';
$string['webservice_test_delete'] = 'Ta bort webbtjänsttest';
$string['response_format'] = 'Svarsformat';
$string['updated'] = 'Uppdaterad';

$string['webservices_disable'] = 'Inaktivera webbtjänster';
$string['webservices_protocol_disable'] = 'Inaktivera webbtjänstprotokoll';

$string['errorwsdisabled'] = 'Webbtjänster är inaktiverade';
$string['errorwsdisabled_help'] = 'Webbtjänster måste vara aktiverade';

$string['restprotocol'] = 'REST-protokoll';
$string['restprotocol_help'] =
    '<strong>Moodle REST-server accepterar GET/POST-parametrar och returnerar <code>JSON/XML</code>-värden. Denna server är inte RESTful.</strong><br />REST (Representational State Transfer) är en arkitektonisk stil för mjukvara för distribuerade hypermediasystem som World Wide Web. REST definierar en uppsättning begränsningar som definierar hur arkitekturer för stora distribuerade hypermediasystem, som webben, bör fungera.';
$string['soapprotocol'] = 'SOAP-protokoll';
$string['soapprotocol_help'] =
    '<strong>Moodle SOAP-server baseras på Zend SOAP-server (som i sin tur baseras på PHP SOAP-server). Zend har publicerat <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">Zend SOAP-klient</a>. Returnerar <code>XML</code>-värden.</strong><br />SOAP (ursprungligen förkortning för Simple Object Access Protocol) är en meddelandeprotokollspecifikation för utbyte av strukturerad information i implementering av webbtjänster. Den använder XML Information Set för meddelandeformatet och förlitar sig på applikationslagerprotokoll, oftast Hypertext Transfer Protocol (HTTP).';
$string['xmlrpcprotocol'] = 'XML-RPC-protokoll';
$string['xmlrpcprotocol_help'] =
    '<strong>Moodle XML-RPC-server hanterar fjärrprocedursamtal över HTTP med XML för att koda samtal och svar. Returnerar värden i <code>XML</code>-format</strong><br />XML-RPC (XML Remote Procedure Call) är ett fjärrprocedursamtalsprotokoll som använder XML för att koda sina samtal och HTTP som transportmekanism. XML-RPC är designat att vara så enkelt som möjligt, samtidigt som det tillåter komplexa datastrukturer att överföras, bearbetas och returneras.';
$string['webservicesready'] = 'Webbtjänster redo';

$string['statusenabled'] = 'Aktiverad';
$string['statusdisabled'] = 'Inaktiverad';
$string['statuserror'] = 'Inaktiverad';

$string['toolong'] = 'Fältvärde är för långt, måste vara mindre än {$a} tecken.';
$string['deletetoken'] = 'Ta bort token';

$string['privacy:metadata'] = 'Webbtjänsthanterare-tillägget lagrar inga personuppgifter.';