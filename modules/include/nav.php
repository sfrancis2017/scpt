<header class="l-header">
    <div class="l-header__inner clearfix">
        <div class="c-header-icon js-hamburger">
            <div class="hamburger-toggle"><span class="bar-top"></span><span class="bar-mid"></span><span class="bar-bot"></span></div>
        </div>
        <div class="c-header-icon has-dropdown"><span class="c-badge c-badge--header-icon animated shake">12</span><i class="fa fa-bell"></i>
            <div class="c-dropdown c-dropdown--notifications">
                <div class="c-dropdown__header"></div>
                <div class="c-dropdown__content"></div>
            </div>
        </div>
        <div class="c-search">
            <input class="c-search__input u-input" placeholder="Search..." type="text"/>
        </div>
        <div class="header-icons-group">
            <a href="logout.php"><div class="c-header-icon logout"><i class="fa fa-power-off"></i></div></a>
        </div>
    </div>
</header>
<div class="l-sidebar">
    <div class="logo">
        <div class="logo__txt">SCPT</div>
    </div>
    <div class="l-sidebar__content">
        <nav class="c-menu js-menu">
            <ul class="u-list">
                <a href="dashboard.php"><li class="c-menu__item is-active" data-toggle="tooltip" title="Dashboard">
                    <div class="c-menu__item__inner"><i class="fa fa-dashboard"></i>
                        <div class="c-menu-item__title"><span><a href="dashboard.php">Dashboard</a></span></div>
                    </div>
                    </li></a>
                <a href="profile.php?userid=<?php echo $_SESSION['user']['id'] ?>"><li class="c-menu__item has-submenu" data-toggle="tooltip" title="Profile">
                    <div class="c-menu__item__inner"><i class="fa fa-user"></i>
                        <div class="c-menu-item__title"><span><a href="profile.php?userid=<?php echo $_SESSION['user']['id'] ?>">Profile</a></span></div>
                    </div>
                </li></a>
                <a href="calendars.php"><li class="c-menu__item has-dropdown" data-toggle="tooltip" title="Calendar">
                    <div class="c-menu__item__inner"><i class="fa fa-calendar"></i>
                        <div class="c-menu-item__title"><span><a href="calendars.php">Calendar</a></span></div>
                    </div>
                    </li></a>
                <a href="availability.php"><li class="c-menu__item has-dropdown" data-toggle="tooltip" title="Availability">
                    <div class="c-menu__item__inner"><i class="fa fa-file-text-o"></i>
                        <div class="c-menu-item__title"><span><a href="availability.php">Availability</a></span></div>
                    </div>
                    </li></a>
                <a href="reservations.php"><li class="c-menu__item has-submenu" data-toggle="tooltip" title="Reservations">
                    <div class="c-menu__item__inner"><i class="fa fa-book"></i>
                        <div class="c-menu-item__title"><span><a href="reservations.php">Reservations</a></span></div>
                    </div>
                    </li></a>
                <?php if (($_SESSION['user']['role'] == "sonadmin") || ($_SESSION['user']['role'] == "hospadmin") || ($_SESSION['user']['role'] == "superadmin")) { ?>
                    <a href="reports.php"><li class="c-menu__item has-submenu" data-toggle="tooltip" title="Reports">
                            <div class="c-menu__item__inner"><i class="fa fa-print"></i>
                                <div class="c-menu-item__title"><span><a href="reports.php">Reports</a></span></div>
                            </div>
                        </li></a>
                    <a href="documents.php"><li class="c-menu__item has-submenu" data-toggle="tooltip" title="Documents">
                            <div class="c-menu__item__inner"><i class="fa fa-file"></i>
                                <div class="c-menu-item__title"><span><a href="documents.php">Documents</a></span></div>
                            </div>
                        </li></a>
                    <a href="settings.php"><li class="c-menu__item has-submenu" data-toggle="tooltip" title="Settings">
                            <div class="c-menu__item__inner"><i class="fa fa-cogs"></i>
                                <div class="c-menu-item__title"><span><a href="settings.php">Settings</a></span></div>
                            </div>
                        </li></a>

                <?php } ?>
            </ul>
        </nav>
    </div>
</div>
