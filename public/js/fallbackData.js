// Fallback data untuk kecamatan Depok jika API eksternal gagal
const FALLBACK_KECAMATAN = [
    { id: "3276010", name: "Pancoran Mas" },
    { id: "3276020", name: "Sukmajaya" },
    { id: "3276030", name: "Cipayung" },
    { id: "3276040", name: "Cimanggis" },
    { id: "3276050", name: "Tapos" },
    { id: "3276060", name: "Beji" },
    { id: "3276070", name: "Bojongsari" },
    { id: "3276080", name: "Cinere" },
    { id: "3276090", name: "Limo" },
    { id: "3276100", name: "Sawangan" },
    { id: "3276110", name: "Cilodong" }
];

// Fallback data untuk beberapa kelurahan utama
const FALLBACK_KELURAHAN = {
    "3276010": [ // Pancoran Mas
        { name: "Pancoran Mas" },
        { name: "Rangkapan Jaya" },
        { name: "Rangkapan Jaya Baru" },
        { name: "Depok" },
        { name: "Mampang" },
        { name: "Depok Jaya" }
    ],
    "3276020": [ // Sukmajaya
        { name: "Sukmajaya" },
        { name: "Mekar Jaya" },
        { name: "Tirtajaya" },
        { name: "Abdijaya" },
        { name: "Baktijaya" },
        { name: "Cisalak" }
    ],
    "3276030": [ // Cipayung
        { name: "Cipayung" },
        { name: "Cipayung Jaya" },
        { name: "Bojong Pondok Terong" },
        { name: "Pondok Jaya" },
        { name: "Ratu Jaya" }
    ],
    "3276040": [ // Cimanggis
        { name: "Cisalak Pasar" },
        { name: "Curug" },
        { name: "Harjamukti" },
        { name: "Mekarsari" },
        { name: "Pasir Gunung Selatan" },
        { name: "Tugu" }
    ],
    "3276050": [ // Tapos
        { name: "Tapos" },
        { name: "Cimpaeun" },
        { name: "Jatijajar" },
        { name: "Cilangkap" },
        { name: "Sukamaju Baru" },
        { name: "Sukatani" },
        { name: "Leuwinanggung" }
    ],
    "3276060": [ // Beji
        { name: "Beji" },
        { name: "Beji Timur" },
        { name: "Pondok Cina" },
        { name: "Kemiri Muka" },
        { name: "Kukusan" },
        { name: "Tanah Baru" }
    ],
    "3276070": [ // Bojongsari
        { name: "Bojongsari" },
        { name: "Bojongsari Baru" },
        { name: "Curug" },
        { name: "Duren Mekar" },
        { name: "Duren Seribu" },
        { name: "Serua" },
        { name: "Pondok Petir" }
    ],
    "3276080": [ // Cinere
        { name: "Cinere" },
        { name: "Gandul" },
        { name: "Pangkalan Jati" },
        { name: "Pangkalan Jati Baru" }
    ],
    "3276090": [ // Limo
        { name: "Limo" },
        { name: "Krukut" },
        { name: "Meruyung" },
        { name: "Grogol" }
    ],
    "3276100": [ // Sawangan
        { name: "Sawangan" },
        { name: "Sawangan Baru" },
        { name: "Bedahan" },
        { name: "Pengasinan" },
        { name: "Pasir Putih" },
        { name: "Cinangka" },
        { name: "Kedaung" }
    ],
    "3276110": [ // Cilodong
        { name: "Cilodong" },
        { name: "Kalimulya" },
        { name: "Jatimulya" },
        { name: "Sukamaju" },
        { name: "Kalibaru" }
    ]
};

// Export untuk digunakan di file lain
window.FALLBACK_KECAMATAN = FALLBACK_KECAMATAN;
window.FALLBACK_KELURAHAN = FALLBACK_KELURAHAN;
