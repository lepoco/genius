<?php

namespace App\Core\Files;

use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Support\Str;

/**
 * Performs operations on files.
 *
 * @author  Pomianowski <kontakt@rapiddev.pl>
 * @license GPL-3.0 https://www.gnu.org/licenses/gpl-3.0.txt
 * @since   1.1.0
 */
final class Image
{
  /**
   * @see https://www.php.net/manual/en/function.imagejpeg.php
   * @see https://www.php.net/manual/en/function.imagescale.php
   */
  public static function scale(string $sourcePath, int $width, int $height, bool $crop = true): string
  {
    $sourceImage = null;
    $imageData = getimagesize($sourcePath);

    $targetPath = Str::beforeLast($sourcePath, '/') . '/' . Str::beforeLast(Str::afterLast($sourcePath, '/'), '.') . '-' . $width . '-' . $height . '.jpg';

    $sourceWidth = $imageData[0];
    $sourceHeight = $imageData[1];
    $mimeType = $imageData['mime'];
    $r = $sourceWidth / $sourceHeight; //ratio

    switch ($mimeType) {
      case 'image/jpeg':
        $sourceImage = imagecreatefromjpeg($sourcePath);
        break;

      case 'image/gif':
        $sourceImage = imagecreatefromgif($sourcePath);
        break;

      case 'image/png':
        $sourceImage = imagecreatefrompng($sourcePath);
        break;
    }

    if (empty($sourceImage)) {
      return null;
    }

    if ($crop) {
      if ($sourceWidth > $sourceHeight) {
        $sourceWidth = ceil($sourceWidth - ($sourceWidth * abs($r - $width / $height)));
      } else {
        $sourceHeight = ceil($sourceHeight - ($sourceHeight * abs($r - $width / $height)));
      }
      $newWidth = $width;
      $newHeight = $height;
    } else {
      if ($width / $height > $r) {
        $newWidth = $height * $r;
        $newHeight = $height;
      } else {
        $newHeight = $width / $r;
        $newWidth = $width;
      }
    }

    $destinationImage = imagecreatetruecolor($newWidth, $newHeight);
    imagecopyresampled($destinationImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

    $success = imagejpeg($destinationImage, $targetPath);

    return $targetPath;

    return null;
  }
}
