<?php
namespace App\PDFCryptoSigner;

use Exception;

class PDFCryptoSigner implements CryptoSigner
{
    private $path;

    /**
     * @throws Exception
     */
    public function __construct(string $path = null)
    {
        if ($path !== null) {
            $this->loadFile($path);
        }
    }

    /**
     * @throws Exception
     */
    public function loadFile(string $path): CryptoSigner
    {
        $this->path = $path;
        return $this;
    }
    /**
     * @throws Exception
     */
    public static function load(string $path): CryptoSigner
    {
        return new self($path);
    }

    public function sign(string $privateKey, array $signerInfo = []): string
    {
        $content = file_get_contents($this->path);
        $signerInfo['version'] = 1;
        $signerInfo['date'] = date('Y-m-d H:i:s');

        $headers = json_encode($signerInfo);
        $base64Headers = CryptoManager::base64Encode($headers);
        $content = $content . "\n" . '% ' . '7PI' . $base64Headers;

        file_put_contents($this->path(), $headers);

        $base64File = CryptoManager::base64Encode($content);
        $hashed = CryptoManager::hash($base64File);
        $signature = CryptoManager::sign($hashed, $privateKey);
        $signature = CryptoManager::base64Encode($signature);

        $comment = '7PI' . $signature;
        $modifiedPDFContent = $content . $comment;

        file_put_contents($this->path, $modifiedPDFContent);

        return $comment;
    }

    public function verify(string $publicKey): array
    {
        try {
            $content = file_get_contents($this->path);

            $explodedContent = explode("\n", $content);
            $embeddedSignature = end($explodedContent);
            array_pop($explodedContent);
            $explodedSignature = explode('7PI', $embeddedSignature);

            if (count($explodedSignature) !== 3) {
                throw new Exception('Invalid signature');
            }

            $encodedHeaders = $explodedSignature[1];
            $encodedSignature = $explodedSignature[2];
            array_pop($explodedSignature);

            $embeddedSignature = implode('7PI', $explodedSignature);

            $explodedContent[] = $embeddedSignature;
            $content = implode("\n", $explodedContent);

            $base64File = CryptoManager::base64Encode($content);
            $hashed = CryptoManager::hash($base64File);

            $signature = CryptoManager::base64Decode($encodedSignature);
            $headers['verified'] = CryptoManager::verify($hashed, $signature, $publicKey);

            if ($headers['verified']){
                $headers = array_merge($headers, json_decode(CryptoManager::base64Decode($encodedHeaders), true));
            }

        } catch (Exception) {
            $headers = ['verified' => false];
        }
        return $headers;
    }

    public function getHeaders(): array|false
    {
        try {
            $content = file_get_contents($this->path);

            $explodedContent = explode("\n", $content);
            $embeddedSignature = end($explodedContent);
            $explodedSignature = explode('7PI', $embeddedSignature);

            if (count($explodedSignature) !== 3) {
                throw new Exception('Invalid signature');
            }

            $encodedHeaders = $explodedSignature[1];

            return json_decode(CryptoManager::base64Decode($encodedHeaders), true);

        } catch (Exception) {
            return false;
        }
    }

    public function path(): string
    {
        return $this->path;
    }

}
