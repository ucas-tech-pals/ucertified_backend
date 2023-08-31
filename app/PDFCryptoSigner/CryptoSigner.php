<?php

namespace App\PDFCryptoSigner;

interface CryptoSigner{
//    public function load(string $path) : CryptoSigner;
    public static function load(string $path) : CryptoSigner;
    public function sign(string $privateKey, array $signerInfo) : string;
    public function verify(string $publicKey) : array;
    public function path() : string;
}