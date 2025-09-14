<?php
// Include config file
require_once "includes/config.php";
require_once "includes/functions.php";

// Include header
include "includes/header.php";
?>

<div class="card">
    <div class="card-header">
        <h2>Tentang Sistem Pakar Perawatan Kulit</h2>
    </div>
    <div class="card-body">
        <h3>Latar Belakang</h3>
        <p>Sistem pakar perawatan kulit ini dikembangkan untuk membantu pengguna dalam menentukan jenis perawatan kulit yang sesuai dengan kondisi kulit mereka. Dengan memanfaatkan pengetahuan dari para ahli perawatan kulit, sistem ini dapat memberikan rekomendasi yang sesuai dengan faktor resiko dan gejala yang dialami oleh pengguna.</p>
        
        <h3>Metode Inferensi</h3>
        <p>Sistem ini menggunakan metode inferensi forward chaining, yaitu metode penalaran yang dimulai dari fakta-fakta yang diketahui (faktor resiko dan gejala), kemudian bergerak maju melalui premis-premis untuk mencapai kesimpulan (rekomendasi perawatan).</p>
        
        <h3>Basis Pengetahuan</h3>
        <p>Basis pengetahuan sistem ini terdiri dari:</p>
        <ul style="margin-left: 20px; margin-bottom: 20px;">
            <li><strong>Faktor Resiko</strong>: 8 jenis faktor resiko yang dapat mempengaruhi kondisi kulit</li>
            <li><strong>Gejala</strong>: 7 jenis gejala yang mungkin dialami pada kulit</li>
            <li><strong>Jenis Kulit</strong>: 4 jenis kulit (normal, kering, berminyak, kombinasi)</li>
            <li><strong>Kelainan Kulit</strong>: 7 jenis kelainan kulit yang mungkin terdeteksi</li>
            <li><strong>Serum</strong>: 10 jenis serum perawatan kulit</li>
            <li><strong>Treatment</strong>: 10 jenis treatment perawatan kulit</li>
        </ul>
        
        <h3>Alur Diagnosis</h3>
        <ol style="margin-left: 20px; margin-bottom: 20px;">
            <li>Pengguna memilih faktor resiko yang dimiliki</li>
            <li>Pengguna memilih gejala yang dialami</li>
            <li>Sistem menganalisis jenis kulit berdasarkan faktor resiko</li>
            <li>Sistem menentukan kelainan kulit berdasarkan gejala</li>
            <li>Sistem menghitung tingkat akurasi diagnosis</li>
            <li>Sistem memberikan rekomendasi serum berdasarkan kelainan kulit</li>
            <li>Sistem memberikan rekomendasi treatment berdasarkan kelainan kulit</li>
        </ol>
        
        <h3>Perhitungan Akurasi</h3>
        <p>Sistem pakar ini menampilkan tingkat akurasi untuk setiap diagnosis yang dilakukan. Akurasi dihitung berdasarkan:</p>
        <ul style="margin-left: 20px; margin-bottom: 20px;">
            <li><strong>Validitas Input</strong>: Seberapa valid faktor resiko dan gejala yang dipilih</li>
            <li><strong>Konsistensi</strong>: Seberapa konsisten antara faktor resiko dan gejala yang dipilih</li>
            <li><strong>Bobot</strong>: Gejala diberi bobot lebih tinggi (70%) dibanding faktor resiko (30%)</li>
        </ul>
        <p>Tingkat akurasi ditampilkan dalam bentuk persentase dengan kode warna:</p>
        <ul style="margin-left: 20px; margin-bottom: 20px;">
            <li><span style="color: #1cc88a; font-weight: bold;">≥ 90%</span>: Akurasi sangat tinggi, rekomendasi dapat diandalkan</li>
            <li><span style="color: #36b9cc; font-weight: bold;">70-89%</span>: Akurasi baik, rekomendasi cukup dapat diandalkan</li>
            <li><span style="color: #f6c23e; font-weight: bold;">50-69%</span>: Akurasi sedang, pertimbangkan untuk konsultasi lebih lanjut</li>
            <li><span style="color: #e74a3b; font-weight: bold;">< 50%</span>: Akurasi rendah, disarankan untuk konsultasi langsung dengan ahli</li>
        </ul>
        
        <h3>Pengembang</h3>
        <p>Sistem pakar ini dikembangkan sebagai bagian dari proyek implementasi sistem pakar dalam bidang perawatan kulit. Pengembangan dilakukan dengan menggunakan teknologi web modern untuk memastikan kemudahan akses dan penggunaan.</p>
        
        <h3>Referensi</h3>
        <ul style="margin-left: 20px; margin-bottom: 20px;">
            <li>Durkin, J. (1994). Expert Systems Design and Development. Prentice Hall.</li>
            <li>Giarratano, J. C., & Riley, G. D. (2004). Expert Systems: Principles and Programming. Course Technology.</li>
            <li>Baumann, L. (2015). Cosmeceuticals and Cosmetic Ingredients. McGraw-Hill Education.</li>
            <li>Draelos, Z. D. (2015). Cosmetic Dermatology: Products and Procedures. Wiley-Blackwell.</li>
        </ul>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h2>Struktur Basis Pengetahuan</h2>
    </div>
    <div class="card-body">
        <h3>Relasi Faktor Resiko dan Jenis Kulit</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Faktor Resiko</th>
                    <th>Jenis Kulit Terkait</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>FR01</td>
                    <td>Bekerja di ruangan AC (air conditioner)</td>
                    <td>Kulit Kering, Kulit Kombinasi</td>
                </tr>
                <tr>
                    <td>FR02</td>
                    <td>Keriput</td>
                    <td>Kulit Kering, Kulit Kombinasi</td>
                </tr>
                <tr>
                    <td>FR03</td>
                    <td>Memiliki alergi</td>
                    <td>Kulit Kering, Kulit Kombinasi</td>
                </tr>
                <tr>
                    <td>FR04</td>
                    <td>Mengikuti program KB (Keluarga Berencana)</td>
                    <td>Kulit Kering, Kulit Kombinasi</td>
                </tr>
                <tr>
                    <td>FR05</td>
                    <td>Menyukai makanan pedas</td>
                    <td>Kulit Berminyak, Kulit Kombinasi</td>
                </tr>
                <tr>
                    <td>FR06</td>
                    <td>Sering menyentuh muka</td>
                    <td>Kulit Berminyak, Kulit Kombinasi</td>
                </tr>
                <tr>
                    <td>FR07</td>
                    <td>Sering memakan makanan berprotein tinggi</td>
                    <td>Kulit Berminyak, Kulit Kombinasi</td>
                </tr>
                <tr>
                    <td>FR08</td>
                    <td>Memiliki kulit berpori-pori besar</td>
                    <td>Kulit Berminyak, Kulit Kombinasi</td>
                </tr>
            </tbody>
        </table>
        
        <h3>Relasi Gejala dan Kelainan Kulit</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Gejala</th>
                    <th>Kelainan Kulit Terkait</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>G01</td>
                    <td>Bintik-bintik hitam di wajah</td>
                    <td>Flek</td>
                </tr>
                <tr>
                    <td>G02</td>
                    <td>Garis halus disekitar mata atau mulut</td>
                    <td>Keriput</td>
                </tr>
                <tr>
                    <td>G03</td>
                    <td>Bintik-bintik merah di wajah</td>
                    <td>Jerawat Tidak Meradang</td>
                </tr>
                <tr>
                    <td>G04</td>
                    <td>Bernanah</td>
                    <td>Jerawat Meradang</td>
                </tr>
                <tr>
                    <td>G05</td>
                    <td>Bintik-bintik hitam disekitar hidung atau pipi</td>
                    <td>Komedo</td>
                </tr>
                <tr>
                    <td>G06</td>
                    <td>Bintik-bintik putih dan keras</td>
                    <td>Milia</td>
                </tr>
                <tr>
                    <td>G07</td>
                    <td>Tidak ada gejala</td>
                    <td>Tidak ada kelainan kulit</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php
// Include footer
include "includes/footer.php";
?> 