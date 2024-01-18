<?php
// This file is part of Moodle - https://moodle.org/
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
 * Webservice function add form
 *
 * @package     local_wsmanager
 * @copyright   2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_wsmanager;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/' . $CFG->admin . '/webservice/forms.php');

class function_form extends \external_service_functions_form {
    public function definition() {
        global $CFG;

        $mform = $this->_form;
        $data = $this->_customdata;

        require_once($CFG->dirroot . '/webservice/lib.php');
        $webservicemanager = new \webservice();
        $functions = $webservicemanager->get_not_associated_external_functions($data['id']);

        foreach ($functions as $functionid => $functionname) {
            $function = \core_external\external_api::external_function_info($functionname);
            if (empty($function->deprecated)) {
                $functions[$functionid] = $function->name . ':' . $function->description;
            } else {
                unset($functions[$functionid]);
            }
        }

        $mform->addElement('searchableselector', 'fids_' . $data['id'], get_string('name'),
            $functions, ['multiple']);
        $mform->addRule('fids_' . $data['id'], get_string('required'), 'required', null, 'client');

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'action');
        $mform->setType('action', PARAM_ALPHANUMEXT);

        $this->add_action_buttons(true, get_string('addfunctions', 'webservice'));

        $this->set_data($data);
    }
}
