<section class="content-header">
    <h1>Payment
        <small>Customer payment</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i></a></li>
        <li class="active">Payment</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Data Customers</h3>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-bordered table-striped" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Order Id</th>
                        <th>Merchant</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th>Tanggal Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($row->result() as $key => $data) { ?>
                        <tr>
                            <td style="width:5%;"><?= $no++ ?>.</td>
                            <td><?= $data->order_id ?></td>
                            <td><?= strtoupper($data->bank); ?></td>
                            <td><?= $data->gross_amount ?></td>
                            <td><?= $data->transaction_status ?></td>
                            <td><?= $data->transaction_time ?></td>
                        </tr>
                    <?php
                    } ?>
                </tbody>
            </table>
        </div>
    </div>

</section>