# Librairie Youtube
PHP Backend pour Youtube
## Dépendance
* PHP Version 7.2
* jQuery 3.5.1
* Navigateur supportant les requêtes PUT, DELETE (> IE6).

## Installation
Télécharger les 5 fichiers et les mettre dans un dossier enfant de /www sur votre serveur PHP. Par exemple /www/ytlib/.
(UNIX) Selon votre environnement de travail, par exemple Ubuntu, vous devez donner la permission au serveur d'écrire et de lire dans le dossier /ytlib/.

* library.php gère l'API REST.
* testapi.php est en réalité un fichier HTML, une simple interface de test du backend.
* testapi.js s'occupe de faire le lien entre l'API et le front, il gère les requêtes AJAX et met à jour le front.
* (Optionnel) mark.json et john.json sont des fichiers tests aidant uniquement à accélerer la phase de test d'ajout et de suppression de libraries et de vidéos.

## Utilisation
L'API est composé de 7 fonctions détaillés ci-dessous:

|Routes|Method|Type|POSTED JSON|Description|
|------|------|------|------|------|
|/library.php|GET|JSON||Récupère la liste des utilisateurs possédant une librarie|
|/library.php?username={username}|GET|JSON||Récupère la librarie vidéo de l'utilisateur précisé|
|/library.php?username={username}&title={title}|GET|JSON||Cherche une vidéo par son titre dans la librarie de l'utilisateur|
|/library.php|POST|JSON|{"username":"John"}|Crée une nouvelle librarie si une librairie à ce nom n'existe pas encore|
|/library.php|PUT|JSON|{"username":"John","title":"song of storms","v":"GpVRNZaLuRc"}|Mets à jour la librarie de en ajoutant une nouvelle vidéo à sa collection|
|/library.php|DELETE|JSON|{"username":"John"}|Supprime la librarie de l'utilisateur spécifié|
|/library.php|DELETE|JSON|{"username":"John","v":"GpVRNZaLuRc"}|Supprime une vidéo spécifique de la collection de l'utilisateur spécifié|

L'API peut-être appellé par un navigateur récent avec des appels AJAX comme dans l'exemple fourni, ou bien directement depuis un serveur PHP avec cURL par exemple, ou encore un client REST tel que ARC.

La page de test permet de tester chacune des fonctions de l'API.
