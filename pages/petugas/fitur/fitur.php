<script type="text/javascript">
  function pilih_level(){
    var level = $("#level").val();
    $.ajax({
      url: "pages/petugas/fitur/ambillevel.php",
      data: "level="+level,
      cache: false,
      success: function(msg){
        $("#list_parent").html(msg);
      }
    });
    pilih_parent();
  }

  function pilih_parent(){
    var level = $("#level").val();
    var parent = $("#parent").val();
    $.ajax({
      url: "pages/petugas/fitur/kode.php",
      data: "level="+level+"&parent="+parent,
      cache: false,
      success: function(msg){
        $("#id").val(msg);
      }
    });
  }
</script>
<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Fitur Aplikasi</h5>
        <div class="button-items" style="padding-bottom: 10px;">
          <button type="button" class="btn btn-primary" onclick="tambah(0, 0);" data-toggle='modal' data-target='#Modal'>Tambah Root</button>
        </div>
        <div class="dataTable_wrapper">
          <table class="table table-striped table-bordered table-hover" id="dataTables">
            <thead>
              <tr style="font-weight: bold;">
                <th width="40px">No</th>
                <th width="40px">Icon</th>
                <th>Title</th>
                <th>Link</th>
                <th>User</th>
                <th width="40px">Publish</th>
                 <th width="40px">Urut</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?
              $cari=mysqli_query($koneksi_skpi, "SELECT * from fitur_skpi where level=0 order by fitur_order, id");
              $no=0;
              while($d=mysqli_fetch_assoc($cari)){
                $no++;
                $level=1;
                echo "<tr class='odd gradeX'>
                <td class=center>$no</td>
                <td align=center><i class='fas fa-$d[icon]'></i></td>
                <td>$d[title]</td>
                <td>$d[url]</td>
                <td>$d[user]</td>
                <td>$d[publish]</td>
                <td>$d[fitur_order]</td>
                <td width='80px' align=center align=center nowrap><div class='btn-group'>
                <button type='button' class='btn btn-primary btn-sm' onclick='tambah($d[id], $level);' data-toggle='modal' data-animation='bounce' data-target='#Modal'><i class='fas fa-plus'></i></button>
                <a href='#' data-toggle='modal' data-target='#Modal' OnClick='return edit(\"$d[id]\");' title='Edit Data' class='btn btn-success btn-sm'><i class='fa fa-fw fa-edit'></i></a>
                <a href='#' OnClick=\"return hapus('$d[id]')\" title='Hapus Data' class='btn btn-warning btn-sm'><i class='fas fa-fw fa-trash'></i></a>
                </div></td>
                </tr>";
                $cari2=mysqli_query($koneksi_skpi, "SELECT * from fitur_skpi where parent_id=$d[id] order by fitur_order, id");
                while($d2=mysqli_fetch_assoc($cari2)){
                  $no++;
                  $spasi=str_repeat("&nbsp;", 5);
                  echo "<tr class='odd gradeX'>
                  <td class=center>$no</td>
                  <td align=center><i class='fas fa-$d2[icon]'></i></td>
                  <td>$spasi $d2[title]</td>
                  <td>$d2[url]</td>
                  <td>$d2[user]</td>
                  <td>$d2[publish]</td>
                  <td>$d2[fitur_order]</td>
                  <td align=center align=center nowrap><div class='btn-group'>
                  <a href='#' data-toggle='modal' data-target='#Modal' OnClick='return edit(\"$d2[id]\");' title='Edit Data' class='btn btn-success btn-sm'><i class='fa fa-fw fa-edit'></i></a>
                  <a href='#' OnClick=\"return hapus('$d2[id]')\" title='Hapus Data' class='btn btn-warning btn-sm'><i class='fas fa-fw fa-trash'></i></a>
                  </div></td>
                  </tr>";
                  $cari3=mysqli_query($koneksi_skpi, "SELECT * from fitur_skpi where parent_id=$d2[id] order by fitur_order, id");
                  while($d3=mysqli_fetch_assoc($cari3)){
                    $no++;
                    $spasi=str_repeat("&nbsp;", 10);
                    echo "<tr class='odd gradeX'>
                    <td class=center>$no</td>
                    <td align=center><i class='fas fa-$d3[icon]'></i></td>
                    <td>$spasi $d3[title]</td>
                    <td>$d3[url]</td>
                    <td>$d3[user]</td>
                    <td>$d3[publish]</td>
                    <td>$d3[fitur_order]</td>
                    <td align=center align=center nowrap><div class='btn-group'>
                    <a href='#' data-toggle='modal' data-target='#Modal' OnClick='return edit(\"$d3[id]\");' title='Edit Data' class='btn btn-success btn-sm'><i class='fa fa-fw fa-edit'></i></a>
                    <a href='#' OnClick=\"return hapus('$d3[id]')\" title='Hapus Data' class='btn btn-warning btn-sm'><i class='fas fa-fw fa-trash'></i></a>
                    </div></td>
                    </tr>";
                  }
                }
              }
              ?>
            </tbody>
          </table>
        </div>               
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="Modal" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form class="form-horizontal" method="post" action="pages/petugas/<?= $url[0] ?>/proses.php" enctype="multipart/form-data" id="form" role="form">
        <div class="modal-header">
          <h5 class="modal-title align-self-center" id="myLargeModalLabel">Fitur Aplikasi</h5>
          <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="aksi" id="aksi" value="">
          <input type="hidden" name="id" id="id" value="">
          <input type="hidden" name="parent_id" id="parent_id" value="">
          <input type="hidden" name="level" id="level" value="">
          <div class="mb-3 row">
            <label class="col-sm-3 control-label" style="text-align: right">Icon</label>
            <div class="col-sm-5">
              <input type="text" name="icon" id="icon" class="form-control">
            </div>
            <div class="col-sm-4" id="ticon">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 control-label" style="text-align: right">Title</label>
            <div class="col-sm-9">
              <input type="text" name="title" id="title" class="form-control" required>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 control-label" style="text-align: right">URL</label>
            <div class="col-sm-9">
              <input type="text" name="url" id="url" class="form-control">
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 control-label" style="text-align: right">Order</label>
            <div class="col-sm-3">
              <input type="number" name="fitur_order" id="fitur_order" class="form-control" required>
            </div>
            <label class="col-sm-3 control-label" style="text-align: right">Publish</label>
            <div class="col-sm-3">
              <select name="publish" id="publish" class="form-control">
                <?
                $sts=array('Y','N');
                foreach ($sts as $k) {
                  echo "<option value='$k'>$k</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="mb-3 row">
            <label class="col-sm-3 control-label" style="text-align: right">User</label>
            <div class="col-sm-9">
              <select name="user[]" id="user" class="form-control mb-3 w-100" style="height:100px;" multiple>
                <?
                $usr=mysqli_query($koneksi_sikadu, "SELECT id_hak, nama_hak FROM hak_akses ORDER BY nama_hak");
                while($t=mysqli_fetch_assoc($usr)){
                  echo "<option value='$t[id_hak]' $sel>$t[nama_hak]</option>";
                }
                ?>
              </select>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary btn-fill btn-magnify" value="Simpan">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<script src="assets/js/jquery-3.1.1.min.js"></script>
<!-- <link href="assets/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
<script src="assets/select2/js/select2.min.js"></script> -->
<script type="text/javascript">
  $('#form').submit(function(evt) {
    evt.preventDefault();
    $('.btn').attr("disabled", "disabled");
    var formData = new FormData(this);
    $.ajax({
      type: 'POST',
      url: $(this).attr('action'),
      data:formData,
      cache:false,
      contentType: false,
      processData: false,
      success: function(data) {
        if(data==""){
          window.location.reload();
        }
        else{
          alert(data);
        }
      },
      error: function(data) {
        alert(data);
      }
    });
  });

  function hapus(a){
    var kode=a;
    var x = confirm("Are you sure you want to delete?");
    if (x){
      $.ajax({
        url: "pages/petugas/fitur/proses.php",
        type: "POST",
        data: "aksi=delete&id="+kode,
        success: function (data) {
          if(data=="")
            location.reload();
          else
            alert(data);
        },
        error: function(error){

        }
      });
    }
    else
      return false;
  }

  function tambah(a, b){
    $('#aksi').val("tambah");
    $('#parent_id').val(a);
    $('#level').val(b);
    $('#id').val(0);
    $('#icon').val("");
    $('#ticon').html("");
    $('#title').val("");
    $('#url').val("");
    $('#fitur_order').val("");
    $('#publish').val("Y");
    $("#user").val("").trigger('change');
  }

  function edit(a){
    $('.btn').attr("disabled", "disabled");
    $.ajax({
      url: "pages/petugas/fitur/proses.php",
      type: "POST",
      dataType: "JSON",
      data: "aksi=cari&id="+a,
      success: function (data) {
        $('#aksi').val("edit");
        $('#parent_id').val(data.parent_id);
        $('#level').val(data.level);
        $('#id').val(data.id);
        $('#icon').val(data.icon);
        $('#ticon').html("<i class='fas fa-"+data.icon+"'></i>");
        $('#title').val(data.title);
        $('#url').val(data.url);
        $('#fitur_order').val(data.fitur_order);
        $('#publish').val(data.publish);
        $("#user").val(data.user);
        // $('#aktif').focus();
        $('.btn').removeAttr("disabled");
      },
      error: function(error){
        alert("Error : "+error.message);
      }
    });
  }
</script>