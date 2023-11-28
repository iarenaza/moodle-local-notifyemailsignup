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
 * Email signup notification event observers.
 *
 * @package    local_notifyemailsignup
 * @author     Iñaki Arenaza
 * @copyright  2017 Iñaki Arenaza
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Email signup notification event observers.
 *
 * @package    local_notifyemailsignup
 * @author     Iñaki Arenaza
 * @copyright  2017 Iñaki Arenaza
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class local_notifyemailsignup_observer {
    /**
     * Event processor - user created
     *
     * @param \core\event\user_created $event
     * @return bool
     */
    public static function user_signup(\core\event\user_created $event) {
        global $DB, $CFG;

        // Make sure the user was created through one of the monitored
        // signup plugins. Otherwise, ignore the event.
        $user = $DB->get_record('user', array('id' => $event->objectid));
        $monitoredauths = explode(',', get_config('local_notifyemailsignup',
                                                  'monitoredauths'));
        if (!in_array($user->auth, $monitoredauths)) {
            return true;
        }

        // It was, so send a notification email to the notification
        // address(es), with the account details.
        $site = get_site();
        $supportuser = core_user::get_support_user();

        // No need to send the password at all (even if it's encrypted).
        $user->password = '++hidden for security reasons++';

        $data = array();
        $data['supportname'] = fullname($supportuser);
        $data['sitename'] = format_string($site->fullname);
        $data['signoff'] = generate_email_signoff();

        // Add the user table fields.
        foreach ($user as $key => $value) {
            $data['signup_user_'.$key] = $value;
        }

        // Add the custom profile fields too.
        $user->profile = array();
        require_once($CFG->dirroot.'/user/profile/lib.php');
        profile_load_custom_fields($user);
        foreach ($user->profile as $key => $value) {
            $data['signup_profile_'.$key] = $value;
        }

        $subject = get_string('notifyemailsignupsubject',
                              'local_notifyemailsignup',
                              format_string($site->fullname));
        $message  = get_string('notifyemailsignupbody',
                               'local_notifyemailsignup', $data);
        $messagehtml = text_to_html($message, false, false, true);

        $supportuser->mailformat = 1; // Always send HTML version as well.

        // Directly email rather than using the messaging system to
        // ensure its not routed to a popup or jabber.
        return email_to_user($supportuser, $supportuser, $subject,
                             $message, $messagehtml);

        return true;
    }
}
