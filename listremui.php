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

if (!is_siteadmin()) {

    redirect($redirecturlhometemp);
}

$systemcontextid = \context_system::instance()->id;
$eivpcontextid = 813;

$sqlcoursecontext = "SELECT * FROM {context} WHERE contextlevel = " . CONTEXT_COURSE . " AND path LIKE '%/$systemcontextid/%'";

$listcoursesidincategory = $DB->get_records_sql($sqlcoursecontext);

foreach ($listcoursesidincategory as $courseidincategory) {

    // On restreint aux cours au format remui à disposition par liste

    if ($DB->record_exists('course_format_options', array('courseid' => $courseidincategory->instanceid, 'format' => 'remuiformat'))) {

        $recordtoupdatename = $DB->get_record('course_format_options', array('courseid' => $courseidincategory->instanceid, 'format' => 'remuiformat',
            'name' => 'remuicourseformat'));

        $course = $DB->get_record('course', array('id' => $courseidincategory->instanceid));
        $coursecategory = $DB->get_record('course_categories', array('id' => $course->category));

        $courseurl = new moodle_url('/course/view.php', array('id' => $course->id));

        if ($recordtoupdatename->value == 0) {

            echo "ID du cours : $courseidincategory->instanceid, Nom du cours : $course->fullname, Catégorie : $coursecategory->name, "
            . "Format Remui Carte, Lien : $courseurl\n<br>";
        } else {

            echo "ID du cours : $courseidincategory->instanceid, Nom du cours : $course->fullname, Catégorie : $coursecategory->name, "
            . "Format Remui Liste, <a href = $courseurl>Lien</a><br>";
        }
    }
}