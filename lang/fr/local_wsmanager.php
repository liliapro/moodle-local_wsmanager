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
 * Strings for component 'local_wsmanager', language 'fr'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Gestionnaire de Services Web';

$string['dashboard'] = 'Tableau de bord';
$string['protocols'] = 'Protocoles';
$string['protocol'] = 'Protocole';
$string['services'] = 'Services';
$string['recommended'] = 'Recommandé';
$string['request'] = 'Requête';
$string['fixit'] = 'Réparer';
$string['testing'] = 'Test en cours';
$string['test'] = 'Test';
$string['webservice_test'] = 'Tester le service web';
$string['function_test'] = 'Tester la fonction';
$string['webservice_test_create'] = 'Créer un service web de test';
$string['webservice_test_delete'] = 'Supprimer le service web de test';
$string['response_format'] = 'Format de réponse';
$string['updated'] = 'Mis à jour';

$string['webservices_disable'] = 'Désactiver les services web';
$string['webservices_protocol_disable'] = 'Désactiver le protocole des services web';

$string['errorwsdisabled'] = 'Services web désactivés';
$string['errorwsdisabled_help'] = 'Les services web doivent être activés';

$string['restprotocol'] = 'Protocole REST';
$string['restprotocol_help'] =
    '<strong>Le serveur REST de Moodle accepte les paramètres GET/POST et retourne des valeurs <code>JSON/XML</code>. Ce serveur n\'est pas RESTful.</strong><br />REST (Representational State Transfer) est un style d\'architecture logicielle créé pour guider la conception et le développement de l\'architecture du World Wide Web. REST définit un ensemble de contraintes sur la façon dont l\'architecture d\'un système hypermédia distribué à l\'échelle d\'Internet comme le Web devrait se comporter.';
$string['soapprotocol'] = 'Protocole SOAP';
$string['soapprotocol_help'] =
    '<strong>Le serveur SOAP de Moodle est basé sur le serveur SOAP de Zend (qui est lui-même basé sur le serveur SOAP de PHP). Zend publie un <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">Client SOAP Zend</a>. Il retourne des valeurs <code>XML</code></strong><br />SOAP (anciennement acronyme de Simple Object Access Protocol) est une spécification de protocole de messagerie pour échanger des informations structurées dans l\'implémentation de services web. Il utilise XML Information Set pour son format de message et s\'appuie sur des protocoles de couche application, le plus souvent le Protocole de Transfert Hypertexte (HTTP).';
$string['webservicesready'] = 'Les services web sont prêts à être utilisés';

$string['statusenabled'] = 'Activé';
$string['statusdisabled'] = 'Désactivé';
$string['statuserror'] = 'Désactivé';

$string['toolong'] = 'La valeur du champ est trop longue ; elle doit contenir {$a} caractères ou moins.';
$string['deletetoken'] = 'Supprimer le jeton';

$string['privacy:metadata'] = 'Le plugin Gestionnaire de Services Web ne stocke aucune donnée personnelle.';
