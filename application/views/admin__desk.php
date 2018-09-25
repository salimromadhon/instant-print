<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h1>Desk</h1>

<div style="margin-bottom: 1.5em">
	<strong><?php echo $this->session->userdata('username'); ?></strong> &middot; <a href="<?php echo base_url('admin/out'); ?>" title="End this session">Check-out</a>
</div>

<div><?php if (isset($message)) echo $message; ?></div>

<div style="margin-bottom: 3em">
	<h5>Files</h5>
	<?php
		$output = '';

		function action($token, $identifier, $progress)
		{
			switch ($progress) {
				case 1:
					return '<a href="'.base_url('admin/print_file/'.$token.'/'.$identifier).'">Finish</a>';
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
			$output .= '<td><a href="'.base_url('admin/load_file/'.$file['client_token'].'/'.$file['identifier']).'" target="_blank">'.$file['fname'].'</a></td>';
			$output .= '<td>'.$file['cname'].'</td>';
			$output .= '<td>'.date('Y/m/d H:i:s', $file['created_at']).'</td>';
			$output .= '<td>'.action($file['client_token'], $file['identifier'], $file['progress']).'</td>';
			$output .= '</tr>';
		}

		if (count($files) > 0)
		{
			$output = '
				<table class="u-full-width">
					<thead>
						<tr>
							<th>Name</th>
							<th>Author</th>
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