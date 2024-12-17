<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <?php include 'includes/header.php'; ?> 
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS for table size */
        .subject-table {
            width: 100%; /* Make the table take the full width of the container */
            table-layout: fixed; /* Fixed layout for equal column widths */
        }
        .subject-table th, .subject-table td {
            text-align: center; /* Center align text */
            overflow: hidden; /* Hide overflow */
            white-space: nowrap; /* Prevent text from wrapping */
            text-overflow: ellipsis; /* Add ellipsis for overflowed text */
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
                <h1 class="mt-4">View Subject</h1>

                <!-- Tabbed Interface for Transcript of Records -->
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
                                <h5>1st Year</h5>
                                <h6>1st Semester</h6>
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
                                            <td>SUB101</td>
                                            <td>Introduction to Programming</td>
                                            <td>3</td>
                                            <td>A</td>
                                        </tr>
                                        <tr>
                                            <td>SUB102</td>
                                            <td>Calculus I</td>
                                            <td>4</td>
                                            <td>B+</td>
                                        </tr>
                                        <tr>
                                            <td>SUB103</td>
                                            <td>General Chemistry</td>
                                            <td>3</td>
                                            <td>B</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <h6>2nd Semester</h6>
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
                                            <td>SUB104</td>
                                            <td>Data Structures</td>
                                            <td>3</td>
                                            <td>A-</td>
                                        </tr>
                                        <tr>
                                            <td>SUB105</td>
                                            <td>Calculus II</td>
                                            <td>4</td>
                                            <td>B</td>
                                        </tr>
                                        <tr>
                                            <td>SUB106</td>
                                            <td>Organic Chemistry</td>
                                            <td>3</td>
                                            <td>B+</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane fade" id="second-year" role="tabpanel" aria-labelledby="second-year-tab">
                                <h5>2nd Year</h5>
                                <h6>1st Semester</h6>
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
                                <h6>2nd Semester</h6>
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
                                <h5>3rd Year</h5>
                                <h6>1st Semester</h6>
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
                                <h6>2nd Semester</h6>
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
                                <h5>4th Year</h5>
                                <h6>1st Semester</h6>
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
                                <h6>2nd Semester</h6>
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