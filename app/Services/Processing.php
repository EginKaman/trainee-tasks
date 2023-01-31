<?php

namespace App\Services;

use App\Exceptions\UnknownProcessingException;
use App\Services\Processing\CsvProcessing;
use App\Services\Processing\JsonProcessing;
use App\Services\Processing\XmlProcessing;
use Illuminate\Http\UploadedFile;

class Processing
{
    private string $mimeType;
    private CsvProcessing|XmlProcessing|JsonProcessing $processing;

    public function setMimeType(string $mimeType)
    {
        $this->mimeType = $mimeType;
        $this->selectProcessing();
        return $this;
    }

    public function validate(UploadedFile $file, $schema)
    {
        $this->processing->validate($file, $schema);
    }

    /**
     * @throws \Exception
     */
    protected function selectProcessing()
    {
        switch ($this->mimeType) {
            case 'text/xml':
            case 'application/xml':
                $this->processing = new XmlProcessing();
                break;
            case 'text/json':
            case 'application/json':
                $this->processing = new JsonProcessing();
                break;
            case 'text/csv':
                $this->processing = new CsvProcessing();
                break;
            default:
                throw new UnknownProcessingException();
        }
    }
}
