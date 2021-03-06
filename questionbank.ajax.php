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
 * Ajax script to update the contents of the question bank dialogue. extend mod_quiz questionbankajax
 *
 * @package    mod_studentquiz
 * @copyright  2017 HSR (http://www.hsr.ch)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);

require_once(dirname(dirname(__DIR__)).'/config.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');
require_once($CFG->dirroot . '/question/editlib.php');

// TODO: more and more I believe we can omit all questionbank relevant overrides!

list($thispageurl, $contexts, $cmid, $cm, $quiz, $pagevars)
    = question_edit_setup('editq', '/mod/studentquiz/view.php', true);
$course = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);

require_login($course, false, $cm);
require_capability('mod/quiz:manage', $contexts->lowest());

// Create quiz question bank view.
// TODO: Remove dependency on mod_quiz here!
$questionbank = new mod_quiz\question\bank\custom_view($contexts, $thispageurl, $course, $cm, $quiz);
$questionbank->set_quiz_has_attempts(quiz_has_attempts($quiz->id));

// Output.
$output = $PAGE->get_renderer('mod_quiz', 'edit');
$contents = $output->question_bank_contents($questionbank, $pagevars);
echo json_encode(array(
    'status'   => 'OK',
    'contents' => $contents,
));
