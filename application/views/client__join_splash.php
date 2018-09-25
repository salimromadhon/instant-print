<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Join success</h1>
<div class="message message-success">Success. Please save your token below to get continuous access for next time.</div>
<div><input class="u-full-width" type="text" value="<?php echo $token; ?>" readonly="readonly"></div>
<div><a href="<?php echo base_url('client/desk'); ?>">Continue to Desk &rarr;</a></div>