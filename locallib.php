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
 * Plugin local functions
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use core_external\external_api;
use local_wsmanager\check\result;

class local_wsmanager {
    const WS_TEST_SHORTNAME = 'local_wsmanager_test_ws';

    const WS_TEST_FUNCTION = 'local_wsmanager_external_function_test_handle';

    const SEPARATOR_1 = '[|local_wsmanager|]';

    const SEPARATOR_2 = '[||local_wsmanager||]';

    const SEPARATOR_3 = '[|||local_wsmanager|||]';

    const SEPARATOR_4 = '[||||local_wsmanager||||]';

    const EXCLUDE_PARAMS = ['moodlewsrestformat', 'moodlewsprotocol'];

    const PROTOCOL_REST = 'rest';

    const PROTOCOL_SOAP = 'soap';

    const PROTOCOL_DEFAULT = 'rest';

    const FORMAT_XML = 'xml';

    const FORMAT_JSON = 'json';

    const REST_RESPONSE_FORMATS = [self::FORMAT_XML, self::FORMAT_JSON];

    const DEFAULT_REST_RESPONSE_FORMAT = self::FORMAT_JSON;

    const METHOD_GET = 'get';

    const METHOD_POST = 'post';

    const DASHBOARD_PAGE = 'local_wsmanager_dashboard';

    const MULTIPLE_STRUCTURE_COUNT = 3;

    /**
     * Get all webservices
     *
     * @return array|bool|null
     * @throws dml_exception
     */
    public static function get_services(): array|bool|null {
        global $DB;
        return $DB->get_records('external_services');
    }

    /**
     * Is webservice internal (not custom)
     *
     * @param stdClass $webservice Webservice database object
     * @return bool
     */
    public static function is_internal(\stdClass $webservice): bool {
        return !empty($webservice->component);
    }

    /**
     * Export moodle strings to Javascript
     *
     * @return void
     */
    public static function js_strings(): void {
        global $PAGE;
        $PAGE->requires->strings_for_js(['deleteuser'], 'admin');
        $PAGE->requires->strings_for_js([
            'areyousure',
            'disable',
            'delete',
            'add',
            'search',
            'show',
            'remove',
            'yes',
            'no',
            'success',
            'summary',
            'error',
        ], 'moodle');
        $PAGE->requires->strings_for_js([
            'token',
            'deleteaservice',
            'deletetoken',
            'functions',
            'removefunction',
            'service',
            'execute',
        ], 'webservice');
        $PAGE->requires->strings_for_js([
            'webservices_disable',
            'webservices_protocol_disable',
            'webservice_test_create',
            'webservice_test_delete',
            'test',
        ], 'local_wsmanager');
    }

    /**
     * Get request method GET/POST by function type
     *
     * @param string $type Function type read/write
     * @return string
     */
    public static function get_request_method_by_function_type(string $type): string {
        switch ($type) {
            case 'read':
                return self::METHOD_GET;
            case 'write':
                return self::METHOD_POST;
        }
        return '';
    }

    /**
     * Check given string is JSON
     *
     * @param string $string
     * @return bool
     */
    protected static function is_json(string $string): bool {
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Prepare Moodle HTML table object
     *
     * @param bool $head Print table head
     * @param string $size1 First column name (size)
     * @param string $size2 Second column name (value)
     * @return html_table
     * @throws coding_exception
     */
    public static function make_table(bool $head = true, string $size1 = '30%', string $size2 = '70%'): \html_table {
        $table = new \html_table();
        $table->head = [
            'name' => $head ? get_string('name') : null,
            'value' => $head ? get_string('value', 'scorm') : null,
        ];
        $table->size = [
            'name' => $size1,
            'value' => $size2,
        ];
        return $table;
    }

    /**
     * Get external function info object
     *
     * @param string $functionname Function name
     * @return bool|stdClass|string
     * @throws coding_exception
     */
    public static function function_info(string $functionname): bool|\stdClass|string {
        return external_api::external_function_info($functionname);
    }

    /**
     * Helper to get function request data for given params
     *
     * @param int $webserviceid Webservice id
     * @param string $functionname Function name
     * @param string|null $token Webservice token
     * @param array|null $params Given function params
     * @param string $protocol Request protocol rest|soap
     * @param string $restformat REST request format xml|json
     * @return array
     */
    public static function request_data(int $webserviceid, string $functionname, ?string $token, ?array $params = null,
        string $protocol = self::PROTOCOL_REST, string $restformat = self::FORMAT_JSON): array {
        global $CFG;
        $ret = [
            'url' => '',
            'params' => [],
            'params_query' => '',
        ];
        $protocol = \core_text::strtolower($protocol);
        $ret['path'] = '/webservice/' . $protocol . '/server.php';
        $ret['endpoint'] = $CFG->wwwroot . $ret['path'];
        $ret['host'] = parse_url($ret['endpoint'], PHP_URL_HOST);
        $ret['params']['wstoken'] = $token;
        $ret['params']['wsfunction'] = $functionname;
        if ($values = self::function_params_data_values($params, $webserviceid, $functionname)) {
            foreach ($values as $key => $value) {
                if (!empty($value)) {
                    $ret['params'][$key] = $value;
                }
            }
        }
        switch ($protocol) {
            case self::PROTOCOL_REST:
                if (in_array($restformat, self::REST_RESPONSE_FORMATS)) {
                    $ret['params']['moodlewsrestformat'] = $restformat;
                }
                break;
            case self::PROTOCOL_SOAP:
                if (isset($params['moodlewsrestformat'])) {
                    unset($ret['params']['moodlewsrestformat']);
                    unset($params['moodlewsrestformat']);
                }
                $ret['params']['wsdl'] = '1';
                break;
        }
        if (!empty($ret['params'])) {
            $httpquery = http_build_query($ret['params'], '', '&');
            $ret['params_query'] = urldecode($httpquery);
            $ret['path'] .= '?' . $ret['params_query'];
            $ret['url'] = $ret['endpoint'] . '?' . $httpquery;
        }
        return $ret;
    }

    /**
     * Function request information
     *
     * @param int $webserviceid Webservice id
     * @param string $functionname Function name
     * @param array|null $params
     * @param string $method
     * @param string $protocol
     * @param string $restformat
     * @param string|null $token
     * @return array
     * @throws coding_exception
     */
    public static function request_info(int $webserviceid, string $functionname, ?array $params = null,
        string $method = self::METHOD_GET, string $protocol = self::PROTOCOL_REST,
        string $restformat = self::DEFAULT_REST_RESPONSE_FORMAT, ?string $token = null): array {
        global $OUTPUT;
        $requestdata = self::request_data($webserviceid, $functionname, $token, $params, $protocol, $restformat);
        $ret = [
            'title' => \html_writer::tag('strong', get_string('request', 'local_wsmanager') . ':'),
            'host' => 'Host: ' . \html_writer::tag('code', $requestdata['host']),
            'path' => \core_text::strtoupper($method) . ' ' . \html_writer::tag('code', $requestdata['path']),
        ];
        switch ($method) {
            case self::METHOD_GET:
                $ret['url'] = get_string('url') . ': ' . \html_writer::link($requestdata['url'], $requestdata['endpoint'] . '... ' .
                        $OUTPUT->pix_icon('i/externallink', get_string('opensinnewwindow'), 'moodle',
                            ['class' => 'fa fa-externallink fa-fw']), ['target' => '_blank']);
                break;
        }
        return $ret;
    }

    /**
     * Services by type internal|external (custom)
     *
     * @param array $services Given services
     * @return array
     */
    public static function get_services_by_type(array $services): array {
        $ret = [];
        foreach ($services as $service) {
            if (self::is_internal($service)) {
                $ret['internal'][$service->id] = $service;
            } else {
                $ret['external'][$service->id] = $service;
            }
        }
        return $ret;
    }

    /**
     * Get all webservices protocols
     *
     * @return array
     * @throws coding_exception
     */
    public static function get_webservices_protocols(): array {
        $ret = [];
        if ($protocols = \core_component::get_plugin_list('webservice')) {
            foreach ($protocols as $protocol => $path) {
                $ret[$protocol] = [
                    'path' => $path,
                    'title' => get_string('pluginname', 'webservice_' . $protocol),
                ];
            }
        }
        return $ret;
    }

    /**
     * Get active (enabled) webservices protocols
     *
     * @param array|null $protocols
     * @return array
     * @throws coding_exception
     */
    public static function get_active_webservices_protocols(?array $protocols = null): array {
        global $CFG;
        $ret = [];
        if ($aprotocols = empty($CFG->webserviceprotocols) ? [] : explode(',', $CFG->webserviceprotocols)) {
            if (empty($protocols)) {
                $protocols = self::get_webservices_protocols();
            }
            foreach ($aprotocols as $protocol) {
                $protocol = trim($protocol);
                if (isset($protocols[$protocol])) {
                    $ret[$protocol] = $protocols[$protocol]['title'];
                }
            }
        }
        return $ret;
    }

    /**
     * Enable/disable webservices protocol. Method for ajax
     *
     * @param string $protocol Protocol key
     * @return array
     * @throws coding_exception
     */
    public static function webservices_protocol_switch(string $protocol): array {
        $enabled = false;
        $activeprotocols = self::get_active_webservices_protocols();
        if (array_key_exists($protocol, $activeprotocols)) {
            $enabled = true;
            unset($activeprotocols[$protocol]);
        } else {
            $activeprotocols[$protocol] = $protocol;
        }
        return [
            'enabled' => $enabled,
            'active' => (bool) count($activeprotocols),
            'result' => set_config('webserviceprotocols', !empty($activeprotocols) ?
                join(',', array_keys($activeprotocols)) : ''),
        ];
    }

    /**
     * Errors that broking work with webservices
     *
     * @return array
     * @throws coding_exception
     */
    public static function get_general_errors(): array {
        global $CFG;
        $ret = [];
        if (!$CFG->enablewebservices) {
            $ret[] = get_string('errorwsdisabled', 'local_wsmanager');
        } else {
            if (!self::get_active_webservices_protocols()) {
                $ret[] = get_string('enableprotocolsdescription', 'webservice');
            }
        }
        return $ret;
    }

    /**
     * Panel with info about webservices status
     *
     * @param bool $fix
     * @return string|null
     * @throws coding_exception|moodle_exception
     */
    public static function webservice_dashboard_info_output(bool $fix = true): ?string {
        global $CFG, $OUTPUT;
        $errors = self::get_general_errors();
        $arr = [
            'has_errors' => !empty($errors),
            'errors' => $errors,
        ];
        if ($fix) {
            $arr['fix'] = $OUTPUT->action_link('javascript:;', get_string('fixit', 'local_wsmanager'),
                null, ['id' => 'local_wsmanager_webservices_fix']);
        } else {
            $arr['fix'] =
                $OUTPUT->action_link(new \moodle_url('/' . $CFG->admin . '/settings.php', [
                    'section' => 'local_wsmanager_dashboard',
                ]), get_string('dashboard', 'local_wsmanager'));
        }
        return $OUTPUT->box(
            $OUTPUT->render_from_template('local_wsmanager/dashboard_info', $arr),
            'generalbox alert alert-' . ($errors ? 'danger' : 'success')
        );
    }

    /**
     * Mobile webservice status info panel
     *
     * @return string
     * @throws coding_exception
     * @throws moodle_exception
     */
    public static function webservice_dashboard_mobile_info_output(): string {
        global $CFG, $OUTPUT;
        if ($CFG->enablemobilewebservice && ($CFG->debugdisplay || defined('WARN_DISPLAY_ERRORS_ENABLED'))) {
            return $OUTPUT->box(get_string('displayerrorswarning', 'tool_mobile') . ' ' .
                \html_writer::link(new \moodle_url('/' . $CFG->admin . '/settings.php', ['section' => 'debugging'],
                    'admin-debugdisplay'), get_string('disable'), ['title' => get_string('disable')]),
                'generalbox alert alert-warning');
        }
        return '';
    }

    /**
     * Webservice protocol status result badge output
     *
     * @param string $protocol Protocol key
     * @param array $activeprotocols Active protocols keys array
     * @return string
     */
    public static function webservice_protocol_status_output(string $protocol, array $activeprotocols): string {
        global $CFG, $OUTPUT;
        if (empty($CFG->enablewebservices)) {
            return $OUTPUT->check_result(new result(result::ERROR, null));
        } else {
            if (empty($CFG->webserviceprotocols)) {
                return $OUTPUT->check_result(new result(result::ERROR, null));
            } else {
                if (array_key_exists($protocol, $activeprotocols)) {
                    return $OUTPUT->check_result(new result(result::ENABLED, null));
                } else {
                    return $OUTPUT->check_result(new result(result::DISABLED, null));
                }
            }
        }
    }

    /**
     * Fix webservices settings to enable the ones. Enable minimum required rules
     *
     * @return array
     */
    public static function webservices_enable_fix(): array {
        global $CFG;
        $ret = [
            'enablewebservices' => false,
            'webserviceprotocols' => false,
        ];
        if (empty($CFG->enablewebservices)) {
            $ret['enablewebservices'] = set_config('enablewebservices', 1);
        }
        if (empty($CFG->webserviceprotocols)) {
            $ret['webserviceprotocols'] = set_config('webserviceprotocols', self::PROTOCOL_DEFAULT);
        }
        return $ret;
    }

    /**
     * Create webservice handler
     *
     * @param array $data Input webservice data
     * @param webservice $webserviceobj webservice class object
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function create_webservice(array $data, \webservice $webserviceobj): array {
        global $DB;
        $errors = [];
        $data['name'] = clean_text($data['name'], PARAM_TEXT);
        $data['shortname'] = clean_text($data['shortname'], PARAM_TEXT);
        $data['requiredcapability'] = clean_text($data['requiredcapability'], PARAM_RAW);
        if (empty($data['name'])) {
            $errors['name'] = get_string('name') . ' ' . get_string('emptysettingvalue', 'admin');
        } else {
            $maxlength = 200;
            if (core_text::strlen($data['name']) > $maxlength) {
                $errors['name'] = get_string('toolong', 'tool_wsmanager', $maxlength);
            } else {
                if ($DB->record_exists('external_services', ['name' => $data['name']])) {
                    $errors['name'] = get_string('nameexists', 'webservice') . ' (' . $data['name'] . ')';
                }
            }
        }
        if (!empty($data['shortname'])) {
            $maxlength = 255;
            if (\core_text::strlen($data['shortname']) > $maxlength) {
                $errors['shortname'] = get_string('toolong', 'tool_wsmanager', $maxlength);
            } else {
                if ($DB->record_exists('external_services', ['shortname' => $data['shortname']])) {
                    $errors['shortname'] = get_string('shortnametaken', 'webservice', $data['shortname']);
                }
            }
        }
        if (empty($errors['name']) && empty($errors['shortname'])) {
            if (!isset($data['enabled'])) {
                $data['enabled'] = 1;
            }
            $data['timemodified'] = time();
            $data['component'] = null;
            $dataobj = (object) $data;
            $dataobj->id = $data['id'] = $webserviceobj->add_external_service($dataobj);
            $params = [
                'objectid' => $data['id'],
            ];
            $event = \core\event\webservice_service_created::create($params);
            $event->trigger();
        }
        return ['webservice' => $data, 'errors' => $errors];
    }

    /**
     * Check token is valid
     *
     * @param stdClass $token Token db object
     * @param int|null $webserviceid Webservice id
     * @return bool
     */
    public static function token_check(\stdClass $token, ?int $webserviceid = null): bool {
        global $CFG;
        if (\core_text::strlen(clean_text($token->token, PARAM_ALPHANUM)) != 32) {
            return false;
        }
        if ($token->validuntil > 0 && $token->validuntil < time()) {
            return false;
        }
        if (!empty($webserviceid)) {
            require_once($CFG->dirroot . '/webservice/lib.php');
            $webserviceobj = new \webservice();
            if ($webservice = $webserviceobj->get_external_service_by_id($webserviceid)) {
                if ($webservice->restrictedusers) {
                    if ($users = $webserviceobj->get_ws_authorised_users($webserviceid)) {
                        if (!array_key_exists($token->userid, $users)) {
                            return false;
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * Check for user can create token
     *
     * @param array $webservice
     * @param int $userid
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function can_create_token(array $webservice, int $userid): bool {
        global $CFG;
        $isofficialmobilewebservice = $webservice['shortname'] == MOODLE_OFFICIAL_MOBILE_SERVICE;
        $context = \context_system::instance();
        if (
            ($isofficialmobilewebservice && has_capability('moodle/webservice:createmobiletoken', $context)) ||
            (!is_siteadmin($userid) && has_capability('moodle/webservice:createtoken', $context)) || is_siteadmin($userid)
        ) {
            if (!empty($webservice['restrictedusers'])) {
                require_once($CFG->dirroot . '/webservice/lib.php');
                $webserviceobj = new \webservice();
                $restricteduser = $webserviceobj->get_ws_authorised_user($webservice['id'], $userid);
                if (empty($restricteduser)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Get webservice/user tokens
     *
     * @param int|null $webserviceid Webservice id
     * @param int|null $userid User id
     * @param bool $single
     * @param bool $onlyok Param to check for only passed tokens
     * @return stdClass|array|bool|null
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function get_tokens(?int $webserviceid = null, ?int $userid = null, bool $single = false,
        bool $onlyok = true): \stdClass|array|bool|null {
        global $DB, $USER;
        $ret = [];
        $args = ['tokentype' => EXTERNAL_TOKEN_PERMANENT];
        $pass = $canview = false;
        if ($webserviceid) {
            $pass = true;
            $args['externalserviceid'] = $webserviceid;
            if ($userid) {
                $canview = true;
                $args['creatorid'] = $userid;
            } else {
                $canview = has_capability('moodle/webservice:managealltokens', \context_system::instance());
            }
        } else {
            if (!$userid) {
                if (!empty($USER->id)) {
                    $userid = $USER->id;
                }
            }
            if ($userid) {
                $pass = true;
                $canview = true;
                $args['creatorid'] = $userid;
            }
        }
        if ($pass) {
            if (!$single) {
                if ($tokens = $DB->get_records('external_tokens', $args, 'timecreated ASC')) {
                    foreach ($tokens as $token) {
                        if ($canview) {
                            if ((self::token_check($token, $webserviceid) && $onlyok) || !$onlyok) {
                                $ret[$token->id] = $token;
                            }
                        }
                    }
                }
            } else {
                return $DB->get_record('external_tokens', $args);
            }
        }
        return $ret;
    }

    /**
     * Get tokens select output by webservice id
     *
     * @param int $webserviceid Webservice id
     * @return string
     */
    public static function tokens_by_webserviceid_output(int $webserviceid): string {
        global $OUTPUT;
        return $OUTPUT->render_from_template('core_admin/setting_configselect', [
            'options' => call_user_func(function() use ($webserviceid) {
                $ret = [];
                if ($tokens = self::get_tokens($webserviceid)) {
                    foreach ($tokens as $token) {
                        $ret[] = ['name' => $token->token, 'value' => $token->id];
                    }
                }
                return $ret;
            }),
        ]);
    }

    /**
     * Get webservice user token
     *
     * @param int $wsid Webservice id
     * @param int|null $userid
     * @return stdClass|bool|null
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function get_webservice_user_token(int $wsid, ?int $userid = null): \stdClass|bool|null {
        global $USER;
        if (empty($userid)) {
            if (!empty($USER->id)) {
                $userid = $USER->id;
            }
        }
        return self::get_tokens($wsid, $userid, true);
    }

    /**
     * Create new webservice token
     *
     * @param array $webservice Webservice data array from database
     * @param int|null $userid User id
     * @param array|null $data Token input data
     * @return array
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function create_webservice_token(array $webservice, ?int $userid = null, ?array $data = null): array {
        global $DB, $USER;
        $ret = [
            'id' => 0,
            'errors' => [],
        ];
        if (!$userid) {
            $userid = $USER->id;
        }
        $context = \context_system::instance();
        if (self::can_create_token($webservice, $userid)) {
            $newtoken = new \stdClass();
            $newtoken->token = md5(uniqid(rand(), 1));
            $newtoken->privatetoken = random_string(64);
            $newtoken->tokentype = EXTERNAL_TOKEN_PERMANENT;
            $newtoken->userid = $userid;
            $newtoken->externalserviceid = $webservice['id'];
            $newtoken->timecreated = time();
            $newtoken->contextid = !empty($data['contextid']) ? $data['contextid'] : $context->id;
            if (!empty($data['creatorid'])) {
                $newtoken->creatorid = $data['creatorid'];
            } else {
                if ($USER) {
                    $newtoken->creatorid = $USER->id;
                } else {
                    $newtoken->creatorid = $userid;
                }
            }
            if (!empty($data['sid'])) {
                $newtoken->sid = $data['sid'];
            }
            if (!empty($data['iprestriction'])) {
                $newtoken->iprestriction = $data['iprestriction'];
            }
            if (!empty($data['validuntil'])) {
                $newtoken->validuntil = intval($data['validuntil']);
            }
            $params = [
                'objectid' => $newtoken->id,
                'relateduserid' => $USER->id,
                'other' => [
                    'auto' => true,
                ],
            ];
            $event = \core\event\webservice_token_created::create($params);
            $event->add_record_snapshot('external_tokens', $newtoken);
            $event->trigger();

            $ret['id'] = intval($DB->insert_record('external_tokens', $newtoken));
        } else {
            $ret['errors'][] = get_string('accessdenied', 'admin');
        }
        return $ret;
    }

    /**
     * Create test webservice
     *
     * @return bool
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function webservice_test_create(): bool {
        global $CFG, $USER;
        if (!self::get_general_errors()) {
            require_once($CFG->dirroot . '/webservice/lib.php');
            $userid = $USER->id;
            $webserviceid = 0;
            $webserviceobj = new \webservice();
            $webservice = $webserviceobj->get_external_service_by_shortname(self::WS_TEST_SHORTNAME);
            if (!$webservice) {
                $webservicecreated = self::create_webservice([
                    'name' => get_string('webservice_test', 'local_wsmanager'),
                    'requiredcapability' => '',
                    'restrictedusers' => 0,
                    'shortname' => self::WS_TEST_SHORTNAME,
                ], $webserviceobj);
                if (!empty($webservicecreated['webservice']['id'])) {
                    $webservice = $webservicecreated['webservice'];
                    $webserviceid = $webservice['id'];
                }
            } else {
                $webserviceid = $webservice->id;
            }
            if ($webserviceid) {
                $addfunction = false;
                if (!self::get_webservice_user_token($webserviceid, $userid)) {
                    $token = self::create_webservice_token((array) $webservice);
                    if (!empty($token['id'])) {
                        $addfunction = true;
                    }
                } else {
                    $addfunction = true;
                }
                if ($addfunction) {
                    if (!$webserviceobj->service_function_exists(self::WS_TEST_FUNCTION, $webserviceid)) {
                        $webserviceobj->add_external_function_to_service(self::WS_TEST_FUNCTION, $webserviceid);
                    }
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Webservice function param description output
     *
     * @param array $param
     * @return string
     */
    public static function webservice_function_param_desc(array $param): string {
        global $OUTPUT;
        $context = [
            'desc' => '',
            'list' => [],
            'listfilled' => false,
        ];
        if (!empty($param['desc'])) {
            $context['desc'] = $param['desc'];
        }
        if (!empty($param['required'])) {
            $context['list']['required'] = true;
        }
        if (!empty($param['default'])) {
            $context['list']['default'] = $param['default'];
        }
        if (!empty($param['type'])) {
            $context['list']['type'] = $param['type'];
        }
        if (!empty($param['allownull'])) {
            $context['list']['allownull'] = true;
        }
        if (!empty($context['list'])) {
            $context['listfilled'] = true;
        }
        return $OUTPUT->render_from_template('local_wsmanager/param_desc', $context);
    }

    /**
     * Unique webservice function param id
     *
     * @param int $webserviceid
     * @param string $functionname
     * @param string $param
     * @return string
     */
    public static function webservice_function_param_id(int $webserviceid, string $functionname, string $param): string {
        return 'local_wsmanager_ws_fn_param_' . $webserviceid . '_' . $functionname . '_' . $param;
    }

    /**
     * Webservice function param form output (input, textarea, checkbox etc.)
     *
     * @param array $paramdata Function data of param
     * @param int $webserviceid
     * @param string $functionname
     * @param string $paramkey Function param key
     * @param int $rows Number of rows if textarea
     * @return string
     * @throws coding_exception
     */
    public static function webservice_function_param_value(
        array $paramdata,
        int $webserviceid,
        string $functionname,
        string $paramkey,
        int $rows = 2
    ): string {
        global $OUTPUT;
        if (!empty($paramdata['type'])) {
            $name = self::webservice_function_param_id($webserviceid, $functionname, $paramkey);
            $context = [
                'id' => $name,
                'name' => $name,
                'value' => !empty($paramdata['default']) ? $paramdata['default'] : '',
            ];
            switch ($paramdata['type']) {
                case PARAM_BOOL:
                    if (empty($paramdata['required'])) {
                        $context['options'][] = ['name' => get_string('choosedots'), 'value' => ''];
                    }
                    $context['options'][] = ['name' => get_string('yes'), 'value' => 1];
                    $context['options'][] = ['name' => get_string('no'), 'value' => 0];
                    return \html_writer::div($OUTPUT->render_from_template('core_admin/setting_configselect', $context),
                        'local_wsmanager_webservice_function_param_value ' .
                        'local_wsmanager_webservice_function_param_value_' . $webserviceid . ' ' .
                        'local_wsmanager_webservice_function_param_value_' . $webserviceid . '_' . $functionname . ' ' .
                        'local_wsmanager_webservice_function_param_value_' . $webserviceid . '_' . $functionname . '_' . $paramkey,
                        [
                            'data-webserviceid' => $webserviceid,
                            'data-functionname' => $functionname,
                        ]);
                default:
                    $context['rows'] = $rows;
                    switch ($paramdata['type']) {
                        case PARAM_INT:
                            if (empty($context['value'])) {
                                $context['value'] = '0';
                            }
                            break;
                    }
                    $ret = $OUTPUT->render_from_template('core_admin/setting_configtextarea', $context);
                    return \html_writer::div($ret,
                        'local_wsmanager_webservice_function_param_value ' .
                        'local_wsmanager_webservice_function_param_value_' . $webserviceid . ' ' .
                        'local_wsmanager_webservice_function_param_value_' . $webserviceid . '_' . $functionname . ' ' .
                        'local_wsmanager_webservice_function_param_value_' . $webserviceid . '_' . $functionname . '_' . $paramkey,
                        [
                            'data-webserviceid' => $webserviceid,
                            'data-functionname' => $functionname,
                        ]);
            }
        }
        return '';
    }

    /**
     * Get webservice DB object
     *
     * @param string|int $webservice Webservice ID/shortname
     * @return stdClass|bool|null
     * @throws dml_exception
     */
    public static function get_webservice(string|int $webservice): \stdClass|bool|null {
        global $DB;
        $ws = null;
        if (is_string($webservice)) {
            $ws = $DB->get_record('external_services', ['shortname' => $webservice]);
        } else {
            if (is_int($webservice)) {
                $ws = $DB->get_record('external_services', ['id' => $webservice]);
            }
        }
        return $ws;
    }

    /**
     * Handle given function params data
     *
     * @param string|array|null $params
     * @return array
     * @throws coding_exception
     */
    public static function handle_params(string|array|null $params): array {
        if (is_string($params)) {
            $ret = [];
            if ($pieces = explode(self::SEPARATOR_4, $params)) {
                foreach ($pieces as $piece) {
                    if (!empty($piece)) {
                        if ($valuearr = explode(self::SEPARATOR_1, $piece)) {
                            $webserviceid = intval($valuearr[0]);
                            $functionname = trim($valuearr[1]);
                            $param = trim($valuearr[2]);
                            $value = trim($valuearr[3]);
                            $ret[$webserviceid][$functionname][$param] = self::function_params_data_handle($functionname, $value,
                                $param);
                        }
                    }
                }
            }
            return $ret;
        } else {
            if (is_array($params)) {
                return $params;
            }
        }
        return [];
    }

    public static function get_function_param_data(\core_external\external_value $paramdata, $value = null): array {
        return [
            'desc' => $paramdata->desc,
            'required' => $paramdata->required,
            'default' => $paramdata->default ?: '',
            'type' => $paramdata->type,
            'allownull' => $paramdata->allownull,
            'value' => !is_null($value) ? $value : $paramdata->default,
        ];
    }

    /**
     * Creating array with dynamic keys array and given value
     *
     * @param $mainarray
     * @param $keys
     * @param $value
     * @return mixed
     * @author 2017 B. Desai <https://stackoverflow.com/users/7450125/b-desai>
     * @link https://stackoverflow.com/a/44949080
     */
    public static function add_keys_dynamic($mainarray, $keys, $value) {
        $tmparray = &$mainarray;
        while (count($keys) > 0) {
            $k = array_shift($keys);
            if (!is_array($tmparray)) {
                $tmparray = [];
            }
            $tmparray = &$tmparray[$k];
        }
        $tmparray = $value;
        return $mainarray;
    }

    /**
     * Recursive function to collect function params data
     *
     * @param $paramdata
     * @param string $key
     * @param array $keys
     * @param array $ret
     * @param int $i
     * @param null $value
     * @param string|null $parent
     * @return void
     */
    public static function function_params_data_recursive($paramdata, string $key, array &$keys, array &$ret, int &$i,
        $value = null, ?string &$parent = null): void {
        if ($paramdata instanceof \core_external\external_multiple_structure) {
            $parent = $paramdata::class;
            if (!empty($paramdata->content)) {
                if (!empty($keys)) {
                    $keys = array_values($keys);
                    unset($keys[count($keys) - 1]);
                }
                $keys[] = $key;
                $keys[] = $i;
                if (empty($i)) {
                    $i++;
                }
                self::function_params_data_recursive($paramdata->content, $key, $keys, $ret, $i, $value, $parent);
            }
        }
        if ($paramdata instanceof \core_external\external_single_structure) {
            if (!in_array($parent, ['core_external\external_multiple_structure']) || empty($parent)) {
                $keys[] = $key;
            }
            $parent = $paramdata::class;
            if (!empty($paramdata->keys)) {
                foreach ($paramdata->keys as $k => $v) {
                    $keys[] = $k;
                    self::function_params_data_recursive($v, $k, $keys, $ret, $i, $value, $parent);
                }
            }
        }
        if ($paramdata instanceof \core_external\external_value) {
            if (!empty($keys)) {
                $keys = array_values($keys);
                if ($parent == 'core_external\external_multiple_structure') {
                    $lastkeyindex = count($keys) - 1;
                    $lastkey = $keys[$lastkeyindex];
                    if (is_int($lastkey)) {
                        unset($keys[$lastkeyindex]);
                    }
                    for ($ii = 0; $ii < self::MULTIPLE_STRUCTURE_COUNT; $ii++) {
                        $keys[] = $ii;
                        $ret = self::add_keys_dynamic($ret, $keys, json_encode(self::get_function_param_data($paramdata, $value)));
                        $keys = array_values($keys);
                        unset($keys[count($keys) - 1]);
                    }
                    $keys = array_values($keys);
                    if (!empty($keys)) {
                        unset($keys[count($keys) - 1]);
                    }
                } else {
                    $ret = self::add_keys_dynamic($ret, $keys, json_encode(self::get_function_param_data($paramdata, $value)));
                    if (!empty($keys)) {
                        unset($keys[count($keys) - 1]);
                    }
                }
            } else {
                $ret[$key] = json_encode(self::get_function_param_data($paramdata, $value));
            }
        }
    }

    /**
     * Get function parameters data
     *
     * @param string $functionname Function name
     * @param null $value
     * @return array
     * @throws coding_exception
     */
    public static function function_params_data(string $functionname, $value = null): array {
        $ret = $keys = [];
        $i = 0;
        $functioninfo = self::function_info($functionname);
        if ($functioninfo) {
            if ($functioninfo->parameters_desc->keys) {
                foreach ($functioninfo->parameters_desc->keys as $paramkey => $paramdata) {
                    self::function_params_data_recursive($paramdata, $paramkey, $keys, $ret, $i, $value);
                    $keys = [];
                    if ($i > 0) {
                        for ($ii = $i; $ii < self::MULTIPLE_STRUCTURE_COUNT; $ii++) {
                            self::function_params_data_recursive($paramdata, $paramkey, $keys, $ret, $ii, $value);
                            $keys = [];
                        }
                    }
                    $i = 0;
                }

            }
        }
        return $ret;
    }

    /**
     * Handle function parameters data to array from JSON values
     *
     * @param string $functionname
     * @param $value
     * @param string|null $onlykey
     * @return array
     * @throws coding_exception
     */
    public static function function_params_data_handle(string $functionname, $value = null, ?string $onlykey = null): array {
        $ret = [];
        if ($data = self::function_params_data($functionname)) {
            $string = urldecode(http_build_query($data, '', self::SEPARATOR_1));
            if ($arr = explode(self::SEPARATOR_1, $string)) {
                foreach ($arr as $item) {
                    $itemarr = explode('={', $item);
                    $key = $itemarr[0];
                    $val = '{' . $itemarr[1];
                    if (self::is_json($val)) {
                        $ret[$key] = json_decode($val, 1);
                        $ret[$key]['value'] = $value;
                    }
                }
                if ($onlykey) {
                    if (isset($ret[$onlykey])) {
                        return $ret[$onlykey];
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * Get array of params values
     *
     * @param array $params
     * @param int $webserviceid
     * @param string $functionname
     * @return array
     */
    public static function function_params_data_values(array $params, int $webserviceid, string $functionname): array {
        $ret = [];
        $params = $params[$webserviceid][$functionname] ?? $params;
        foreach ($params as $key => $value) {
            if (empty($value['value'])) {
                if (!empty($value['default'])) {
                    $ret[$key] = $value['default'];
                } else {
                    if (!empty($value['allownull'])) {
                        switch ($value['type']) {
                            case PARAM_INT:
                                $ret[$key] = '0';
                                break;
                            default:
                                $ret[$key] = '';
                        }
                    }
                }
            } else {
                $ret[$key] = $value['value'];
            }
        }
        return $ret;
    }

    /**
     * Webservice function parameter output
     *
     * @param string $paramkey
     * @param array $paramdata
     * @param int $webserviceid Webservice id
     * @param string $functionname Function name
     * @return array
     * @throws coding_exception
     */
    public static function function_param_output(string $paramkey, array $paramdata, int $webserviceid,
        string $functionname): array {
        global $OUTPUT;
        if (!in_array($paramkey, self::EXCLUDE_PARAMS)) {
            return [
                'name' => \html_writer::tag('code',
                        $paramkey . (!empty($paramdata['required']) ? '*' : '')) . ' ' .
                    $OUTPUT->render_from_template('core/help_icon', [
                        'alt' => $paramdata['desc'],
                        'text' => self::webservice_function_param_desc($paramdata),
                        'ltr' => true,
                    ]),
                'value' => self::webservice_function_param_value($paramdata, $webserviceid, $functionname, $paramkey),
            ];
        }
        return [];
    }

    /**
     * Table of webservice function params
     *
     * @param int $webserviceid Webservice id
     * @param string $functionname
     * @param bool $head Table header
     * @param array|null $params
     * @return string
     * @throws coding_exception
     */
    public static function function_params_output(int $webserviceid, string $functionname, bool $head = true,
        ?array $params = null): string {
        $params = $params ?: self::function_params_data_handle($functionname);
        if ($params) {
            $table = self::make_table($head);
            foreach ($params as $key => $value) {
                $table->data[] = self::function_param_output($key, $value, $webserviceid, $functionname);
            }
            return \html_writer::table($table);
        }
        return '';
    }

    /**
     * Get webservice function request method
     *
     * @param string $functionname
     * @param $functioninfo
     * @return string
     * @throws coding_exception
     */
    public static function function_method(string $functionname, $functioninfo = null): string {
        $functioninfo = $functioninfo ?: self::function_info($functionname);
        $functionmethod = self::get_request_method_by_function_type($functioninfo->type);
        return $functionmethod;
    }

    /**
     * Check and output for response error
     *
     * @param string $response
     * @return string|null
     * @throws coding_exception
     */
    public static function response_error(string $response): ?string {
        if (self::is_json($response)) {
            $response = json_decode($response, 1);
            if (is_array($response)) {
                if (!empty($response['exception']) && !empty($response['errorcode'])) {
                    $table = self::make_table(true, '20%', '80%');
                    $table->data = [
                        [
                            'name' => 'exception',
                            'value' => \html_writer::tag('code', $response['exception']),
                        ],
                        [
                            'name' => 'errorcode',
                            'value' => \html_writer::tag('code', $response['errorcode']),
                        ],
                        [
                            'name' => get_string('message', 'message'),
                            'value' => \html_writer::tag('em', $response['message']),
                        ],
                    ];
                    return \html_writer::table($table);
                }
            }
        }
        return null;
    }

    /**
     * Handle webservice function
     *
     * @param int $webserviceid
     * @param string $functionname Function name
     * @param string $token Webservice token
     * @param string|array|null $params
     * @param string $method
     * @param string $protocol
     * @param string $restformat
     * @return bool|string|null
     * @throws coding_exception
     */
    public static function external_function_handle(int $webserviceid, string $functionname, string $token,
        string|array|null $params, string $method = self::METHOD_GET, string $protocol = self::PROTOCOL_DEFAULT,
        string $restformat = self::DEFAULT_REST_RESPONSE_FORMAT) {
        global $CFG;
        require_once($CFG->libdir . '/filelib.php');
        $requestdata = self::request_data($webserviceid, $functionname, $token, self::handle_params($params), $protocol,
            $restformat);
        $curl = new \curl();
        $curl->resetHeader();
        $response = '';
        switch (\core_text::strtolower($method)) {
            case self::METHOD_GET:
                $response = $curl->get($requestdata['endpoint'], $requestdata['params']);
                break;
            case self::METHOD_POST:
                $response = $curl->post($requestdata['endpoint'], http_build_query($requestdata['params'], '', '&'));
                break;
        }
        if ($error = self::response_error($response)) {
            return $error;
        }
        return $response;
    }

    public static function external_function_test_handle($data) {
        return $data;
    }

    /**
     * Function info table
     *
     * @param string $functionname Function name
     * @param int $webserviceid Webservice db id
     * @return string
     * @throws coding_exception
     * @throws dml_exception
     */
    public static function external_function_handle_output(string $functionname, int $webserviceid) {
        global $CFG, $OUTPUT;
        require_once($CFG->dirroot . '/webservice/lib.php');
        $ret = '';
        $functioninfo = self::function_info($functionname);
        $table = self::make_table();
        $webserviceobj = new \webservice();
        $webservice = $webserviceobj->get_external_service_by_id($webserviceid);
        $protocols = self::get_active_webservices_protocols();
        $restformatrow = new \html_table_row([
            'name' => get_string('format'),
            'value' => \html_writer::div($OUTPUT->render_from_template('core_admin/setting_configselect', [
                'options' => [
                    [
                        'name' => \core_text::strtoupper(self::FORMAT_JSON),
                        'value' => self::FORMAT_JSON,
                        'selected' => self::FORMAT_JSON == self::DEFAULT_REST_RESPONSE_FORMAT,
                    ],
                    [
                        'name' => \core_text::strtoupper(self::FORMAT_XML),
                        'value' => self::FORMAT_XML,
                        'selected' => self::FORMAT_XML == self::DEFAULT_REST_RESPONSE_FORMAT,
                    ],
                ],
            ]), 'local_wsmanager_external_function_handle_select', [
                'id' => 'local_wsmanager_external_function_handle_restformat_' . $webserviceid . '_' . $functionname,
                'data-webserviceid' => $webserviceid,
                'data-functionname' => $functionname,
                'data-instance' => 'restformat',
            ]),
        ]);
        $restformatrow->id = 'local_wsmanager_external_function_handle_restformat_row_' . $webserviceid . '_' . $functionname;
        if (array_keys($protocols)[0] != self::PROTOCOL_REST) {
            $restformatrow->style = 'display:none';
        }
        $method = self::get_request_method_by_function_type($functioninfo->type);
        $table->data = [
            [
                'name' => get_string('webservice', 'webservice'),
                'value' => \html_writer::tag('strong', $webservice->name),
            ],
            [
                'name' => get_string('function', 'webservice'),
                'value' => \html_writer::tag('strong', $functionname),
            ],
            [
                'name' => get_string('description'),
                'value' => \html_writer::tag('em', $functioninfo->description),
            ],
            [
                'name' => get_string('type', 'mnet'),
                'value' => \html_writer::tag('code', $functioninfo->type),
            ],
            [
                'name' => get_string('method', 'mnet'),
                'value' => \html_writer::div($OUTPUT->render_from_template('core_admin/setting_configselect', [
                        'options' => [
                            [
                                'name' => \core_text::strtoupper(self::METHOD_GET),
                                'value' => self::METHOD_GET,
                                'selected' => $method == self::METHOD_GET,
                            ],
                            [
                                'name' => \core_text::strtoupper(self::METHOD_POST),
                                'value' => self::METHOD_POST,
                                'selected' => $method == self::METHOD_POST,
                            ],
                        ],
                    ]) . get_string('recommended') . ': ' . \html_writer::tag('code', \core_text::strtoupper($method)),
                    'local_wsmanager_external_function_handle_select', [
                        'id' => 'local_wsmanager_external_function_handle_method_' . $webserviceid . '_' . $functionname,
                        'data-webserviceid' => $webserviceid,
                        'data-functionname' => $functionname,
                        'data-instance' => 'method',
                    ]),
            ],
            [
                'name' => get_string('protocol', 'local_wsmanager'),
                'value' => \html_writer::div($OUTPUT->render_from_template('core_admin/setting_configselect', [
                    'data-functionname' => $functionname,
                    'options' => call_user_func(function() use ($protocols) {
                        $ret = [];
                        if ($protocols) {
                            foreach ($protocols as $protocol => $protocoltitle) {
                                $ret[] = ['name' => $protocoltitle, 'value' => $protocol];
                            }
                        }
                        return $ret;
                    }),
                ]), 'local_wsmanager_external_function_handle_select', [
                    'id' => 'local_wsmanager_external_function_handle_protocol_' . $webserviceid . '_' . $functionname,
                    'data-webserviceid' => $webserviceid,
                    'data-functionname' => $functionname,
                    'data-instance' => 'protocol',
                ]),
            ],
            $restformatrow,
            [
                'name' => get_string('token', 'webservice'),
                'value' => \html_writer::div(self::tokens_by_webserviceid_output($webserviceid),
                    'local_wsmanager_external_function_handle_select', [
                        'id' => 'local_wsmanager_external_function_handle_token_' . $webserviceid . '_' . $functionname,
                        'data-webserviceid' => $webserviceid,
                        'data-functionname' => $functionname,
                        'data-instance' => 'token',
                    ]),
            ],
        ];
        $ret .= \html_writer::table($table);
        $params = self::function_params_data_handle($functionname);
        $ret .= self::function_params_output($webserviceid, $functionname, false, $params);
        $tokens = self::get_tokens($webserviceid);
        $ret .= \html_writer::div(
            join(
                '<br />',
                self::request_info(
                    $webserviceid,
                    $functionname,
                    $params,
                    self::get_request_method_by_function_type($functioninfo->type),
                    array_keys(self::get_active_webservices_protocols())[0],
                    self::DEFAULT_REST_RESPONSE_FORMAT,
                    $tokens ? array_values($tokens)[0]->token : ''
                )
            ),
            'local_wsmanager_bordered', [
            'id' => 'local_wsmanager_request_info_' . $webserviceid . '_' . $functionname,
        ]);
        $ret .= \html_writer::div(\html_writer::div(\html_writer::div(\html_writer::tag('strong',
                get_string('response', 'webservice')) . ':<br />' . \html_writer::div('', '',
                ['id' => 'local_wsmanager_external_function_handle_response_content_' . $webserviceid . '_' . $functionname]),
            'local_wsmanager_bordered'), '', [
            'id' => 'local_wsmanager_external_function_handle_response_' . $webserviceid . '_' . $functionname,
            'style' => 'display:none',
        ]), '', [
            'style' => 'min-height:70px',
        ]);
        $ret .= \html_writer::div(\html_writer::link('javascript:;', get_string('execute', 'webservice'),
            [
                'class' => 'btn btn-primary btn-block local_wsmanager_external_function_handle',
                'data-webserviceid' => $webserviceid,
                'data-functionname' => $functionname,
            ]), 'd-grid gap-2');

        return $ret;
    }
}
