<?php 

namespace App\Services;

interface IPFSStorageService {

        public function upload($fileContent, $fileName);

        public function get(); 
}
?>