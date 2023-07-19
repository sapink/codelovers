<!DOCTYPE html>
<html>
<head>
    <title>Instanpage - V2</title>
    <!-- Load Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style> body { background-color: #0f0f0f; color: #32cd32; font-family: 'Courier New', Courier, monospace; } .container { max-width: 600px; margin-top: 50px; } h1 { font-size: 36px; color: #32cd32; margin-bottom: 30px; text-align: center; } label { color: #32cd32; } .form-control-file { background-color: transparent; border-color: #32cd32; color: #32cd32; font-weight: 500; } .btn-primary { background-color: #32cd32; border-color: #32cd32; color: black; font-weight: 700; } .hasil-proses { margin-top: 20px; background-color: #0f0f0f; color: #32cd32; padding: 20px; font-size: 16px; } .hasil-proses h2 { color: #32cd32; margin-bottom: 10px; font-size: 24px; } .hasil-proses ul { list-style: none; padding-left: 0; margin-left: 0; } .hasil-proses li { margin-bottom: 5px; } .hasil-proses a { text-decoration: none; color: #00ffff; } /* CSS Media Queries */ @media (max-width: 767px) { .container { margin-top: 20px; } .form-group { margin-bottom: 20px; } .btn-primary { width: 100%; } } </style>
</head>
<body>
    <div class="container">
        <h1>Instanpage - V2</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="listFile">Pilih Keyword:</label>
                <input type="file" class="form-control-file" id="listFile" name="listFile">
            </div>
            <div class="form-group">
                <label for="templateFiles">Pilih Template:</label>
                <input type="file" class="form-control-file" id="templateFiles" name="templateFiles[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary mb-2">Proses</button>
        </form>

        <?php
            function prosesFileListTemplate($listFile, $templateFiles, $baseURL) {
                $output = '';
                $usedTemplates = []; // Menyimpan daftar template yang telah digunakan

                if (file_exists($listFile)) {
                    $folders = file($listFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                    $totalTemplates = count($templateFiles); // Jumlah template yang tersedia

                    foreach ($folders as $folderName) {
                        $folderName = trim($folderName);
                        if (!empty($folderName)) {
                            $folderNameSlug = str_replace(' ', '-', $folderName);
                            if (!file_exists($folderNameSlug)) {
                                mkdir($folderNameSlug, 0777, true);

                                $pageTitle = htmlspecialchars($folderName, ENT_QUOTES, 'UTF-8');
                                $pageTitle = ucwords($pageTitle);

                                // Memilih template secara acak
                                $templateIndex = array_rand($templateFiles);
                                $templateFile = $templateFiles[$templateIndex];
                                unset($templateFiles[$templateIndex]); // Menghapus template yang telah digunakan dari daftar

                                // Memuat isi file template.html
                                $fileContent = file_get_contents($templateFile);
                                $fileContent = str_replace('{$pageTitle}', $pageTitle, $fileContent);

                                file_put_contents($folderNameSlug . "/index.php", $fileContent, LOCK_EX); // Overwrite file index.php jika sudah ada

                                // Menambah file google51df5d19c3dcef7a.html
                                $verificationFileContent = "google-site-verification: google51df5d19c3dcef7a.html";
                                file_put_contents($folderNameSlug . "/google51df5d19c3dcef7a.html", $verificationFileContent);

                                // Menambah file sitemap.xml
                                $sitemapContent = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
                                $sitemapContent .= '<urlset' . "\n";
                                $sitemapContent .= '      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
                                $sitemapContent .= '      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' . "\n";
                                $sitemapContent .= '      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9' . "\n";
                                $sitemapContent .= '            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">' . "\n";
                                $sitemapContent .= '<!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->' . "\n\n";
                                $sitemapContent .= '<url>' . "\n";
                                $sitemapContent .= '  <loc>' . $baseURL . $folderNameSlug . '/</loc>' . "\n";
                                $sitemapContent .= '  <lastmod>' . date('Y-m-d\TH:i:sP') . '</lastmod>' . "\n";
                                $sitemapContent .= '</url>' . "\n\n";
                                $sitemapContent .= '</urlset>';

                                file_put_contents($folderNameSlug . "/sitemap.xml", $sitemapContent);

                                // Generate URL
                                $folderURL = $baseURL . $folderNameSlug . '/';
                                $output .= "<a href=\"$folderURL\">$folderURL</a><br>\n";

                                // Menyimpan template yang telah digunakan
                                $usedTemplates[$folderName] = $templateFile;

                                // Jika semua template telah digunakan, reset daftar template
                                if (empty($templateFiles)) {
                                    $templateFiles = $usedTemplates;
                                    $usedTemplates = [];
                                }
                            } else {
                                $output .= "Folder $folderName sudah ada.<br>\n";
                            }
                        }
                    }
                } else {
                    $output .= "File $listFile tidak ditemukan.";
                }

                return $output;
            }

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $listFile = $_FILES['listFile']['tmp_name'];

                // Memeriksa apakah ada file template yang diunggah
                if (isset($_FILES['templateFiles']['tmp_name'])) {
                    $templateFiles = $_FILES['templateFiles']['tmp_name'];
                } else {
                    $templateFiles = []; // Jika tidak ada file template yang diunggah
                }

                if (is_uploaded_file($listFile) && !empty($templateFiles)) {
                    $baseURL = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/';
                    $hasilProses = prosesFileListTemplate($listFile, $templateFiles, $baseURL);

                    echo '<h2 class="text-center">Hasil Proses:</h2>';
                    echo '<div class="hasil-proses">';
                    echo '<ul>';
                    echo $hasilProses;
                    echo '</ul>';
                    echo '</div>';
                } else {
                    echo '<div class="alert alert-danger">Terjadi kesalahan dalam mengunggah file.</div>';
                }
            }
        ?>
    </div>

        <!-- Load Bootstrap JavaScript -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>