<?php

class block_welcome_student extends block_base {
    public function init() {
        $this->title = get_string('welcome_student', 'block_welcome_student');
    }

    // The PHP tag and the curly bracket for the class definition
    // will only be closed after there is another function added in the next section.

    public function get_content()
    {
        global $USER;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;

        foreach (get_user_roles(context_system::instance(), $USER->id) as $role) {
            if ($role->name == 'Student' || $role->shortname == 'student') {
                $this->content->text = "
<p>Governance Practitioner Exams (Lily to link to GP exams)</p>
 <p>Governance Practitioner Work Experience (Lily to link to https://lms-chartgov.co.za/course/view.php?id=483)</p>
 <p>Board Exams (Lily to link to Board Exams)</p>
 <p>Board Assignments (Lily to link to Board assignments)</p>
 <p>Board Work Experience (Lily to link to https://lms-chartgov.co.za/course/view.php?id=482)</p>
<h3>COMPULSORY BOARD ASSIGNMENTS</h3>
<p>The closing date for the May 2023 assignments is 11 April 2023 at 14H00.</p>
<ul>
<li>Company Secretarial Practice</li>
<li>Finance for Decision-Making</li> 
<li>Applied Governance</li>
<li>Corporate Law</li>
<li>Development of Strategy</li>
<li>Risk and Compliance</li>
</ul>
<p>Only submit assignments for the module/s you are registered for in May 2023. If you are registered for other modules that does not appear on the list above, there is no assignment.</p>
                ";
                break;
            }
        }

        return $this->content;
    }
}
