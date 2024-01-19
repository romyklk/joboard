<?php

namespace App\Services;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploadFilesServices extends AbstractController
{
    // Crée une fonction qui permet de générer un nom unique pour le fichier
    private function generateUniqueFileName()
    {
        // On génère un nom unique pour le fichier

        $name = bin2hex(random_bytes(16)) . '' . uniqid() . '' . time() . '' . rand(1, 9999);

        return $name;
    }

    // Crée une fonction qui permet de sauvegarder le fichier

    public function saveFile($file)
    {
        // récupérer le nom du fichier grâce à originalName
        $fileName = $file->getClientOriginalName();

        // On génère un nom unique pour le fichier. On récupère l'extension du fichier grâce à guessExtension()
        $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();

        // On déplace le fichier dans le dossier uploads
        //$this->getParameter() est une méthode du abstractController qui permet de récupérer le dossier uploads
        $file->move(
            $this->getParameter('uploads_directory'),
            $fileName
        );
        return $fileName;
    }

    // Crée une fonction qui permet de mettre à jour le fichier

    public function updateFile($currentFile, $oldFile)
    {
        $fileName = $this->saveFile($currentFile);

        // On supprime l'ancien fichier si son nom est différent de l'image par défaut
        /*    if ($oldFile !== 'default.png') {
            try {
                unlink($this->getParameter('uploads_directory') . '/' . $oldFile);
            } catch (\Throwable $th) {
                //Throwable est l'interface de base pour toutes les exceptions et erreurs dans PHP 7. Throwable implémente l'interface Throwable. Les exceptions sont des objets qui peuvent être lancés. Les erreurs sont des objets qui représentent des erreurs fatales qui ne peuvent pas être lancées.
                
            }
        } */

        $this->deleteFile($oldFile);

        return $fileName;
    }


    // Crée une fonction qui permet de supprimer le fichier
    public function deleteFile($oldFile)
    {
        // On supprime l'ancien fichier si son nom est différent de l'image par défaut
        if ($oldFile !== 'default.png') {
            try {
                unlink($this->getParameter('uploads_directory') . '/' . $oldFile);
            } catch (\Throwable $th) {
            }
        }
    }
}
