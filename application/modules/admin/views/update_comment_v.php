<?php foreach (getComentar($number_activity, $content_id)->result() as $komentar) { ?>
    <input type="hidden" id="content_id_" value="<?= $komentar->content_id ?>">
    <input type="hidden" id="activity_number_" value="<?= $komentar->activity_number ?>">
    <div class="item">
        <a href="#" class="name">
            <small class="text-muted pull-right"></i> <?= date('M j, Y, g:i a', strtotime($komentar->create_date)) ?></small>
            <?= $komentar->id_comentator ?>
        </a>
        <p class="message">
            <?= $komentar->isi_komentar ?>
        </p>
    </div>
<?php } ?>