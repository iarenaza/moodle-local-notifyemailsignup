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
 * Version details
 *
 * @package    local_notifyemailsignup
 * @author     Iñaki Arenaza
 * @copyright  2017 Iñaki Arenaza
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// Ensure the configurations for this site are set.
if ($hassiteconfig) {
    // Create the new settings page - in a local plugin this is not
    // defined as standard, so normal $settings->methods will throw an
    // error as $settings will be NULL.
    $settings = new admin_settingpage(
        'local_notifyemailsignup',
        get_string('pluginname', 'local_notifyemailsignup'));

    // Create the settings page itself.
    $ADMIN->add('localplugins', $settings);

    $signupplugins = function () {
        $options = array();
        $plugins = core_component::get_plugin_list('auth');
        foreach ($plugins as $name => $fulldir) {
            $plugin = get_auth_plugin($name);
            if (!$plugin || !($plugin->can_signup())) {
                continue;
            }
            $options[$name] = get_string('pluginname', 'auth_'.$name);
        }
        return $options;
    };

    // Add setting fields to the settings for this page.
    $settings->add(
        new admin_setting_configmulticheckbox(
            'local_notifyemailsignup/monitoredauths',
            get_string('monitoredauths', 'local_notifyemailsignup'),
            get_string('monitoredauths_desc', 'local_notifyemailsignup'),
            array('email' => 1), $signupplugins()));
    unset($signupplugins);
}
