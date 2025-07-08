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
 * Strings for component 'local_wsmanager', language 'tr'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Web servisleri yöneticisi';

$string['dashboard'] = 'Kontrol Paneli';
$string['protocols'] = 'Protokoller';
$string['protocol'] = 'Protokol';
$string['services'] = 'Servisler';
$string['recommended'] = 'Önerilen';
$string['request'] = 'İstek';
$string['fixit'] = 'Düzelt';
$string['testing'] = 'Test ediliyor';
$string['test'] = 'Test';
$string['webservice_test'] = 'Web servisini test et';
$string['function_test'] = 'Fonksiyonu test et';
$string['webservice_test_create'] = 'Test web servisi oluştur';
$string['webservice_test_delete'] = 'Test web servisini sil';
$string['response_format'] = 'Yanıt formatı';
$string['updated'] = 'Güncellendi';

$string['webservices_disable'] = 'Web servislerini devre dışı bırak';
$string['webservices_protocol_disable'] = 'Web servisi protokolünü devre dışı bırak';

$string['errorwsdisabled'] = 'Web servisleri devre dışı';
$string['errorwsdisabled_help'] = 'Web servisleri etkinleştirilmelidir';

$string['restprotocol'] = 'REST protokolü';
$string['restprotocol_help'] =
    '<strong>Moodle REST sunucusu GET/POST parametrelerini kabul eder ve <code>JSON/XML</code> değerleri döndürür. Bu sunucu RESTfull değildir.</strong><br />REST (Temsili durum transferi), World Wide Web mimarisinin tasarımını ve geliştirilmesini yönlendirmek için oluşturulmuş bir yazılım mimari stilidir. REST, Web gibi internet ölçeğinde dağıtılmış bir hipermedya sisteminin mimarisinin nasıl davranması gerektiğine dair bir dizi kısıtlama tanımlar.';
$string['soapprotocol'] = 'SOAP protokolü';
$string['soapprotocol_help'] =
    '<strong>Moodle SOAP sunucusu, Zend SOAP sunucusuna (kendisi de PHP SOAP sunucusuna dayanmaktadır) dayanmaktadır. Zend, bir <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">Zend SOAP istemcisi</a> yayınlamaktadır. <code>XML</code> değerleri döndürür</strong><br />SOAP (eski adıyla Basit Nesne Erişim Protokolü), web servislerinin uygulanmasında yapılandırılmış bilgi alışverişi için bir mesajlaşma protokolü belirtimidir. Mesaj formatı için XML Bilgi Seti\'ni kullanır ve çoğunlukla Köprü Metni Aktarım Protokolü (HTTP) olmak üzere uygulama katmanı protokollerine dayanır.';
$string['xmlrpcprotocol'] = 'XML-RPC protokolü';
$string['xmlrpcprotocol_help'] =
    '<strong>Moodle XML-RPC sunucusu, çağrıları ve yanıtları kodlamak için XML kullanarak HTTP üzerinden uzak prosedür çağrılarını işler. <code>XML</code> formatında değerler döndürür</strong><br />XML-RPC (XML Remote Procedure Call), çağrılarını kodlamak için XML ve taşıma mekanizması olarak HTTP kullanan bir uzak prosedür çağrı protokolüdür. XML-RPC mümkün olduğunca basit olacak şekilde tasarlanmış, aynı zamanda karmaşık veri yapılarının iletilmesine, işlenmesine ve döndürülmesine izin verir.';
$string['webservicesready'] = 'Web servisleri kullanıma hazır';

$string['statusenabled'] = 'Etkin';
$string['statusdisabled'] = 'Devre dışı';
$string['statuserror'] = 'Devre dışı';

$string['toolong'] = 'Alan değeri çok uzun; {$a} harf veya daha az içermelidir.';
$string['deletetoken'] = 'Tokenı sil';

$string['privacy:metadata'] = 'Web servisleri yöneticisi eklentisi herhangi bir kişisel veri saklamaz.';
