<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Join</h1>
<?php echo form_error('name', '<div class="message message-error">', '</div>'); ?>
<?php echo form_open(); ?>
	<input class="u-full-width" name="name" type="text" placeholder="Enter your name&hellip;">
</form>