<?= $this->extend('template/layout_admin') ?>
<?= $this->section('content') ?>
<?php $rusak = validation_errors();?>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title;?></h1>
        </div>
        <div class="section-body">
            <div class="mb-3">
                <a href="<?= base_url('operator/penggajian');?>" class="btn btn-info">Kembali</a>
            </div>
            <iframe src="<?= base_url('operator/penggajian/pdf/').$bulan.'/'.$tahun;?>" frameborder="0" class="w-100"
                height="700px"></iframe>
            <iframe src="<?= base_url('operator/penggajian/slip/').$bulan.'/'.$tahun;?>" frameborder="0" class="w-100"
                height="700px"></iframe>
        </div>
    </section>
</div>
<?= $this->endSection('content') ?>