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
 * @package    local_wsmanager
 * @copyright  2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/webservice/lib.php');
require_once($CFG->dirroot . '/local/wsmanager/classes/check/result.php');
require_once($CFG->dirroot . '/local/wsmanager/locallib.php');
require_once($CFG->dirroot . '/local/wsmanager/dashboard_setting.php');

if ($hassiteconfig) {
    $ADMIN->add('server', new \admin_category('local_wsmanager',
        new lang_string('pluginname', 'local_wsmanager')));

    $temp = new \admin_settingpage(\local_wsmanager::DASHBOARD_PAGE,
        new lang_string('dashboard', 'local_wsmanager'));
    $temp->add(new \local_wsmanager_dashboard_setting());
    $ADMIN->add('local_wsmanager', $temp);

    $ADMIN->add('local_wsmanager', new \admin_externalpage('local_wsmanager_webservices',
        new lang_string('webservices', 'webservice'),
        new \moodle_url('/local/wsmanager/webservices.php')));
}
