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
 * Webservice token create form
 *
 * @package     local_wsmanager
 * @copyright   2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_wsmanager;

defined('MOODLE_INTERNAL') || die();

use core_user;

require_once($CFG->libdir . '/formslib.php');

class token_form extends \moodleform {

    /**
     * Defines the form fields.
     */
    public function definition() {
        $mform = $this->_form;
        $data = $this->_customdata;

        $mform->addElement('hidden', 'webserviceid');
        $mform->setType('webserviceid', PARAM_INT);
        $mform->setDefault('webserviceid', $data['webserviceid']);

        // User selector.
        $attributes = [
            'id' => 'local_wsmanager_webservice_token_create_user_' . $data['webserviceid'],
            'multiple' => false,
            'ajax' => 'core_user/form_user_selector',
            'valuehtmlcallback' => function($userid) {
                global $OUTPUT;

                $context = \context_system::instance();
                $fields = core_user\fields::for_name()->with_identity($context, false);
                $record = core_user::get_user($userid, 'id ' . $fields->get_sql()->selects, MUST_EXIST);

                $user = (object) [
                    'id' => $record->id,
                    'fullname' => fullname($record, has_capability('moodle/site:viewfullnames', $context)),
                    'extrafields' => [],
                ];

                foreach ($fields->get_required_fields([core_user\fields::PURPOSE_IDENTITY]) as $extrafield) {
                    $user->extrafields[] = (object) [
                        'name' => $extrafield,
                        'value' => s($record->$extrafield),
                    ];
                }

                return $OUTPUT->render_from_template('core_user/form_user_selector_suggestion', $user);
            },
        ];
        $mform->addElement('autocomplete', 'user', get_string('user'),
            [], $attributes);
        $mform->addRule('user', get_string('required'), 'required', null, 'client');

        $mform->addElement('text', 'iprestriction', get_string('iprestriction', 'webservice'),
            ['id' => 'local_wsmanager_webservice_token_create_iprestriction_' . $data['webserviceid']]);
        $mform->setType('iprestriction', PARAM_RAW_TRIMMED);

        $mform->addElement('date_selector', 'validuntil',
            get_string('validuntil', 'webservice'), ['optional' => true]);
        $mform->setType('validuntil', PARAM_INT);

        $mform->addElement('hidden', 'action');
        $mform->setType('action', PARAM_ALPHANUMEXT);

        $this->add_action_buttons(true, get_string('add'));

        $this->set_data($data);
    }

    /**
     * Validate the submitted data.
     *
     * @param array $data Submitted data.
     * @param array $files Submitted files.
     * @return array Validation errors.
     */
    public function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);

        if (!empty($data['user']) && $data['user'] !== 'NaN') {
            if ($DB->get_field('user', 'suspended', ['id' => $data['user']], MUST_EXIST)) {
                $errors['user'] = get_string('suspended', 'core') . ' - ' . get_string('forbiddenwsuser', 'core_webservice');
            }
        } else {
            $errors['user'] = get_string('statusunknown', 'core');
        }

        return $errors;
    }
}
