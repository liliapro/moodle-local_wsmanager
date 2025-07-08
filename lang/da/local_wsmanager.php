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
 * Strings for component 'local_wsmanager', language 'da'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Håndtering af webtjenester';

$string['dashboard'] = 'Dashboard';
$string['protocols'] = 'Protokoller';
$string['protocol'] = 'Protokol';
$string['services'] = 'Tjenester';
$string['recommended'] = 'Anbefalet';
$string['request'] = 'Anmodning';
$string['fixit'] = 'Ret det';
$string['testing'] = 'Test';
$string['test'] = 'Test';
$string['webservice_test'] = 'Webtjenestetest';
$string['function_test'] = 'Funktionstest';
$string['webservice_test_create'] = 'Opret test-webtjeneste';
$string['webservice_test_delete'] = 'Slet test-webtjeneste';
$string['response_format'] = 'Svarformat';
$string['updated'] = 'Opdateret';

$string['webservices_disable'] = 'Deaktiver webtjenester';
$string['webservices_protocol_disable'] = 'Deaktiver webtjenesteprotokol';

$string['errorwsdisabled'] = 'Webtjenester er deaktiveret';
$string['errorwsdisabled_help'] = 'Webtjenester skal være aktiveret';

$string['restprotocol'] = 'REST-protokol';
$string['restprotocol_help'] =
    '<strong>Moodle REST-serveren accepterer GET/POST-parametre og returnerer <code>JSON/XML</code>-værdier. Denne server er ikke RESTful.</strong><br />REST (Representational State Transfer) er en softwarearkitekturstil, der er skabt til at guide design og udvikling af arkitekturen for World Wide Web. REST definerer et sæt begrænsninger for, hvordan arkitekturen i et distribueret hypermediesystem på internetskala, såsom internettet, skal opføre sig.';
$string['soapprotocol'] = 'SOAP-protokol';
$string['soapprotocol_help'] =
    '<strong>Moodle SOAP-serveren er baseret på Zend SOAP-serveren (som selv er baseret på PHP SOAP-serveren). Zend udgiver en <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">Zend SOAP-klient</a>. Den returnerer <code>XML</code>-værdier.</strong><br />SOAP (oprindeligt et akronym for Simple Object Access Protocol) er en meddelelsesprotokolspecifikation til udveksling af struktureret information i implementeringen af webtjenester. Den bruger XML Information Set til sit meddelelsesformat og er afhængig af applikationslagsprotokoller, oftest Hypertext Transfer Protocol (HTTP).';
$string['xmlrpcprotocol'] = 'XML-RPC-protokol';
$string['xmlrpcprotocol_help'] =
    '<strong>Moodle XML-RPC-server håndterer eksterne procedurekald over HTTP ved hjælp af XML til at kode kald og svar. Returnerer værdier i <code>XML</code>-format</strong><br />XML-RPC (XML Remote Procedure Call) er en ekstern procedurekald-protokol, der bruger XML til at kode sine kald og HTTP som transportmekanisme. XML-RPC er designet til at være så simpel som muligt, samtidig med at den tillader komplekse datastrukturer at blive transmitteret, behandlet og returneret.';
$string['webservicesready'] = 'Webtjenester er klar til brug';

$string['statusenabled'] = 'Aktiveret';
$string['statusdisabled'] = 'Deaktiveret';
$string['statuserror'] = 'Deaktiveret';

$string['toolong'] = 'Feltværdien er for lang; den skal indeholde {$a} tegn eller mindre.';
$string['deletetoken'] = 'Slet token';

$string['privacy:metadata'] = 'Pluginet Håndtering af webtjenester gemmer ingen personlige data.';
