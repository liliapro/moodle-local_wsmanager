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
 * Class to output webservice tokens.
 *
 * @package    local_wsmanager
 * @category   output
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_wsmanager\output;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/local/wsmanager/classes/token_form.php');

class tokens implements \renderable, \templatable {
    protected $webserviceobj = null;

    protected $webservice = null;

    protected $tokens = [];

    public function __construct(\webservice $webserviceobj, \stdClass $webservice, ?array $tokens) {
        $this->webserviceobj = $webserviceobj;
        $this->webservice = $webservice;
        $this->tokens = $tokens;
    }

    public static function table(\stdClass $webservice, array $tokens): string {
        global $CFG, $DB, $USER;
        $table = \local_wsmanager::make_table();
        $table->head = [
            'token' => get_string('token', 'webservice'),
            'user' => get_string('user'),
            'iprestriction' => get_string('iprestriction', 'webservice'),
            'validuntil' => get_string('validuntil', 'webservice'),
        ];
        $table->size = [
            'token' => '25%',
            'user' => '20%',
            'iprestriction' => '15%',
            'validuntil' => '10%',
        ];
        $ctx = \context_system::instance();
        $showalltokens = has_capability('moodle/webservice:managealltokens', $ctx);
        if ($showalltokens) {
            $table->head['tokencreator'] = get_string('tokencreator', 'webservice');
            $table->size['tokencreator'] = '15%';
        }
        $table->head['delete'] = get_string('delete');
        $table->size['delete'] = '30%';
        if ($showalltokens) {
            $table->size['delete'] = '15%';
        }
        if ($tokens) {
            foreach ($tokens as $token) {
                $userprofilurl = new \moodle_url('/user/profile.php', ['id' => $token->userid]);
                $userobj = $DB->get_record('user', ['id' => $token->userid]);
                $tokenuser = \html_writer::link($userprofilurl, fullname($userobj), ['title' => fullname($userobj)]);
                $creatoruser = $DB->get_record('user', ['id' => $token->creatorid]);
                $creatorprofileurl = new \moodle_url('/user/profile.php', ['id' => $token->creatorid]);
                $data = [];
                $tokencell =
                    new \html_table_cell(($token->creatorid != $USER->id) ? '&ndash;' : \html_writer::tag('strong', $token->token));
                $data['token'] = $tokencell;
                $data['user'] = $tokenuser;
                $iprestrictioncell = new \html_table_cell($token->iprestriction ? '<small>' .
                    $token->iprestriction . '</small>' : '&ndash;');
                $data['iprestriction'] = $iprestrictioncell;
                $validuntilcell = new \html_table_cell(!empty($token->validuntil) ?
                    userdate($token->validuntil, '%d %b %Y') : '&ndash;');
                $data['validuntil'] = $validuntilcell;
                if ($showalltokens) {
                    $tokencreatorcell = new \html_table_cell(\html_writer::link($creatorprofileurl,
                        fullname($creatoruser), ['title' => fullname($creatoruser)]));
                    $data['tokencreator'] = $tokencreatorcell;
                }
                $deletecell = new \html_table_cell(\html_writer::link('javascript:;',
                    get_string('delete'), [
                        'class' => 'btn btn-danger btn-sm local_wsmanager_webservice_token_delete',
                        'data-webserviceid' => $webservice->id,
                        'data-tokenid' => $token->id,
                        'data-token' => $token->token,
                        'title' => get_string('delete'),
                    ]));
                $data['delete'] = $deletecell;

                $table->data[] = new \html_table_row($data);
            }
        }
        $tokenmanagementcell1 = new \html_table_cell();
        $tokenmanagementcell1->colspan = $showalltokens ? 5 : 4;
        $tokenmanagementcell1->text = '';
        if (\local_wsmanager::can_create_token((array) $webservice, $USER->id)) {
            $tokenmanagementcell1->text = \html_writer::link('javascript:;', get_string('add') . ' ' .
                get_string('token', 'webservice'),
                [
                    'class' => 'btn btn-primary local_wsmanager_webservice_token_create_form_button',
                    'id' => 'local_wsmanager_webservice_token_create_form_button_' . $webservice->id,
                    'data-webserviceid' => $webservice->id,
                    'title' => get_string('token', 'webservice'),
                ]);
        }
        $tokenmanagementcell2 = new \html_table_cell(\html_writer::link('/' . $CFG->admin .
            '/webservice/tokens.php', get_string('settings')));
        $table->data[] = new \html_table_row([$tokenmanagementcell1, $tokenmanagementcell2]);
        return \html_writer::table($table);
    }

    public function export_for_template(\renderer_base $output): array {
        $context = [
            'table' => \html_writer::div(self::table($this->webservice, $this->tokens), null,
                ['id' => 'local_wsmanager_webservice_tokens_table_' . $this->webservice->id]),
            'form' => '',
            'button' => '',
        ];
        $createtokenmform = new \local_wsmanager\token_form(null, ['webserviceid' => $this->webservice->id],
            'post', null,
            [
                'id' => 'local_wsmanager_webservice_token_create_form_' . $this->webservice->id,
                'class' => 'local_wsmanager_webservice_token_create_form',
            ]);
        if ($createtokenmform->is_submitted()) {
            $ajax = ['success' => false, 'errors' => []];
            if (!$data = $createtokenmform->get_data()) {
                $ajax['errors'] = $createtokenmform->validation($data, null);
            } else {
                $ajax['success'] = true;
            }
            echo json_encode($ajax);
            die;
        }
        $context['form'] = \html_writer::div(
            \html_writer::tag('h3', get_string('createtoken', 'webservice')) .
            \html_writer::tag('h5', $this->webservice->name) . $createtokenmform->render(),
            'local_wsmanager_bordered local_wsmanager_webservice_token_create_form_wrapper',
            [
                'style' => 'display:none',
                'id' => 'local_wsmanager_webservice_token_create_form_wrapper_' . $this->webservice->id,
                'data-webserviceid' => $this->webservice->id,
            ]);
        return $context;
    }

    public function get_template_name() {
        return 'local_wsmanager/tokens';
    }
}
