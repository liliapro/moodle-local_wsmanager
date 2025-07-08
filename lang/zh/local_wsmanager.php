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
 * Strings for component 'local_wsmanager', language 'zh_cn'
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$string['pluginname'] = 'Web服务管理器';

$string['dashboard'] = '仪表板';
$string['protocols'] = '协议';
$string['protocol'] = '协议';
$string['services'] = '服务';
$string['recommended'] = '推荐';
$string['request'] = '请求';
$string['fixit'] = '修复';
$string['testing'] = '测试中';
$string['test'] = '测试';
$string['webservice_test'] = '测试Web服务';
$string['function_test'] = '测试函数';
$string['webservice_test_create'] = '创建测试Web服务';
$string['webservice_test_delete'] = '删除测试Web服务';
$string['response_format'] = '响应格式';
$string['updated'] = '已更新';

$string['webservices_disable'] = '禁用Web服务';
$string['webservices_protocol_disable'] = '禁用Web服务协议';

$string['errorwsdisabled'] = 'Web服务已禁用';
$string['errorwsdisabled_help'] = '需要启用Web服务';

$string['restprotocol'] = 'REST协议';
$string['restprotocol_help'] =
    '<strong>Moodle的REST服务器接受GET/POST参数并返回<code>JSON/XML</code>值。此服务器不是RESTful的。</strong><br />REST（表述性状态传递）是一种软件架构风格，用于指导万维网架构的设计和开发。REST定义了一组约束条件，规定了像Web这样的互联网规模分布式超媒体系统的架构应该如何运行。';
$string['soapprotocol'] = 'SOAP协议';
$string['soapprotocol_help'] =
    '<strong>Moodle的SOAP服务器基于Zend SOAP服务器（它本身基于PHP SOAP服务器）。Zend发布了<a href="https://docs.zendframework.com/zend-soap/client/" target="_blank">Zend SOAP客户端</a>。返回<code>XML</code>值。</strong><br />SOAP（原为Simple Object Access Protocol的缩写）是用于在Web服务实现中交换结构化信息的消息协议规范。它使用XML信息集作为消息格式，并依赖于应用层协议，最常见的是超文本传输协议（HTTP）。';
$string['xmlrpcprotocol'] = 'XML-RPC协议';
$string['xmlrpcprotocol_help'] =
    '<strong>Moodle XML-RPC服务器通过HTTP处理远程过程调用，使用XML编码调用和响应。返回<code>XML</code>格式的值</strong><br />XML-RPC（XML远程过程调用）是一种远程过程调用协议，它使用XML编码其调用，使用HTTP作为传输机制。XML-RPC设计得尽可能简单，同时允许传输、处理和返回复杂的数据结构。';
$string['webservicesready'] = 'Web服务已准备就绪';

$string['statusenabled'] = '已启用';
$string['statusdisabled'] = '已禁用';
$string['statuserror'] = '已禁用';

$string['toolong'] = '字段值过长，必须包含{$a}个字符或更少。';
$string['deletetoken'] = '删除令牌';

$string['privacy:metadata'] = 'Web服务管理器插件不存储任何个人数据。';
