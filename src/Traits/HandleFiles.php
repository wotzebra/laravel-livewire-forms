<?php

namespace Wotz\LivewireForms\Traits;

use Illuminate\Validation\ValidationException;

trait HandleFiles
{
    public $files = [];

    public function saveUploadedFiles($step = null)
    {
        $fileFields = $step
            ? $this->getForm()->stepFileFieldStack($step)
            : $this->getForm()->fileFieldStack();

        // Set it to null, otherwise it's an empty string
        foreach (array_keys($fileFields) as $key) {
            $this->fields[$key] = null;
        }

        foreach ($this->files ?? [] as $key => $file) {
            if (is_array($file)) {
                // Multi upload
                foreach ($file as $value) {
                    $uploadedFile = $this->uploadFile($fileFields[$key] ?? [], $value);
                    $this->fields[$key][] = $uploadedFile->id ?? null;
                }
            } else {
                // Single upload
                $uploadedFile = $this->uploadFile($fileFields[$key] ?? [], $file);
                $this->fields[$key] = $uploadedFile->id ?? null;
            }
        }
    }

    private function uploadFile($field, $file)
    {
        try {
            return $file->save($field->disk ?? 'public');
        } catch (ValidationException $e) {
            throw ValidationException::withMessages([
                'files.' . $field->getName() => $e->getMessage(),
            ]);
        }
    }
}
