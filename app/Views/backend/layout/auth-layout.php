<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title><?= isset($pageTitle) ? $pageTitle : 'New Page Title' ?></title>
    <link rel="apple-touch-icon" sizes="180x180" href="/backend//backend/vendors/images/favicon.ico" />
    <link rel="icon" type="image/png" sizes="32x32" href="/backend/vendors/images/favicon.ico" />
    <link rel="icon" type="image/png" sizes="16x16" href="/backend/vendors/images/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="/backend/vendors/styles/core.css" />
    <link rel="stylesheet" type="text/css" href="/backend/vendors/styles/icon-font.min.css" />
    <link rel="stylesheet" type="text/css" href="/backend/vendors/styles/style.css" />
    <?= $this->renderSection('stylesheets') ?>
</head>

<body class="login-page">

    <div class="login-wrap d-flex align-items-center flex-wrap justify-content-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mx-auto col-lg-5">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </div>
    </div>

    <script src="/backend/vendors/scripts/core.js"></script>
    <script src="/backend/vendors/scripts/script.min.js"></script>
    <script src="/backend/vendors/scripts/process.js"></script>
    <script src="/backend/vendors/scripts/layout-settings.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>

    <?= $this->renderSection('scripts') ?>
</body>

</html>