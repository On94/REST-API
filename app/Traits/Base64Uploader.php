<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait Base64Uploader
{
    /**
     * @param string $validatedString
     * @param string $uploadPath
     * @return string
     */
    public function storeAndGetFIlePat(string $validatedString,string $uploadPath): string
    {
        $filename = $this->generateUniqFilename();
        Storage::disk('public')->put("$uploadPath/$filename", $this->decodeBase64data($validatedString));
        return asset("storage/$uploadPath/$filename");
    }

    /**
     * @param string $validatedString
     * @return bool|string
     */
    public function decodeBase64data(string $validatedString): bool|string
    {
        $base64Image = substr($validatedString, strpos($validatedString, ',') + 1);
        return base64_decode($base64Image);
    }

    /**
     * @return string
     */
    public function generateUniqFilename(): string
    {
        return Str::random(40) . '.png';
    }

}
