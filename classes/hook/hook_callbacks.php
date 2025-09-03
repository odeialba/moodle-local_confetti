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

namespace local_confetti\hook;

/**
 * @package    local_confetti
 * @copyright  2025 Odei Alba <odeialba@odeialba.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {
    public static function before_http_headers_callback(): void {
        global $PAGE, $SESSION, $USER;

        if (!empty($SESSION->throw_confetti)) {
            unset($SESSION->throw_confetti);

            $settings = [
                'preset' => get_config('local_confetti', 'confettipreset'),
                'text' => get_config('local_confetti', 'confettitext')
            ];
            $PAGE->requires->js_call_amd('local_confetti/confetti', 'init', [$settings]);

            if ($PAGE->pagetype == 'my-index'){
                set_user_preference('show_login_confetti', 'no');
            }

            $event = \local_confetti\event\confetti_thrown::create([
                'context' => \core\context\system::instance(),
                'userid' => $USER->id,
            ]);
            $event->trigger();
        }
    }
}
