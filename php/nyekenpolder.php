<!DOCTYPE html>
<html>
<head>
    <title>Daftar Folder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link href="<?php echo $current_url; ?>" itemprop="mainEntityOfPage" rel="canonical">
    <style>body { background-color: #111; color: #fff; font-family: "Helvetica Neue",Helvetica,Arial,sans-serif; } h1 { font-size: 36px; font-weight: bold; text-align: center; margin-top: 50px; } .container { max-width: 800px; margin: 0 auto; padding: 20px; } table { margin-top: 30px; } table th, table td { color: #fff; } @media (max-width: 576px) {h1 { font-size: 30px; }.container { padding: 10px; }}</style>
</head>
<body>
    <div class="container">
        <h1>Daftar Folder</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Folder</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Mendapatkan path direktori saat ini
                $dir = __DIR__;

                // Membaca semua file dan folder di dalam direktori
                $items = scandir($dir);

                // Menampilkan daftar folder (mengabaikan file)
                foreach ($items as $item) {
                    $itemPath = $dir . DIRECTORY_SEPARATOR . $item;
                    if (is_dir($itemPath) && $item != '.' && $item != '..') {
                        echo '<tr>';
                        echo '<td><a href="' . $item . '" target="_blank">' . $item . '</a></td>';
                        echo '</tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</body>
</html>