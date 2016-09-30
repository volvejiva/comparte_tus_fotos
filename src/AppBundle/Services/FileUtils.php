<?php
namespace AppBundle\Services;

use AppBundle\Entity\Album;

class FileUtils{
    protected $logger;
    protected $entityManager;
    protected $appDirectory;
    
    public function __construct($logger, $entityManager, $appDirectory) {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->appDirectory = $appDirectory;
    }
    
    public function getDirectoryforAlbum(Album $album){
        $directoryName = $album->getId() . "_" . $album->getToken();
        $newDirectory = $this->appDirectory . "/../web/files/" . $directoryName;
        
        return $newDirectory;
    }
    
    public function getImagesforAlbum(Album $album){
        $ficherosTemp  = scandir($this->getDirectoryForAlbum($album));
        $imagenes = array();
        foreach($ficherosTemp as $fichero) {
            if ($fichero != "." && $fichero != "..") {
                $imagenes[] = 'files/' . $album->getId() . '_' . $album->getToken() . '/' . $fichero;
            }
        }
        
        return $imagenes;
    }
    
    /**
     * La función crea un directorio si no existe
     * Si el directorio se crea devuelve true y si no se crear porque ya existía devuelve false
     * 
     * @param Album $album
     * @return boolean
     **/
    public function createDirectoryforAlbum(Album $album){
        if(!file_exists($this->getDirectoryforAlbum($album))){
                    mkdir($this->getDirectoryforAlbum($album));
                    return true;
        }else{
            return false;
        }
    }

    /**
     * La función descomprime el fichero ZIP asociado al Album que se pase por parámetro
     * En el directorio correspondiente para el Album
     * En caso de fallar o no haber un fichero ZIP asociado devuelve false
     * Si todo funciona bien devuelve true
     * 
     * @param Album $album
     * @return bool
     **/
    public function uncompress($album){
        $success = false;
        
        if ($album->getZipName() != "") {
            
            // 1. Creamos el directorio
            $this->createDirectoryForAlbum($album);
            
            // 2. Extraemos en ese directorio las imágenes de mi ZIP
            $zipRouteComplete = $this->appDirectory . "/../web/files/" . $album->getZipName();
            
            $zip = new \ZipArchive;
            $res = $zip->open($zipRouteComplete);
            if ($res === TRUE) {
                // extract it to the path we determined above
                $zip->extractTo($this->getDirectoryForAlbum($album));
                $zip->close();
                $success = true;
            }         
            
            // 3. Eliminamos el ZIP dejando sólo el directorio con las imágenes
            unlink($zipRouteComplete);                
        }
        
        return $success;
    }
}