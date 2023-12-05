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
 * Class to output webservices dashboard.
 *
 * @package    local_wsmanager
 * @category   output
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_wsmanager\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/webservice/lib.php');
require_once($CFG->dirroot . '/local/wsmanager/locallib.php');

use core_external\external_api;
use local_wsmanager\check\result;

class dashboard implements \renderable, \templatable {
    protected function overview(\renderer_base $output): string {
        global $CFG;
        $table = new \html_table();
        $table->head = [
            'status' => get_string('status'),
            'summary' => get_string('summary'),
            'enable' => get_string('enable'),
        ];
        $table->size = [
            'status' => '30%',
            'summary' => '40%',
            'enable' => '30%',
        ];

        $rows = [
            'enablewebservices' => [
                'status' => $output->render_from_template('local_wsmanager/check_webservices', [
                    'enablewebservices' => $CFG->enablewebservices,
                    'ok_str' => $output->check_result(new result(result::ENABLED, null)),
                    'error_str' => $output->check_result(new result(result::DISABLED, null))
                        . '&nbsp;' . $output->help_icon('errorwsdisabled', 'local_wsmanager'),
                ]),
                'desc' => get_string('configenablewebservices', 'admin') . '<br />' .
                    \html_writer::tag('small', \html_writer::link(new \moodle_url('/' .
                        $CFG->admin . '/settings.php', ['section' => 'optionalsubsystems'],
                        'admin-enablewebservices'), get_string('settings'),
                        ['title' => get_string('settings')])),
                'enable' => $output->render_from_template('core/toggle', [
                    'id' => 'local_wsmanager_switch_webservice_state',
                    'checked' => $CFG->enablewebservices,
                    'label' => get_string('enable') . ' / ' . get_string('disable'),
                    'labelclasses' => 'sr-only',
                ]),
            ],
        ];
        $protocols = \local_wsmanager::get_webservices_protocols();
        $protocolsactive = \local_wsmanager::get_active_webservices_protocols($protocols);
        if ($protocols) {
            foreach ($protocols as $protocol => $protocoldata) {
                $data = [
                    'status' => \html_writer::div(\local_wsmanager::webservice_protocol_status_output($protocol,
                        $protocolsactive), null, ['id' => 'local_wsmanager_protocol_status_' . $protocol]),
                    'desc' => $protocoldata['title'] . '&nbsp;' .
                        $output->help_icon($protocol . 'protocol', 'local_wsmanager') .
                        ($protocol == \local_wsmanager::PROTOCOL_DEFAULT ? '<br />' . \html_writer::tag('em',
                                get_string('recommended')) : '') . '<br />' .
                        \html_writer::tag('small', \html_writer::link(new \moodle_url('/' .
                            $CFG->admin . '/settings.php', ['section' => 'webserviceprotocols']),
                            get_string('settings'), ['title' => get_string('settings')])),
                    'enable' => \html_writer::div($output->render_from_template('core/toggle', [
                        'id' => 'local_wsmanager_protocol_switch_' . $protocol,
                        'checked' => webservice_protocol_is_enabled($protocol),
                        'label' => get_string('enable') . ' / ' . get_string('disable'),
                        'labelclasses' => 'sr-only',
                        'disabled' => !$CFG->enablewebservices,
                        'dataattributes' => [
                            [
                                'name' => 'protocol',
                                'value' => $protocol,
                            ],
                        ],
                    ]), 'local_wsmanager_protocols_switch'),
                ];
                $rows[$protocol] = new \html_table_row($data);
                $rows[$protocol]->attributes['class'] = 'local_wsmanager_protocol_check_row';
                $rows[$protocol]->id = 'local_wsmanager_protocol_check_row_' . $protocol;
            }
            $configwebservicepluginscell = new \html_table_cell(
                \html_writer::tag('em', get_string('configwebserviceplugins', 'webservice')));
            $configwebservicepluginscell->colspan = 3;
            $configwebservicepluginscell->style = 'text-align:center';
            $rows['configwebserviceplugins'] = new \html_table_row([$configwebservicepluginscell]);
            $data = [
                'status' => '',
                'desc' => get_string('configenablewsdocumentation', 'admin',
                    \html_writer::link(new \moodle_url(get_docs_url('How_to_get_a_security_key')),
                        get_string('supplyinfo', 'webservice'), [
                            'title' => get_string('supplyinfo', 'webservice'),
                        ])),
                'enable' => $output->render_from_template('core/toggle', [
                    'id' => 'local_wsmanager_switch_wsdocumentation',
                    'checked' => $CFG->enablewsdocumentation,
                    'label' => get_string('enable') . ' / ' . get_string('disable'),
                    'labelclasses' => 'sr-only',
                ]),
            ];
            $rows['enablewsdocumentation'] = new \html_table_row($data);
            $rows['enablewsdocumentation']->id = 'local_wsmanager_enablewsdocumentation';
            if (!$CFG->enablewebservices) {
                $rows['enablewsdocumentation']->style = 'display:none';
            }
        }
        $table->data = $rows;
        return \html_writer::table($table);
    }

    protected function mobile_app(\renderer_base $output): string {
        global $CFG;
        $table = new \html_table();
        $table->head = [
            'status' => get_string('status'),
            'summary' => get_string('summary'),
            'enable' => get_string('enable'),
        ];
        $table->size = [
            'status' => '30%',
            'summary' => '40%',
            'enable' => '30%',
        ];
        $table->data = [
            [
                'status' => $output->render_from_template('local_wsmanager/check_mobileapp', [
                    'enablemobilewebservice' => $CFG->enablemobilewebservice,
                    'ok_str' => $output->check_result(new result(result::ENABLED, null)),
                    'error_str' => $output->check_result(new result(result::DISABLED, null)) .
                        '&nbsp;' . $output->help_icon('errorwsdisabled', 'local_wsmanager'),
                ]),
                'summary' => get_string('configenablemobilewebservice', 'admin',
                        \html_writer::link(new \moodle_url(get_docs_url('Enable_mobile_web_services')),
                            get_string('documentation'), ['title' => get_string('documentation')])) .
                    '<br />' . \html_writer::tag('small', \html_writer::link(new \moodle_url('/' .
                        $CFG->admin . '/category.php', ['category' => 'mobileapp']), get_string('settings'),
                        ['title' => get_string('settings')])),
                'enable' => $output->render_from_template('core/toggle', [
                    'id' => 'local_wsmanager_webservice_mobile_switch',
                    'checked' => $CFG->enablemobilewebservice,
                    'label' => get_string('enablemobilewebservice', 'admin'),
                    'labelclasses' => 'sr-only',
                    'disabled' => !$CFG->enablewebservices,
                ]),
            ],
        ];
        return \html_writer::table($table);
    }

    protected function testing(\renderer_base $output): string {
        global $CFG;
        $table = new \html_table();
        $table->head = [
            'name' => get_string('name'),
            'value' => get_string('value', 'scorm'),
        ];
        $table->size = [
            'name' => '30%',
            'value' => '70%',
        ];
        $table->data = [
            [
                'name' => '',
                'value' => \html_writer::link(new \moodle_url('/' . $CFG->admin . '/webservice/testclient.php'),
                    get_string('testclient', 'webservice'), [
                        'title' => get_string('testclient', 'webservice'),
                    ]
                ),
            ],
        ];
        return \html_writer::table($table);
    }

    public static function test_webservice_function_table1(\html_table $table): string {
        $table->data[] = [
            'name' => '',
            'value' => \html_writer::link('javascript:;',
                get_string('webservice_test_create', 'local_wsmanager'),
                [
                    'class' => 'btn btn-secondary',
                    'id' => 'local_wsmanager_webservice_test_create_button',
                    'title' => get_string('webservice_test_create', 'local_wsmanager'),
                ]
            ),
        ];
        return \html_writer::table($table);
    }

    public static function test_webservice_function_table2(\html_table $table, \stdClass $testwebservice): string {
        global $CFG, $OUTPUT;
        $functioninfo = external_api::external_function_info(\local_wsmanager::WS_TEST_FUNCTION);
        $functionmethod = \local_wsmanager::function_method(\local_wsmanager::WS_TEST_FUNCTION, $functioninfo);
        $rowstatus = new \html_table_row();
        $rowstatus->id = 'local_wsmanager_webservice_row_' . $testwebservice->id;
        if (!$testwebservice->enabled) {
            $rowstatus->attributes['class'] = 'local_wsmanager_webservice_row_danger';
        }
        $rowstatus->cells = [
            'name' => get_string('enabled', 'webservice'),
            'value' => $OUTPUT->render_from_template('core/toggle', [
                'id' => 'local_wsmanager_switch_webservice_state_' . $testwebservice->id,
                'checked' => $testwebservice->enabled,
                'label' => get_string('enable') . ' / ' . get_string('disable'),
                'dataattributes' => [
                    [
                        'name' => 'switch',
                        'value' => 'webservice',
                    ],
                    [
                        'name' => 'name',
                        'value' => get_string('service', 'webservice'),
                    ],
                    [
                        'name' => 'instance',
                        'value' => 'enabled',
                    ],
                    [
                        'name' => 'webserviceid',
                        'value' => $testwebservice->id,
                    ],
                ],
                'labelclasses' => 'sr-only',
            ]),
        ];
        $table->data[] = $rowstatus;
        $table->data[] = [
            'name' => \html_writer::link(new \moodle_url('/' . $CFG->admin . '/webservice/service.php', [
                'id' => $testwebservice->id,
            ]), get_string('webservice', 'webservice'),
                ['title' => get_string('service', 'webservice')]),
            'value' => $testwebservice ? \html_writer::tag('em', $testwebservice->name) : '',
        ];
        $table->data[] = [
            'name' => \html_writer::link(new \moodle_url('/' . $CFG->admin .
                '/webservice/service_functions.php', ['id' => $testwebservice->id]),
                get_string('function', 'webservice'),
                ['title' => get_string('function', 'webservice')]),
            'value' => \html_writer::tag('strong', \local_wsmanager::WS_TEST_FUNCTION),
        ];
        $table->data[] = [
            'name' => get_string('description'),
            'value' => \html_writer::tag('em', $functioninfo->description),
        ];
        $table->data[] = [
            'name' => get_string('delete'),
            'value' => \html_writer::link('javascript:;', get_string('delete'), [
                'id' => 'local_wsmanager_webservice_test_delete',
                'class' => 'btn btn-danger',
                'data-webserviceid' => $testwebservice->id,
                'title' => get_string('delete'),
            ]),
        ];
        $table->data[] = [
            'name' => get_string('type', 'mnet'),
            'value' => \html_writer::tag('code', $functioninfo->type),
        ];
        $table->data[] = [
            'name' => get_string('testing', 'local_wsmanager'),
            'value' => \html_writer::link('javascript:;', get_string('testing', 'local_wsmanager'),
                [
                    'class' => 'btn btn-primary local_wsmanager_external_function_handle_wrapper',
                    'data-functionname' => 'local_wsmanager_external_function_test_handle',
                    'data-webserviceid' => $testwebservice->id,
                    'data-method' => $functionmethod,
                    'title' => get_string('testing', 'local_wsmanager'),
                ]),
        ];
        return \html_writer::table($table);
    }

    public static function webservice_test_dashboard_table_output(): string {
        global $OUTPUT;
        $context = [
            'table1' => '',
            'table2' => '',
        ];
        if (!\local_wsmanager::get_general_errors()) {
            $webservice = new \webservice();
            $testwebservice = $webservice->get_external_service_by_shortname(\local_wsmanager::WS_TEST_SHORTNAME);
            $table = \local_wsmanager::make_table();
            if (!$testwebservice) {
                $context['table1'] = self::test_webservice_function_table1($table);
            } else {
                $context['table2'] = self::test_webservice_function_table2($table, $testwebservice);
            }
        }
        return $OUTPUT->render_from_template('local_wsmanager/test_webservice_function_table', $context);
    }

    protected function documentation(\renderer_base $output): string {
        $table = new \html_table();
        $table->head = [
            'name' => get_string('name'),
            'url' => get_string('url'),
        ];
        $table->size = [
            'name' => '30%',
            'url' => '70%',
        ];
        $data = [
            [
                'name' => get_string('webservices', 'webservice'),
                'url' => 'https://moodledev.io/docs/apis/subsystems/external',
            ],
            [
                'name' => get_string('wsdocumentation', 'webservice'),
                'url' => get_docs_url('Using_web_services'),
            ],
            [
                'name' => get_string('wsdocapi', 'webservice'),
                'url' => 'https://docs.moodle.org/dev/Creating_a_web_service_client',
                'url_title' => get_string('wsclientdoc', 'webservice'),
            ],
            [
                'name' => get_string('functions', 'webservice'),
                'url' => 'https://docs.moodle.org/dev/Web_service_API_functions',
            ],
            [
                'name' => get_string('protocols', 'local_wsmanager'),
                'url' => 'https://docs.moodle.org/dev/Webservice_protocols',
            ],
            [
                'name' => get_string('mobileapp', 'tool_mobile'),
                'url' => get_docs_url('Mobile_web_services'),
            ],
            [
                'name' => get_string('security', 'admin'),
                'url' => 'https://moodledev.io/docs/apis/subsystems/external/security',
            ],
            [
                'name' => 'FAQ',
                'url' => get_docs_url('Web_services_FAQ'),
            ],
            [
                'name' => get_string('forum', 'forum'),
                'url' => 'https://moodle.org/mod/forum/view.php?id=6971',
            ],
        ];
        foreach ($data as $item) {
            $table->data[] = [
                'name' => $item['name'],
                'url' => \html_writer::link((new \moodle_url($item['url']))->out(),
                    ($item['url_title'] ?? $item['name']) . ' ' .
                    $output->pix_icon('i/externallink', get_string('opensinnewwindow'), 'moodle',
                        ['class' => 'fa fa-externallink fa-fw']),
                    [
                        'target' => '_blank',
                        'title' => $item['url_title'] ?? $item['name'],
                    ]),
            ];
        }
        return \html_writer::table($table);
    }

    public function export_for_template(\renderer_base $output): array {
        $generalerrors = \local_wsmanager::get_general_errors();
        return [
            'general_errors' => $generalerrors,
            'dashboard_info' => \local_wsmanager::webservice_dashboard_info_output(),
            'overview_table' => $this->overview($output),
            'mobileappwrapperattrs_style' => $generalerrors ? 'display:none' : '',
            'mobile_info' => \local_wsmanager::webservice_dashboard_mobile_info_output(),
            'mobile_app_table' => $this->mobile_app($output),
            'testingwrapperattrs_style' => \local_wsmanager::get_general_errors() ? 'display:none' : '',
            'testing_table' => $this->testing($output),
            'test_webservice_function_table' => self::webservice_test_dashboard_table_output(),
            'documentation' => $this->documentation($output),
        ];
    }

    public function get_template_name() {
        return 'local_wsmanager/dashboard';
    }
}
