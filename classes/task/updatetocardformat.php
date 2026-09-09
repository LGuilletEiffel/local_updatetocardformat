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
 * Task file
 *
 * @package    local_updatetocardformat
 * @author     Laurent GUILLET <laurent.guillet@univ-eiffel.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_updatetocardformat\task;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/lib.php');

class updatetocardformat extends \core\task\adhoc_task {

    public function execute() {

        global $DB;

        mtrace("Début de la tâche de mise à jour de changement de format de cours.");

        $data = $this->get_custom_data();
        $updatedcategoryid = $data->updatedcategoryid;

        // On récupère tous les cours de la catégorie.

        if ($updatedcategoryid == 0) {

            $contextid = \context_system::instance()->id;
        } else {

            $contextid = $DB->get_record('context', array('contextlevel' => CONTEXT_COURSECAT, 'instanceid' => $updatedcategoryid))->id;
        }

        $sqlcoursecontext = "SELECT * FROM {context} WHERE contextlevel = " . CONTEXT_COURSE . " AND path LIKE '%/$contextid/%'";

        $listcoursesidincategory = $DB->get_records_sql($sqlcoursecontext);

        foreach ($listcoursesidincategory as $courseidincategory) {

            // On restreint aux cours au format remui à disposition par carte

            $sql = "SELECT * FROM {course_format_options} WHERE courseid = ?";

            $DB->record_exists_sql($sql, array($courseidincategory->instanceid));

            if ($DB->record_exists_sql($sql, array($courseidincategory->instanceid))) {

                mtrace("Cours Remui id :" . $courseidincategory->instanceid);

                // Ici, on change le format du cours.

                $course = $DB->get_record('course', array('id' => $courseidincategory->instanceid));

                mtrace("Cours passé au format tuile :" . $courseidincategory->instanceid);
                $course->format = 'tiles';

                update_course($course);

                mtrace("Cours : " . $courseidincategory->instanceid . " mis à jour");
            }
        }
    }

}
