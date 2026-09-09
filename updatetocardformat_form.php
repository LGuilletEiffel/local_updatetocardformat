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
 * Update to card format Form file
 *
 * @package    local_updatetocardformat
 * @author     Laurent GUILLET <laurent.guillet@univ-eiffel.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

// Moodleform is defined in formslib.php.
require_once("$CFG->libdir/formslib.php");

class updatetocardformat_form extends moodleform {

    public function definition() {
        global $DB, $CFG;

        $mform = $this->_form; // Don't forget the underscore!

        $listcategories = $DB->get_records('course_categories');
        $categories[0] = get_string('system', 'local_updatetocardformat');
        foreach ($listcategories as $category) {

            $categories[$category->id] = $category->name;
        }

        $mform->addElement('select', 'updatedcategory',
                get_string('updatedcategory', 'local_updatetocardformat'),
                $categories);

        $this->add_action_buttons(true, get_string('validateform', 'local_updatetocardformat'));
    }

}
