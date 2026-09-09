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
 * Instantiate the Form and add the task to queue
 *
 * @package    local_updatetocardformat
 * @author     Laurent GUILLET <laurent.guillet@univ-eiffel.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(__DIR__ . '/../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->dirroot . '/local/updatetocardformat/updatetocardformat_form.php');

$systemcontext = context_system::instance();

$redirecturlhometemp = new moodle_url('/my');

require_login();

$PAGE->set_context($systemcontext);

$localurl = new moodle_url('/local/updatetocardformat/index.php');

$PAGE->set_url($localurl);

if (!is_siteadmin()) {

    redirect($redirecturlhometemp);
}

// Instantiate simplehtml_form.
$mform = new updatetocardformat_form();

$redirecturlhome = new moodle_url('/my');

// Form processing and displaying is done here.
if ($mform->is_cancelled()) {

    redirect($redirecturlhome);
} else if ($fromform = $mform->get_data()) {

    $updatatocardformattask = new \local_updatetocardformat\task\updatetocardformat();
    $updatatocardformattask->set_custom_data(array(
        'updatedcategoryid' => $fromform->updatedcategory
    ));

    // Queue the task for the next run.
    \core\task\manager::queue_adhoc_task($updatatocardformattask);

    redirect($redirecturlhome);
} else {

    admin_externalpage_setup('local_updatetocardformat'); // Sets the navbar & expands navmenu.

    echo $OUTPUT->header();
    $mform->display();
    echo $OUTPUT->footer();
}


