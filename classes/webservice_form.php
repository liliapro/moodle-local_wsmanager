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
 * Webservice create form
 *
 * @package     local_wsmanager
 * @copyright   2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_wsmanager;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/' . $CFG->admin . '/webservice/forms.php');

class webservice_form extends \external_service_form {
    public function definition() {
        $mform = $this->_form;
        $service = isset($this->_customdata) ? $this->_customdata : new \stdClass();

        $mform->addElement('text', 'name', get_string('name'));
        $mform->addRule('name', get_string('required'), 'required', null, 'client');
        $mform->setType('name', PARAM_TEXT);

        $mform->addElement('text', 'shortname', get_string('shortname'), 'maxlength="255" size="20"');
        $mform->setType('shortname', PARAM_TEXT);
        if (!empty($service->id)) {
            $mform->hardFreeze('shortname');
            $mform->setConstants('shortname', $service->shortname);
        }

        $mform->addElement('advcheckbox', 'enabled', get_string('enabled', 'webservice'));
        $mform->setType('enabled', PARAM_BOOL);
        $mform->addElement('advcheckbox', 'restrictedusers',
            get_string('restrictedusers', 'webservice'));
        $mform->addHelpButton('restrictedusers', 'restrictedusers', 'webservice');
        $mform->setType('restrictedusers', PARAM_BOOL);

        $mform->addElement('advcheckbox', 'downloadfiles', get_string('downloadfiles', 'webservice'));
        $mform->setAdvanced('downloadfiles');
        $mform->addHelpButton('downloadfiles', 'downloadfiles', 'webservice');
        $mform->setType('downloadfiles', PARAM_BOOL);

        $mform->addElement('advcheckbox', 'uploadfiles', get_string('uploadfiles', 'webservice'));
        $mform->setAdvanced('uploadfiles');
        $mform->addHelpButton('uploadfiles', 'uploadfiles', 'webservice');

        $currentcapabilityexist = false;
        if (empty($service->requiredcapability)) {
            $service->requiredcapability = '';
            $currentcapabilityexist = true;
        }

        $systemcontext = \context_system::instance();
        $allcapabilities = $systemcontext->get_capabilities();
        $capabilitychoices = [];
        $capabilitychoices[''] = get_string('norequiredcapability', 'webservice');
        foreach ($allcapabilities as $cap) {
            $capabilitychoices[$cap->name] = $cap->name . ': '
                . get_capability_string($cap->name);
            if (!empty($service->requiredcapability)
                && $service->requiredcapability == $cap->name) {
                $currentcapabilityexist = true;
            }
        }

        $mform->addElement('searchableselector', 'requiredcapability',
            get_string('requiredcapability', 'webservice'), $capabilitychoices);
        $mform->addHelpButton('requiredcapability', 'requiredcapability', 'webservice');
        $mform->setAdvanced('requiredcapability');
        $mform->setType('requiredcapability', PARAM_RAW);
        if (empty($currentcapabilityexist)) {
            global $OUTPUT;
            $mform->addElement('static', 'capabilityerror', '',
                $OUTPUT->notification(get_string('selectedcapabilitydoesntexit',
                    'webservice', $service->requiredcapability)));
            $service->requiredcapability = "";
        }

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        if (!empty($service->id)) {
            $buttonlabel = get_string('savechanges');
        } else {
            $buttonlabel = get_string('addaservice', 'webservice');
        }

        $this->add_action_buttons(true, $buttonlabel);

        $this->set_data($service);
    }
}
