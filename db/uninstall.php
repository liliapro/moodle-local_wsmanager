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
 * Uninstall trigger for component 'local_wsmanager'
 *
 * @link        https://docs.moodle.org/dev/Installing_and_upgrading_plugin_database_tables#uninstall.php
 * @package     local_wsmanager
 * @copyright   2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

function xmldb_local_wsmanager_uninstall() {
    global $CFG;
    require_once($CFG->dirroot . '/webservice/lib.php');
    require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
    $webservice = new \webservice();
    if ($service = $webservice->get_external_service_by_shortname(\local_wsmanager::WS_TEST_SHORTNAME)) {
        $webservice->delete_service($service->id);
    }
    return true;
}
