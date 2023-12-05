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
 * Class to output webservice functions.
 *
 * @package    local_wsmanager
 * @category   output
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_wsmanager\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/wsmanager/locallib.php');

class functions implements \renderable, \templatable {
    protected $webservice = null;

    public function __construct(\stdClass $webservice) {
        $this->webservice = $webservice;
    }

    public static function table(\stdClass $webservice): string {
        global $DB;
        $ret = '';
        if ($functions = $DB->get_records('external_services_functions',
            ['externalserviceid' => $webservice->id], 'functionname asc')) {
            $table = new \html_table();
            $table->head = [
                'name' => get_string('function', 'webservice'),
                'description' => get_string('description'),
                'test' => get_string('test', 'local_wsmanager'),
                'delete' => get_string('delete'),
            ];
            $table->size = [
                'name' => '25%',
                'description' => '25%',
                'test' => '20%',
                'delete' => '30%',
            ];
            $tokens = \local_wsmanager::get_tokens($webservice->id);
            foreach ($functions as $function) {
                $functioninfo = \local_wsmanager::function_info($function->functionname);
                $table->data[] = [
                    $function->functionname,
                    $functioninfo->description,
                    $tokens ? \html_writer::link('javascript:;', get_string('testing', 'local_wsmanager'), [
                        'class' => 'btn btn-primary local_wsmanager_external_function_handle_wrapper',
                        'data-functionname' => $function->functionname,
                        'data-webserviceid' => $webservice->id,
                        'data-method' => \local_wsmanager::function_method($function->functionname, $functioninfo),
                        'title' => get_string('testing', 'local_wsmanager'),
                    ]) : \html_writer::tag('em', get_string('empty', 'quiz') . ' ' . get_string('token', 'webservice')),
                    \html_writer::link('javascript:;', get_string('delete'), [
                        'class' => 'btn btn-danger local_wsmanager_webservice_function_delete',
                        'data-webserviceid' => $webservice->id,
                        'data-functionid' => $function->id,
                        'data-functionname' => $function->functionname,
                        'title' => get_string('delete'),
                    ]),
                ];
            }
            $ret = \html_writer::table($table);
        }
        return \html_writer::div($ret, 'local_wsmanager_webservice_functions_table',
            ['id' => 'local_wsmanager_webservice_functions_table_' . $webservice->id]);
    }

    public function export_for_template(\renderer_base $output): array {
        global $CFG;
        $context = ['table' => self::table($this->webservice)];
        $addfunctionform = new \local_wsmanager\function_form(null, ['id' => $this->webservice->id],
            \local_wsmanager::METHOD_POST, null, [
                'id' => 'local_wsmanager_webservice_functions_add_form_' . $this->webservice->id,
                'class' => 'local_wsmanager_webservice_functions_add_form',
                'data-webserviceid' => $this->webservice->id,
            ]);
        if ($addfunctionform->is_submitted()) {
            $ajax = ['success' => false, 'errors' => []];
            if (!$data = $addfunctionform->get_data()) {
                $ajax['errors'] = $addfunctionform->validation($data, null);
            } else {
                $ajax['success'] = true;
            }
            echo json_encode($ajax);
            die;
        }
        $context['form'] = \html_writer::div(
            \html_writer::tag('h3', get_string('add') . ' ' .
                get_string('function', 'webservice')) .
            $addfunctionform->render(), 'local_wsmanager_bordered local_wsmanager_webservice_functions_add_form_wrapper',
            [
                'style' => 'display:none',
                'id' => 'local_wsmanager_webservice_functions_add_form_wrapper_' .
                    $this->webservice->id,
                'data-webserviceid' => $this->webservice->id,
            ]);
        $table = \local_wsmanager::make_table(false, '70%', '30%');
        $table->data[] = [
            \html_writer::link('javascript:;', get_string('add') . ' ' .
                get_string('function', 'webservice'), [
                'class' => 'btn btn-primary local_wsmanager_webservice_functions_add_form_button',
                'id' => 'local_wsmanager_webservice_functions_add_form_button_' . $this->webservice->id,
                'data-webserviceid' => $this->webservice->id,
                'title' => get_string('function', 'webservice'),
            ]),
            \html_writer::link(new \moodle_url('/' . $CFG->admin . '/webservice/service_functions.php', [
                'id' => $this->webservice->id,
            ]), get_string('settings'), [
                'title' => get_string('settings'),
            ]),
        ];
        $context['footer'] = \html_writer::table($table);
        return $context;
    }

    public function get_template_name() {
        return 'local_wsmanager/functions';
    }
}
