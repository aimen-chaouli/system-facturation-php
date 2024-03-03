<ul class="nav navbar-nav">
<li class="dropdown">
	<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Factures
	<span class="caret"></span></button>
	<ul class="dropdown-menu">
		<li><a href="invoice_list.php">Listes de Factures</a></li>
		<li><a href="create_invoice.php">Créer une nouvelle Facture</a></li>				  
	</ul>
</li>
<?php 
if($_SESSION['userid']) { ?>
	<li class="dropdown">
		<button class="btn btn-info dropdown-toggle" type="button" data-toggle="dropdown">Connecté <?php echo $_SESSION['user']; ?>
		<span class="caret"></span></button>
		<ul class="dropdown-menu">
			<li><a href="#">Compte</a></li>
			<li><a href="action.php?action=logout">Déconnecter</a></li>		  
		</ul>
	</li>
<?php } ?>
</ul>
<br /><br /><br /><br />