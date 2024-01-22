<?php

namespace PHPMaker2024\demo2024;

// Page object
$News = &$Page;
?>
<?php
$Page->showMessage();
?>
<div class="card">
	<div class="card-header">
		<h5 class="m-0">Latest News</h5>
	</div>
    <div class="card-body">
		<h6 class="card-title">22-01-2024 - Project Learning Membership <small>(alfa-release)</small> Released</h6>
		<p class="card-text">For more information, please visit Project-Learning website</p>
		<a target="_blank" href="https://projectlearning.id" class="btn btn-primary">Go to Project-Learning website</a>
	</div>
	<!--<div class="card-body">
		<h6 class="card-title">2023/09/05 - PHPMaker 2024 Released</h6>
		<p class="card-text">For more information, please visit PHPMaker website.</p>
		<a href="https://phpmaker.dev" class="btn btn-primary">Go to PHPMaker website</a>
	</div>-->
</div>

<?= GetDebugMessage() ?>
