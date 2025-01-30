<div class="py-4">
  <div class="d-flex justify-content-between w-100 flex-wrap">
    <div class="mb-3 mb-lg-0">
      <h1 class="h4">SKPI Dashboard</h1>
      <p class="mb-0">Surat Keterangan Pendamping Ijazah</p>
    </div>
    <div>
      <!-- <a href="https://themesberg.com/docs/volt-bootstrap-5-dashboard/components/tables/" class="btn btn-outline-gray"><i class="far fa-question-circle mr-1"></i> Bootstrap Tables Docs</a> -->
    </div>
  </div>
</div>

<?
$akses=$_SESSION['hak_akses'];
include "pages/home_$akses.php";
?>