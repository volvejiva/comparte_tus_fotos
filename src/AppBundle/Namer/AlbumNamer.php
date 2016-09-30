<?php
namespace AppBundle\Namer;

use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Mapping\PropertyMapping;

/**
 * Namer class.
 */
class AlbumNamer implements NamerInterface
{
    /**
     * Creates a name for the file being uploaded.
     *
     * @param object $obj The object the upload is attached to.
     * @param string $field The name of the uploadable field to generate a name for.
     * @return string The file name.
     */
    function name($object, PropertyMapping $mapping)
    {
        return uniqid('', true) . ".zip";
    }
}