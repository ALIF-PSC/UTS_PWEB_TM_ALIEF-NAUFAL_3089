CREATE DATABASE IF NOT EXISTS Sistem_Perpustakaan;
USE Sistem_Perpustakaan;

CREATE TABLE buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    judul_buku VARCHAR(255) NOT NULL,
    pengarang VARCHAR(100) NOT NULL,
    penerbit VARCHAR(100),
    tahun_terbit YEAR,
    isbn VARCHAR(20),
    stok INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE anggota (
    id_anggota INT AUTO_INCREMENT PRIMARY KEY,
    nama_anggota VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    telepon VARCHAR(15),
    alamat TEXT,
    tanggal_daftar DATE,
    status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif'
);

CREATE TABLE peminjaman (
    id_peminjaman INT AUTO_INCREMENT PRIMARY KEY,
    id_anggota INT,
    id_buku INT,
    tanggal_pinjam DATE,
    tanggal_kembali DATE,
    status ENUM('Dipinjam', 'Dikembalikan') DEFAULT 'Dipinjam',
    FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota),
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku)
);

