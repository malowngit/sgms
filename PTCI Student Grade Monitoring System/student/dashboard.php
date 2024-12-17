<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <?php include 'includes/header.php'; ?> 
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .subject-table {
            width: 100%;
            table-layout: fixed;
        }
        .subject-table th, .subject-table td {
            text-align: center; 
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis; 
        }
    </style>
</head> 
<body class="sb-nav-fixed"> 
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark"> 
    <?php include 'includes/topnavbar.php'; ?> 
</nav> 
<div id="layoutSidenav"> 
    <div id="layoutSidenav_nav"> 
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion"> 
            <?php include 'includes/sidenavbar.php'; ?> 
        </nav> 
    </div> 
    <div id="layoutSidenav_content"> 
        <main> 
            <div class="container-fluid px-4"> 
                <h1 class="mt-4">Dashboard</h1> 

                <div class="card">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs" id="transcriptTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="first-year-tab" data-toggle="tab" href="#first-year" role="tab" aria-controls="first-year" aria-selected="true">1st Year</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="second-year-tab" data-toggle="tab" href="#second-year" role="tab" aria-controls="second-year" aria-selected="false">2nd Year</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="third-year-tab" data-toggle="tab" href="#third-year" role="tab" aria-controls="third-year" aria-selected="false">3rd Year</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="fourth-year-tab" data-toggle="tab" href="#fourth-year" role="tab" aria-controls="fourth-year" aria-selected="false">4th Year</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content" id="transcriptTabsContent">
                            <div class="tab-pane fade show active" id="first-year" role="tabpanel" aria-labelledby="first-year-tab">
                                <h5>1st Semester</h5>
                                <table class="table table-bordered subject-table">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject</th>
                                            <th>Units</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>CC1</td>
                                            <td>Intro to Computing</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>CC2</td>
                                            <td>Computer Programming 1</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>GE1</td>
                                            <td>Mathematics in the Modern World</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>GE2</td>
                                            <td>Understanding the Self</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>GE3</td>
                                            <td>Reading Philippine History</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>GE4</td>
                                            <td>Society & Culture</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>GE5</td>
                                            <td>Pre-Calculus for Non-Stem</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>NSTP1</td>
                                            <td>LTS/ROTC</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>PE1</td>
                                            <td>Physical Fitness</td>
                                            <td>2</td>
                                            <td>95</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h5>2nd Semester</h5>
                                <table class="table table-bordered subject-table">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject</th>
                                            <th>Units</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>DC6</td>
                                            <td>Human Interaction</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>DC1</td>
                                            <td>Web Development</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>DC2</td>
                                            <td>Discrete Mathematics</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>CC3</td>
                                            <td>Computer Programming 2</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>GE6</td>
                                            <td>Purposive Communication</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>GE7</td>
                                            <td>The Contemporary World</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>GE8</td>
                                            <td>Ethics</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>GE9</td>
                                            <td>Accounting 1</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>PE2</td>
                                            <td>Rhytmic Activities</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                        <tr>
                                            <td>NSTP 2</td>
                                            <td>LTS/ROTC</td>
                                            <td>3</td>
                                            <td>95</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="second-year" role="tabpanel" aria-labelledby="second-year-tab">
                                <h5>1st Semester</h5>
                                <table class="table table-bordered subject-table">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject</th>
                                            <th>Units</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>SUB201</td>
                                            <td>Algorithms</td>
                                            <td>3</td>
                                            <td>A</td>
                                        </tr>
                                        <tr>
                                            <td>SUB202</td>
                                            <td>Physics I</td>
                                            <td>4</td>
                                            <td>B+</td>
                                        </tr>
                                        <tr>
                                            <td>SUB203</td>
                                            <td>Biochemistry</td>
                                            <td>3</td>
                                            <td>B</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h5>2nd Semester</h5>
                                <table class="table table-bordered subject-table">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject</th>
                                            <th>Units</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>SUB204</td>
                                            <td>Computer Systems</td>
                                            <td>3</td>
                                            <td>A-</td>
                                        </tr>
                                        <tr>
                                            <td>SUB205</td>
                                            <td>Physics II</td>
                                            <td>4</td>
                                            <td>B</td>
                                        </tr>
                                        <tr>
                                            <td>SUB206</td>
                                            <td>Microbiology</td>
                                            <td>3</td>
                                            <td>B+</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="third-year" role="tabpanel" aria-labelledby="third-year-tab">
                                <h5>1st Semester</h5>
                                <table class="table table-bordered subject-table">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject</th>
                                            <th>Units</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>SUB301</td>
                                            <td>Operating Systems</td>
                                            <td>3</td>
                                            <td>A</td>
                                        </tr>
                                        <tr>
                                            <td>SUB302</td>
                                            <td>Electronics</td>
                                            <td>4</td>
                                            <td>B+</td>
                                        </tr>
                                        <tr>
                                            <td>SUB303</td>
                                            <td>Pharmacology</td>
                                            <td>3</td>
                                            <td>B</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h5>2nd Semester</h5>
                                <table class="table table-bordered subject-table">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject</th>
                                            <th>Units</th>
                                            <th>Grade</th>
                                        </tr>
 </thead>
                                    <tbody>
                                        <tr>
                                            <td>SUB304</td>
                                            <td>Database Systems</td>
                                            <td>3</td>
                                            <td>A-</td>
                                        </tr>
                                        <tr>
                                            <td>SUB305</td>
                                            <td>Electrical Circuits</td>
                                            <td>4</td>
                                            <td>B</td>
                                        </tr>
                                        <tr>
                                            <td>SUB306</td>
                                            <td>Pathology</td>
                                            <td>3</td>
                                            <td>B+</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="fourth-year" role="tabpanel" aria-labelledby="fourth-year-tab">
                                <h5>1st Semester</h5>
                                <table class="table table-bordered subject-table">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject</th>
                                            <th>Units</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>SUB401</td>
                                            <td>Software Engineering</td>
                                            <td>3</td>
                                            <td>A</td>
                                        </tr>
                                        <tr>
                                            <td>SUB402</td>
                                            <td>Control Systems</td>
                                            <td>4</td>
                                            <td>B+</td>
                                        </tr>
                                        <tr>
                                            <td>SUB403</td>
                                            <td>Immunology</td>
                                            <td>3</td>
                                            <td>B</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h5>2nd Semester</h5>
                                <table class="table table-bordered subject-table">
                                    <thead>
                                        <tr>
                                            <th>Subject Code</th>
                                            <th>Subject</th>
                                            <th>Units</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>SUB404</td>
                                            <td>Artificial Intelligence</td>
                                            <td>3</td>
                                            <td>A-</td>
                                        </tr>
                                        <tr>
                                            <td>SUB405</td>
                                            <td>Communication Systems</td>
                                            <td>4</td>
                                            <td>B</td>
                                        </tr>
                                        <tr>
                                            <td>SUB406</td>
                                            <td>Genetics</td>
                                            <td>3</td>
                                            <td>B+</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <?php include 'includes/footer.php'; ?>
        </footer>
    </div>
</div>
<?php include 'includes/script.php'; ?>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>