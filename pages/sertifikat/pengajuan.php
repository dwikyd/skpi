<h3>Finalisasi SKPI</h3>

<div class="alert alert-secondary" role="alert">
	Dengan melakukan Finalisasi Surat Keterangan Pendamping Ijazah (SKPI), berarti anda menyatakan bahwa isi dari prestasi yang anda isikan sudah benar. Kesalahan penulisan, ejaan, penerjemahan dan penyusunan kalimat akan berdampak pada kesalahan isi dari SKPI anda. Pastikan isi sudah benar serta seluruh sertifikat prestasi SKPI sudah disetujui dan diterjemahkan, silahkan cek sekali lagi sebelum <b>Finalisasi</b>.
	<div class="text-center">
		<div class="btn-group">
			<button class="btn btn-danger" onclick="kirim();">Finalisasi</button>
			<a href="sertifikat.html" class="btn btn-info">Batal</a>
		</div>
	</div>
</div>

<script type="text/javascript">
	function kirim(){
		Swal.fire({
			title: 'Anda yakin ?',
			text: "Kami yakinkan sekali lagi kesalahan isi SKPI yang anda Finalisasi menjadi tanggung jawab anda.",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Finalisasi',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				$.ajax({
					url: 'pages/sertifikat/proses.php',
					type: "POST",
					data: "aksi=submit",
					success: function (data) {
						if(data==''){
							Swal.fire({
								title: 'Success',
								text: "SKPI berhasil di submit.",
								icon: 'success',
								showCancelButton: false,
								confirmButtonColor: '#3085d6',
								cancelButtonColor: '#d33',
								confirmButtonText: 'Oke'
							}).then((result) => {
								if (result.isConfirmed) {
									window.location.href="sertifikat.html";
								}
							})
						}
						else{
							Swal.fire('Error!', data, 'error');
						}
					}
				});
			}
		})
	}
</script>