
# Cuisinotron - like site test

Ce projet est à destination des BTS SNIR
et n'a aucune vocation à être utilisé
autrement qu'à des fins de tests.

## Prérequis

Version de PHP: 7.4+
Version de MariaDB : 10.*+

## Installation

Récupérer le projet via Git ou export.
Créer un fichier libs/cfg.ini en suivant le fichier cfg.ini.dist.
S'assurer de bien compléter les valeurs du fichier ini.

## Administration

Ajouter le fichier .htpasswd au même endroit que le .htaccess
et ajouter les utilisateurs.
Un script admin/encode.php existe pour créer des mots de passe sha1.
Vous pouvez l'utiliser via `php admin/encode.php <mdp>`
en remplaçant `<mdp>` par le mot de passe à encoder.

## Contenu

Le fichier m.html contient une maquette, à des fins de test.
Le fichier test.php est utilisé à des fins de tests unitaires.
Le dossier admin est la zone administrateur.
Le dossier libs contient les librairies.
Le dossier resources contient les ressources (images, style, scripts...).
