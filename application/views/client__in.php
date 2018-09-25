<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Check-in</h1>
<?php echo form_open(); ?>
<?php echo form_error('token', '<div class="message message-error">', '</div>'); ?>
<?php if (isset($message)) echo $message; ?>
	<input class="u-full-width" name="token" type="text" placeholder="Enter your token&hellip;">
</form>