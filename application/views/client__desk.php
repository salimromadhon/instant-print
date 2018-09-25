<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Desk</h1>

<div style="margin-bottom: 1.5em">
	<strong><?php echo $client['name']; ?></strong> &middot; <a href="<?php echo base_url('client/out'); ?>" title="End this session">Check-out</a><br>
	<input class="u-full-width text-small" type="text" value="<?php echo $client['token']; ?>" readonly="readonly" style="padding: 0; height: 1em; border: none; margin: 0; color: grey" title="Double click to select token">
</div>

<div><?php if (isset($message)) echo $message; ?></div>

<div>
	<h5>Upload</h5>
	<?php echo form_open_multipart('client/do_upload'); ?>
		<div>
			<label id="upload-box" class="button u-full-width" for="clientfile" style="white-space: nowrap; overflow: hidden;">
				<i class="icon cloud upload"></i>
				<div id="upload-info" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-align: left;">Select file&hellip; (.txt)</div>
			</label>					
			<input type="file" id="clientfile" name="clientfile" style="display: none">
			<input class="button button-primary" type="submit" value="Upload">
		</div>
	</form>
</div>

<div style="margin-bottom: 3em">
	<h5>Files</h5>
	<?php
		$output = '';

		function action($identifier, $progress)
		{
			switch ($progress) {
				case 0:
					return '<a href="'.base_url('client/print_file/'.$identifier).'">Print</a>';
					break;

				case 1:
					return '<span class="text-warning">Queued</span>';
					break;

				case 2:
					return '<span class="text-success">Printed</span>';
					break;
				
				default:
					return NULL;
					break;
			}

			return NULL;
		}

		foreach ($files as $file)
		{
			$output .= '<tr>';
			$output .= '<td><a href="'.base_url('client/load_file/'.$file['identifier']).'" target="_blank">'.$file['name'].'</a></td>';
			$output .= '<td>'.date('Y/m/d H:i:s', $file['created_at']).'</td>';
			$output .= '<td>'.action($file['identifier'], $file['progress']).'</td>';
			$output .= '</tr>';
		}

		if (count($files) > 0)
		{
			$output = '
				<table class="u-full-width">
					<thead>
						<tr>
							<th>Name</th>
							<th>Date</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
			'.$output.'
					</tbody>
				</table>
			';
			echo $output;
		}
		else
		{
			echo '<p class="text-muted">Nothing here.</p>';
		}
	?>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$("#clientfile").change(function() {
			if($(this).not(":empty")) {
				// $("#upload-box").addClass("blue");
				$("#upload-info").html($(this).val());
			}
		});
		$("#clientfile").change(function() {
			if($(this).val() == "") {
				// $("#upload-box").removeClass("blue");
				$("#upload-info").html("Select file&hellip; (.txt)");
			}
		});
	});
</script>