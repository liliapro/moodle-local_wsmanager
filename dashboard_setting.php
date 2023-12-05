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
 * Admin setting output
 *
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use local_wsmanager as wsmanager;

class local_wsmanager_dashboard_setting extends \admin_setting {
    public function __construct() {
        global $PAGE;
        if (!empty($_GET['section']) && $_GET['section'] == wsmanager::DASHBOARD_PAGE) {
            wsmanager::js_strings();
            $PAGE->requires->js_call_amd('local_wsmanager/base', 'init',
                [wsmanager::SEPARATOR_1, wsmanager::SEPARATOR_2, wsmanager::SEPARATOR_3, wsmanager::SEPARATOR_4]);
            $PAGE->requires->js_call_amd('local_wsmanager/dashboard', 'init',
                [wsmanager::SEPARATOR_1, wsmanager::SEPARATOR_2, wsmanager::SEPARATOR_3, wsmanager::SEPARATOR_4]);
        }
        $this->name = 'local_wsmanager';
        $this->nosave = true;
        parent::__construct('local_wsmanager', get_string('dashboard', 'local_wsmanager'), '', '');
    }

    public function get_setting() {
        return true;
    }

    public function get_full_name() {
        return '';
    }

    public function write_setting($data) {
        return false;
    }

    public function output_html($data, $query = '') {
        global $CFG, $OUTPUT;
        require_once($CFG->dirroot . '/local/wsmanager/classes/output/dashboard.php');
        $renderable = new \local_wsmanager\output\dashboard();
        return $OUTPUT->render($renderable);
    }
}
