<?php

namespace App\Support;

use Illuminate\Validation\Validator;

class ContentBlocks
{
    public static function rules(): array
    {
        return [
            'content_blocks' => 'present|array|max:40',
            'content_blocks.*' => 'array:type,text,items,rows',
            'content_blocks.*.type' => 'required|in:heading,paragraph,bullets,numbered,table',
            'content_blocks.*.text' => 'nullable|string|max:8000',
            'content_blocks.*.items' => 'sometimes|array|max:30',
            'content_blocks.*.items.*' => 'required|string|max:1000',
            'content_blocks.*.rows' => 'sometimes|array|max:25',
            'content_blocks.*.rows.*' => 'required|array|min:1|max:6',
            'content_blocks.*.rows.*.*' => 'required|string|max:500',
        ];
    }

    public static function validate(Validator $validator): void
    {
        foreach (($validator->getData()['content_blocks'] ?? []) as $index => $block) {
            if (! is_array($block)) {
                continue;
            }
            $field = match ($block['type'] ?? '') {
                'heading', 'paragraph' => 'text', 'bullets', 'numbered' => 'items', 'table' => 'rows', default => null,
            };
            if ($field && empty($block[$field])) {
                $validator->errors()->add("content_blocks.$index.$field", 'Completa el contenido del bloque o elimínalo.');
            }
            if (($block['type'] ?? '') === 'table' && is_array($block['rows'] ?? null)) {
                $widths = array_map(fn ($row) => is_array($row) ? count($row) : 0, $block['rows']);
                if (count(array_unique($widths)) > 1) {
                    $validator->errors()->add("content_blocks.$index.rows", 'Todas las filas deben tener el mismo número de columnas.');
                }
            }
        }
    }
}
