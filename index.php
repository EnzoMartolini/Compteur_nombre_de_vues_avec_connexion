<!DOCTYPE html>

<?php require 'function.php'; ?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>Compteur nombre de vues</title>
</head>
<body>
<?php AJOUTE_VUE() ?>
<?php if (CONNECT()===true):?> <!-- Verifie si le connexion est valide -->
        <div id=affichage>
        <div class=liste>
            <form>
                <ul>
                    <?php 
                    LISTE_ANNEE();
                    ?>
                </ul>
            </form>
        </div>

        <div id=nombre_de_vue>
            <h1> <?= TOTAL_VUES() ?> </h1>
        </div>
    </div>
<?php endif ?>   

</body>
</html>