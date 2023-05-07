<?php

namespace App\Utils;

class StorageParser
{

    public function getStorageFromHdd(string $hddValue): string
    {
        // Get the value till GB or TB
        $pattern = '/^((\d+x)?\d+(TB|GB))/';
        preg_match($pattern, $hddValue, $matches);
        if (empty($matches)) {
            return '0 GB';
        }

        // If there are multiple disks, calculate the total storage in GB
        $value = $matches[1];
        if (strpos($value, 'x') !== false) {
            [$count, $size] = explode('x', $value);
            $sizeInGB = $this->getSizeInGB($size);
            return $sizeInGB * $count.' GB';
        }
        //here value is in like 120GB or 2TB
        return $this->getSizeInGB($value).' GB';
    }

    private function getSizeInGB($size)
    {
        if (str_contains($size, 'TB')) {
            //get the integer value of the size
            $sizeInGB = (int) str_replace('TB', '', $size);
            $sizeInGB *= 1024;
            return $sizeInGB;
        }

        return (int) str_replace('GB', '', $size);
    }

}
