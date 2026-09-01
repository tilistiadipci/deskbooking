<!-- Breadcrumbs-->
  <style>
  	ol {
  		height: 40px;
  	}
  	.ayam {
  		font-size: 20px;
  		font-family: Calibri;
  	}
  </style>
<ol class="breadcrumb breadcrumb-bg-blue">
<?php foreach ($this->uri->segments as $segment): ?>
	<?php 
		$url = substr($this->uri->uri_string, 0, strpos($this->uri->uri_string, $segment)) . $segment;
		$is_active =  $url == $this->uri->uri_string;
	?>
	<li class="breadcrumb-item <?php echo $is_active ? 'active': '' ?> ayam">
		<?php if($is_active): ?>
			<?php echo ucfirst($segment) ?>
		<?php else: ?>
			<?php echo ucfirst($segment) ?></a>
		<?php endif; ?>
	</li>
<?php endforeach; ?>
</ol>
