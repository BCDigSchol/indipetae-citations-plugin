<?php

/**
 * Class IndipetaeCitationsPlugin
 *
 * Implements custom citation used in the Indipetae database.
 *
 */
class IndipetaeCitationsPlugin extends Omeka_Plugin_AbstractPlugin
{
    /**
     * @var array Plugin filters.
     */
    protected $_filters = array('item_citation');

    /**
     * Replace the standard citation
     *
     * @param string $citation The citation text to be filtered (unused).
     * @param array $args Arguments provided to the filter.
     * @return string The filtered citation text.
     */
    public function filterItemCitation($citation, $args)
    {

        // Display the transcribers initials, formatted appropriately.
        $transcribers = $this->getAllFields($args['item'], 'Contributor');
        if (count($transcribers) === 1) {
            $transcribed_by = $transcribers[0];
        } elseif (count($transcribers) === 2) {
            $transcribed_by = "{$transcribers[0]} and ${transcribers[1]}";
        } else {
            $last_transcriber = array_pop($transcribers);
            $transcribed_by = implode(', ', $transcribers) . ", and $last_transcriber";
        }


        // Regular metadata.
        $title = $this->getField($args['item'], 'Title');
        $archive = $this->getField($args['item'], 'Identifier');
        $folder = $this->getField($args['item'], 'Has Format');
        $number = $this->getField($args['item'], 'Has Version');
        $id = $args['item']->id;

        // Format date
        $date = date('F j, Y');

        return "“$title,” $archive, $folder, $number, <cite>Digital Indipetae Database</cite>, accessed $date, https://indipetae.bc.edu/items/show/$id. Transcribed by $transcribed_by.";
    }

    /**
     * Get a single metadata field
     *
     * @param $item
     * @param string $field
     * @return string|null
     */
    protected function getField($item, string $field): ?string
    {
        return metadata($item, ['Dublin Core', $field], ['no_filter' => true, 'all' => true])[0] ?? '';
    }

    /**
     * Get all fields for a metadata value
     *
     * @param $item
     * @param string $field
     * @return array
     */
    protected function getAllFields($item, string $field): array
    {
        return metadata($item, ['Dublin Core', $field], ['no_filter' => true, 'all' => true]);
    }
}
