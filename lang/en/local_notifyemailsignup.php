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
 * Strings for Email signup notification plugin, 'en' language
 *
 * @package    local_notifyemailsignup
 * @author     Iñaki Arenaza
 * @copyright  2017 Iñaki Arenaza
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['notifyemailsignupsubject'] = '{$a}: New account signup notification';
$string['notifyemailsignupbody'] = 'Hi {$a->supportname},

A new account has been requested at \'{$a->sitename}\'
using the following details:

- email address: {$a->signup_user_email}
- username: {$a->signup_user_username}
- first name: {$a->signup_user_firstname}
- last name: {$a->signup_user_lastname}

Cheers from the \'{$a->sitename}\' administrator,
{$a->signoff}';
$string['pluginname'] = 'Notify site administrators about new Email Signups';
