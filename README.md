# CiviShield extension for CiviCRM
This extension protects your website against repeat submissions of credit card data within the same user session.

People that purchase stolen credit card data in bulk, or that try to guess credit card numbers, need to test these with small transactions. They often find websites on the Internet that have credit card submission forms, and program bots to submit these credit card numbers to the website, seeing which ones go through.

Unfortunately users of CiviCRM are easy targets for such scheme through their donation or event registration forms.

This extension protects against such abuse by forbidding the same user session to do repeat submissions on contribution forms. When such abuse is detected, it:
- displays an error message to the end-user (ie. 'CiviShield: suspicious transaction detected, please contact the site owner if this is an error.')
- logs paramaters of this failed attempt in the file {$ConfigAndLog}/civishield_log

**ATTENTION**: this extension is NOT publicly released yet. It MIGHT create some false positives and therefore block some valid transactions. It comes WITHOUT warranties of any kind, use it at your own risk.

It has been in use on several Cividesk customer's instances for a while now without any adverse consequences, but that does not prove anything.
If you install and use it, your feedback will be greatly appreciated to robustify/improve it so we can issue an official release with more guaranteees.