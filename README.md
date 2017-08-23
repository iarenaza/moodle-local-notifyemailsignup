## What this Moodle plugin is for ##

This Moodle plugin sends an email notification message to the 'Support
email' address every time a new Moodle user account is created via the
'Email signup' authentication plugin. The notification message
contains some essential details about the account just created (email
address, full name and user account name).

The email is sent when the user signs up, not when the user account is
confirmed. So the plugin will notify even about accounts that may
never be confirmed.

## Supported Moodle Versions ##

The plugin currently works with Moodle 2.7 or later versions.

## Installation ##

This is a standard [Moodle Local Plugin](https://docs.moodle.org/dev/Local_plugins),
so you can follow the standard installation instructions for Moodle
Plugins at https://docs.moodle.org/en/Installing_plugins . Note that
if you install this plugin manually at the server, you need to install
it inside the 'local' directory at the top of the moodle installation
directory.

## Configuration ##

The only configuration used by the plugin is the Support Contact
settings. It uses the 'Support name' and 'Support email' settings as
the recipient of the email notification messages it sends.

You can customise the content/wording of the notification messages by
editing the language strings of the plugin, e.g., through the built-in
'Language customisation' mechanism. All the user table fields are
available in the $a object as {$a->signup_*fieldname*}, where
*fieldname* is one of ``id``, ``username``, ``auth``, ``firstname``,
``lastname``, etc. The ``password`` field does not contain the actual
password, for security reasons.
