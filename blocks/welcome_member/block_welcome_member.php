<?php

class block_welcome_member extends block_base {
    public function init() {
        $this->title = get_string('welcome_member', 'block_welcome_member');
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
            if ($role->name == 'Member' || $role->shortname == 'member') {
                $this->content->text = "
<p>Our members benefit from the following:</p>
<ul>
<li>Lorem ipsum dolor sit amet</li>
<li>Consectetur adipiscing elit,</li>
<li>Sed do eiusmod tempor incididunt</li>
</ul>
<p>Continuing Professional Development (Lily to link to https://lms-chartgov.co.za/course/view.php?id=486)</p>
<p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. 
Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. 
Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur?</p>
                ";
                break;
            }
        }

        return $this->content;
    }
}
