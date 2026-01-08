<?php

namespace App\Controllers;

use App\Models\Notes;

class NotesController {
    protected $di;
    protected $request;
    protected $view;
    protected $response;
    protected $flashSession;
    protected $db;

    public function _setDi($di) {
        $this->di = $di;
        $this->request = $di->get('request');
        $this->view = $di->get('view');
        $this->response = $di->get('response');
        $this->flashSession = $di->get('flashSession');
        $this->db = $di->get('db');
    }

    /**
     * Index - Tampil daftar semua catatan
     */
    public function indexAction()
    {
        // Ambil semua catatan
        $notes = Notes::find([
            'order' => 'tanggal DESC, id DESC'
        ]);

        // Set view data
        $this->view->setViewsDir(dirname(__DIR__) . '/views/');
        $this->view->setFlashSession($this->flashSession);
        $this->view->render('notes/index', ['notes' => $notes]);
    }

    /**
     * Create - Tampilkan form dan simpan catatan baru
     */
    public function createAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = trim($_POST['judul'] ?? '');
            $isi = trim($_POST['isi'] ?? '');
            $tanggal = trim($_POST['tanggal'] ?? date('Y-m-d'));

            // Validasi input
            $errors = [];
            if (empty($judul)) {
                $errors[] = 'Judul harus diisi';
            }
            if (empty($isi)) {
                $errors[] = 'Isi catatan harus diisi';
            }
            if (empty($tanggal)) {
                $errors[] = 'Tanggal harus diisi';
            }

            if (!empty($errors)) {
                $this->flashSession->_flash('error', implode(', ', $errors));
            } else {
                $note = new Notes();
                $note->judul = $judul;
                $note->isi = $isi;
                $note->tanggal = $tanggal;

                if ($note->save()) {
                    $this->flashSession->_flash('success', 'Catatan berhasil ditambahkan!');
                    header('Location: /notes');
                    exit;
                } else {
                    $messages = [];
                    foreach ($note->getMessages() as $msg) {
                        $messages[] = $msg->message ?? '';
                    }
                    // Log error untuk debug
                    error_log('Save failed. Validation result: ' . ($note->validation() ? 'true' : 'false'));
                    error_log('Messages: ' . implode(', ', $messages));
                    
                    if (empty($messages)) {
                        $this->flashSession->_flash('error', 'Gagal menyimpan catatan!');
                    } else {
                        $this->flashSession->_flash('error', implode(', ', $messages));
                    }
                }
            }
        }

        $this->view->setViewsDir(dirname(__DIR__) . '/views/');
        $this->view->setFlashSession($this->flashSession);
        $this->view->render('notes/create');
    }

    /**
     * Edit - Tampilkan form edit dan update catatan
     */
    public function editAction($id = null)
    {
        if (!$id) {
            $this->flashSession->_flash('error', 'ID catatan tidak ditemukan!');
            header('Location: /notes');
            exit;
        }

        $note = Notes::findFirstById($id);

        if (!$note) {
            $this->flashSession->_flash('error', 'Catatan tidak ditemukan!');
            header('Location: /notes');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $judul = trim($_POST['judul'] ?? '');
            $isi = trim($_POST['isi'] ?? '');
            $tanggal = trim($_POST['tanggal'] ?? '');

            // Validasi input
            $errors = [];
            if (empty($judul)) {
                $errors[] = 'Judul harus diisi';
            }
            if (empty($isi)) {
                $errors[] = 'Isi catatan harus diisi';
            }
            if (empty($tanggal)) {
                $errors[] = 'Tanggal harus diisi';
            }

            if (!empty($errors)) {
                $this->flashSession->_flash('error', implode(', ', $errors));
            } else {
                $note->judul = $judul;
                $note->isi = $isi;
                $note->tanggal = $tanggal;

                if ($note->save()) {
                    $this->flashSession->_flash('success', 'Catatan berhasil diperbarui!');
                    header('Location: /notes');
                    exit;
                } else {
                    $messages = [];
                    foreach ($note->getMessages() as $msg) {
                        $messages[] = $msg->message ?? '';
                    }
                    if (empty($messages)) {
                        $this->flashSession->_flash('error', 'Gagal memperbarui catatan!');
                    } else {
                        $this->flashSession->_flash('error', implode(', ', $messages));
                    }
                }
            }
        }

        $this->view->setViewsDir(dirname(__DIR__) . '/views/');
        $this->view->setFlashSession($this->flashSession);
        $this->view->render('notes/edit', ['note' => $note]);
    }

    /**
     * Delete - Hapus catatan
     */
    public function deleteAction($id = null)
    {
        if (!$id) {
            $this->flashSession->_flash('error', 'ID catatan tidak ditemukan!');
            header('Location: /notes');
            exit;
        }

        $note = Notes::findFirstById($id);

        if (!$note) {
            $this->flashSession->_flash('error', 'Catatan tidak ditemukan!');
            header('Location: /notes');
            exit;
        }

        if ($note->delete()) {
            $this->flashSession->_flash('success', 'Catatan berhasil dihapus!');
        } else {
            $this->flashSession->_flash('error', 'Gagal menghapus catatan!');
        }

        header('Location: /notes');
        exit;
    }
}
