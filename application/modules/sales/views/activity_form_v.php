<?php
// var_dump($activity_detail->result());
// var_dump($page);
// var_dump($_SESSION);
?>
<?php $this->view('header') ?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <a href="<?= base_url($_SESSION['user_page'] . '/showActivity') ?>" class="btn btn-warning pull-right">Cancel</a>
    <h1>
      Form Activity
    </h1>
  </section>
  <!-- Main content -->
  <form method="post" id="fileinfo" action="javascript:void(0)">
    <input type="hidden" name="page" value="<?= $page ?>">
    <section class="content">
      <div class="row">
        <div class="col-md-4">
          <div class="box box-primary">
            <div class="box-body">
              <table class="table table-resposive table-bordered">
                <tr>
                  <td>
                    <label for="">Number</label>
                  </td>
                  <td>
                    <input type="text" class="form-control" id="number" name="number" placeholder="" value="<?= $activity->number ?>" readonly>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="">Activity</label>
                  </td>
                  <td>
                    <select class="form-control" id="activity" name="activity" <?= $page == 'readonly' || $page == 'update' ? 'disabled' : '' ?>>
                      <option value="">--Pilih--</option>
                      <?php foreach ($showActivity as $act) { ?>
                        <option value="<?= $act->id ?>" <?= $act->id == $activity->activity ? 'selected' : '' ?>><?= ucwords($act->activity_name) ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="">Start Periode</label>
                  </td>
                  <td>
                    <input class="form-control" type="date" id="start_periode" name="start_periode" value="<?= $activity->start_periode ?>" <?= $page == 'readonly' || $page == 'update' ? 'readonly' : '' ?>>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="">End Periode</label>
                  </td>
                  <td>
                    <input class="form-control" type="date" id="end_periode" name="end_periode" value="<?= $activity->end_periode ?>" <?= $page == 'readonly' || $page == 'update' ? 'readonly' : '' ?>>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="">Brand</label>
                  </td>
                  <?php if ($page == 'create_new') { ?>
                    <td>
                      <select class="form-control select2" multiple="multiple" name="brand[]" id="brand">
                        <option value="">--Pilih--</option>
                        <?php foreach ($showBrand as $br) { ?>
                          <option value="<?= $br->BrandCode ?>" <?= $br->BrandCode == $activity->brand_code ? 'selected' : '' ?>><?= $br->BrandName ?></option>
                        <?php } ?>
                      </select>
                    </td>
                  <?php } else { ?>
                    <td>
                      <?php
                      $brand = explode(',', $activity->brand_code);
                      foreach ($brand as $bb) {
                      ?>
                        <span class="label label-primary"><?= BrandName($bb) ?></span>
                      <?php } ?>
                    </td>
                  <?php } ?>
                </tr>
                <tr>
                  <td>
                    <label for="">Assign To</label>
                  </td>
                  <td>
                    <!-- <input type="text" class="form-control" name="assign" id="assign" placeholder="" value="<?= $activity->assign ?>" readonly> -->
                    <select name="assign" id="assign" class="form-control">
                      <option value="">--Pilih--</option>
                      <?php foreach ($assign_to->result() as $assign) { ?>
                        <option value="<?= $assign->spv ?>" <?= $assign->spv == $activity->assign ? 'selected' : '' ?>><?= getFullname($assign->spv) ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="">Customer</label>
                  </td>
                  <td>
                    <select class="form-control select2" name="customer" id="customer">
                      <option value="">--Pilih--</option>
                      <?php foreach ($customer as $bp_name) { ?>
                        <option value="<?= $bp_name->CardCode ?>" <?= $activity->customer_code == $bp_name->CardCode ? 'selected' : '' ?>><?= $bp_name->CustomerName ?></option>
                      <?php } ?>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="">Doc. Ref</label>
                  </td>
                  <td>
                    <input type="text" class="form-control" name="no_ref" id="no_ref" placeholder="" value="<?= $activity->no_ref ?>" <?= $page == 'readonly' || $page == 'update' ? 'readonly' : '' ?>>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="">Priority</label>
                  </td>
                  <td>
                    <select class="form-control" name="priority" id="priority" <?= $page == 'readonly' || $page == 'update' ? 'disabled' : '' ?>>
                      <option value="" <?= $activity->priority == null ? 'selected' : '' ?>>--Pilih--</option>
                      <option value="urgent" <?= $activity->priority == 'urgent' ? 'selected' : '' ?>>Urgent</option>
                      <option value="medium" <?= $activity->priority == 'medium' ? 'selected' : '' ?>>Medium</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="">Status</label>
                  </td>
                  <td>
                    <?php if ($page == 'create_new') { ?>
                      <input class="form-control" type="text" id="status" name="status" value="open" readonly>
                    <?php } else { ?>
                      <select class="form-control select2" name="status" id="status" <?= $page == 'readonly' ? 'disabled' : '' ?>>
                        <option value="" <?= $activity->status == null ? 'selected' : '' ?>>--Pilih--</option>
                        <option value="open" <?= $activity->status == 'open' || $page == 'create_new' ? 'selected' : '' ?>>Open</option>
                        <option value="close" <?= $activity->status == 'close' ? 'selected' : '' ?>>Close</option>
                        <option value="cancel" <?= $activity->status == 'cancel' ? 'selected' : '' ?>>Cancel</option>
                      </select>
                    <?php } ?>
                  </td>
                </tr>
                <?php if ($page != 'create_new') { ?>
                  <tr>
                    <td>
                      <label for="">Created Date</label>
                    </td>
                    <td>
                      <input type="text" class="form-control" id="" placeholder="" value="<?= date('Y-m-d H:i:s', strtotime($activity->CreateTime)) ?>" readonly>
                    </td>
                  </tr>
                <?php } ?>
                <?php if ($page == 'readonly' && $activity->status == 'close') { ?>
                  <tr>
                    <td>
                      <label for="">Closed Date</label>
                    </td>
                    <td>
                      <input type="text" class="form-control" id="" placeholder="" value="<?= date('Y-m-d H:i:s', strtotime($activity->ClosedTime)) ?>" readonly>
                    </td>
                  </tr>
                <?php } ?>
              </table>
            </div>
          </div>
        </div>

        <div class="col-md-8">
          <?php if ($page == 'update' || $page == 'readonly') { ?>
            <?php if ($activity_detail->result() > 0) { ?>
              <?php $no = 1;
              $num = 1;
              foreach ($activity_detail->result() as $data) { ?>
                <div class="nav-tabs-custom" style="background-color:bisque">
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab_<?= $no++ ?>" data-toggle="tab">Remaks</a></li>
                    <li><a href="#tab_<?= $no++ ?>" data-toggle="tab">Picture Uploaded</a></li>
                    <li><a href="#tab_<?= $no++ ?>" data-toggle="tab">Content</a></li>
                  </ul>
                  <div class="tab-content" style="background-color:bisque">
                    <small class="text-muted pull-right"><i class="fa fa-clock-o"></i> <?= date('M j, Y, g:i a', strtotime($data->CreateTime)) ?></small>
                    <div class="tab-pane active" id="tab_<?= $num++ ?>">
                      <div class="form-group">
                        <textarea id="" class="form-control" rows="3" placeholder="Enter Remaks" disabled><?= $data->remaks ?></textarea>
                      </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_<?= $num++ ?>">
                      <div class="form-group">
                        <?php
                        $image = explode(',', $data->image_name);
                        foreach ($image as $img) {
                        ?>
                          <!-- <a href=""> -->
                          <img onclick="showModal('<?= $img ?>')" src="<?= $img == '' ? base_url() . 'uploads/image_activity/no_image.jpg' : base_url() . 'uploads/image_activity/' . $img ?>" class="user-image" alt="User Image" style="max-width:150px;">
                          <!-- </a> -->
                        <?php } ?>
                      </div>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_<?= $num++ ?>">
                      <div class="form-group">
                        <textarea id="" class="form-control" rows="3" placeholder="Enter Content" disabled><?= $data->content ?></textarea>
                      </div>
                    </div>
                    <div class="box">
                      <div class="box-header">
                      </div>
                      <div class="box-body chat-box" id="chat-box">
                        <?php foreach (getComentar($data->number_activity, $data->content_id)->result() as $komentar) { ?>
                          <input type="hidden" id="content_id_" value="<?= $komentar->content_id ?>">
                          <input type="hidden" id="activity_number_" value="<?= $komentar->activity_number ?>">
                          <div class="item">
                            <a href="#" class="name">
                              <small class="text-muted pull-right"> <?= date('M j, Y, g:i a', strtotime($komentar->create_date)) ?></small>
                              <?= $komentar->id_comentator ?>
                            </a>
                            <p class="message">
                              <?= $komentar->isi_komentar ?>
                            </p>
                          </div>
                        <?php } ?>
                      </div>
                      <div class="box-footer">
                        <!-- <span>2 pesan belum dibaca <button class="btn btn-primary btn-xs">Baca Sekarang</button></span> -->
                        <div class="input-group">
                          <input type="hidden" id="number_activity" value="<?= $data->number_activity ?>">
                          <input type="hidden" id="content_id" value="<?= $data->content_id ?>">
                          <input class="form-control" id="isi_komentar" placeholder="Type message...">

                          <div class="input-group-btn">
                            <button onclick="kirimKomentar(this)" id="kirim_komentar" class="btn btn-success">Send</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              <?php } ?>
            <?php } ?>
          <?php } ?>

          <?php if ($page != 'readonly') { ?>
            <div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_a" data-toggle="tab">Remaks</a></li>
                <li><a href="#tab_b" data-toggle="tab">Upload Picture</a></li>
                <li><a href="#tab_c" data-toggle="tab">Content</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="tab_a">
                  <div class="form-group">
                    <textarea name="remaks" class="form-control" rows="3" placeholder="Enter Remaks"></textarea>
                  </div>
                </div>
                <div class="tab-pane" id="tab_b">
                  <div class="form-group">
                    <input type="file" onchange="reviewImage()" id="link_document" name="link_document[]" accept=".jpeg, .jpg, .png" multiple>
                    <span>Max size 2Mb, Jenis file (.jpeg, .jpg, .png), Total file 3</span>
                    <img class="cocote" src="" alt="" id="compresimage" style="max-width:100px;">
                    <div id="imageplace"></div>
                    <div id="imageNamePlace"></div>
                  </div>
                </div>
                <div class="tab-pane" id="tab_c">
                  <div class="form-group">
                    <textarea name="content" class="form-control" rows="3" placeholder="Enter Content"></textarea>
                  </div>
                </div>
              </div>
            </div>
            <button class="btn btn-primary pull-right" onclick="sendAll()"> <?= $page == 'create_new' ? 'Create' : 'Follow Up' ?></button>
          <?php } ?>
        </div>

      </div>
    </section>
  </form>
</div>
<div id="muncul_modal">

</div>
<?php $this->view('footer') ?>

<script>
  $(document).ready(function() {
    $('.select2').select2();

    // setInterval(function() {
    //   reloadChatBox();
    // }, 1000);

  });


  // function reloadChatBox() {
  //   var chat_box = document.querySelectorAll('div.chat-box');
  //   for (var i = 0; i < chat_box.length; i++) {
  //     if (chat_box[i].querySelectorAll('#content_id_').length > 0) {
  //       var content_id_ = chat_box[i].querySelector('#content_id_').value;
  //       var activity_number_ = chat_box[i].querySelector('#activity_number_').value;
  //       $(chat_box[i]).load('<?= base_url('activity/reload_comment') ?>', {
  //         number_activity: activity_number_,
  //         content_id: content_id_,
  //       });
  //     }
  //   }
  // }






  function kirimKomentar(elem) {
    var content = elem.parentElement.parentElement;
    var number_activity = content.querySelector('#number_activity').value;
    var id_content = content.querySelector('#content_id').value;
    var isi_komentar = content.querySelector('#isi_komentar').value;
    var chat_box = content.parentElement.parentElement.querySelector('#chat-box');

    if (isi_komentar == '') {
      return false;
    }

    $.ajax({
      url: "<?= base_url($_SESSION['user_page'] . '/kirim_komentar') ?>",
      type: "POST",
      data: {
        number_activity,
        id_content,
        isi_komentar
      },
      dataType: "JSON",
      success: function(response) {
        if (response.komentar == 'masuk') {
          $(chat_box).load('<?= base_url($_SESSION['user_page'] . '/reload_comment_after_send') ?>', {
            number_activity,
            content_id: id_content
          });
          content.querySelector('#isi_komentar').value = '';
        } else {
          alert('Komentar gagal');
        }
      }
    });
  }













  function reviewImage() {
    var file = document.getElementById('link_document').files;

    if (file.length > 0) {
      for (var x = 0; x < file.length; x++) {
        var file_size_allowed = file[x].size;
        var file_allowed = /(\.jpg|\.jpeg|\.png|\.gif)$/i;
        if (!file_allowed.exec(file[x].name)) {
          alert("File " + file[x].name + " tidak valid");
          document.getElementById('link_document').value = '';
          return false;
        } else if (file_size_allowed > 2000000) {
          alert("Ukuran file " + file[x].name + " terlalu besar max 2Mb");
          document.getElementById('link_document').value = '';
          return false;
        } else if (file.length > 3) {
          alert("Maksimal 3 File");
          document.getElementById('link_document').value = '';
          return false
        }
      }
    } else {
      var text = "Upload gambar kosong, apakah ingin lanjut?";
      if (confirm(text) == true) {
        return true;
      } else {
        return false;
      }
    }
    var imageplace = document.getElementById('imageplace');
    imageplace.innerHTML = '';
    loading()

    for (var x = 0; x < file.length; x++) {
      var compress = new Compressor(file[x], {
        quality: 1,
        maxWidth: 300,
        success(result) {
          result.name = "test.jpg";
          var reader = new FileReader();
          reader.readAsDataURL(result);
          reader.onloadend = function() {
            var base64data = reader.result;
            var img = document.createElement('img');
            img.style = "max-width:100px;margin-right:5px;";
            img.src = base64data;
            img.className = "img-review";
            imageplace.appendChild(img);
            setTimeout(loading_stop(), 5000);
          }
        },
        error(err) {}
      });
    }

  }

  function sendAll() {
    var number = $('#number').val();
    var activity = $('#activity').val();
    var start_periode = $('#start_periode').val();
    var end_periode = $('#end_periode').val();
    var brand = $('#brand').val();
    var assign = $('#assign').val();
    var customer = $('#customer').val();
    var no_ref = $('#no_ref').val();
    var priority = $('#priority').val();
    var status = $('#status').val();
    var remaks = $('#remaks').val();

    if (number == '' || activity == '' || start_periode == '' || end_periode == '' || brand == '' || assign == '' || customer == '' || no_ref == '' || priority == '' || status == '' || remaks == '') {
      alert('Semua data harus diisi');
      return false
    }

    var form = document.querySelector('form#fileinfo');
    form.action = "<?= base_url($_SESSION['user_page'] . '/prosesActivity') ?>";
    form.method = "POST";
    form.enctype = "multipart/form-data";
    form.submit();
    loading();
  }



  function loading() {
    var div = document.getElementById('muncul_loading');
    div.classList.add('loading');
  }

  function loading_stop() {
    var element = document.getElementById("muncul_loading");
    element.classList.remove("loading");
  }

  function showModal(img) {
    var muncul_modal = $('#muncul_modal').load('<?= base_url($_SESSION['user_page'] . '/showModalImage') ?>', {
      img
    });
  }
</script>