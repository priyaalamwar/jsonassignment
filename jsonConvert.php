<?php
/** @Priya
* Function to convert flattened JSON into a hierarchical JSON
* 
* @param array  $flatJson     array of records to apply the hierarchy
* @param string $flatJsonId   property to read the current record_id, e.g. 'id'
* @param string $flatParentId property to read the related parent_id, e.g. 'parent_id '
* @param string $child        name of the property to place children, e.g. 'children'
* @param string $parentId     optional filter to filter by parent
*
* @return array
 */
class Convert
{
    public static function convertJson($flatJson, $flatJsonId = 'id', $flatParentId = 'parent_id', $child = 'children', $parentId = null)
    {
        $hierarchicalJson = [];
        foreach ($flatJson as $children) {
            if (isset($children[$flatParentId]) && $children[$flatParentId] == $parentId) {
                $children[$child]   = self::convertJson($flatJson, $flatJsonId, $flatParentId, $child, $children[$flatJsonId]); 
                if(empty($children[$child])){
                    unset($children[$child]);
                }
                $hierarchicalJson[] = $children;
            }
        }
        if (!$parentId) {
            $categoryId         = array_column($flatJson, $flatJsonId);
            $categoryParentId   = array_column($flatJson, $flatParentId);
            $missingCategoryIds = array_filter(array_diff($categoryParentId, $categoryId));
            foreach ($flatJson as $record) {
                if (in_array($record[$flatParentId], $missingCategoryIds)) {
                    $hierarchicalJson[] = $record;
                }
            }
        }
        return $hierarchicalJson;
    }
}
?>
