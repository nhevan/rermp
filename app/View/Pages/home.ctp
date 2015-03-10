<?php
if (!Configure::read('debug')):
	throw new NotFoundException();
endif;
App::uses('Debugger', 'Utility');
?>
<h2>Overview of RERMP2, LGED</h2>
<span class="notice success">
This is the Homepage of this Application. Please login to proceed.
</span>
<p>
This Page contains all the general information about the project RERMP2.
</p>