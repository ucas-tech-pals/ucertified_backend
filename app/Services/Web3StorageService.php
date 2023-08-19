<?php 

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class Web3StorageService implements IPFSStorageService{

    private $domain = "https://api.web3.storage";

    public function upload($fileContent, $fileName)
    { 
        $response = Http::withHeaders([
            "Authorization" => "Bearer ". env('WEB3_STORAGE_KEY')
        ])->attach("file", $fileContent , $fileName)->post($this->domain . "/upload")->json();

        $cid = Arr::get($response, "cid");

        $ipfs_link = "https://ipfs.io/ipfs/{$cid}/{$fileName}";
        return $ipfs_link;
    }
// _________________________________________________________

    public function get()
    {
        $response = Http::withHeaders([
            "Authorization" => "Bearer ". env('WEB3_STORAGE_KEY')
        ])->get($this->domain . "/user/uploads")->json();

        $stored_files = [];
        
        foreach ($response as $item) {
            $cid = $item['cid'];
            $created = substr($item['created'], 0, 10);
            $sizeInMegabytes = $item['dagSize'] / 1024;
            $formattedSize = number_format($sizeInMegabytes, 2);
            $ipfs_link = "https://ipfs.io/ipfs/{$cid}/";
            $stored_files[] = [
                'size' => "{$formattedSize} MB",
                'link' => $ipfs_link,
                'created' => $created,
            ];
        }
        return $stored_files;
    }
}
// _________________________________________________________

?>