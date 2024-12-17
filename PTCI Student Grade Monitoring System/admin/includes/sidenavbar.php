<div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading"></div>
                            <a class="nav-link" href="<?php echo "dashboard.php" ?>">

                                <div class="sb-nav-link-icon"><i class="fas fa-gauge-high"></i></div>
                                <span class="nav-link-text">Dashboard</span>
                            </a>
                            <div class="sb-sidenav-menu-heading">School</div>
                            <a class="nav-link" href="<?php echo "schoolyear.php" ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-school"></i></div>
                                School Year
                              </a>
                              <a class="nav-link" href="<?php echo "semester.php" ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-layer-group"></i></div>
                                Semester
                              </a>
                              <a class="nav-link" href="<?php echo "yearlevel.php" ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-layer-group"></i></div>
                                Year Level
                              </a>
                            <a class="nav-link" href="<?php echo "department.php" ?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-building"></i></div>
                                Department
                            </a>
                            <a class="nav-link" href="<?php echo "classes.php"?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-users-rectangle"></i></div>
                                Classes
                            </a>
                            <div class="sb-sidenav-menu-heading">User</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseStudents" aria-expanded="false" aria-controls="collapseStudents">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                Students
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseStudents" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo "studentlist.php" ?>">Student List</a>
                                    <!--a class="nav-link" href="<!?php echo "studentclasses.php" ?>">Student Classes</a-->
                                    <a class="nav-link" href="<?php echo "studentgrades.php" ?>">Student Grades</a>
                                </nav>
                            </div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseTeacher" aria-expanded="false" aria-controls="collapseTeacher">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-tie"></i></div>
                                Teacher
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="collapseTeacher" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="<?php echo "teacherlist.php" ?>">Teacher List</a>
                                    <a class="nav-link" href="<?php echo "teacheradvisory.php" ?>">Teacher Advisory</a>
                                </nav>
                            </div>
                            <div class="sb-sidenav-menu-heading">Configuration</div>
                            <a class="nav-link" href="<?php echo "authentication.php"?>">
                                <div class="sb-nav-link-icon"><i class="fas fa-users-rectangle"></i></div>
                                Authentication
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Administrator
                    </div>