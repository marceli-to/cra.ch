<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;
use Intervention\Image\Interfaces\ImageInterface;

class ImageCache
{
    /**
     * Cache directory
     */
    protected $cachePath;

    /**
     * Original images directory
     */
    protected $imagePath;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cachePath = storage_path('app/public/cache');
        $this->imagePath = storage_path('app/public/uploads');
        
        // Create cache directory if it doesn't exist
        if (!File::exists($this->cachePath)) {
            File::makeDirectory($this->cachePath, 0755, true);
        }
    }

    /**
     * Get cached image
     *
     * @param string $template
     * @param string $filename
     * @param int|null $maxSize
     * @param string|null $coords
     * @return string
     */
    public function getCachedImage($template, $filename, $maxSize = null, $coords = null)
    {
        // Generate cache key
        $cacheKey = $this->generateCacheKey($template, $filename, $maxSize, $coords);
        
        // Full path to the cached file
        $cachedImagePath = $this->cachePath . '/' . $cacheKey;
        
        // Check if cached file exists
        if (File::exists($cachedImagePath)) {
            return $cachedImagePath;
        }
        
        // Original image path
        $originalImagePath = $this->imagePath . '/' . $filename;
        
        // Check if original file exists
        if (!File::exists($originalImagePath)) {
            return null;
        }
        
        // Process image with template
        $image = $this->applyTemplate($template, $originalImagePath, $maxSize, $coords);
        
        // Save processed image to cache
        $image->save($cachedImagePath);
        
        return $cachedImagePath;
    }

    /**
     * Apply template to image
     *
     * @param string $template
     * @param string $path
     * @param int|null $maxSize
     * @param string|null $coords
     * @return ImageInterface
     */
    protected function applyTemplate($template, $path, $maxSize = null, $coords = null)
    {
        // Load image
        $image = Image::read($path);
        
        // Get template class
        $templateClass = $this->getTemplateClass($template);
        
        if (!$templateClass) {
            return $image;
        }
        
        // Create template instance with parameters
        $templateInstance = new $templateClass($maxSize, $coords);
        
        // Apply template
        return $templateInstance->apply($image);
    }

    /**
     * Get template class
     *
     * @param string $template
     * @return string|null
     */
    protected function getTemplateClass($template)
    {
        $className = 'App\\Filters\\Image\\Template\\' . ucfirst($template);
        
        if (class_exists($className)) {
            return $className;
        }
        
        return null;
    }

    /**
     * Generate cache key
     *
     * @param string $template
     * @param string $filename
     * @param int|null $maxSize
     * @param string|null $coords
     * @return string
     */
    protected function generateCacheKey($template, $filename, $maxSize = null, $coords = null)
    {
        // Create a unique hash based on template, filename and params
        $hash = md5($template . $filename . $maxSize . $coords);
        
        // Get file extension
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        // Return cache key
        return $hash . '.' . $extension;
    }

    /**
     * Clear cache
     *
     * @return void
     */
    public function clearCache()
    {
        if (File::exists($this->cachePath)) {
            File::cleanDirectory($this->cachePath);
        }
    }
}
