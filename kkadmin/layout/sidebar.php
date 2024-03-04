<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
			<?php if ($userRole == 'Super-Admin' || $userRole == 'Admin') : ?>
				<li><a class="" href="../pages/index.php">
						<i class="flaticon-homepage"></i>
						<span class="nav-text">Accueil</span>
					</a>
				</li>
            <?php endif; ?>
            <?php if ($userRole == 'Super-Admin' || $userRole == 'Admin' || $userRole == 'Blogger') : ?>
                <li><a class="" href="../pages/category.php">
                        <i class="flaticon-plugin"></i>
                        <span class="nav-text">Catégories d'article</span>
                    </a>
                </li>
                <li><a class="" href="../pages/article.php">
                        <i class="flaticon-contract"></i>
                        <span class="nav-text">Article</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($userRole == 'Super-Admin' || $userRole == 'Admin' || $userRole == 'Editor') : ?>
                <li><a class="" href="../pages/project.php">
                        <i class="flaticon-app-store"></i>
                        <span class="nav-text">Projets</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($userRole == 'Super-Admin') : ?>    
                <li><a class="" href="../pages/awards.php">
                        <i class="flaticon-contract"></i>
                        <span class="nav-text">Les prix awards</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($userRole == 'Super-Admin'|| $userRole == 'Admin') : ?>    
                <li><a class="" href="../pages/clients.php">
                        <i class="flaticon-contract"></i>
                        <span class="nav-text">Les clients</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($userRole == 'Super-Admin'|| $userRole == 'Admin') : ?>    
                <li><a class="" href="../pages/testimonials.php">
                        <i class="flaticon-contract"></i>
                        <span class="nav-text">Les témoignages</span>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($userRole == 'Super-Admin') : ?>
                <li><a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
                        <i class="flaticon-form"></i>
                        <span class="nav-text">Gestion des comptes</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="../pages/user-index.php">Récap des publications</a></li>
                        <li><a href="../pages/users.php">Les utilisateurs</a></li>
                    </a>
                </li>
                    </ul>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
