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
 * Strings for component 'local_wsmanager', language 'no'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Webtjenestebehandler';

$string['dashboard'] = 'Kontrollpanel';
$string['protocols'] = 'Protokoller';
$string['protocol'] = 'Protokoll';
$string['services'] = 'Tjenester';
$string['recommended'] = 'Anbefalt';
$string['request'] = 'Forespørsel';
$string['fixit'] = 'Fiks det';
$string['testing'] = 'Testing';
$string['test'] = 'Test';
$string['webservice_test'] = 'Webtjenestetest';
$string['function_test'] = 'Funksjonstest';
$string['webservice_test_create'] = 'Opprett webtjenestetest';
$string['webservice_test_delete'] = 'Slett webtjenestetest';
$string['response_format'] = 'Responsformat';
$string['updated'] = 'Oppdatert';

$string['webservices_disable'] = 'Deaktiver webtjenester';
$string['webservices_protocol_disable'] = 'Deaktiver webtjenesteprotokoll';

$string['errorwsdisabled'] = 'Webtjenester er deaktivert';
$string['errorwsdisabled_help'] = 'Webtjenester må være aktivert';

$string['restprotocol'] = 'REST-protokoll';
$string['restprotocol_help'] =
    '<strong>Moodle REST-server aksepterer GET/POST-parametere og returnerer <code>JSON/XML</code>-verdier. Denne serveren er ikke RESTful.</strong><br />REST (Representational State Transfer) er en arkitektonisk stil for programvare for distribuerte hypermediasystemer som World Wide Web. REST definerer et sett med begrensninger som definerer hvordan arkitekturer for store distribuerte hypermediasystemer, som nettet, bør fungere.';
$string['soapprotocol'] = 'SOAP-protokoll';
$string['soapprotocol_help'] =
    '<strong>Moodle SOAP-server er basert på Zend SOAP-server (som igjen er basert på PHP SOAP-server). Zend har publisert <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">Zend SOAP-klient</a>. Returnerer <code>XML</code>-verdier.</strong><br />SOAP (opprinnelig forkortelse for Simple Object Access Protocol) er en meldingsprotokollspesifikasjon for utveksling av strukturert informasjon i implementering av webtjenester. Den bruker XML Information Set for meldingsformatet og er avhengig av applikasjonslags-protokoller, oftest Hypertext Transfer Protocol (HTTP).';
$string['xmlrpcprotocol'] = 'XML-RPC-protokoll';
$string['xmlrpcprotocol_help'] =
    '<strong>Moodle XML-RPC-server håndterer eksterne prosedyrekall over HTTP ved å bruke XML til å kode kall og svar. Returnerer verdier i <code>XML</code>-format</strong><br />XML-RPC (XML Remote Procedure Call) er en ekstern prosedyrekall-protokoll som bruker XML til å kode sine kall og HTTP som transportmekanisme. XML-RPC er designet for å være så enkel som mulig, samtidig som den tillater komplekse datastrukturer å bli overført, behandlet og returnert.';
$string['webservicesready'] = 'Webtjenester klare';

$string['statusenabled'] = 'Aktivert';
$string['statusdisabled'] = 'Deaktivert';
$string['statuserror'] = 'Deaktivert';

$string['toolong'] = 'Feltverdi er for lang, må være mindre enn {$a} tegn.';
$string['deletetoken'] = 'Slett token';

$string['privacy:metadata'] = 'Webtjenestebehandler-tillegget lagrer ikke personlige data.';