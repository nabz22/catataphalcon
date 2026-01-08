<?php

namespace App\Models;

class Notes extends BaseModel {
    protected static $table = 'notes';
    
    public function validation() {
        if (empty($this->attributes['judul'])) {
            $this->addMessage('judul', 'Judul harus diisi');
            return false;
        }
        
        if (empty($this->attributes['isi'])) {
            $this->addMessage('isi', 'Isi catatan harus diisi');
            return false;
        }
        
        if (empty($this->attributes['tanggal'])) {
            $this->addMessage('tanggal', 'Tanggal harus diisi');
            return false;
        }
        
        return true;
    }
}
