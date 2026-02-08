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
    
    public function createFromUploadedFile(UploadedFile $file, object $owner, string $ownerType): Image
    {
        // Handle file upload, generate filename, etc.
        // Return a new Image entity
    }
    
    public function deleteImage(Image $image): void
    {
        // Delete the file and remove the entity
    }
    
    // Additional methods for image manipulation, retrieval, etc.
}
```

### 4. Configure Services

Add configuration for the image service:

```yaml
# config/services.yaml
parameters:
    image_upload_directory: '%kernel.project_dir%/public/uploads/images'

services:
    App\Service\ImageService:
        arguments:
            $uploadDir: '%image_upload_directory%'
```

### 5. Update Form Types

Modify RelicType and SaintType to include file upload fields:

```php
// In RelicType.php
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

// In buildForm method
->add('imageFile', FileType::class, [
    'label' => 'Relic Image',
    'mapped' => false,
    'required' => false,
    'constraints' => [
        new File([
            'maxSize' => '2M',
            'mimeTypes' => [
                'image/jpeg',
                'image/png',
                'image/webp',
            ],
            'mimeTypesMessage' => 'Please upload a valid image (JPEG, PNG, WEBP)',
        ])
    ],
    'attr' => [
        'class' => 'form-control',
    ],
    'help' => 'Upload an image of the relic (max size: 2MB)',
    'help_attr' => ['class' => 'form-text text-muted'],
    'label_attr' => ['class' => 'form-label'],
])
```

### 6. Update Controllers

Modify RelicController and SaintController to handle file uploads:

```php
// In RelicController.php
use App\Service\ImageService;

// In new and edit methods
public function new(Request $request, EntityManagerInterface $entityManager, ImageService $imageService): Response
{
    // ...
    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('imageFile')->getData();
        
        if ($imageFile) {
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

1. Test uploading images for Relics
2. Test uploading images for Saints
3. Test uploading multiple images
4. Test image display in templates
5. Test image deletion
6. Test with various image formats and sizes

## Rollout Plan

1. Implement the Image entity and ImageService
2. Update the Relic entity and forms first
3. Test thoroughly with Relics
4. Once stable, implement for Saints
5. Deploy to production

## Conclusion

This implementation provides a flexible and scalable solution for handling image uploads in the Reliquary project. By abstracting image handling into a dedicated entity and service, we ensure clean separation of concerns and make future enhancements (like cloud storage integration) easier to implement.