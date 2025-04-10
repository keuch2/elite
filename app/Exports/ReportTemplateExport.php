<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportTemplateExport implements FromArray, WithHeadings
{
    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    public function headings(): array
    {
        $allFields = \App\Helpers\ReportFields::getAvailableFields();
        $sectionCounts = [];
        $sectionOrder = [];

        // Count the number of selected fields per section and maintain order
        foreach ($this->fields as $field) {
            foreach ($allFields as $section => $fields) {
                if (array_key_exists($field, $fields)) {
                    if (!isset($sectionCounts[$section])) {
                        $sectionCounts[$section] = 0;
                        $sectionOrder[] = $section;
                    }
                    $sectionCounts[$section]++;
                    break;
                }
            }
        }

        // Build the section row (Row 1) with proper alignment
        $sectionRow = [];
        $currentIndex = 0;
        foreach ($sectionOrder as $section) {
            // Add the section name at the starting index of its fields
            for ($i = 0; $i < $currentIndex; $i++) {
                if (!isset($sectionRow[$i])) {
                    $sectionRow[$i] = '';
                }
            }
            $sectionRow[$currentIndex] = $section;
            // Add empty cells for the remaining fields in this section
            for ($i = 1; $i < $sectionCounts[$section]; $i++) {
                $sectionRow[$currentIndex + $i] = '';
            }
            $currentIndex += $sectionCounts[$section];
        }

        // Ensure the section row matches the length of the fields row
        while (count($sectionRow) < count($this->fields)) {
            $sectionRow[] = '';
        }

        // Row 2: Field headers
        return [$sectionRow, $this->fields];
    }

    public function array(): array
    {
        // Return an empty array since this is a template (no data yet)
        return [];
    }
}