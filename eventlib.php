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
 * Email signup notification event handlers.
 *
 * @package    notifyemailsignup
 * @author     Iñaki Arenaza
 * @copyright  2017 Iñaki Arenaza
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Event handler for cohort enrolment plugin.
 *
 * We try to keep everything in sync via listening to events,
 * it may fail sometimes, so we always do a full sync in cron too.
 *
 * @package    notifyemailsignup
 * @copyright  2017 Iñaki Arenaza
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class notify_email_signup_handler {
    /**
     * Event processor - user created
     *
     * @param \core\event\user_created $event
     * @return bool
     */
    public static function user_signup(\core\event\user_created $event) {
        global $DB, $CFG;

        // Make sure the user was created through email signup plugin. Otherwise, ignore the event.
        $user = $DB->get_record('user', array('id' => $event->objectid));
        if($user->auth !== 'email') {
            return true; // false??
        }

        // It was, so send a notification email to the notification address(es), with user details.
        $site = get_site();
        $supportuser = core_user::get_support_user();

        $data = new stdClass();
        $data->signupemail = $user->email;
        $data->signupfullname = fullname($user);
        $data->signupusername = $user->username;
        $data->supportname = fullname($supportuser);
        $data->sitename = format_string($site->fullname);
        $data->signoff = generate_email_signoff();

        $subject = get_string('notifyemailsignupsubject', 'local_notifyemailsignup', format_string($site->fullname));
        $message  = get_string('notifyemailsignupbody', 'local_notifyemailsignup', $data);
        $messagehtml = text_to_html($message, false, false, true);

        $supportuser->mailformat = 1; // Always send HTML version as well.

        // Directly email rather than using the messaging system to ensure its not routed to a popup or jabber.
        return email_to_user($supportuser, $supportuser, $subject, $message, $messagehtml);

        return true;
    }
}
