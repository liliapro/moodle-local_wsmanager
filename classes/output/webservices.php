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
 * Class to output webservices manager.
 *
 * @package    local_wsmanager
 * @category   output
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_wsmanager\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/webservice/lib.php');
require_once($CFG->dirroot . '/local/wsmanager/classes/webservice_form.php');
require_once($CFG->dirroot . '/local/wsmanager/classes/output/tokens.php');
require_once($CFG->dirroot . '/local/wsmanager/classes/output/users.php');
require_once($CFG->dirroot . '/local/wsmanager/classes/output/functions.php');

class webservices implements \renderable, \templatable {
    protected $webserviceobj = null;

    public function __construct() {
        $this->webserviceobj = new \webservice();
    }

    public static function nav_title(\stdClass $webservice): string {
        return $webservice->name . (!$webservice->enabled ? ' [<em style="color:red">' .
                get_string('off', 'mnet') . '</em>]' : '');
    }

    public function webservice_data(\stdClass $webservice = null, ?array $tokens = null) {
        global $CFG, $OUTPUT;
        $ret = \html_writer::tag('h1', $webservice->name);
        $table = \local_wsmanager::make_table();
        $rowstatus = new \html_table_row();
        $rowstatus->id = 'local_wsmanager_webservice_row_' . $webservice->id;
        if (!$webservice->enabled) {
            $rowstatus->attributes['class'] = 'local_wsmanager_webservice_row_danger';
        }
        $rowstatus->cells = [
            'name' => get_string('enabled', 'webservice'),
            'value' => $OUTPUT->render_from_template('core/toggle', [
                'id' => 'local_wsmanager_switch_webservice_state_' . $webservice->id,
                'checked' => $webservice->enabled,
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
                        'value' => $webservice->id,
                    ],
                ],
                'labelclasses' => 'sr-only',
            ]),
        ];
        $rownamecellvalue = new \html_table_cell();
        if (!\local_wsmanager::is_internal($webservice)) {
            $rownamecellvaluetext = $OUTPUT->render_from_template('core_admin/setting_configtext', [
                    'id' => 'local_wsmanager_webservice_name_' . $webservice->id,
                    'name' => '',
                    'value' => $webservice->name,
                    'readonly' => !$webservice->enabled,
                ]) . \html_writer::link('javascript:;', get_string('savechanges'), [
                    'class' => 'btn btn-primary btn-block local_wsmanager_webservice_name_update',
                    'style' => 'margin-top:5px',
                    'data-webserviceid' => $webservice->id,
                ]);
        } else {
            $rownamecellvaluetext = \html_writer::tag('strong', $webservice->name);
        }
        $rownamecellvalue->text = $rownamecellvaluetext;
        $table->data = [
            $rowstatus,
            [
                'name' => get_string('name'),
                'value' => $rownamecellvalue,
            ],
            [
                'name' => get_string('shortname'),
                'value' => $webservice->shortname,
            ],
            [
                'name' => get_string('restrictedusers', 'webservice') . ' ' .
                    $OUTPUT->help_icon('restrictedusers', 'webservice'),
                'value' => $OUTPUT->render_from_template('core/toggle', [
                    'id' => 'local_wsmanager_webservice_restrictedusers_' . $webservice->id,
                    'checked' => $webservice->restrictedusers,
                    'label' => get_string('enable') . ' / ' . get_string('disable'),
                    'dataattributes' => [
                        [
                            'name' => 'switch',
                            'value' => 'webservice',
                        ],
                        [
                            'name' => 'name',
                            'value' => get_string('restrictedusers', 'webservice'),
                        ],
                        [
                            'name' => 'instance',
                            'value' => 'restrictedusers',
                        ],
                        [
                            'name' => 'webserviceid',
                            'value' => $webservice->id,
                        ],
                    ],
                    'labelclasses' => 'sr-only',
                    'disabled' => \local_wsmanager::is_internal($webservice),
                ]),
            ],
            [
                'name' => get_string('downloadfiles', 'webservice') . ' ' .
                    $OUTPUT->help_icon('downloadfiles', 'webservice'),
                'value' => $OUTPUT->render_from_template('core/toggle', [
                    'id' => 'local_wsmanager_webservice_downloadfiles_' . $webservice->id,
                    'checked' => $webservice->restrictedusers,
                    'label' => get_string('enable') . ' / ' . get_string('disable'),
                    'dataattributes' => [
                        [
                            'name' => 'switch',
                            'value' => 'webservice',
                        ],
                        [
                            'name' => 'name',
                            'value' => get_string('downloadfiles', 'webservice'),
                        ],
                        [
                            'name' => 'instance',
                            'value' => 'downloadfiles',
                        ],
                        [
                            'name' => 'webserviceid',
                            'value' => $webservice->id,
                        ],
                    ],
                    'labelclasses' => 'sr-only',
                ]),
            ],
            [
                'name' => get_string('uploadfiles', 'webservice') . ' ' .
                    $OUTPUT->help_icon('uploadfiles', 'webservice'),
                'value' => $OUTPUT->render_from_template('core/toggle', [
                    'id' => 'local_wsmanager_webservice_uploadfiles_' . $webservice->id,
                    'checked' => $webservice->uploadfiles,
                    'label' => get_string('enable') . ' / ' . get_string('disable'),
                    'dataattributes' => [
                        [
                            'name' => 'switch',
                            'value' => 'webservice',
                        ],
                        [
                            'name' => 'name',
                            'value' => get_string('uploadfiles', 'webservice'),
                        ],
                        [
                            'name' => 'instance',
                            'value' => 'uploadfiles',
                        ],
                        [
                            'name' => 'webserviceid',
                            'value' => $webservice->id,
                        ],
                    ],
                    'labelclasses' => 'sr-only',
                ]),
            ],
            [
                'name' => get_string('timeadded', 'data'),
                'value' => userdate($webservice->timecreated, '%d %b %Y %R'),
            ],
            [
                'name' => '',
                'value' => \html_writer::link(new \moodle_url('/' . $CFG->admin . '/webservice/service.php',
                    [
                        'id' => $webservice->id,
                    ]), get_string('edit'),
                    [
                        'title' => get_string('edit'),
                    ]
                ),
            ],
        ];
        if (empty($webservice->component)) {
            $table->data[] = [
                'name' => get_string('delete'),
                'value' => \html_writer::link('javascript:;',
                    get_string('delete'),
                    [
                        'class' => 'btn btn-danger btn-sm local_wsmanager_webservice_delete',
                        'data-webserviceid' => $webservice->id,
                        'data-name' => $webservice->name,
                        'title' => get_string('delete'),
                    ]
                ),
            ];
        }
        $ret .= \html_writer::tag('h4', get_string('settings'));
        $ret .= \html_writer::table($table);
        $ret .= \html_writer::empty_tag('hr');

        $ret .= \html_writer::tag('h4', get_string('managetokens', 'webservice'));
        $renderable = new \local_wsmanager\output\tokens($this->webserviceobj, $webservice, $tokens);
        $ret .= $OUTPUT->render($renderable);

        $renderable = new \local_wsmanager\output\users($this->webserviceobj, $webservice);
        $ret .= \html_writer::div($OUTPUT->render($renderable), 'local_wsmanager_webservice_users_table',
            ['id' => 'local_wsmanager_webservice_users_table_' . $webservice->id]);

        $ret .= \html_writer::tag('h4', get_string('functions', 'webservice'));
        $renderable = new \local_wsmanager\output\functions($webservice);
        $ret .= $OUTPUT->render($renderable);

        return $ret;
    }

    public function export_for_template(\renderer_base $output): array {
        global $CFG;
        $mform = new \local_wsmanager\webservice_form(null);
        if ($mform->is_submitted()) {
            $ajax = ['success' => false, 'errors' => []];
            if (!$data = $mform->get_data()) {
                $ajax['errors'] = $mform->validation($data, null);
            } else {
                $ajax['success'] = true;
            }
            echo json_encode($ajax);
            die;
        }
        $context = [
            'admin' => $CFG->admin,
            'create_webservice_form' => $mform->render(),
            'errors' => \local_wsmanager::get_general_errors(),
            'info' => \local_wsmanager::webservice_dashboard_info_output(false),
        ];
        $webservices = \local_wsmanager::get_services();
        if ($servicesbytype = \local_wsmanager::get_services_by_type($webservices)) {
            $arr = [
                'internal' => 0,
                'external' => 1,
            ];
            $i = 0;
            foreach ($servicesbytype as $type => $services) {
                switch ($type) {
                    case 'internal':
                        $context['services'][$arr[$type]]['title'] = get_string('servicesbuiltin', 'webservice');
                        break;
                    case 'external':
                        $context['services'][$arr[$type]]['title'] = get_string('servicescustom', 'webservice');
                        break;
                }
                $ii = 0;
                foreach ($services as $webservice) {
                    $ctx = [
                        'webserviceid' => $webservice->id,
                        'name' => $webservice->name,
                        'active' => $i === 0,
                        'enabled' => $webservice->enabled,
                        'internal' => \local_wsmanager::is_internal($webservice),
                        'shortname' => $webservice->shortname,
                        'restrictedusers' => $webservice->restrictedusers,
                        'text' => self::nav_title($webservice),
                    ];
                    $tokens = \local_wsmanager::get_tokens($webservice->id);
                    $context['services'][$arr[$type]]['items'][$ii] = $ctx;

                    $context['services'][$arr[$type]]['output'][$ii] = $ctx;
                    $context['services'][$arr[$type]]['output'][$ii]['settings'] = $this->webservice_data($webservice, $tokens);
                    $ii++;
                    $i++;
                }
            }
        }
        return $context;
    }

    public function get_template_name() {
        return 'local_wsmanager/webservices';
    }
}
