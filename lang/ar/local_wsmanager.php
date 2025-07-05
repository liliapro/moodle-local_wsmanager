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
 * Strings for component 'local_wsmanager', language 'ar'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'مدير خدمات الويب';

$string['dashboard'] = 'لوحة التحكم';
$string['protocols'] = 'البروتوكولات';
$string['protocol'] = 'البروتوكول';
$string['services'] = 'الخدمات';
$string['recommended'] = 'مُوصى به';
$string['request'] = 'طلب';
$string['fixit'] = 'إصلاح';
$string['testing'] = 'جاري الاختبار';
$string['test'] = 'اختبار';
$string['webservice_test'] = 'اختبار خدمة الويب';
$string['function_test'] = 'اختبار الوظيفة';
$string['webservice_test_create'] = 'إنشاء اختبار خدمة الويب';
$string['webservice_test_delete'] = 'حذف اختبار خدمة الويب';
$string['response_format'] = 'تنسيق الاستجابة';
$string['updated'] = 'محدّث';

$string['webservices_disable'] = 'تعطيل خدمات الويب';
$string['webservices_protocol_disable'] = 'تعطيل بروتوكول خدمات الويب';

$string['errorwsdisabled'] = 'خدمات الويب معطلة';
$string['errorwsdisabled_help'] = 'يجب تمكين خدمات الويب';

$string['restprotocol'] = 'بروتوكول REST';
$string['restprotocol_help'] =
    '<strong>يقبل خادم Moodle REST معاملات GET/POST ويعيد قيم <code>JSON/XML</code>. هذا الخادم ليس RESTful.</strong><br />REST (نقل الحالة التمثيلية) هو نمط معماري لهندسة البرمجيات للأنظمة الفائقة الوسائط الموزعة مثل شبكة الويب العالمية. يحدد REST مجموعة من القيود التي تحدد كيف يجب أن تعمل هندسة الأنظمة الفائقة الوسائط الموزعة واسعة النطاق مثل الويب.';
$string['soapprotocol'] = 'بروتوكول SOAP';
$string['soapprotocol_help'] =
    '<strong>يعتمد خادم Moodle SOAP على خادم Zend SOAP (الذي يعتمد بدوره على خادم PHP SOAP). نشرت Zend <a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">عميل Zend SOAP</a>. يعيد قيم <code>XML</code>.</strong><br />SOAP (في الأصل اختصار لـ Simple Object Access Protocol) هو مواصفة بروتوكول المراسلة لتبادل المعلومات المنظمة في تنفيذ خدمات الويب. يستخدم XML Information Set لتنسيق الرسائل ويعتمد على بروتوكولات طبقة التطبيق، وأكثرها شيوعاً بروتوكول نقل النص الفائق (HTTP).';
$string['webservicesready'] = 'خدمات الويب جاهزة';

$string['statusenabled'] = 'مُمكَّن';
$string['statusdisabled'] = 'مُعطَّل';
$string['statuserror'] = 'مُعطَّل';

$string['toolong'] = 'قيمة الحقل طويلة جداً، يجب أن تكون أقل من {$a} حرف.';
$string['deletetoken'] = 'حذف الرمز المميز';

$string['privacy:metadata'] = 'إضافة مدير خدمات الويب لا تخزن أي بيانات شخصية.';