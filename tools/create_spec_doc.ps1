$ErrorActionPreference = "Stop"

$root = Split-Path -Parent $PSScriptRoot
$output = Join-Path $root "Spesifikasi_Web_KIPK_SAW.docx"
$workDir = Join-Path $root ("tools\docx_build_" + (Get-Date -Format "yyyyMMddHHmmss"))

[void][System.IO.Directory]::CreateDirectory($workDir)
[void][System.IO.Directory]::CreateDirectory((Join-Path $workDir "_rels"))
[void][System.IO.Directory]::CreateDirectory((Join-Path $workDir "word"))
[void][System.IO.Directory]::CreateDirectory((Join-Path $workDir "word\_rels"))
[void][System.IO.Directory]::CreateDirectory((Join-Path $workDir "docProps"))

function XmlEscape([string] $text) {
    return [System.Security.SecurityElement]::Escape($text)
}

function Paragraph([string] $text, [string] $style = "") {
    $styleXml = ""
    if ($style) {
        $styleXml = "<w:pPr><w:pStyle w:val=`"$style`"/></w:pPr>"
    }
    return "<w:p>$styleXml<w:r><w:t xml:space=`"preserve`">$(XmlEscape $text)</w:t></w:r></w:p>"
}

function Bullet([string] $text) {
    return Paragraph ("- " + $text)
}

function Table([string[]] $headers, [object[][]] $rows) {
    $xml = "<w:tbl><w:tblPr><w:tblStyle w:val=`"TableGrid`"/><w:tblW w:w=`"0`" w:type=`"auto`"/><w:tblBorders><w:top w:val=`"single`" w:sz=`"4`" w:space=`"0`" w:color=`"auto`"/><w:left w:val=`"single`" w:sz=`"4`" w:space=`"0`" w:color=`"auto`"/><w:bottom w:val=`"single`" w:sz=`"4`" w:space=`"0`" w:color=`"auto`"/><w:right w:val=`"single`" w:sz=`"4`" w:space=`"0`" w:color=`"auto`"/><w:insideH w:val=`"single`" w:sz=`"4`" w:space=`"0`" w:color=`"auto`"/><w:insideV w:val=`"single`" w:sz=`"4`" w:space=`"0`" w:color=`"auto`"/></w:tblBorders></w:tblPr>"
    $xml += "<w:tr>"
    foreach ($header in $headers) {
        $xml += "<w:tc><w:tcPr><w:shd w:fill=`"D9EAF7`"/></w:tcPr>" + (Paragraph $header) + "</w:tc>"
    }
    $xml += "</w:tr>"
    foreach ($row in $rows) {
        $xml += "<w:tr>"
        foreach ($cell in $row) {
            $xml += "<w:tc>" + (Paragraph ([string] $cell)) + "</w:tc>"
        }
        $xml += "</w:tr>"
    }
    $xml += "</w:tbl>"
    return $xml
}

$criteriaRows = @(
    @("C1", "Bahasa Indonesia", "Benefit", "0,1667 / 16,67%", "Nilai Bahasa Indonesia"),
    @("C2", "IPA", "Benefit", "0,1667 / 16,67%", "Nilai Ilmu Pengetahuan Alam"),
    @("C3", "IPS", "Benefit", "0,1667 / 16,67%", "Nilai Ilmu Pengetahuan Sosial"),
    @("C4", "Bahasa Inggris", "Benefit", "0,1667 / 16,67%", "Nilai Bahasa Inggris"),
    @("C5", "Agama", "Benefit", "0,1667 / 16,67%", "Nilai Pendidikan Agama"),
    @("C6", "Matematika", "Benefit", "0,1665 / 16,65%", "Nilai Matematika")
)

$sampleRows = @(
    @("C1", "100", "100", "1,000000", "0,166700"),
    @("C2", "100", "100", "1,000000", "0,166700"),
    @("C3", "100", "100", "1,000000", "0,166700"),
    @("C4", "100", "100", "1,000000", "0,166700"),
    @("C5", "100", "100", "1,000000", "0,166700"),
    @("C6", "100", "100", "1,000000", "0,166500")
)

$rankingRows = @(
    @("1", "Ahmad Fikri", "1,000000", "Lulus"),
    @("2", "Siti Nurhaliza", "0,849990", "Lulus"),
    @("3", "Rizky Pratama", "0,650010", "Lulus"),
    @("4", "Dewi Lestari", "0,483330", "Tidak Lulus"),
    @("5", "Bagas Maulana", "0,333340", "Tidak Lulus")
)

$body = ""
$body += Paragraph "SPESIFIKASI WEBSITE SELEKSI KIP-K UMT DENGAN METODE SAW" "Title"
$body += Paragraph ("Tanggal dokumen: " + (Get-Date -Format "dd/MM/yyyy")) "Subtitle"
$body += Paragraph "Dokumen ini menjelaskan spesifikasi website seleksi KIP-K berbasis Laravel serta mekanisme perhitungan Simple Additive Weighting (SAW) yang diterapkan pada project."

$body += Paragraph "1. Gambaran Umum Sistem" "Heading1"
$body += Paragraph "Website ini digunakan untuk mengelola proses pendaftaran, verifikasi, tes, perhitungan nilai, ranking, dan pengumuman hasil seleksi KIP-K. Sistem memiliki dua peran utama, yaitu admin dan user/pendaftar."
$body += Bullet "Pendaftar mengisi profil, mengikuti tes, mengunggah dokumen, melihat status, dan melihat hasil seleksi setelah diumumkan."
$body += Bullet "Admin mengelola user, status pendaftaran, kriteria SAW, soal tes, kuota lulus, perhitungan SAW, ranking, pengumuman, notifikasi, dan laporan."

$body += Paragraph "2. Spesifikasi Teknis" "Heading1"
$body += Table @("Komponen", "Keterangan") @(
    @("Framework", "Laravel 13"),
    @("Bahasa Backend", "PHP 8.3"),
    @("Database", "MySQL, database lokal kipk_saw"),
    @("Frontend", "Blade template, Bootstrap, CSS/JS lokal"),
    @("Autentikasi", "Login berbasis email atau NISN dengan role admin/user"),
    @("Session", "Database session"),
    @("Metode SPK", "Simple Additive Weighting (SAW)")
)

$body += Paragraph "3. Aktor dan Hak Akses" "Heading1"
$body += Table @("Aktor", "Hak Akses Utama") @(
    @("Admin", "Dashboard, pengaturan pendaftaran, user, pendaftar, kriteria, sub-kriteria, soal tes, jawaban tes, kuota, proses SAW, nilai terhitung, hasil SAW, ranking, laporan, notifikasi."),
    @("User/Pendaftar", "Dashboard, profil, tes, dokumen, status pendaftaran, hasil seleksi, notifikasi.")
)

$body += Paragraph "4. Fitur Admin" "Heading1"
$body += Bullet "Dashboard menampilkan total user, pendaftar terverifikasi, peserta yang sudah tes, soal aktif, kuota lulus, jumlah lulus, jumlah tidak lulus, dan hasil yang telah diproses."
$body += Bullet "Kelola pendaftaran untuk membuka atau menutup registrasi akun baru."
$body += Bullet "Kelola user untuk melihat, mencari, mengaktifkan, menonaktifkan, atau menghapus akun pendaftar."
$body += Bullet "Kelola pendaftar untuk verifikasi atau penolakan status pendaftaran."
$body += Bullet "Kelola kriteria dan sub-kriteria SAW."
$body += Bullet "Kelola soal tes, durasi tes, status tes dibuka/ditutup, dan instruksi tes."
$body += Bullet "Kelola kuota lulus. Kuota menentukan berapa ranking teratas yang berstatus Lulus."
$body += Bullet "Proses SAW untuk normalisasi nilai, menghitung nilai terbobot, menentukan final score, ranking, dan status kelulusan."
$body += Bullet "Nilai Terhitung menampilkan detail nilai awal, skor, normalisasi, dan nilai terbobot tiap kriteria. Halaman ini memiliki filter sesuai abjad, sesuai ranking, dan tanggal diubah."
$body += Bullet "Hasil SAW dan Ranking menampilkan hasil akhir seleksi."
$body += Bullet "Laporan dapat dicetak untuk kebutuhan dokumentasi."

$body += Paragraph "5. Fitur User/Pendaftar" "Heading1"
$body += Bullet "Registrasi menggunakan nama, Nomor Akun KIP, asal sekolah, NISN, NPSN, dan nomor WA."
$body += Bullet "Login menggunakan email atau NISN. Password pendaftar berasal dari Nomor Akun KIP saat registrasi."
$body += Bullet "Melengkapi atau memperbarui profil pendaftar."
$body += Bullet "Mengikuti tes jika tes sudah dibuka admin. Sistem menyimpan jawaban dan menghitung skor per kriteria."
$body += Bullet "Mengunggah dokumen pendukung."
$body += Bullet "Melihat status pendaftaran dan hasil seleksi ketika hasil telah diumumkan."

$body += Paragraph "6. Struktur Data Utama" "Heading1"
$body += Table @("Tabel", "Fungsi") @(
    @("users", "Menyimpan akun admin dan user, role, password hash, dan status aktif."),
    @("student_profiles", "Menyimpan profil pendaftar seperti Nomor Akun KIP, NISN, NPSN, asal sekolah, nomor WA, alamat, dan status."),
    @("alternatives", "Menyimpan alternatif/pendaftar yang akan dihitung dalam SAW."),
    @("criteria", "Menyimpan kriteria SAW, tipe benefit/cost, bobot, dan deskripsi."),
    @("sub_criteria", "Menyimpan rentang nilai dan skor sub-kriteria."),
    @("test_questions", "Menyimpan soal tes, opsi A-D, jawaban benar, urutan, dan status aktif."),
    @("test_answers", "Menyimpan jawaban pendaftar dan skor jawaban."),
    @("alternative_scores", "Menyimpan raw value, score, normalized value, dan weighted value per pendaftar per kriteria."),
    @("saw_results", "Menyimpan final score, ranking, status lulus/tidak lulus, dan waktu pengumuman."),
    @("documents", "Menyimpan dokumen yang diunggah pendaftar."),
    @("notifications", "Menyimpan notifikasi admin atau pendaftar."),
    @("test_settings", "Menyimpan durasi tes, kuota lulus, status registrasi, status tes, dan instruksi.")
)

$body += Paragraph "7. Kriteria dan Bobot SAW" "Heading1"
$body += Table @("Kode", "Kriteria", "Tipe", "Bobot", "Keterangan") $criteriaRows
$body += Paragraph "Total bobot adalah 1,0000 atau 100%. Semua kriteria default bertipe benefit, artinya semakin tinggi skor maka semakin baik."

$body += Paragraph "8. Alur Perhitungan SAW" "Heading1"
$body += Bullet "Admin memverifikasi pendaftar. Hanya pendaftar dengan status verified yang dihitung."
$body += Bullet "User mengerjakan tes. Setiap jawaban benar bernilai 100 dan jawaban salah/kosong bernilai 0."
$body += Bullet "Nilai per kriteria dihitung dari rata-rata skor soal aktif dalam kriteria tersebut."
$body += Bullet "Sistem mengambil nilai maksimum dan minimum setiap kriteria dari seluruh pendaftar terverifikasi."
$body += Bullet "Nilai dinormalisasi sesuai tipe kriteria benefit atau cost."
$body += Bullet "Nilai normalisasi dikalikan bobot kriteria untuk mendapatkan nilai terbobot."
$body += Bullet "Final score adalah jumlah seluruh nilai terbobot."
$body += Bullet "Ranking diurutkan dari final score tertinggi ke terendah."
$body += Bullet "Status Lulus diberikan untuk ranking yang masuk kuota. Sisanya menjadi Tidak Lulus."

$body += Paragraph "9. Rumus SAW" "Heading1"
$body += Paragraph "Untuk kriteria benefit:"
$body += Paragraph "Rij = Xij / Max(Xj)"
$body += Paragraph "Untuk kriteria cost:"
$body += Paragraph "Rij = Min(Xj) / Xij"
$body += Paragraph "Nilai terbobot:"
$body += Paragraph "Vij = Rij x Wj"
$body += Paragraph "Nilai akhir:"
$body += Paragraph "Vi = jumlah seluruh Vij"
$body += Paragraph "Keterangan: Rij adalah nilai normalisasi alternatif i pada kriteria j, Xij adalah skor awal, Wj adalah bobot kriteria, dan Vi adalah final score alternatif."

$body += Paragraph "10. Contoh Perhitungan" "Heading1"
$body += Paragraph "Contoh berikut memakai data ranking 1, yaitu Ahmad Fikri. Karena nilai setiap kriteria adalah 100 dan nilai maksimum tiap kriteria juga 100, maka normalisasi setiap kriteria menjadi 1,000000."
$body += Table @("Kode", "Raw Value", "Score", "Normalisasi", "Nilai Terbobot") $sampleRows
$body += Paragraph "Final score = 0,166700 + 0,166700 + 0,166700 + 0,166700 + 0,166700 + 0,166500 = 1,000000. Karena final score tertinggi, pendaftar berada pada ranking 1."

$body += Paragraph "11. Contoh Hasil Ranking" "Heading1"
$body += Table @("Ranking", "Nama", "Final Score", "Status") $rankingRows

$body += Paragraph "12. Akun Default" "Heading1"
$body += Bullet "Admin: admin@kipk.test dengan password password."
$body += Bullet "Contoh user: user1@kipk.test atau NISN 2026000001 dengan password KIPK2026001."

$body += Paragraph "13. Catatan Implementasi" "Heading1"
$body += Bullet "Perhitungan SAW dijalankan di SawCalculationService."
$body += Bullet "Jawaban tes dan skor per kriteria dihitung di UserTestSubmissionService."
$body += Bullet "Pengaturan kuota lulus berada di modul Kuota Lulus admin."
$body += Bullet "Hasil dapat diumumkan melalui menu Hasil SAW sehingga user dapat melihat hasil seleksi."

$documentXml = "<?xml version=`"1.0`" encoding=`"UTF-8`" standalone=`"yes`"?><w:document xmlns:w=`"http://schemas.openxmlformats.org/wordprocessingml/2006/main`"><w:body>$body<w:sectPr><w:pgSz w:w=`"11906`" w:h=`"16838`"/><w:pgMar w:top=`"1440`" w:right=`"1440`" w:bottom=`"1440`" w:left=`"1440`"/></w:sectPr></w:body></w:document>"

$stylesXml = @"
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
  <w:style w:type="paragraph" w:styleId="Normal"><w:name w:val="Normal"/><w:qFormat/><w:rPr><w:sz w:val="22"/><w:szCs w:val="22"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Title"><w:name w:val="Title"/><w:qFormat/><w:pPr><w:spacing w:after="240"/></w:pPr><w:rPr><w:b/><w:sz w:val="34"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Subtitle"><w:name w:val="Subtitle"/><w:qFormat/><w:rPr><w:i/><w:color w:val="666666"/><w:sz w:val="22"/></w:rPr></w:style>
  <w:style w:type="paragraph" w:styleId="Heading1"><w:name w:val="heading 1"/><w:basedOn w:val="Normal"/><w:next w:val="Normal"/><w:qFormat/><w:pPr><w:spacing w:before="320" w:after="120"/></w:pPr><w:rPr><w:b/><w:sz w:val="28"/></w:rPr></w:style>
  <w:style w:type="table" w:styleId="TableGrid"><w:name w:val="Table Grid"/><w:uiPriority w:val="59"/><w:qFormat/><w:tblPr><w:tblBorders><w:top w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:left w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:bottom w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:right w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:insideH w:val="single" w:sz="4" w:space="0" w:color="auto"/><w:insideV w:val="single" w:sz="4" w:space="0" w:color="auto"/></w:tblBorders></w:tblPr></w:style>
</w:styles>
"@

$contentTypes = @"
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
  <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
  <Override PartName="/docProps/core.xml" ContentType="application/vnd.openxmlformats-package.core-properties+xml"/>
  <Override PartName="/docProps/app.xml" ContentType="application/vnd.openxmlformats-officedocument.extended-properties+xml"/>
</Types>
"@

$rels = @"
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/package/2006/relationships/metadata/core-properties" Target="docProps/core.xml"/>
  <Relationship Id="rId3" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/extended-properties" Target="docProps/app.xml"/>
</Relationships>
"@

$docRels = @"
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>
"@

$core = @"
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<cp:coreProperties xmlns:cp="http://schemas.openxmlformats.org/package/2006/metadata/core-properties" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:dcterms="http://purl.org/dc/terms/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
  <dc:title>Spesifikasi Website KIP-K SAW</dc:title>
  <dc:creator>Codex</dc:creator>
  <cp:lastModifiedBy>Codex</cp:lastModifiedBy>
  <dcterms:created xsi:type="dcterms:W3CDTF">$(Get-Date -Format "yyyy-MM-ddTHH:mm:ssZ")</dcterms:created>
  <dcterms:modified xsi:type="dcterms:W3CDTF">$(Get-Date -Format "yyyy-MM-ddTHH:mm:ssZ")</dcterms:modified>
</cp:coreProperties>
"@

$app = @"
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Properties xmlns="http://schemas.openxmlformats.org/officeDocument/2006/extended-properties" xmlns:vt="http://schemas.openxmlformats.org/officeDocument/2006/docPropsVTypes">
  <Application>Codex</Application>
</Properties>
"@

[System.IO.File]::WriteAllText((Join-Path $workDir "[Content_Types].xml"), $contentTypes, [System.Text.Encoding]::UTF8)
[System.IO.File]::WriteAllText((Join-Path $workDir "_rels\.rels"), $rels, [System.Text.Encoding]::UTF8)
[System.IO.File]::WriteAllText((Join-Path $workDir "word\document.xml"), $documentXml, [System.Text.Encoding]::UTF8)
[System.IO.File]::WriteAllText((Join-Path $workDir "word\styles.xml"), $stylesXml, [System.Text.Encoding]::UTF8)
[System.IO.File]::WriteAllText((Join-Path $workDir "word\_rels\document.xml.rels"), $docRels, [System.Text.Encoding]::UTF8)
[System.IO.File]::WriteAllText((Join-Path $workDir "docProps\core.xml"), $core, [System.Text.Encoding]::UTF8)
[System.IO.File]::WriteAllText((Join-Path $workDir "docProps\app.xml"), $app, [System.Text.Encoding]::UTF8)

if ([System.IO.File]::Exists($output)) {
    [System.IO.File]::Delete($output)
}

Add-Type -AssemblyName System.IO.Compression.FileSystem
[System.IO.Compression.ZipFile]::CreateFromDirectory($workDir, $output)

Write-Output $output
