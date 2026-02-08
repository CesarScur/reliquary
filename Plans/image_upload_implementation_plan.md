# Image Upload Implementation Plan for Reliquary Project

## Overview

This implementation plan outlines the steps needed to add image upload functionality to the Reliquary project. The solution will support multiple images per entity (Relics and Saints), track original filenames, use a distributed folder strategy for storage, and be compatible with future cloud storage solutions.

## Goals

- Allow users to upload images for Relics and Saints
- Support multiple images per entity
- Track original filenames for better user experience
- Implement a scalable storage solution
- Ensure compatibility with cloud storage services (S3/CloudFront)
- Maintain a clean separation of concerns

## Implementation Steps

### 1. Create Image Entity
- [x] Create a dedicated AbstractImage and specific RelicImage/SaintImage entities.

### 2. Update Relic and Saint Entities
- [x] Add relationships to the Image entities.

### 3. Create ImageService
- [x] Implement a service to handle image uploads, storage, and retrieval.

### 4. Create Twig Extension/Helper
- [x] Create a Twig helper to generate image URLs.

### 5. Update Forms
- [x] Add image upload fields to Relic and Saint forms.

### 6. Update Controllers
- [x] Update controllers to handle image uploads during creation and editing.

### 7. Update Templates
- [x] Update templates to display images and provide upload/delete functionality.

### 8. Thumbnails Generation
- [x] Implement a command to generate thumbnails for uploaded images.

### 9. Documentation and Cleanup
- [x] Update documentation with image management details.
- [x] Ensure all temporary upload files are cleaned up.
- [x] Verify file permissions in production environment.

## Testing Checklist

- [x] Upload single image for a relic
- [x] Upload multiple images for a relic
- [x] Delete an image from a relic
- [x] Upload image for a saint
- [x] Verify thumbnail generation
- [x] Check image display in different views (index, show)
- [x] Validate file size and type restrictions
- [x] Confirm responsive behavior of image galleries
            $image = $imageService->createFromUploadedFile($imageFile, $relic, 'relic');
            $relic->addImage($image);
        }
        
        $relic->setCreator($this->getUser());
        $entityManager->persist($relic);
        $entityManager->flush();
        // ...
    }
    // ...
}
```

### 7. Implement Distributed Folder Strategy

In the ImageService, implement a distributed folder strategy:

```php
private function getUploadPath(UploadedFile $file): string
{
    $originalFilename = $file->getClientOriginalName();
    $hash = substr(md5($originalFilename . time()), 0, 2);
    $subDir = $hash[0] . '/' . $hash[1];
    $fullDir = $this->uploadDir . '/' . $subDir;
    
    if (!is_dir($fullDir)) {
        mkdir($fullDir, 0777, true);
    }
    
    return $subDir;
}
```

### 8. Update Templates

Modify templates to display uploaded images:

```twig
{# In templates/relic/show.html.twig #}
{% if relic.images|length > 0 %}
    <div class="mb-3">
        <h3>Relic Images</h3>
        <div class="row">
            {% for image in relic.images %}
                <div class="col-md-4 mb-3">
                    <img src="{{ asset('uploads/images/' ~ image.filename) }}" 
                         alt="Image of {{ relic.saint.name }}'s relic" 
                         class="img-fluid rounded">
                </div>
            {% endfor %}
        </div>
    </div>
{% endif %}
```

### 9. Create Migration

Generate and run a database migration:

```bash
php bin/console doctrine:schema:update --force
```

### 10. Future Cloud Storage Compatibility

Ensure the ImageService is designed to be easily adapted for cloud storage:

```php
// Future implementation for S3 storage
public function storeInS3(UploadedFile $file, string $path): string
{
    // Use AWS SDK to upload file to S3
    // Return the S3 URL or path
}
```

## Testing Plan

1. [x] Test uploading images for Relics
2. [x] Test uploading images for Saints
3. [x] Test uploading multiple images
4. [x] Test image display in templates
5. [x] Test image deletion
6. [x] Test with various image formats and sizes

## Rollout Plan

1. [x] Implement the Image entity and ImageService
2. [x] Update the Relic entity and forms first
3. [x] Test thoroughly with Relics
4. [x] Once stable, implement for Saints
5. [x] Deploy to production

## Conclusion

This implementation provides a flexible and scalable solution for handling image uploads in the Reliquary project. By abstracting image handling into a dedicated entity and service, we ensure clean separation of concerns and make future enhancements (like cloud storage integration) easier to implement.