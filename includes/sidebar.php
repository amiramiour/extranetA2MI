<!-- BEGIN #sidebar -->
    <div id="sidebar" class="app-sidebar">
        <!-- BEGIN scrollbar -->
        <div class="app-sidebar-content" data-scrollbar="true" data-height="100%">
            <!-- BEGIN menu -->
            <div class="menu">
                <div class="menu-profile">
                    <a href="profile.php" class="menu-profile-link" data-toggle="app-sidebar-profile"
                       data-target="#appSidebarProfileMenu">
                        <div class="menu-profile-cover with-shadow"></div>
                        <div class="menu-profile-info">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1"><?= $_SESSION['name'] ?> <?= $_SESSION['firstname'] ?></div>
                                <div class="menu-caret ms-auto"></div>
                            </div>
                            <?php if (isset($_SESSION['role']) AND $_SESSION['role'] != 'client' AND isset($_SESSION['mail'])): ?>
                            <small><?= ucfirst($_SESSION['role'] ?? '') ?></small>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
                <div id="appSidebarProfileMenu" class="">
                    <div class="menu-item <?php if($result=="index") echo 'active'; ?>">
                        <a href="index.php" class="menu-link">
                            <div class="menu-icon">
                                <i class="fa fa-home"></i>
                            </div>
                            <div class="menu-text">Accueil</div>
                        </a>
                    </div>
                    <div class="menu-item <?php if($result=="profile") echo 'active'; ?>">
                        <a href="profile.php?id=<?= $_SESSION['id']; ?>" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-cog"></i></div>
                            <div class="menu-text">Profil</div>
                        </a>
                    </div>
                    <div class="menu-item">
                        <a href="logout.php" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-lock-open"></i></div>
                            <div class="menu-text"> Déconnexion</div>
                        </a>
                    </div>
                    <!--<div class="menu-item">
                        <a href="javascript:;" class="menu-link">
                            <div class="menu-icon"><i class="fa fa-pencil-alt"></i></div>
                            <div class="menu-text"> Nous contacter</div>
                        </a>
                    </div>-->
                </div>
                <?php if (isset($_SESSION['role']) AND $_SESSION['role'] != 'client' AND isset($_SESSION['mail'])): ?>
                <div class="menu-divider m-0"></div>
                <div class="menu-header">Back-Office</div>

                <div class="menu-item has-sub <?php if($result=="orders" OR $result=="products" OR $result=="category") echo 'active'; ?>">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-cart-shopping"></i>
                        </div>
                        <div class="menu-text">Boutique</div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item <?php if($result=="orders") echo 'active'; ?>">
                            <a href="orders.php" class="menu-link">
                                <div class="menu-text">Commandes</div>
                            </a>
                        </div>
                        <div class="menu-item <?php if($result=="products") echo 'active'; ?>">
                            <a href="products.php" class="menu-link">
                                <div class="menu-text">Produits</div>
                            </a>
                        </div>
                        <div class="menu-item <?php if($result=="category") echo 'active'; ?>">
                            <a href="category.php" class="menu-link">
                                <div class="menu-text">Catégories</div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="menu-item <?php if($result=="communication") echo 'active'; ?>">
                    <a href="communication.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-comments"></i>
                        </div>
                        <div class="menu-text">Communication</div>
                    </a>
                </div>
                <div class="menu-item <?php if($result=="formation") echo 'active'; ?>">
                    <a href="formation.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-tasks"></i>
                        </div>
                        <div class="menu-text">Formation</div>
                    </a>
                </div>
                <div class="menu-item <?php if($result=="sitepage") echo 'active'; ?>">
                    <a href="sitepage.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-institution alias"></i>
                        </div>
                        <div class="menu-text">Informations du site</div>
                    </a>
                </div>
                <div class="menu-item <?php if($result=="infoservice") echo 'active'; ?>">
                    <a href="infoservice.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-cogs"></i>
                        </div>
                        <div class="menu-text">Service informatique</div>
                    </a>
                </div>
                <div class="menu-item <?php if($result=="management") echo 'active'; ?>">
                    <a href="management.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-building"></i>
                        </div>
                        <div class="menu-text">Gestion administrative</div>
                    </a>
                </div>
                <div class="menu-item <?php if($result=="siteweb") echo 'active'; ?>">
                    <a href="siteweb.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-code"></i>
                        </div>
                        <div class="menu-text">Sites</div>
                    </a>
                </div>

                <div class="menu-divider m-0"></div>
                <div class="menu-header">Extranet</div>

                <div class="menu-item <?php if($result=="users") echo 'active'; ?>">
                    <a href="users.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-users"></i>
                        </div>
                        <div class="menu-text">Utilisateurs</div>
                    </a>
                </div>
                <div class="menu-item <?php if($result=="fournisseurs") echo 'active'; ?>">
                    <a href="fournisseurs.php" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-truck"></i>
                        </div>
                        <div class="menu-text">Fournisseurs</div>
                    </a>
                </div>

                    <div class="menu-item <?php if($result=="bi") echo 'active'; ?>">
                        <a href="bi.php" class="menu-link">
                            <div class="menu-icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="menu-text">Intervention (BI)</div>
                        </a>
                    </div>
                    <div class="menu-item <?php if($result=="sav") echo 'active'; ?>">
                        <a href="sav.php" class="menu-link">
                            <div class="menu-icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="menu-text">SAV</div>
                        </a>
                    </div>
                    <div class="menu-item <?php if($result=="commandes_devis") echo 'active'; ?>">
                        <a href="list_commandes.php" class="menu-link">
                            <div class="menu-icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="menu-text">Commandes</div>
                        </a>
                    </div>
                    <div class="menu-item <?php if($result=="commandes_devis") echo 'active'; ?>">
                        <a href="list_devis.php" class="menu-link">
                            <div class="menu-icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="menu-text">Devis</div>
                        </a>
                    </div>
                    <div class="menu-item <?php if($result=="pret") echo 'active'; ?>">
                        <a href="pret.php" class="menu-link">
                            <div class="menu-icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="menu-text">Prêts</div>
                        </a>
                    </div>
                    <div class="menu-item <?php if($result=="formulaire") echo 'active'; ?>">
                        <a href="formulaire.php" class="menu-link">
                            <div class="menu-icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="menu-text">Formulaires</div>
                        </a>
                    </div>
                    <div class="menu-item <?php if($result=="fiche") echo 'active'; ?>">
                        <a href="fiche.php" class="menu-link">
                            <div class="menu-icon">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="menu-text">Fiche individuelle</div>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="menu-divider m-0"></div>
                <div class="menu-header">Outils</div>
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-sitemap"></i>
                        </div>
                        <div class="menu-text">Dashboard</div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="index.html" class="menu-link"><div class="menu-text">Dashboard v1</div></a>
                        </div>
                        <div class="menu-item">
                            <a href="index_v2.html" class="menu-link"><div class="menu-text">Dashboard v2</div></a>
                        </div>
                        <div class="menu-item">
                            <a href="index_v3.html" class="menu-link"><div class="menu-text">Dashboard v3</div></a>
                        </div>
                    </div>
                </div>
               
                <div class="menu-item">
                    <a href="widget.html" class="menu-link">
                        <div class="menu-icon">
                            <i class="fab fa-simplybuilt"></i>
                        </div>
                        <div class="menu-text">Widgets <span class="menu-label">NEW</span></div>
                    </a>
                </div>
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-gem"></i>
                        </div>
                        <div class="menu-text">UI Elements <span class="menu-label">NEW</span></div> 
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="ui_general.html" class="menu-link">
                                <div class="menu-text">General <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_typography.html" class="menu-link">
                                <div class="menu-text">Typography</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_tabs_accordions.html" class="menu-link">
                                <div class="menu-text">Tabs & Accordions</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_unlimited_tabs.html" class="menu-link">
                                <div class="menu-text">Unlimited Nav Tabs</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_modal_notification.html" class="menu-link">
                                <div class="menu-text">Modal & Notification <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_widget_boxes.html" class="menu-link">
                                <div class="menu-text">Widget Boxes</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_media_object.html" class="menu-link">
                                <div class="menu-text">Media Object</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_buttons.html" class="menu-link">
                                <div class="menu-text">Buttons <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_icons.html" class="menu-link">
                                <div class="menu-text">Icons</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_simple_line_icons.html" class="menu-link">
                                <div class="menu-text">Simple Line Icons</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_ionicons.html" class="menu-link">
                                <div class="menu-text">Ionicons</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_tree.html" class="menu-link">
                                <div class="menu-text">Tree View</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_language_bar_icon.html" class="menu-link">
                                <div class="menu-text">Language Bar & Icon</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_social_buttons.html" class="menu-link">
                                <div class="menu-text">Social Buttons</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_tour.html" class="menu-link">
                                <div class="menu-text">Intro JS</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="ui_offcanvas_toasts.html" class="menu-link">
                                <div class="menu-text">Offcanvas & Toasts <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item ">
                    <a href="bootstrap_5.html" class="menu-link">
                        <div class="menu-icon-img">
                            <!--<img src="../assets/img/logo/logo-bs5.png" alt="" />-->
                        </div>
                        <div class="menu-text">Bootstrap 5 <span class="menu-label">NEW</span></div> 
                    </a>
                </div>
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-list-ol"></i>
                        </div>
                        <div class="menu-text">Form Stuff <span class="menu-label">NEW</span></div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="form_elements.html" class="menu-link">
                                <div class="menu-text">Form Elements <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="form_plugins.html" class="menu-link">
                                <div class="menu-text">Form Plugins <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="form_slider_switcher.html" class="menu-link">
                                <div class="menu-text">Form Slider + Switcher</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="form_validation.html" class="menu-link">
                                <div class="menu-text">Form Validation</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="form_wizards.html" class="menu-link">
                                <div class="menu-text">Wizards <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="form_wysiwyg.html" class="menu-link">
                                <div class="menu-text">WYSIWYG</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="form_editable.html" class="menu-link">
                                <div class="menu-text">X-Editable</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="form_multiple_upload.html" class="menu-link">
                                <div class="menu-text">Multiple File Upload</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="form_summernote.html" class="menu-link">
                                <div class="menu-text">Summernote</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="form_dropzone.html" class="menu-link">
                                <div class="menu-text">Dropzone</div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-table"></i>
                        </div>
                        <div class="menu-text">Tables</div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="table_basic.html" class="menu-link">
                                <div class="menu-text">Basic Tables</div>
                            </a>
                        </div>
                        <div class="menu-item has-sub">
                            <a href="javascript:;" class="menu-link">
                                <div class="menu-text">Managed Tables</div>
                                <div class="menu-caret"></div>
                            </a>
                            <div class="menu-submenu">
                                <div class="menu-item">
                                    <a href="table_manage.html" class="menu-link">
                                        <div class="menu-text">Default</div>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a href="table_manage_buttons.html" class="menu-link">
                                        <div class="menu-text">Buttons</div>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a href="table_manage_colreorder.html" class="menu-link">
                                        <div class="menu-text">ColReorder</div>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a href="table_manage_fixed_columns.html" class="menu-link">
                                        <div class="menu-text">Fixed Column</div>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a href="table_manage_fixed_header.html" class="menu-link">
                                        <div class="menu-text">Fixed Header</div>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a href="table_manage_keytable.html" class="menu-link">
                                        <div class="menu-text">KeyTable</div>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a href="table_manage_responsive.html" class="menu-link">
                                        <div class="menu-text">Responsive</div>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a href="table_manage_rowreorder.html" class="menu-link">
                                        <div class="menu-text">RowReorder</div>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a href="table_manage_scroller.html" class="menu-link">
                                        <div class="menu-text">Scroller</div>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a href="table_manage_select.html" class="menu-link">
                                        <div class="menu-text">Select</div>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a href="table_manage_combine.html" class="menu-link">
                                        <div class="menu-text">Extension Combination</div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-chart-pie"></i>
                        </div>
                        <div class="menu-text">Chart</div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="chart-flot.html" class="menu-link">
                                <div class="menu-text">Flot Chart</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="chart-js.html" class="menu-link">
                                <div class="menu-text">Chart JS</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="chart-d3.html" class="menu-link">
                                <div class="menu-text">d3 Chart</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="chart-apex.html" class="menu-link">
                                <div class="menu-text">Apex Chart</div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-image"></i>
                        </div>
                        <div class="menu-text">Gallery</div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="gallery.html" class="menu-link">
                                <div class="menu-text">Gallery v1</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="gallery_v2.html" class="menu-link">
                                <div class="menu-text">Gallery v2</div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-cogs"></i>
                        </div>
                        <div class="menu-text">Page Options <span class="menu-label">NEW</span></div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="page_blank.html" class="menu-link">
                                <div class="menu-text">Blank Page</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_footer.html" class="menu-link">
                                <div class="menu-text">Page with Footer</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_fixed_footer.html" class="menu-link">
                                <div class="menu-text">Page with Fixed Footer <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_without_sidebar.html" class="menu-link">
                                <div class="menu-text">Page without Sidebar</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_right_sidebar.html" class="menu-link">
                                <div class="menu-text">Page with Right Sidebar</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_minified_sidebar.html" class="menu-link">
                                <div class="menu-text">Page with Minified Sidebar</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_two_sidebar.html" class="menu-link">
                                <div class="menu-text">Page with Two Sidebar</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_line_icons.html" class="menu-link">
                                <div class="menu-text">Page with Line Icons</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_ionicons.html" class="menu-link">
                                <div class="menu-text">Page with Ionicons</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_full_height.html" class="menu-link">
                                <div class="menu-text">Full Height Content</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_wide_sidebar.html" class="menu-link">
                                <div class="menu-text">Page with Wide Sidebar</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_mega_menu.html" class="menu-link">
                                <div class="menu-text">Page with Mega Menu</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_top_menu.html" class="menu-link">
                                <div class="menu-text">Page with Top Menu</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_boxed_layout.html" class="menu-link">
                                <div class="menu-text">Page with Boxed Layout</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_mixed_menu.html" class="menu-link">
                                <div class="menu-text">Page with Mixed Menu</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_boxed_layout_with_mixed_menu.html" class="menu-link">
                                <div class="menu-text">Boxed Layout with Mixed Menu</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="page_with_search_sidebar.html" class="menu-link">
                                <div class="menu-text">Page with Search Sidebar <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-gift"></i>
                        </div>
                        <div class="menu-text">Extra <span class="menu-label">NEW</span></div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="extra_timeline.html" class="menu-link">
                                <div class="menu-text">Timeline</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="extra_coming_soon.html" class="menu-link">
                                <div class="menu-text">Coming Soon Page</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="extra_search_results.html" class="menu-link">
                                <div class="menu-text">Search Results</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="extra_invoice.html" class="menu-link">
                                <div class="menu-text">Invoice</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="extra_404_error.html" class="menu-link">
                                <div class="menu-text">404 Error Page</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="extra_profile.html" class="menu-link">
                                <div class="menu-text">Profile Page</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="extra_scrum_board.html" class="menu-link">
                                <div class="menu-text">Scrum Board <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="extra_cookie_acceptance_banner.html" class="menu-link">
                                <div class="menu-text">Cookie Acceptance Banner <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="extra_orders.html" class="menu-link">
                                <div class="menu-text">Orders <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="extra_products.html" class="menu-link">
                                <div class="menu-text">Products <i class="fa fa-paper-plane text-theme"></i></div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-key"></i>
                        </div>
                        <div class="menu-text">Login & Register</div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="login.html" class="menu-link">
                                <div class="menu-text">Login</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="login_v2.html" class="menu-link">
                                <div class="menu-text">Login v2</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="login_v3.html" class="menu-link">
                                <div class="menu-text">Login v3</div>
                            </a>
                        </div>
                        <div class="menu-item">
                            <a href="register_v3.html" class="menu-link">
                                <div class="menu-text">Register v3</div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-medkit"></i>
                        </div>
                        <div class="menu-text">Helper</div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item">
                            <a href="helper_css.html" class="menu-link">
                                <div class="menu-text">Predefined CSS Classes</div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="menu-item has-sub">
                    <a href="javascript:;" class="menu-link">
                        <div class="menu-icon">
                            <i class="fa fa-align-left"></i>
                        </div>
                        <div class="menu-text">Menu Level</div>
                        <div class="menu-caret"></div>
                    </a>
                    <div class="menu-submenu">
                        <div class="menu-item has-sub">
                            <a href="javascript:;" class="menu-link">
                                <div class="menu-text">Menu 1.1</div>
                                <div class="menu-caret"></div>
                            </a>
                            <div class="menu-submenu">
                                <div class="menu-item has-sub">
                                    <a href="javascript:;" class="menu-link">
                                        <div class="menu-text">Menu 2.1</div>
                                        <div class="menu-caret"></div>
                                    </a>
                                    <div class="menu-submenu">
                                        <div class="menu-item"><a href="javascript:;" class="menu-link"><div class="menu-text">Menu 3.1</div></a></div>
                                        <div class="menu-item"><a href="javascript:;" class="menu-link"><div class="menu-text">Menu 3.2</div></a></div>
                                    </div>
                                </div>
                                <div class="menu-item"><a href="javascript:;" class="menu-link"><div class="menu-text">Menu 2.2</div></a></div>
                                <div class="menu-item"><a href="javascript:;" class="menu-link"><div class="menu-text">Menu 2.3</div></a></div>
                            </div>
                        </div>
                        <div class="menu-item"><a href="javascript:;" class="menu-link"><div class="menu-text">Menu 1.2</div></a></div>
                        <div class="menu-item"><a href="javascript:;" class="menu-link"><div class="menu-text">Menu 1.3</div></a></div>
                    </div>
                </div>

                <!-- BEGIN minify-button -->
                <div class="menu-item d-flex">
                    <a href="javascript:;" class="app-sidebar-minify-btn ms-auto" data-toggle="app-sidebar-minify"><i class="fa fa-angle-double-left"></i></a>
                </div>
                <!-- END minify-button -->
            </div>
            <!-- END menu -->
        </div>
        <!-- END scrollbar -->
    </div>
    <div class="app-sidebar-bg"></div>
    <div class="app-sidebar-mobile-backdrop"><a href="#" data-dismiss="app-sidebar-mobile" class="stretched-link"></a></div>
    <!-- END #sidebar -->