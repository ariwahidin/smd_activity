<?php
// var_dump($activity_detail->result());
// var_dump($page);
// var_dump($_SESSION);
?>
<?php $this->view('header') ?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1>
      Detail Activity
    </h1>
  </section>
  <!-- Main content -->
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
                  <?= $activity->number ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="">Activity</label>
                </td>
                <td>
                  <?= ucfirst(getActivityName($activity->activity)) ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="">Start Periode</label>
                </td>
                <td>
                  <?= date('d-M-Y', strtotime($activity->start_periode)) ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="">End Periode</label>
                </td>
                <td>
                  <?= date('d-M-Y', strtotime($activity->end_periode)) ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="">Brand</label>
                </td>
                <td>
                  <?php
                  $brand = explode(',', $activity->brand_code);
                  foreach ($brand as $bb) {
                  ?>
                    <span class="label label-primary"><?= BrandName($bb) ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="">Assign To</label>
                </td>
                <td>
                  <?= getFullname($activity->assign) ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="">Customer</label>
                </td>
                <td>
                  <?= getCustomerName($activity->customer_code) ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="">Doc. Ref</label>
                </td>
                <td>
                  <?= $activity->no_ref ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="">Priority</label>
                </td>
                <td>
                  <?= ucfirst($activity->priority) ?>
                </td>
              </tr>
              <tr>
                <td>
                  <label for="">Status</label>
                </td>
                <td>
                  <?= ucfirst($activity->status) ?>
                </td>
              </tr>
              <?php if ($page != 'create_new') { ?>
                <tr>
                  <td>
                    <label for="">Created Date</label>
                  </td>
                  <td>
                    <?= date("d-M-Y, g:i a", strtotime($activity->CreateTime)) ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="">Created By</label>
                  </td>
                  <td>
                    <?= getFullname($activity->CreatedBy) ?>
                  </td>
                </tr>
              <?php } ?>
              <?php if ($activity->status == 'close') { ?>
                <tr>
                  <td>
                    <label for="">Closed Date</label>
                  </td>
                  <td>
                    <?= date('d-M-Y, g:i a', strtotime($activity->ClosedTime)) ?>
                  </td>
                </tr>
                <tr>
                  <td>
                    <label for="">Closed By</label>
                  </td>
                  <td>
                    <?= getFullname(($activity->ClosedBy)) ?>
                  </td>
                </tr>
              <?php } ?>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-8">

        <div id="isi_content_kanan">
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
                  <small class="text-muted pull-right"><i class=""></i> <?= ucwords(strtolower(getFullname($data->CreatedBy))).', '. date('M j, Y, g:i a', strtotime($data->CreateTime)) ?></small>
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
                        <img onclick="showModal('<?= $img ?>')" src="<?= $img == '' ? base_url() . 'uploads/image_activity/no_image.jpg' : base_url() . 'uploads/image_activity/' . $img ?>" class="user-image" alt="User Image" style="max-width:100px;">
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
                            <b><?= ucwords(strtolower(getFullname($komentar->id_comentator))) ?></b>
                          </a>
                          <p class="message">
                            <?= ucfirst($komentar->isi_komentar) ?>
                          </p>
                        </div>
                      <?php } ?>
                    </div>

                    <?php if ($activity->status != 'close') { ?>
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
                    <?php } ?>

                  </div>
                </div>
              </div>

            <?php } ?>
          <?php } ?>
        </div>

        <div id="section_follow_up" style="display:none">
          <div class="nav-tabs-custom">
            <form id="form_follow_up">
              <input type="hidden" name="activity_number" value="<?= $activity->number ?>">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_a" data-toggle="tab">Remaks</a></li>
                <li><a href="#tab_b" data-toggle="tab">Upload Picture</a></li>
                <li><a href="#tab_c" data-toggle="tab">Content</a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="tab_a">
                  <div class="form-group">
                    <textarea name="remaks" id="remaks" class="form-control" rows="3" placeholder="Enter Remaks"></textarea>
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
                    <textarea id="content" name="content" class="form-control" rows="3" placeholder="Enter Content"></textarea>
                  </div>
                </div>
              </div>
            </form>

            <div class="box-footer">

              <button class="btn btn-primary pull-right" onclick="sendUpdate()">Follow Up</button>
              <button class="btn btn-warning" onclick="cancelUpdate()">Cancel</button>
            </div>

          </div>
        </div>

        <?php if ($activity->status != 'close') { ?>
          <div id="tempat_tombol">
            <button onclick="closeActivity('<?= $activity->number ?>')" class="btn btn-success">Close Activity</button>
            <button id="btn_show_follow_up" class="btn btn-primary" onclick="showFormFollowUp()">Follow Up</button>
          </div>
        <?php } ?>

      </div>

    </div>
  </section>
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

  function closeActivity(number) {
    window.location.href = '<?=base_url($_SESSION['user_page'].'/closeActivity/')?>'+number;
  }

  function showFormFollowUp() {
    var section_follow_up = document.getElementById('section_follow_up');
    var button = document.getElementById('tempat_tombol');
    button.style.display = 'none';
    section_follow_up.style.display = 'revert';
  }

  function sendUpdate() {
    // var section_update = document.querySelector('div#section_follow_up');
    var form_follow_up = document.querySelector('form#form_follow_up');
    form_follow_up.action = '<?= base_url($_SESSION['user_page'] . '/follow_up') ?>';
    form_follow_up.method = "POST";
    form_follow_up.enctype = "multipart/form-data";
    if (form_follow_up.querySelector('#remaks').value == '') {
      alert('remaks kosong');
      return false;
    }
    if (form_follow_up.querySelector('#link_document').value == '') {
      alert('upload file kosong');
      return false;
    }
    if (form_follow_up.querySelector('#content').value == '') {
      alert('content kosong');
      return false;
    }
    form_follow_up.submit();
    loading();
    // console.log(form_follow_up);
  }


  function cancelUpdate() {
    var section_follow_up = document.getElementById('section_follow_up');
    section_follow_up.style.display = 'none';
    var button = document.getElementById('tempat_tombol');
    button.style.display = 'revert';
  }


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
          alert("Ukuran file " + file[x].name + " terlalu besar");
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
        maxWidth: 200,
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

  // function sendAll() {
  //   var img_picture = document.querySelectorAll("img.img-review");
  //   var picture = [];
  //   var formData = new FormData(document.getElementById("fileinfo"));

  //   for (var i = 0; i < img_picture.length; i++) {
  //     //file blob for image
  //     formData.append("image[]", img_picture[i].src);
  //   }

  //   var number = $('#number').val();
  //   var activity = $('#activity').val();
  //   var start_periode = $('#start_periode').val();
  //   var end_periode = $('#end_periode').val();
  //   var brand = $('#brand').val();
  //   var assign = $('#assign').val();
  //   var customer = $('#customer').val();
  //   var no_ref = $('#no_ref').val();
  //   var priority = $('#priority').val();
  //   var status = $('#status').val();
  //   var remaks = $('#remaks').val();

  //   if (number == '' || activity == '' || start_periode == '' || end_periode == '' || brand == '' || assign == '' || customer == '' || no_ref == '' || priority == '' || status == '' || remaks == '') {
  //     alert('Semua data harus diisi');
  //     return false
  //   }

  //   loading();

  //   $.ajax({
  //     url: "<?= base_url($_SESSION['user_page'] . '/prosesActivity') ?>",
  //     type: "POST",
  //     data: formData,
  //     cache: false,
  //     contentType: false,
  //     processData: false,
  //     enctype: 'multipart/form-data',
  //     dataType: "JSON",
  //     success: function(response) {
  //       if (response.success == true) {
  //         window.location.href = "<?= base_url($_SESSION['user_page'] . '/showActivity') ?>";
  //       }
  //     }
  //   });
  // }



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