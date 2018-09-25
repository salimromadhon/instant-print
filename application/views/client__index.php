<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Print now</h1>
<p>You need a token to start printing.</p>
<div>
	<a class="button button-primary" href="<?php echo base_url('client/join'); ?>">Get token</a>
	<a class="button" href="<?php echo base_url('client/in'); ?>">Check-in</a>
</div>