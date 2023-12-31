<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;

class Jadwal extends BaseController
{
    public function index()
    {
        $data['title'] = 'Jadwal';
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['jadwal'] = $this->KaryawanModel->getUserWithGuru();
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('operator/jadwal/read', $data);
    }

    public function libur()
    {
        $data['title'] = 'Libur';
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['hari'] = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        $data['jadwal'] = $this->JadwalModel;
        return view('operator/libur/read', $data);
    }

    public function create($id)
    {
        $data['title'] = 'Jadwal Guru';
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $cekJadwal = $this->JadwalModel->checkJadwal($id);
        $data['setting'] = $this->PengaturanModel->find(1);
        if ($cekJadwal) {
            $data["create"] = "Update";
            $data['jadwal'] = $this->KaryawanModel->getUserAndJabatan($id);
            $data['hari'] = $cekJadwal;
        } else {
            $data["create"] = "Submit";
            $data['hari'] = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
            $data['jadwal'] = $this->KaryawanModel->getUserAndJabatan($id);
        }
        return view('operator/jadwal/create', $data);
    }

    public function save()
    {
        $user_id = $this->request->getPost('user_id');
        $hari = $this->request->getPost('hari');
        $jam_masuk = $this->request->getPost('jam_masuk');
        $jam_keluar = $this->request->getPost('jam_keluar');
        $jam_mengajar = $this->request->getPost('jam_mengajar');
        $status = $this->request->getPost('status');
        $data = [];
        for ($i = 0; $i < count($hari); $i++) {
            $data[] = [
                'user_id' => $user_id,
                'hari' => $hari[$i],
                'jam_masuk' => $jam_masuk[$i],
                'jam_keluar' => $jam_keluar[$i],
                'jam_mengajar' => $jam_mengajar[$i],
                'status' => $status[$i],
                'status_backup' => $status[$i]
            ];
        };
        $this->JadwalModel->insertBatch($data);
        session()->setFlashdata('pesan', 'Data Jadwal berhasil ditambahkan.');
        return redirect()->to(base_url('operator/jadwal'));
    }

    public function update()
    {
        $user_id = $this->request->getPost('user_id');
        $hari = $this->request->getPost('hari');
        $jam_masuk = $this->request->getPost('jam_masuk');
        $jam_keluar = $this->request->getPost('jam_keluar');
        $jam_mengajar = $this->request->getPost('jam_mengajar');
        $status = $this->request->getPost('status');
        $data = [];
        for ($i = 0; $i < count($hari); $i++) {
            $data[] = [
                'hari' => $hari[$i],
                'jam_masuk' => $jam_masuk[$i],
                'jam_keluar' => $jam_keluar[$i],
                'jam_mengajar' => $jam_mengajar[$i],
                'status' => $status[$i],
                'status_backup' => $status[$i]
            ];
        };
        $this->JadwalModel->where('user_id', $user_id)->updateBatch($data, 'hari');
        session()->setFlashdata('pesan', 'Data Jadwal berhasil diupdate.');
        return redirect()->to(base_url('operator/jadwal'));
    }

    public function updatelibur()
    {
        $hari = $this->request->getPost('hari');
        $status = $this->request->getPost('libur');

        if (!empty($hari) && is_array($hari)) {
            foreach ($hari as $key => $h) {
                if (isset($status[$key]) && $status[$key] !== null) {
                    $allHari = $this->JadwalModel->where('hari', $h)->findAll();
                    foreach ($allHari as $row) {
                        $this->JadwalModel->update($row['id'], ['status' => $status[$key]]);
                    }
                } else {
                    $allHari = $this->JadwalModel->where('hari', $h)->findAll();
                    foreach ($allHari as $row) {
                        $this->JadwalModel->update($row['id'], ['status' => $row['status_backup']]);
                    }
                }
            }
            session()->setFlashdata('pesan', 'Data Libur berhasil diperbarui.');
        }
        return redirect()->to(base_url('operator/libur'));
    }
}