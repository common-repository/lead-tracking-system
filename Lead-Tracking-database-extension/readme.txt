=== Contact Form 7 to Database Extension ===
Contributors: msimpson
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=NEVDJ792HKGFN&lc=US&item_name=Wordpress%20Plugin&item_number=cf7%2dto%2ddb%2dextension&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
Tags: contact form,database,contact form database,save contact form
Requires at least: 3.2.1
Tested up to: 3.3.1
Stable tag: 2.2.6

Saves submitted form data to the database and provides short codes to display it. Captures data from Contact Form 7 and Fast Secure Contact Form

== Description ==

Saves form submissions to the database that come from Contact Form 7 (CF7) plugin and/or Fast Secure Contact Form (FSCF) plugin.

CF7 and FSCF are great plugins but but were lacking one thing...the ability to save the form data to the database.
And if you get a lot of form submissions, then you end up sorting through a lot of email.
This plugin-to-a-plugin provides that functionality.

You will need to have CF7 and/or FSCF installed along with this plugin.
When using CF7, this plugin also puts a menu item in the Administration Plugins menu where you can see the data in the database.
When using FSCF, this plugin puts links on its Admin page.
You can also use the [cfdb-html], [cfdb-table], [cfdb-datatable], [cfdb-value] and [cfdb-json] shortcodes to display the data on a non-admin page on your site.

Disclaimer: I am not the maker of Contact Form 7 nor Fast Secure Contact Form and am not associated with the development of those plugins.

== Installation ==

1. Your WordPress site must be running PHP5 or better. This plugin will fail to activate if your site is running PHP4.
1. Be sure that Contact Form 7 and/or Fast Secure Contact Form is installed and activated (this is an extension to them)
1. Fast Secure Contact Form should be at least version 2.9.7

Notes:

* Installing this plugin creates its own table. If you uninstall it, it will delete its table and any data you have in it. (But you can deactivate it without loosing any data).
* Tested using PHP 5.2.13, MySQL 5.0 (Using 1and1 for hosting)

== Frequently Asked Questions ==

= Where can I find documentation on the plugin? =
Refer the <a href="http://cfdbplugin.com/">Plugin Site</a>


= Where do I see the data? =

* Contact Form 7 Users: In the admin page, under CF7's top level "Contact" admin menu. Look for "Contact" -> "Database"
* Fast Secure Contact Form Users: In the admin page, Plugins -> FS Contact Form Option, There is a "Database" link at the top of the page
* For a direct link, use http://&lt;your-wordpress-site&gt;/wp-admin/admin.php?page=CF7DBPluginSubmissions

= Can I display form data on a non-admin web page or in a post? =

Yes, <a href="http://cfdbplugin.com/?page_id=89">documentation on shortcodes</a> `[cfdb-html]`, `[cfdb-datatable]`, `[cfdb-table]`, `[cfdb-json]` and `[cfdb-value]`, etc.

= What is the name of the table where the data is stored? =

`wp_cf7dbplugin_submits`
Note: if you changed your WordPress MySql table prefix from the default `wp_` to something else, then this table will also have that prefix instead of `wp_` (`$wpdb->prefix`)
Note: previous to version 2.0, the table was named "wp_CF7DBPlugin_SUBMITS" but was changed to all-lowercase as part of the 2.0 upgrade.

= If I uninstall the plugin, what happens to its data in the database? =

The table and all its data are deleted when you uninstall but you can change a setting on the options page to
prevent it from being deleted. You can always deactivate the plugin without loosing data.


== Screenshots ==

1. Admin Panel view of submitted form data

== Changelog ==

= 2.2.7 =
* Bug Fix: Fix to match change to Contact Form 7 version 3.1 where uploaded files were not being saved to the database.

= 2.2.6 =
* Bug Fix: seeing error "undefined function is_plugin_active()"

= 2.2.5 =
* Bug Fix: Admin page data table was not showing top search banner when datatable using i18n
* Displays Jalali dates when wp-jalali plugin is installed and activated

= 2.2.4 =
* Improvement: cfdb-html now supports nested short codes
* Bug Fix: short code builder for cfdb-html not showing template HTML tags
* Improvement: if "form" missing from a short code, PHP error no longer happens

= 2.2.3 =
* Can do exports via the Short Code Builder Page with the main short code options applied (show, hide, search, filter, limit, orderby)

= 2.2.2 =
* Bug fix: for some users the fix in 2.2.1 for setting character encoding when retrieving was throwing exception because
no $wpdb->set_charset() method exists. Made code handle this case

= 2.2.1 =
* Bug fix: relating to character encoding: umlaut characters not displaying correctly
* Bug fix: "class" attribution on table tag was not being emitted for [cfdb-datatables]
* Fixed some strings that were not being internationalized.
* More links to documentation on Database Short Code page

= 2.2 =
* Short code "filter" values using submit_time can now use 'strtotime' values. E.g. "submit_time>last week"
* Dropped index `form_name_field_name_idx` but this fails on some MySQL versions and needs to be done manually (<a href="http://bugs.mysql.com/bug.php?id=37910">MySQL Bug 37910</a>).

= 2.1.1 =
* Upgrade of DataTables to version 1.8.2 from 1.7.6

= 2.1 =
* New short code: [cfdb-save-form-post]
* [cfdb-html] new option: "stripbr"
* Raw submit_time is available for short codes as a field name
* Removed unnecessary quotes in Short Code builder page for "enc" value in URL generated by [cfdb-export-link]
* On uninstall, table in DB is no longer dropped by default
* When accessing a file link, it will it can now display in the browser instead of forcing a download. Mime-type issues resolves.

= 2.0.1 =
* Bug fix: where [cfdb-count] always gave total, ignoring filters
* Added 'percent' function to [cfdb-count]

= 2.0 =
* Data editing support in conjunction with Contact Form to Database Edit plugin
* Name of table that stores data was down-cased from wp_CF7DBPlugin_SUBMITS to wp_cf7dbplugin_submits to avoid issues
 described in http://wordpress.org/support/topic/cf7-and-cfdb-not-saving-submissions

= 1.8.8 =
* Bug fix: when using "filter" operators < and > where not working in cases where they were represented &amp;gt; and &amp;lt; HTML codes
* Bug fix: "orderby" in shortcode was ignoring if ordered by "Submitted" or "submit_time"
* Filter operations now try to do numeric comparisons when possible
* Can now use "submit_time" field in "filter" to filter on the timestamp float.
* [cfdb-html] now preserves line breaks in fields by converting new lines to BR tags.
* CFDBFormIterator now includes a 'submit_time' field showing the raw timestamp

= 1.8.7 =
* [cfdb-html] now has wpautop option
* Form input is now always run through stripslashes() regardless of whether or not get_magic_quotes_gpc is on. This is to be consistent with wp_magic_quotes always being called

= 1.8.6 =
* New shortcode: [cfdb-export-link]
* Bug fix in JSON output

= 1.8.5 =
* Added Shortcode builder page
* [cf7db-count] shortcode now supports counting all forms using form="*" or a list of forms using form="form1,form2,form3"
* [cf7db-html] now has "filelinks" option useful for displaying image uploads. 
* Added options to turn on/off capturing form submissions from CF7 and FSCF

= 1.8.4 =
* Added "cfdb_submit" hook. See http://cfdbplugin.com/?page_id=377
* Added delimiter option for [cfdb-value] shortcode, e.g. [cfdb-value delimiter=',']
* Bug fix related to [cfdb-value] when not used as a shortcode (it was not observing show & hide options)
* Now including DataTables distribution inside this distribution so that page does not reference scripts from another site (so sites using https have everything encrypted on the page)
* In [cfdb-html] shortcode, now removing undesired leading "br" tag and ending "p" tag that WordPress injects. This was messing up table rows (tr tags) in the shortcode because WP was injecting line breaks between the rows.

= 1.8.3 =
* Minor bug fixes.

= 1.8.2 =
* Minor bug fixes.
* Added option to not delete data on uninstall

= 1.8.1 =
* Fixed bug introduced in 1.8 where deleting individual rows from the admin page did nothing.

= 1.8 =
* New shortcodes [cfdb-html] and [cfdb-count]
* New Shortcode option: 'limit'
* New Shortcode option: 'orderby'
* Performance/memory enhancements to enable plugin to handle large data volumes
* Now capturing form submission times with microseconds to avoid collision of two submissions during the same second
* Fixed to work with installations where wp-content is not at the standard location
* Shortcode "show" and "hide" values can now use regular expressions to identify columns
* Option to show database query text on Admin page

= 1.7 =
* Creating an export from the admin panel now filters rows based on text in the DataTable "Search" field.
* [cfdb-json] now has "format" option.
* Fixed bug where "Submitted" column would sometimes appear twice in shortcodes
* Now can filter on "Submitted" column.
* Admin Database page is now blank by default and you have to select a form to display.

= 1.6.5 =
* Now fully supports internationalization (i18n) but we need people to contribute more translation files.
* DataTables (including those created by shortcodes) will automatically i18n based on translations available from DataTables.net
* Italian translation courtesy of Gianni Diurno
* Turkish translation courtesy of Oya Simpson
* Admin page DataTable: removed horizontal scrolling because headers do not scroll with columns properly
* Updated license to GPL3 from GPL2

= 1.6.4 =
* Bug fix: Fixed bug causing FireFox to not display DataTables correctly.

= 1.6.3 =
* Bug fix: Handling problem where user is unable to export from Admin page because jQuery fails to be loaded.

= 1.6.2 =
* Bug fix: avoiding inclusion of DataTables CSS in global admin because of style conflicts & efficiency

= 1.6.1 =
* Bug fix in CSV Exports where Submitted time format had a comma in it, the comma was being interpreted as a
field delimiter.
* Accounting for local timezone offset in display of dates

= 1.6 =
* Admin page for viewing data is not sortable and filterable
* New shortcode: [cfdb-datatable] to putting sortable & filterable tables on posts and pages.
    This incorporates http://www.datatables.net
* Option for display of localized date-time format for Submitted field based on WP site configuration in
"Database Options" -> "Use Custom Date-Time Display Format"
* Option to save Cookie data along with the form data. "Field names" of cookies will be "Cookie <cookie-name>"
See "Database Options" -> "Save Cookie Data with Form Submissions" and "Save only cookies in DB named"

= 1.5 =
* Now works with Fast Secure Contact Form (FSCF)
* New shortcode `[cfdb-value]`
* New shortcode `[cfdb-json]`
* Renamed shortcode `[cf7db-table]` to `[cfdb-table]` (dropped the "7") but old one still works.
* Added option to set roles that can see data when using `[cfdb-table]` shortcode
* Can now specify per-column CSS for `[cfdb-table]` shortcode table (see FAQ)
* Fixed bug with `[cfdb-table]` shortcode where the table aways appeared at the top of a post instead of embedded with the rest of the post text.

= 1.4.5 =
* Added a PHP version check. This Plugin Requires PHP5 or later. Often default configurations are PHP4. Now a more informative error is given when the user tries to activate the plugin with PHP4.

= 1.4.4 =
* If user is logged in when submitting a form, 'Submitted Login' is captured
* `[cfdb-table]` shortcode options for filtering rows including using user variables (see FAQ)
* `[cfdb-table]` shortcode options for CSS
* Can exclude forms from being saved to DB by name

= 1.4.2 =
* Added `[cf7db-table]` shortcode to incorporate form data on regular posts and pages. Use `[cf7db-table form="your-form"]` with optional "show" and "hide: [cf7db-table form="your-form" show="field1,field2,field3"] (optionally show selected fields), [cf7db-table form="your-form" hide="field1,field2,field3"] (optionally hide selected fields)

= 1.4 =
* Added export to Google spreadsheet
* Now saves files uploaded via a CF7 form. When defining a file upload in CF7, be sure to set a file size limit. Example: [file upload limit:10mb]
* Made date-time format configurable.
* Can specify field names to be excluded from being saved to the DB.
* In Database page, the order of columns in the table follows the order of fields from the last form submitted.

= 1.3 =
* Added export to Excel Internet Query
* "Submitted" now shows time with timezone instead of just the date.
* The height of cells in the data display are limited to avoid really tall rows. Overflow cells will get a scroll bar.
* Protection against HTML-injection
* Option to show line breaks in multi-line form submissions
* Added POT file for i18n

= 1.2.1 =
* Option for UTF-8 or UTF-16LE export. UTF-16LE works better for MS Excel for some people but does it not preserve line breaks inside a form entry.

= 1.2 =
* Admin menu now appears under CF7's "Contact" top level menu
* Includes an Options page to configure who can see and delete submitted data in the database
* Saves data in DB table as UTF-8 to support non-latin character encodings.
* CSV Export now in a more Excel-friendly encoding so it can properly display characters from different languages

= 1.1 =
* Added Export to CSV file
* Now can delete a row

= 1.0 =
* Initial Revision.

== Upgrade Notice ==

= 1.6 =
New cool DataTable

= 1.5 =
Now works with <a href="http://wordpress.org/extend/plugins/si-contact-form/">Fast Secure Contact Form</a>. Plus more and better shortcodes.
