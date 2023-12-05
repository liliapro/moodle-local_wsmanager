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
 * Class to output webservice users.
 *
 * @package    local_wsmanager
 * @category   output
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_wsmanager\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/wsmanager/classes/token_form.php');

class users implements \renderable, \templatable {
    protected $webserviceobj = null;

    protected $webservice = null;

    public function __construct(\webservice $webserviceobj, \stdClass $webservice) {
        $this->webserviceobj = $webserviceobj;
        $this->webservice = $webservice;
    }

    public static function table(\stdClass $webservice, array $users): string {
        global $CFG, $OUTPUT;
        $table = \local_wsmanager::make_table();
        $table->head = [
            'fullname' => get_string('fullname'),
            'iprestriction' => get_string('iprestriction', 'webservice') . ' ' .
                $OUTPUT->help_icon('iprestriction', 'webservice', get_string('iprestriction', 'webservice')),
            'validuntil' => get_string('validuntil', 'webservice') . ' ' .
                $OUTPUT->help_icon('validuntil', 'webservice', get_string('validuntil', 'webservice')),
            'delete' => get_string('delete'),
        ];
        $table->size = [
            'fullname' => '30%',
            'iprestriction' => '25%',
            'validuntil' => '25%',
            'delete' => '20%',
        ];
        $table->id = 'local_wsmanager_webservice_users_table_' . $webservice->id;
        if ($users) {
            foreach ($users as $user) {
                $usertitle = fullname($user);
                if (has_capability('moodle/user:viewdetails', \context_system::instance())) {
                    $usertitle = \html_writer::link(new \moodle_url('/user/profile.php', [
                        'id' => $user->id,
                    ]), $usertitle, [
                        'title' => $usertitle,
                    ]);
                }
                $row = new \html_table_row([
                    'fullname' => '<p><strong>' . $usertitle . ',<br />' .
                        s($user->email) . '</strong></p><p>' . \html_writer::link(new \moodle_url('/' .
                            $CFG->admin . '/webservice/service_user_settings.php',
                            [
                                'userid' => $user->id,
                                'serviceid' => $webservice->id,
                            ]), get_string('edit'),
                            ['title' => get_string('edit')]) . '</p>',
                    'iprestriction' => $user->iprestriction,
                    'validuntil' => $webservice->validuntil ? userdate($webservice->validuntil, '%d %b %Y') : '&ndash;',
                    'delete' => \html_writer::link('javascript:;', get_string('delete'),
                        [
                            'class' => 'btn btn-danger btn-sm local_wsmanager_webservice_user_delete',
                            'data-webserviceid' => $webservice->id,
                            'data-userid' => $user->id,
                            'title' => get_string('delete'),
                        ]),
                ]);
                $row->id = 'local_wsmanager_webservice_user_row_' . $webservice->id . '_' . $user->id;
                $row->attributes = ['class' => 'local_wsmanager_webservice_user_row_' . $webservice->id];
                $table->data[] = $row;
            }
        }
        return \html_writer::table($table);
    }

    public function export_for_template(\renderer_base $output): array {
        global $CFG;
        $context = [
            'table' => '',
            'title' => '',
            'button' => '',
        ];
        if ($this->webservice->restrictedusers) {
            $context['title'] = \html_writer::tag('h4', get_string('users'));
            if ($users = $this->webserviceobj->get_ws_authorised_users($this->webservice->id)) {
                $context['table'] = self::table($this->webservice, $users);
            }
            $context['button'] = \html_writer::link(new \moodle_url('/' . $CFG->admin .
                '/webservice/service_users.php', ['id' => $this->webservice->id]),
                get_string('add'), ['title' => get_string('add')]);
        }
        return $context;
    }

    public function get_template_name() {
        return 'local_wsmanager/users';
    }
}
