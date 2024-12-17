<div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading"></div>
                            <a class="nav-link" href="<?php echo "dashboard.php" ?>">

                                <div class="sb-nav-link-icon"><i class="fas fa-gauge-high"></i></div>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                            <div class="sb-sidenav-menu-heading">Teacher</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseStudents" aria-expanded="false" aria-controls="collapseStudents">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                Advisory
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseStudents" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo "studentlist.php" ?>">Student List</a>
                                    <a class="nav-link" href="<?php echo "classes.php" ?>">Classes</a>
                                    <a class="nav-link" href="<?php echo "gradestudent.php" ?>">Grade Student</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Teacher's Name
                    </div>