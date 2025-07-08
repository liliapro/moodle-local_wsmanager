<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

namespace local_wsmanager\output;

use local_wsmanager;
use local_wsmanager\check\result;
use plugin_renderer_base;

defined('MOODLE_INTERNAL') || die();

/**
 * WSManager renderer
 *
 * @package    local_wsmanager
 * @copyright  2024 Your Name
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {

    /**
     * Render dashboard widget
     *
     * @return string HTML output
     */
    public function output_dashboard_widget() {
        return $this->render(new dashboard());
    }

    public function render_dashboard(dashboard $dashboard) {
        return $this->render_from_template($dashboard->get_template_name(), $dashboard->export_for_template($this));
    }

    public function check_result(result $result) {
        return local_wsmanager::check_result($result);
    }
}
