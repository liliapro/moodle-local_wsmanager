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
 * Class override for \core\check\result class to implement own rules.
 *
 * @copyright   2023 Lilia Smirnova <lilia.pro@protonmail.com>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package     local_wsmanager
 */

namespace local_wsmanager\check;

class result extends \core\check\result {
    const ENABLED = 'enabled';

    const DISABLED = 'disabled';

    public function export_for_template(\renderer_base $output) {
        return [
            'status' => clean_text(get_string('status' . $this->status,
                in_array($this->status, [self::ENABLED, self::DISABLED, self::ERROR]) ? 'local_wsmanager' : '')),
            'isok' => $this->status === self::ENABLED,
            'iswarning' => $this->status === self::DISABLED,
            'iserror' => $this->status === self::ERROR,
        ];
    }
}
