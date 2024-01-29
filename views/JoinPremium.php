<?php

namespace PHPMaker2024\prj_alfa;

// Page object
$JoinPremium = &$Page;
?>
<?php
$Page->showMessage();
?>
<!--
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
-->

<!-- <div class="card">
	<div class="card-header">
		<h5 class="m-0">Latest News</h5>
	</div>
	<div class="card-body">
		<h6 class="card-title">2023/09/05 - PHPMaker 2024 Released</h6>
		<p class="card-text">For more information, please visit PHPMaker website.</p>
		<a href="https://phpmaker.dev" class="btn btn-primary">Go to PHPMaker website</a>
	</div>
</div> -->

<!-- <div class="card" style="width: 18rem;"> -->
<div class="card">
	<div class="card-body">
		<!-- <h5 class="card-title">Membership</h5> -->
		<h6 class="card-title">Membership</h6>
		<p class="card-text">Skema Membership</p>
		<!-- <h6 class="card-subtitle text-body-secondary">Skema Membership</h6> -->
		<!-- <br> -->
		<!-- <p class="card-text">Selamat datang ...</p> -->
		<p class="card-text">Di website ini, membership dibagi menjadi dua, yaitu FREE dan PREMIUM. Untuk membership free hanya dapat mengakses materi tertentu saja, sedangkan untuk membership premium dapat mengakses semua materi yang tersedia.</p>
		<p class="card-text">Saat ini membership premium yang tersedia hanya untuk jangka waktu satu tahun dengan biaya HANYA SEBESAR</p>
		<!-- <p class="card-text">Rp. 100.000</p> -->
        <h6 class="card-title">Rp. 100.000</h6>
        <br>
        <br>
		<p class="card-text">dengan demikian, selama satu tahun ke depan jika terdapat tambahan/update materi yang diperuntukkan bagi member premium maka Anda dapat langsung mengaksesnya tanpa tambahan biaya apapun.</p>
		<p class="card-text">Jika setelah satu tahun terdapat tambahan/update materi maka untuk dapat mengakses materi tersebut Anda diharuskan memperpanjang akun premium Anda.</p>
		<p class="card-text">Selain dapat mengakses materi yang tersedia, sebagai member premium Anda juga akan mendapatkan benefit berupa voucher potongan harga, catatan: voucher tersebut dapat berubah sewaktu-waktu.</p>
		<p class="card-text">Skema dan harga di atas hanya tersedia pada masa promosi saja yang tidak tentu batas waktunya (masa promosi dapat berubah sewaktu-waktu), untuk itu segera manfaatkan kesempatan baik ini. Banyak orang telah merasakan manfaat materi premium yang tersedia di website ini, tentunya Anda juga bukan ?</p>
		<!-- <a href="#" class="card-link">Card link</a> -->
		<!-- <a href="#" class="card-link">Another link</a> -->
        <p><a class="btn btn-primary" href="register">Beli Akun Premium 1 tahun</a></p>
	</div>
</div>
<?= GetDebugMessage() ?>
