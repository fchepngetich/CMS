<!DOCTYPE html>
<html lang="en-gb" dir="ltr">

<head>
    <title>Asset Management System - Zetech University</title>
    <link rel="icon" type="image/x-icon" href="includes/images/favicon.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
        integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="includes/css/main.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <link rel="canonical" href="https://mali.zetech.ac.ke/">
    <meta name="author" content="Zetech University Mali">
    <meta name="keywords"
        content="asset management system, asset tracking, maintain assets, asset issuing, maintenance scheduling, digital assets, physical assets, asset optimization">
    <meta name="description"
        content="Mali is an asset management system designed for Zetech University staff to maintain their assets.">
    <meta name="robots" content="index, follow">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="https://www.mali.zetech.ac.ke">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Asset Management System - Zetech University">
    <meta property="og:description"
        content="Mali is an asset management system designed for Zetech University staff to maintain their assets.">
    <meta property="og:image" content="https://sime.zetech.ac.ke/images/logo.png">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="https://www.mali.zetech.ac.ke">
    <meta name="twitter:title" content="Asset Management System - Zetech University">
    <meta name="twitter:description"
        content="Mali is an asset management system designed for Zetech University staff to maintain their assets.">
    <meta name="twitter:image" content="https://sime.zetech.ac.ke/images/logo.png">

    <!-- Structured Data -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "WebSite",
      "name": "Asset Management System",
      "url": "https://www.mali.zetech.ac.ke",
      "description": "Mali is an asset management system designed for Zetech University staff to maintain their assets."
    }
    </script>
</head>

<body>
    <H1 style="display:none;">Zetech University Assets menagement System</H1>
    <H2 style="display:none;">Zetech University - Inventing the future </H2>


    <p style="display:none;" class="hidden">Assets management system,designed for zetech university internal use.</p>

    <?php
    include ('./includes/navbar.php');
    ?>
    <section class="nav-bar">
        <nav class="navbar  navbar-expand-lg navbar-dark container " id="navbar">
            <a class="navbar-brand" href=""><img src="includes/images/logo.png" alt="Logo"></a>
            <h3 class="page-header">Assets Management System</h3>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">


                <?php
                if (isset($_SESSION['id'])) { ?>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item ">
                            <a class="nav-link myitems " href="index.php?page=home">My Items</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle myitems" href="#" id="requisitionsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Requisitions
                            </a>
                            <div class="dropdown-menu" aria-labelledby="requisitionsDropdown">
                                <?php
                                $rolePages = [
                                    'line_manager' => 'line_manager',
                                    'cost_center_manager' => 'cost_center_head',
                                    'financial_controller' => 'financial_controller',
                                    'store_keeper' => 'store_keeper'
                                ];

                                echo '<a class="dropdown-item" href="index.php?page=requisitions">My Requisitions</a>';

                                if (isset($_SESSION['role'])) {
                                    foreach ($_SESSION['role'] as $userRole) {
                                        if (array_key_exists($userRole, $rolePages)) {
                                            echo '<a class="dropdown-item" href="index.php?page=' . $rolePages[$userRole] . '">' . ucfirst(str_replace('_', ' ', $userRole)) . ' Requisitions</a>';
                                        }
                                    }
                                }
                                ?>
                            </div>
                        </li>
                        <?php
                        // Check if the user has 'financial_controller' role
                        if (isset($_SESSION['role']) && in_array('technician', $_SESSION['role'])) { ?>
                            <li class="nav-item ">
                                <a class="nav-link myitems " href="index.php?page=technician_repair"> Clearance</a>
                            </li>
                        <?php } ?>

                        <li class="nav-item ">
                            <a class="nav-link myitems " href="index.php?page=self_stocking">Self Stocking</a>
                        </li>

                        <?php
                        // Check if the user has roles 'admin', 'viewer', or 'asset_manager'
                        if (isset($_SESSION['role']) && array_intersect($_SESSION['role'], ['1', '10', '9'])) { ?>
                            <li class="nav-item ml-auto">
                                <a class="nav-link myitems " href="index.php?page=admin">Admin</a>
                            </li>
                        <?php } elseif (isset($_SESSION['role']) && in_array('3', $_SESSION['role'])) { ?>
                            <li class="nav-item ">
                                <a class="nav-link myitems" href="index.php?page=all_items">All Items</a>
                            </li>
                        <?php } ?>

                        <?php if (isset($fullName)): ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $lastName;
                                    ; ?>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item profile" href="index.php?page=change_password">Change Password</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item profile" href="index.php?page=logout"
                                        onclick="return confirm('Are you sure you want to logout?');">Logout</a>
                                </div>
                            </li>

                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="index.php?page=login">Login</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php } ?>

            </div>
        </nav>
    </section>


    <div class='container content'>
        <div class="row">


        </div>
    </div>
    <script defer src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <footer class=" footer">
        <div class="container">
            <div class="row copyright">
                <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                    <h3>Contact Us</h3>
                    <p>Zetech University</p>
                    <p>P.O. Box 12345, Nairobi</p>
                    <p>Email: info@zetech.ac.ke</p>
                    <p>Phone: +254 722 123 456</p>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <h3>Quick Links</h3>
                    <ul class="list-unstyled">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Services</a></li>
                        <li><a href="#">Contact
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">

            <p class="mt-3 mb-0">Copyright &copy; <?php echo date("Y"); ?> <a
                    href="https://zetech.ac.ke/">Zetech University</a> Assets Management System. All rights reserved.
            </p>
        </div>
        </div>
    </footer>
    <script defer src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script defer src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script defer src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script defer src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script defer src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script defer type="text/javascript" src="includes/js/script.js"></script>
</body>

</html>