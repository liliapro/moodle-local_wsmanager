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
 * Prints webservices main page.
 *
 * @package     local_wsmanager
 * @copyright   2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

require_once(__DIR__ . '/../../config.php');

defined('MOODLE_INTERNAL') || die();

use local_wsmanager as wsmanager;

require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/local/wsmanager/classes/output/webservices.php');

admin_externalpage_setup('local_wsmanager_webservices', '', null,
    '/local/wsmanager/webservices.php');
global $OUTPUT, $PAGE;
$PAGE->set_context(\context_system::instance());
$PAGE->set_heading(get_string('webservices', 'webservice'));
if ($CFG->branch >= 32) {
    if ($CFG->branch < 39) {
        // in future will be support for Moodle <4.0
    }
    if ($CFG->branch >= 39) {
        wsmanager::js_strings();
        $PAGE->requires->js_call_amd('local_wsmanager/base_mdl39', 'init',
            [wsmanager::SEPARATOR_1, wsmanager::SEPARATOR_2, wsmanager::SEPARATOR_3, wsmanager::SEPARATOR_4]);
        $PAGE->requires->js_call_amd('local_wsmanager/webservices_mdl39', 'init',
            [wsmanager::SEPARATOR_1, wsmanager::SEPARATOR_2, wsmanager::SEPARATOR_3, wsmanager::SEPARATOR_4]);
    }
}
if (!data_submitted()) {
    echo $OUTPUT->header();
}
$renderable = new \local_wsmanager\output\webservices();
echo $OUTPUT->render($renderable);
echo \html_writer::empty_tag('input', ['type' => 'hidden', 'value' => $PAGE->url, 'id' => 'local_wsmanager_url']);
if (!data_submitted()) {
    echo $OUTPUT->footer();
}
