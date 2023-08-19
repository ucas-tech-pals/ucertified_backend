<?php 

namespace App\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class NFTStorageService implements IPFSStorageService{

    private $domain = "https://api.nft.storage";

    public function upload($fileContent, $fileName)
    { 
        $response = Http::withHeaders([
            "Authorization" => "Bearer ". env('NFT_STORAGE_KEY')
        ])->attach("file", $fileContent , $fileName)->post($this->domain . "/upload")->json();

        $cid = Arr::get($response, "value.cid");

        $ipfs_link = "https://ipfs.io/ipfs/{$cid}/{$fileName}";
        return $ipfs_link;
    }
// _________________________________________________________

    public function get()
    {
        $response = Http::withHeaders([
            "Authorization" => "Bearer ". env('NFT_STORAGE_KEY')
        ])->get($this->domain)->json();

        $stored_files = [];
        foreach ($response['value'] as $item) {
            $cid = $item['cid'];
            $created = substr($item['created'], 0, 10);
            $sizeInMegabytes = $item['size'] / 1024;
            $formattedSize = number_format($sizeInMegabytes, 2);

            foreach ($item['files'] as $file) {
                $name = $file['name'];
                $type = $file['type'];
                $ipfs_link = "https://ipfs.io/ipfs/{$cid}/{$name}";

                $stored_files[] = [
                    'name' => $name,
                    'type' => $type,
                    'size' => "{$formattedSize} MB",
                    'link' => $ipfs_link,
                    'created' => $created,
                ];
            }
        }

        return $stored_files;
    }
}
// _________________________________________________________

?>