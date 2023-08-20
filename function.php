<?php 

    function LISTE_ANNEE(){ 
        for($i = 0; $i < 6; $i++)
        { 
            $annee = (date('Y')) - $i;
            $id=null;

            if (isset($_GET['year'])&&$annee===(int)$_GET['year']): //Defini l'id "active" sur la liste active
                $id= ' id=active ';
            endif;

            if(!file_exists(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$annee)): //Si le dossier de l'année actuelle n'existe pas, il est crée
                mkdir(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$annee);
            endif;

            echo '<a href="/index.php?year=' . $annee . '"><div class=bouton_annee'.$id.'>' . $annee . '</div></a>'; //Affiche la liste des 6 dernières années

            if ((isset($_GET['year'])) && $annee === (int)$_GET['year']) : //Quand l'année est selectionnée, on appelle la fonction LISTE_MOIS pour afficher les mois de l'année
                LISTE_MOIS(); 
            endif;

        }
    }

    function LISTE_MOIS(){
        $mois = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");
        if (isset($_GET['year'])):
            $year=($_GET['year']);
        else: $year=null;
        endif;

        if (isset($_GET['month'])):
            $month=($_GET['month']);
        else: $month=null;
        endif;

        foreach($mois as $value){
            $id=null;

            if(!file_exists(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$year.DIRECTORY_SEPARATOR.$value.'.txt')): //Si le fichier d'un mois n'existe pas, on le créer et on implémente 0
                file_put_contents(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$year.DIRECTORY_SEPARATOR.$value.'.txt', '0');
            endif;

            if(isset($_GET['month'])&&$value===$_GET['month']):
                $id= ' id=active ';
            endif;
            echo '<a href="/index.php?year='. $year . '&month=' . $value . '">'. '<div class="bouton_mois" '. $id .'>' . $value . '</div></a>'; //Affiche la liste des mois
        }
    }

    function TOTAL_VUES(){

        if (isset($_GET['year']) && isset($_GET['month'])):
            $year = $_GET['year'];
            $month = $_GET['month'];

            $total = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.$year.DIRECTORY_SEPARATOR.$month.'.txt'); //Retourne le nombre de vues selon le mois selectionné
            return 'Il y a eu ' . $total . ' vues en ' . $month . ' ' . $year;

        elseif (isset($_GET['year'])):

            $year = $_GET['year'];
            $total = null;
            $dossier = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . '*.txt'; //Si le mois n'est pas selectionné on affiche le total de l'année selectionnée
            $doss = glob($dossier);

            foreach ($doss as $value):
                $total += file_get_contents($value);
            endforeach;
            return 'Il y a eu ' . $total . ' vues en ' . $year;
        else:
            $dossier = __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR .'*'; // Si rien n'est selectionné on affiche le total de toutes les vues du site
            $doss = glob($dossier);
            $total=null;
            foreach($doss as $value):
                $dossier = glob($value . DIRECTORY_SEPARATOR . '*.txt');
                    foreach($dossier as $dossiers)
                    $total += file_get_contents($dossiers);
            endforeach;

            echo 'Il y a eu ' . $total . 'vues sur ce site';
        endif;

    }

    function AJOUTE_VUE(){

    // Défini les tableaux de correspondance entre les mois en anglais et en français
    $english_months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $french_months = array("Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre");


    $date = date("F");
    $date = str_replace($english_months, $french_months, $date);
     $year=(date('Y'));

    $total=file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . $date.'.txt'); //Recupère le nombre de vues pour le mois en cours
    $total+=1; //Ajoute un pour une vue
    file_put_contents( __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . $year . DIRECTORY_SEPARATOR . $date.'.txt', $total); //Renvoie le total de vue dans le fichier

    }

    function FORMULAIRE(){ //Soumet un formulaire de connexion
            echo <<<HTML
            
            <form action="" method='POST'>
            
            <label> Entrez le username <label>
                <input type='text' id='username' name='username' />
            
            <label> Entrez le mot de passe <label>
                <input type='text' id='password' name='password' />

            </br> <label> Rester connecté <label>
                <input type=checkbox id='stay' name='stay' />
            
    </br></br><button type='submit'>Envoyer</button>
            
HTML;
    }

    function CONNECT(){

    if(isset($_COOKIE['username'])&&isset($_COOKIE['password'])&&$_COOKIE['username']==='admin'&&$_COOKIE['password']==='admin'): //Verifie si les cookies de conexion ne sont pas déjà présent
        return true;
    else:
        if(isset($_POST['username'])&&isset($_POST['password'])): 

            if($_POST['username']==='admin'&&$_POST['password']==='admin'): //accepte la connexion, et set les cookies
                $cookie_name_username=$_POST['username'];
                $cookie_name_password=$_POST['password'];
                $cookie_stay=0;
                
                if(isset($_POST['stay'])):
                $cookie_stay=time()+60*60*24*30;
                endif;

                setcookie('username', $cookie_name_username,$cookie_stay);
                setcookie('password', $cookie_name_password,$cookie_stay);

                return true;
            else:
                setcookie('username', '');
                setcookie('password', '');
                FORMULAIRE();
                echo '</br> Les identifiants sont incorrectes'; //Refuse la connexion et unset les cookies
            endif;

        else:

            FORMULAIRE(); //Si c'est la première connexion on soumet le formulaire directement
        endif;
    endif;
    }