<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title><?php if(isset($title)) echo $title . ' &ndash; '; ?>VocomPrint</title>
		<meta name="description" content="<?php if(isset($description)) echo $description; ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/normalize.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/skeleton.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
		<link rel="icon" type="image/png" href="">
		<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>" type="text/javascript"></script>
	</head>
	<body>
		<div class="container site-container" style="margin: 5em auto">