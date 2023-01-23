<?php
// var_dump($activity->result());
// var_dump($_SESSION);
?>
<?php $this->view('header') ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Data Activity
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-body">
                        <table class="table table-responsive table-bordered table-striped tableDataActivity">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Number</th>
                                    <th>Created</th>
                                    <th>Assign</th>
                                    <th>Activity</th>
                                    <th>Priority</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($activity->result() as $data) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $data->number ?></td>
                                        <td><?= date('Y-m-d H:i:s', strtotime($data->CreateTime)) ?></td>
                                        <td><?= $data->assign ?></td>
                                        <td><?= $data->activity_name ?></td>
                                        <td><?= $data->priority ?></td>
                                        <td>
                                            <span class="label label-<?= $data->status == 'close' ? 'danger' : ($data->status == 'cancel' ? 'warning' : 'primary') ?>"><?= $data->status ?></span>
                                        </td>
                                        <td>
                                            <a class="btn btn-info btn-xs" href="<?= base_url($_SESSION['user_page'].'/showFormReadOnly/') . $data->number ?>">lihat</a>
                                            <?php if ($data->status != 'close' && $data->status != 'cancel' && $data->CreatedBy == $_SESSION['username'] || $_SESSION['user_page'] == 'Administrator') { ?>
                                                <a class="btn btn-primary btn-xs" href="<?= base_url($_SESSION['user_page'].'/showFormUpdate/') . $data->number ?>">update</a>
                                            <?php } ?>
                                            <?php if ($_SESSION['user_page'] == 'admin') { ?>
                                                <a href="<?= base_url($_SESSION['user_page'].'/deleteActivity/') . $data->number ?>" class="btn btn-danger btn-xs">delete</a>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $this->view('footer') ?>
<script>
    $(document).ready(function() {
        $('.tableDataActivity').dataTable({
            rowReorder: {
                selector: 'td:nth-child(2)'
            },
            responsive: true,
        });
    })
</script>