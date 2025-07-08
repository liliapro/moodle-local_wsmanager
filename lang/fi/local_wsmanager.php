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
 * Strings for component 'local_wsmanager', language 'fi'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Verkkopalvelujen hallinta';

$string['dashboard'] = 'Hallintapaneeli';
$string['protocols'] = 'Protokollat';
$string['protocol'] = 'Protokolla';
$string['services'] = 'Palvelut';
$string['recommended'] = 'Suositeltu';
$string['request'] = 'Pyyntö';
$string['fixit'] = 'Korjaa';
$string['testing'] = 'Testaus';
$string['test'] = 'Testi';
$string['webservice_test'] = 'Verkkopalvelun testi';
$string['function_test'] = 'Funktion testi';
$string['webservice_test_create'] = 'Luo verkkopalvelun testi';
$string['webservice_test_delete'] = 'Poista verkkopalvelun testi';
$string['response_format'] = 'Vastauksen muoto';
$string['updated'] = 'Päivitetty';

$string['webservices_disable'] = 'Poista verkkopalvelut käytöstä';
$string['webservices_protocol_disable'] = 'Poista verkkopalvelujen protokolla käytöstä';

$string['errorwsdisabled'] = 'Verkkopalvelut on poistettu käytöstä';
$string['errorwsdisabled_help'] = 'Verkkopalvelujen on oltava käytössä';

$string['restprotocol'] = 'REST-protokolla';
$string['restprotocol_help'] =
    '<strong>Moodle REST -palvelin hyväksyy GET/POST-parametrit ja palauttaa <code>JSON/XML</code>-arvot. Tämä palvelin ei ole RESTful.</strong><br />REST (Representational State Transfer) on ohjelmistoarkkitehtuurin arkkitehtoninen tyyli hajautetuille hypermediajärjestelmille, kuten World Wide Web. REST määrittelee joukon rajoituksia, jotka määrittävät, miten suurten hajautettujen hypermediajärjestelmien arkkitehtuurien, kuten webin, tulisi toimia.';
$string['soapprotocol'] = 'SOAP-protokolla';
$string['soapprotocol_help'] =
    '<strong>Moodle SOAP -palvelin perustuu Zend SOAP -palvelimeen (joka puolestaan perustuu PHP SOAP -palvelimeen). Zend on julkaissut <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">Zend SOAP -asiakkaan</a>. Palauttaa <code>XML</code>-arvot.</strong><br />SOAP (alun perin lyhenne sanoista Simple Object Access Protocol) on viestiprotokolan määrittely strukturoidun tiedon vaihtamiseen verkkopalvelujen toteutuksessa. Se käyttää XML Information Set -viestimuotoa ja nojaa sovelluskerroksen protokolliin, yleisimmin Hypertext Transfer Protocol (HTTP).';
$string['xmlrpcprotocol'] = 'XML-RPC-protokolla';
$string['xmlrpcprotocol_help'] =
    '<strong>Moodlen XML-RPC-palvelin käsittelee etäproseduurikutsuja HTTP:n kautta käyttäen XML:ää kutsujen ja vastausten koodaamiseen. Palauttaa arvot <code>XML</code>-muodossa</strong><br />XML-RPC (XML Remote Procedure Call) on etäproseduurikutsuprotokolla, joka käyttää XML:ää kutsujen koodaamiseen ja HTTP:tä kuljetusmekanismina. XML-RPC on suunniteltu mahdollisimman yksinkertaiseksi, samalla sallien monimutkaisten tietorakenteiden siirtämisen, käsittelyn ja palauttamisen.';
$string['webservicesready'] = 'Verkkopalvelut valmiit';

$string['statusenabled'] = 'Käytössä';
$string['statusdisabled'] = 'Ei käytössä';
$string['statuserror'] = 'Ei käytössä';

$string['toolong'] = 'Kentän arvo on liian pitkä, sen on oltava alle {$a} merkkiä.';
$string['deletetoken'] = 'Poista tunnus';

$string['privacy:metadata'] = 'Verkkopalvelujen hallinta -lisäosa ei tallenna henkilötietoja.';