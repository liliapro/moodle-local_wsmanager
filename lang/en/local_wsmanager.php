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
 * Strings for component 'local_wsmanager', language 'en'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Web services manager';

$string['dashboard'] = 'Dashboard';
$string['protocols'] = 'Protocols';
$string['protocol'] = 'Protocol';
$string['services'] = 'Services';
$string['recommended'] = 'Recommended';
$string['request'] = 'Request';
$string['fixit'] = 'Fix it';
$string['testing'] = 'Testing';
$string['test'] = 'Test';
$string['webservice_test'] = 'Test web service';
$string['function_test'] = 'Test function';
$string['webservice_test_create'] = 'Create test web service';
$string['webservice_test_delete'] = 'Delete test web service';
$string['response_format'] = 'Response format';
$string['updated'] = 'Updated';

$string['webservices_disable'] = 'Disable web services';
$string['webservices_protocol_disable'] = 'Disable web service protocol';

$string['errorwsdisabled'] = 'Web services disabled';
$string['errorwsdisabled_help'] = 'Web services must be enabled';

$string['restprotocol'] = 'REST protocol';
$string['restprotocol_help'] =
    '<strong>The Moodle REST server accepts GET/POST parameters and return <code>JSON/XML</code> values. This server is not RESTfull.</strong><br />REST (Representational state transfer) is a software architectural style that was created to guide the design and development of the architecture for the World Wide Web. REST defines a set of constraints for how the architecture of an Internet-scale distributed hypermedia system, such as the Web, should behave.';
$string['soapprotocol'] = 'SOAP protocol';
$string['soapprotocol_help'] =
    '<strong>The Moodle SOAP server is based on the Zend SOAP server (itself based on the PHP SOAP server). Zend publishes a <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">Zend SOAP client</a>. Returns <code>XML</code> values</strong><br />SOAP (formerly an acronym for Simple Object Access Protocol) is a messaging protocol specification for exchanging structured information in the implementation of web services. It uses XML Information Set for its message format, and relies on application layer protocols, most often Hypertext Transfer Protocol (HTTP).';

$string['webservicesready'] = 'Web services are ready to go';

$string['statusenabled'] = 'Enabled';
$string['statusdisabled'] = 'Disabled';
$string['statuserror'] = 'Disabled';

$string['toolong'] = 'Field value is too long; it should contain {$a} letters or less.';

$string['privacy:metadata'] = 'Web services manager plugin does not store any personal data.';
