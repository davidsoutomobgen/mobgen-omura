<?php if (!$model->isNewRecord) { ?>
    <div class="box box-success collapsed-box">
        <div class="box-header with-border">
            <h3 class="box-title">API Keys</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
        </div>

        <div class="box-body">
            <table id="w0" class="table table-striped table-bordered detail-view">
                <tbody>
                <tr><th>ID</th><td><?php echo $otaProject->id; ?></td></tr>
                <tr><th>API Project</th><td><?php echo $otaProject->proAPIKey; ?></td></tr>
                <tr><th>Build API</th><td><?php echo $otaProject->proAPIBuildKey;?></td></tr>
                <tr><th>API Hash Build (to overwrite)</th><td><?php echo $model->buiHash; ?></td></tr>
                <tr><th>Modified date</th><td><?php echo date('Y-m-d H:i:s', strtotime($model->updated_at));?></td></tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="clear"></div>
<?php } ?>