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
 * External local_wsmanager API.
 *
 * @package    local_wsmanager
 * @category   external
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_wsmanager;

defined('MOODLE_INTERNAL') || die();

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_multiple_structure;
use core_external\external_single_structure;
use core_external\external_value;

/**
 * External tool module external functions
 *
 * @package    local_wsmanager
 * @category   external
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since      Moodle 3.0
 */
class external extends external_api {
    public static function webservice_dashboard_info_output_parameters() {
        return new external_function_parameters([]);
    }

    public static function webservice_dashboard_info_output() {
        global $CFG, $PAGE;
        $PAGE->set_context(\context_system::instance());
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        return \local_wsmanager::webservice_dashboard_info_output();
    }

    public static function webservice_dashboard_info_output_returns() {
        return new external_value(PARAM_RAW, 'Web Services status info output');
    }

    public static function webservices_state_switch_parameters() {
        return new external_function_parameters([]);
    }

    public static function webservices_state_switch() {
        global $CFG;
        $ret = [
            'enabled' => (bool) $CFG->enablewebservices,
            'result' => set_config('enablewebservices', !$CFG->enablewebservices),
            'active_protocols' => false,
        ];
        if ($CFG->enablewebservices) {
            set_config('enablemobilewebservice', 0);
            set_config('webserviceprotocols', '');
        } else {
            require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
            $ret['active_protocols'] = (bool) \local_wsmanager::get_active_webservices_protocols();
        }
        return $ret;
    }

    public static function webservices_state_switch_returns() {
        return new external_single_structure([
            'enabled' => new external_value(PARAM_BOOL, 'enabled'),
            'result' => new external_value(PARAM_BOOL, 'result'),
            'active_protocols' => new external_value(PARAM_BOOL, 'has active protocols'),
        ]);
    }

    public static function webservice_state_switch_parameters() {
        return new external_function_parameters([
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
            'instance' => new external_value(PARAM_ALPHANUMEXT, 'webservice field'),
        ]);
    }

    public static function webservice_state_switch($webserviceid, $instance) {
        global $CFG, $DB;
        require_once($CFG->dirroot . '/webservice/lib.php');
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        $webserviceobj = new \webservice();
        $params = self::validate_parameters(self::webservice_state_switch_parameters(),
            ['webserviceid' => $webserviceid, 'instance' => $instance]);
        if ($webservice = $webserviceobj->get_external_service_by_id($params['webserviceid'])) {
            $orig = $webservice->{$params['instance']};
            $webservice->{$params['instance']} = !$orig;
            $DB->update_record('external_services', $webservice);
            switch ($params['instance']) {
                case 'restrictedusers':
                    if (!\local_wsmanager::is_internal($webservice)) {
                        if (!empty($orig)) {
                            $DB->delete_records('external_services_users', ['externalserviceid' => $params['webserviceid']]);
                        }
                    }
                    break;
            }
            return true;
        }
        return false;
    }

    public static function webservice_state_switch_returns() {
        return new external_value(PARAM_BOOL, 'webservice');
    }

    public static function webservices_protocol_switch_parameters() {
        return new external_function_parameters([
            'protocol' => new external_value(PARAM_ALPHANUM, 'protocol alias'),
        ]);
    }

    public static function webservices_protocol_switch($protocol) {
        global $CFG;
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        $params = self::validate_parameters(self::webservices_protocol_switch_parameters(), ['protocol' => $protocol]);
        return \local_wsmanager::webservices_protocol_switch($params['protocol']);
    }

    public static function webservices_protocol_switch_returns() {
        return new external_single_structure([
            'enabled' => new external_value(PARAM_BOOL, 'enabled'),
            'active' => new external_value(PARAM_BOOL, 'has active protocols'),
            'result' => new external_value(PARAM_BOOL, 'result'),
        ]);
    }

    public static function webservice_protocol_status_output_parameters() {
        return new external_function_parameters([
            'protocol' => new external_value(PARAM_ALPHANUM, 'protocol alias'),
        ]);
    }

    public static function webservice_protocol_status_output($protocol) {
        global $CFG, $PAGE;
        $PAGE->set_context(\context_system::instance());
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        $protocols = \local_wsmanager::get_webservices_protocols();
        $protocolsactive = \local_wsmanager::get_active_webservices_protocols($protocols);
        $params = self::validate_parameters(self::webservice_protocol_status_output_parameters(), ['protocol' => $protocol]);
        return \local_wsmanager::webservice_protocol_status_output($params['protocol'], $protocolsactive);
    }

    public static function webservice_protocol_status_output_returns() {
        return new external_value(PARAM_RAW, 'protocol status');
    }

    public static function request_info_parameters() {
        return new external_function_parameters([
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
            'functionname' => new external_value(PARAM_ALPHANUMEXT, 'function name'),
            'params' => new external_value(PARAM_RAW, 'request params'),
            'method' => new external_value(PARAM_ALPHANUM, 'method'),
            'protocol' => new external_value(PARAM_ALPHANUM, 'protocol'),
            'restformat' => new external_value(PARAM_ALPHANUM, 'REST protocol format'),
            'token' => new external_value(PARAM_ALPHANUM, 'token'),
        ]);
    }

    public static function request_info($webserviceid, $functionname, $params, $method, $protocol, $restformat, $token) {
        global $CFG, $PAGE;
        $PAGE->set_context(\context_system::instance());
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        $params2 = self::validate_parameters(self::request_info_parameters(), [
            'webserviceid' => $webserviceid,
            'functionname' => $functionname,
            'params' => $params,
            'method' => $method,
            'protocol' => $protocol,
            'restformat' => $restformat,
            'token' => $token,
        ]);
        $params = \local_wsmanager::handle_params($params2['params']);
        $requestinfo = \local_wsmanager::request_info($params2['webserviceid'], $params2['functionname'], $params,
            $params2['method'], $params2['protocol'], $params2['restformat'], $params2['token']);
        return join('<br />', $requestinfo);
    }

    public static function request_info_returns() {
        return new external_value(PARAM_RAW, 'request info');
    }

    public static function webservice_mobile_state_switch_parameters() {
        return new external_function_parameters([]);
    }

    public static function webservice_mobile_state_switch() {
        global $CFG;
        return [
            'enabled' => (bool) $CFG->enablemobilewebservice,
            'result' => set_config('enablemobilewebservice', !$CFG->enablemobilewebservice),
        ];
    }

    public static function webservice_mobile_state_switch_returns() {
        return new external_single_structure([
            'enabled' => new external_value(PARAM_BOOL, 'enabled'),
            'result' => new external_value(PARAM_BOOL, 'result'),
        ]);
    }

    public static function webservice_documentation_switch_parameters() {
        return new external_function_parameters([]);
    }

    public static function webservice_documentation_switch() {
        global $CFG;
        return set_config('enablewsdocumentation', !$CFG->enablewsdocumentation);
    }

    public static function webservice_documentation_switch_returns() {
        return new external_value(PARAM_BOOL, 'result');
    }

    public static function webservice_dashboard_mobile_info_output_parameters() {
        return new external_function_parameters([]);
    }

    public static function webservice_dashboard_mobile_info_output() {
        global $CFG, $PAGE;
        $PAGE->set_context(\context_system::instance());
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        return \local_wsmanager::webservice_dashboard_mobile_info_output();
    }

    public static function webservice_dashboard_mobile_info_output_returns() {
        return new external_value(PARAM_RAW, 'mobile web services status info output');
    }

    public static function webservices_enable_fix_parameters() {
        return new external_function_parameters([]);
    }

    public static function webservices_enable_fix() {
        global $CFG;
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        return \local_wsmanager::webservices_enable_fix();
    }

    public static function webservices_enable_fix_returns() {
        return new external_single_structure([
            'enablewebservices' => new external_value(PARAM_BOOL, 'enabled'),
            'webserviceprotocols' => new external_value(PARAM_BOOL, 'result'),
        ]);
    }

    public static function webservice_test_create_parameters() {
        return new external_function_parameters([]);
    }

    public static function webservice_test_create() {
        global $CFG;
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        return \local_wsmanager::webservice_test_create();
    }

    public static function webservice_test_create_returns() {
        return new external_value(PARAM_BOOL, 'test webservice created');
    }

    public static function webservice_create_parameters() {
        return new external_function_parameters([
            'name' => new external_value(PARAM_TEXT, 'webservice name'),
            'shortname' => new external_value(PARAM_TEXT, 'webservice shortname'),
            'enabled' => new external_value(PARAM_INT, 'webservice enabled'),
            'restrictedusers' => new external_value(PARAM_INT, 'webservice has restricted users'),
            'downloadfiles' => new external_value(PARAM_INT, 'webservice allows files download'),
            'uploadfiles' => new external_value(PARAM_INT, 'webservice allows files upload'),
            'requiredcapability' => new external_value(PARAM_RAW, 'webservice required capability'),
        ]);
    }

    public static function webservice_create($name, $shortname, $enabled, $restrictedusers, $downloadfiles, $uploadfiles,
        $requiredcapability) {
        global $CFG;
        require_once($CFG->dirroot . '/webservice/lib.php');
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        $webserviceobj = new \webservice();
        $params = self::validate_parameters(self::webservice_create_parameters(), [
            'name' => $name,
            'shortname' => $shortname,
            'enabled' => $enabled,
            'restrictedusers' => $restrictedusers,
            'downloadfiles' => $downloadfiles,
            'uploadfiles' => $uploadfiles,
            'requiredcapability' => $requiredcapability,
        ]);
        return \local_wsmanager::create_webservice([
            'name' => $params['name'],
            'shortname' => $params['shortname'],
            'enabled' => $params['enabled'],
            'restrictedusers' => $params['restrictedusers'],
            'downloadfiles' => $params['downloadfiles'],
            'uploadfiles' => $params['uploadfiles'],
            'requiredcapability' => $params['requiredcapability'],
        ], $webserviceobj);
    }

    public static function webservice_create_returns() {
        return new external_single_structure([
            'webservice' => new external_single_structure([
                'name' => new external_value(PARAM_TEXT, 'webservice name'),
                'enabled' => new external_value(PARAM_INT, 'webservice enabled', VALUE_OPTIONAL, 1),
                'requiredcapability' => new external_value(PARAM_RAW, 'webservice required capability', VALUE_OPTIONAL),
                'restrictedusers' => new external_value(PARAM_INT, 'webservice restricted users', VALUE_OPTIONAL, 0),
                'component' => new external_value(PARAM_ALPHANUMEXT, 'webservice component', VALUE_OPTIONAL),
                'shortname' => new external_value(PARAM_TEXT, 'webservice shortname'),
                'downloadfiles' => new external_value(PARAM_INT, 'download files', VALUE_OPTIONAL, 0),
                'uploadfiles' => new external_value(PARAM_INT, 'upload files', VALUE_OPTIONAL, 0),
            ]),
            'errors' => new external_single_structure([
                'name' => new external_value(PARAM_RAW, 'name error', VALUE_OPTIONAL),
                'shortname' => new external_value(PARAM_RAW, 'shortname error', VALUE_OPTIONAL),
                'requiredcapability' => new external_value(PARAM_RAW, 'required capability error', VALUE_OPTIONAL),
            ]),
        ]);
    }

    public static function webservice_delete_parameters() {
        return new external_function_parameters([
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
        ]);
    }

    public static function webservice_delete($webserviceid) {
        global $CFG;
        require_once($CFG->dirroot . '/webservice/lib.php');
        $webserviceobj = new \webservice();
        $params = self::validate_parameters(self::webservice_delete_parameters(), ['webserviceid' => $webserviceid]);
        if ($webservice = $webserviceobj->get_external_service_by_id($params['webserviceid'])) {
            if (empty($webservice->component)) {
                $webserviceobj->delete_service($params['webserviceid']);
                return true;
            }
        }
        return false;
    }

    public static function webservice_delete_returns() {
        return new external_value(PARAM_BOOL, 'test webservice deleted');
    }

    public static function external_function_handle_parameters() {
        return new external_function_parameters([
            'webserviceid' => new external_value(PARAM_INT, 'Webservice id'),
            'functionname' => new external_value(PARAM_ALPHANUMEXT, 'external function name'),
            'token' => new external_value(PARAM_ALPHANUM, 'token'),
            'params' => new external_value(PARAM_RAW, 'params provided'),
            'method' => new external_value(PARAM_ALPHA, 'request method'),
            'moodlewsprotocol' => new external_value(PARAM_ALPHA, 'request protocol'),
            'moodlewsrestformat' => new external_value(PARAM_ALPHANUM, 'response format for REST protocol'),
        ]);
    }

    public static function external_function_handle($webserviceid, $functionname, $token, $params, $method = 'get',
        $moodlewsprotocol = 'rest', $moodlewsrestformat = 'json') {
        global $CFG;
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        $params2 = self::validate_parameters(self::external_function_handle_parameters(), [
            'webserviceid' => $webserviceid,
            'functionname' => $functionname,
            'token' => $token,
            'params' => $params,
            'method' => $method,
            'moodlewsprotocol' => $moodlewsprotocol,
            'moodlewsrestformat' => $moodlewsrestformat,
        ]);
        return \local_wsmanager::external_function_handle($params2['webserviceid'], $params2['functionname'], $params2['token'],
            $params2['params'], $params2['method'], $params2['moodlewsprotocol'], $params2['moodlewsrestformat']);
    }

    public static function external_function_handle_returns() {
        return new external_value(PARAM_RAW, 'data');
    }

    public static function external_function_test_handle_parameters() {
        global $CFG;
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        return new external_function_parameters([
            'data' => new external_value(PARAM_RAW, 'data to output', VALUE_REQUIRED, 'Hello World'),
        ]);
    }

    public static function external_function_test_handle($data) {
        global $CFG;
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        $params = self::validate_parameters(self::external_function_test_handle_parameters(), ['data' => $data]);
        return \local_wsmanager::external_function_test_handle($params['data']);
    }

    public static function external_function_test_handle_returns() {
        return new external_value(PARAM_RAW, 'data');
    }

    public static function function_param_output_parameters() {
        return new external_function_parameters([
            'paramkey' => new external_value(PARAM_ALPHANUMEXT, 'function name'),
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
        ]);
    }

    public static function external_function_handle_output_parameters() {
        return new external_function_parameters([
            'functionname' => new external_value(PARAM_ALPHANUMEXT, 'function name'),
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
        ]);
    }

    public static function external_function_handle_output($functionname, $webserviceid) {
        global $CFG, $PAGE;
        $PAGE->set_context(\context_system::instance());
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        $params = self::validate_parameters(self::external_function_handle_output_parameters(),
            ['functionname' => $functionname, 'webserviceid' => $webserviceid]);
        return \local_wsmanager::external_function_handle_output($params['functionname'], $params['webserviceid']);
    }

    public static function external_function_handle_output_returns() {
        return new external_value(PARAM_RAW, 'output data');
    }

    public static function webservice_test_dashboard_table_output_parameters() {
        return new external_function_parameters([]);
    }

    public static function webservice_test_dashboard_table_output() {
        global $CFG, $PAGE;
        $PAGE->set_context(\context_system::instance());
        require_once($CFG->dirroot . '/webservice/lib.php');
        return \local_wsmanager\output\dashboard::webservice_test_dashboard_table_output();
    }

    public static function webservice_test_dashboard_table_output_returns() {
        return new external_value(PARAM_RAW, 'mobile web services status info output');
    }

    public static function webservice_nav_title_parameters() {
        return new external_function_parameters([
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
        ]);
    }

    public static function webservice_nav_title($webserviceid) {
        global $CFG;
        require_once($CFG->dirroot . '/webservice/lib.php');
        require_once($CFG->dirroot . '/local/wsmanager/classes/output/webservices.php');
        $webserviceobj = new \webservice();
        $params = self::validate_parameters(self::webservice_nav_title_parameters(), ['webserviceid' => $webserviceid]);
        if ($webservice = $webserviceobj->get_external_service_by_id($params['webserviceid'])) {
            return \local_wsmanager\output\webservices::nav_title($webservice);
        }
        return '';
    }

    public static function webservice_nav_title_returns() {
        return new external_value(PARAM_RAW, 'webservice navigation title');
    }

    public static function webservice_tokens_table_output_parameters() {
        return new external_function_parameters([
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
        ]);
    }

    public static function webservice_tokens_table_output($webserviceid) {
        global $CFG;
        require_once($CFG->dirroot . '/webservice/lib.php');
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        require_once($CFG->dirroot . '/local/wsmanager/classes/output/tokens.php');
        $webserviceobj = new \webservice();
        $params = self::validate_parameters(self::webservice_tokens_table_output_parameters(), ['webserviceid' => $webserviceid]);
        if ($webservice = $webserviceobj->get_external_service_by_id($params['webserviceid'])) {
            $tokens = \local_wsmanager::get_tokens($params['webserviceid']);
            return \local_wsmanager\output\tokens::table($webservice, $tokens);
        }
        return '';
    }

    public static function webservice_tokens_table_output_returns() {
        return new external_value(PARAM_RAW, 'tokens table');
    }

    public static function webservice_token_create_parameters() {
        return new external_function_parameters([
            'webserviceid' => new external_value(PARAM_INT, 'Webservice ID'),
            'userid' => new external_value(PARAM_INT, 'User ID'),
            'iprestriction' => new external_value(PARAM_RAW_TRIMMED, 'IP restriction'),
            'valid_until' => new external_single_structure([
                'day' => new external_value(PARAM_INT, 'valid until day', VALUE_OPTIONAL),
                'month' => new external_value(PARAM_INT, 'valid until month', VALUE_OPTIONAL),
                'year' => new external_value(PARAM_INT, 'valid until year', VALUE_OPTIONAL),
            ], 'valid until', VALUE_OPTIONAL),
        ]);
    }

    public static function webservice_token_create($webserviceid, $userid, $iprestriction, $validuntil) {
        global $CFG;
        require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
        $params = self::validate_parameters(self::webservice_token_create_parameters(), [
            'webserviceid' => $webserviceid,
            'userid' => $userid,
            'iprestriction' => $iprestriction,
            'valid_until' => $validuntil,
        ]);
        if ($webservice = \local_wsmanager::get_webservice($webserviceid)) {
            $data = [
                'iprestriction' => $params['iprestriction'],
            ];
            if (!empty($validuntil)) {
                if (!empty($params['valid_until']['day']) && !empty($params['valid_until']['month']) &&
                    !empty($params['valid_until']['year'])) {
                    $data['validuntil'] = mktime(0, 0, 0, $params['valid_until']['month'], $params['valid_until']['day'],
                        $params['valid_until']['year']);
                }
            }
            return \local_wsmanager::create_webservice_token((array) $webservice, $params['userid'], $data);
        }
        return ['id' => 0, 'errors' => []];
    }

    public static function webservice_token_create_returns() {
        return new external_single_structure([
            'id' => new external_value(PARAM_INT, 'token id'),
            'errors' => new external_multiple_structure(
                new external_value(PARAM_RAW, 'error message')
            ),
        ]);
    }

    public static function webservice_token_delete_parameters() {
        return new external_function_parameters([
            'tokenid' => new external_value(PARAM_INT, 'token id'),
        ]);
    }

    public static function webservice_token_delete($tokenid) {
        global $CFG;
        require_once($CFG->dirroot . '/webservice/lib.php');
        $webserviceobj = new \webservice();
        $webserviceobj->delete_user_ws_token($tokenid);
        return true;
    }

    public static function webservice_token_delete_returns() {
        return new external_value(PARAM_BOOL, 'token deleted');
    }

    public static function webservice_user_delete_parameters() {
        return new external_function_parameters([
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
            'userid' => new external_value(PARAM_INT, 'user id'),
        ]);
    }

    public static function webservice_user_delete($webserviceid, $userid) {
        global $DB;
        $params = self::validate_parameters(self::webservice_user_delete_parameters(),
            ['webserviceid' => $webserviceid, 'userid' => $userid]);
        return $DB->delete_records('external_services_users',
            ['externalserviceid' => $params['webserviceid'], 'userid' => $params['userid']]);
    }

    public static function webservice_user_delete_returns() {
        return new external_value(PARAM_BOOL, 'webservice user deleted');
    }

    public static function webservice_users_table_output_parameters() {
        return new external_function_parameters([
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
        ]);
    }

    public static function webservice_users_table_output($webserviceid) {
        global $CFG, $OUTPUT, $PAGE;
        $PAGE->set_context(\context_system::instance());
        require_once($CFG->dirroot . '/webservice/lib.php');
        require_once($CFG->dirroot . '/local/wsmanager/classes/output/users.php');
        $webserviceobj = new \webservice();
        $params = self::validate_parameters(self::webservice_users_table_output_parameters(), ['webserviceid' => $webserviceid]);
        if ($webservice = $webserviceobj->get_external_service_by_id($params['webserviceid'])) {
            $renderable = new \local_wsmanager\output\users($webserviceobj, $webservice);
            return $OUTPUT->render($renderable);
        }
        return '';
    }

    public static function webservice_users_table_output_returns() {
        return new external_value(PARAM_RAW, 'webservice users table');
    }

    public static function webservice_functions_table_output_parameters() {
        return new external_function_parameters([
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
        ]);
    }

    public static function webservice_functions_table_output($webserviceid) {
        global $CFG;
        require_once($CFG->dirroot . '/webservice/lib.php');
        require_once($CFG->dirroot . '/local/wsmanager/classes/output/functions.php');
        $webserviceobj = new \webservice();
        $params = self::validate_parameters(self::webservice_functions_table_output_parameters(),
            ['webserviceid' => $webserviceid]);
        if ($webservice = $webserviceobj->get_external_service_by_id($params['webserviceid'])) {
            return \local_wsmanager\output\functions::table($webservice);
        }
        return '';
    }

    public static function webservice_functions_table_output_returns() {
        return new external_value(PARAM_RAW, 'webservice functions table');
    }

    public static function webservice_function_add_parameters() {
        return new external_function_parameters([
            'functionid' => new external_value(PARAM_INT, 'function id'),
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
        ]);
    }

    public static function webservice_function_add($functionid, $webserviceid) {
        global $CFG;

        require_once($CFG->dirroot . '/webservice/lib.php');
        $webserviceobj = new \webservice();
        $params = self::validate_parameters(self::webservice_function_add_parameters(),
            ['functionid' => $functionid, 'webserviceid' => $webserviceid]);
        if ($function = $webserviceobj->get_external_function_by_id($params['functionid'])) {
            $webserviceobj->add_external_function_to_service($function->name, $params['webserviceid']);
            return true;
        }
        return false;
    }

    public static function webservice_function_add_returns() {
        return new external_value(PARAM_BOOL, 'webservice function add result');
    }

    public static function webservice_function_delete_parameters() {
        return new external_function_parameters([
            'functionid' => new external_value(PARAM_INT, 'function id'),
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
        ]);
    }

    public static function webservice_function_delete($functionid, $webserviceid) {
        global $CFG;
        require_once($CFG->dirroot . '/webservice/lib.php');
        $webserviceobj = new \webservice();
        $params = self::validate_parameters(self::webservice_function_delete_parameters(),
            ['functionid' => $functionid, 'webserviceid' => $webserviceid]);
        if ($function = $webserviceobj->get_external_function_by_id($params['functionid'])) {
            $webserviceobj->remove_external_function_from_service($function->name, $params['webserviceid']);
            return true;
        }
        return false;
    }

    public static function webservice_function_delete_returns() {
        return new external_value(PARAM_BOOL, 'webservice function delete result');
    }

    public static function webservice_rename_parameters() {
        return new external_function_parameters([
            'webserviceid' => new external_value(PARAM_INT, 'webservice id'),
            'name' => new external_value(PARAM_RAW, 'webservice new name'),
        ]);
    }

    public static function webservice_rename($webserviceid, $name) {
        global $DB;
        $params = self::validate_parameters(self::webservice_rename_parameters(),
            ['webserviceid' => $webserviceid, 'name' => $name]);
        if (\core_text::strlen($params['name']) <= 200) {
            if (!$DB->get_records('external_services', ['name' => $name])) {
                $webservice = new \stdClass();
                $webservice->id = $webserviceid;
                $webservice->name = clean_param($name, PARAM_RAW);
                return $DB->update_record('external_services', $webservice);
            }
        }
        return false;
    }

    public static function webservice_rename_returns() {
        return new external_value(PARAM_BOOL, 'webservice rename result');
    }
}
