<?php declare(strict_types=1);

namespace App\Services\Processing;

use App\Exceptions\UnknownProcessingException;

class XmlProcessing implements ProcessingInterface
{

    private \DOMDocument $DOMDocument;
    private string $schema;

    public function __construct(\DOMDocument $DOMDocument)
    {
        $this->DOMDocument = $DOMDocument;
        if (!file_exists(resource_path('schemas/schema.xsd'))) {
            throw new UnknownProcessingException();
        }
        $this->schema = file_get_contents(resource_path('schemas/schema.xsd'));
    }

    /**
     * @param $file
     * @param $schema
     * @return \LibXMLError[]|bool
     */
    public function validate($file, $schema): array|bool
    {
        $this->DOMDocument->loadXML($file->get());
        if ($this->DOMDocument->schemaValidate($this->schema)) {
            return true;
        }
        return libxml_get_errors();
    }

    public function read($file)
    {

    }
}
