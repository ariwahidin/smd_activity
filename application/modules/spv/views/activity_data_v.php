<?php
// var_dump($activity->result());
// var_dump($_SESSION);
?>
<?php $this->view('header') ?>
<div class="content-wrapper">
    <section class="content-header">
        <a href="<?= base_url($_SESSION['user_page'] . '/showForm') ?>" class="btn btn-primary pull-right">New Activity</a>
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
                                    <th>No.Doc</th>
                                    <th>Activity</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Created</th>
                                    <th>Assign</th>
                                    <th>Priority</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;
                                foreach ($activity->result() as $data) { ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><a href="<?= base_url($_SESSION['user_page'] . '/showFormReadOnly/') . $data->number ?>"><?= $data->number ?></a></td>
                                        <td><?= ucfirst($data->activity_name) ?></td>
                                        <td><a href="<?= base_url($_SESSION['user_page'] . '/showFormReadOnly/') . $data->number ?>"><?= getCustomerName($data->customer_code) ?></a></td>
                                        <td>
                                            <?= ucfirst($data->status) ?>
                                        </td>
                                        <td><?= $data->fullname ?></td>
                                        <td><?= date('d-M-Y', strtotime($data->CreateTime)) ?></td>
                                        <td><?= getFullname($data->assign) ?></td>
                                        <td><?= ucfirst($data->priority) ?></td>
                                        <td>
                                            <a class="btn btn-info btn-xs" href="<?= base_url($_SESSION['user_page'] . '/showFormReadOnly/') . $data->number ?>">lihat</a>
                                            <!-- <?php if ($data->status != 'close' && $data->status != 'cancel' && $data->CreatedBy == $_SESSION['username'] || $_SESSION['user_page'] == 'Administrator') { ?>
                                                <a class="btn btn-primary btn-xs" href="<?= base_url($_SESSION['user_page'] . '/showFormUpdate/') . $data->number ?>">update</a>
                                            <?php } ?> -->
                                            <?php if ($_SESSION['user_page'] == 'Administrator') { ?>
                                                <a href="<?= base_url($_SESSION['user_page'] . '/deleteActivity/') . $data->number ?>" class="btn btn-danger btn-xs">delete</a>
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