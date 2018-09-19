<?php
/**
 * Created by PhpStorm.
 * User: Hamid Raza
 * Date: 9/19/2018
 * Time: 11:16 AM
 */

namespace App\Library;

use App\Library\FEG\System\FEGSystemHelper;
use \App\Models\Sximo;
use App\Models\Core\Groups;
use App\Models\CustemRelation;
use Illuminate\Database\Eloquent\Collection;

class FEGDBRelationHelpers
{
    /**
     * @param $relatedId
     * @param $relatedType
     * @param $relatedTypeTo
     * @param int $isExcluded
     * @param bool $aliasOverride
     * @return Collection
     */
    public static function getCustomRelationRecords($relatedId, $relatedType, $relatedTypeTo, $isExcluded = 0, $aliasOverride = true)
    {
        $relatedId = is_array($relatedId) ? $relatedId : [$relatedId];

        $customRelation = CustemRelation::select('id', 'related_id', 'related_to', 'related_type', 'related_type_to', 'is_excluded', 'created_at', 'updated_at')
            ->whereIn('related_id', $relatedId)->orWhereIn('related_to', $relatedId);

        $result = $customRelation->whereIn('related_type', [$relatedType, $relatedTypeTo])
            ->whereIn('related_type_to', [$relatedType, $relatedTypeTo])
            ->where('is_excluded', $isExcluded)->get();
        return $aliasOverride ? self::updateRelationBasedAlias($result) : $result;
    }

    /**
     * @param $relatedId
     * @param $relatedTo
     * @param $relatedType
     * @param $relatedTypeTo
     * @param int $isExcluded
     * @return bool
     */
    public static function insertCustomRelation($relatedId, $relatedTo, $relatedType, $relatedTypeTo, $isExcluded = 0)
    {

        if (!empty($relatedId) && !empty($relatedTo) && !empty($relatedType) &&
            !empty($relatedTypeTo) && ($isExcluded == 1 || $isExcluded == 0)
        ) {

            $customRelation = new CustemRelation();
            $relationData = [
                'related_id' => $relatedId,
                'related_to' => $relatedTo,
                'related_type' => $relatedType,
                'related_type_to' => $relatedTypeTo,
                'is_excluded' => $isExcluded,
            ];
            $result = $customRelation->create($relationData) ? true : false;
            return $result;
        }
        return false;
    }

    /**
     * @param $relatedType
     * @param $relatedTypeTo
     * @param int $isExcluded
     * @param int $relatedId
     * @param int $relatedTo
     * @return bool
     */
    public static function destroyCustomRelation($relatedType, $relatedTypeTo, $isExcluded = 0, $relatedId = 0, $relatedTo = 0)
    {
        $customRelation = CustemRelation::where('related_type', $relatedType)
            ->where('related_type_to', $relatedTypeTo)
            ->where('is_excluded', $isExcluded);

        if ($relatedId > 0) {
            $customRelation->where('related_id', $relatedId)->orWhere('related_to', $relatedId);
        }
        if ($relatedTo > 0) {
            $customRelation->where('related_id', $relatedTo)->orWhere('related_to', $relatedTo);
        }
        $result = $customRelation->delete() ? true : false;
        return $result;
    }

    /**
     * @param $relatedType
     * @param $relatedTypeTo
     * @param int $isExcluded
     * @return bool
     */
    public static function destroyCustomRelationAll($relatedType, $relatedTypeTo, $isExcluded = 0)
    {
        $customRelation = CustemRelation::where('related_type', $relatedType)
            ->where('related_type_to', $relatedTypeTo)
            ->where('is_excluded', $isExcluded);

        $result = $customRelation->delete() ? true : false;
        return $result;
    }

    /**
     * @param $collection
     * @return Collection
     */
    public static function updateRelationBasedAlias($collection)
    {

        foreach ($collection as $item) {
            $relatedType = explode("\\", $item->related_type);
            $relatedTypeTo = explode("\\", $item->related_type_to);
            $relatedTypeToAttr = $relatedTypeTo[count($relatedTypeTo) - 1] . "_id";
            $relatedTypeAttr = $relatedType[count($relatedType) - 1] . "_id";
            $item->$relatedTypeToAttr = $item->related_to;
            $item->$relatedTypeAttr = $item->related_id;
            unset($item->related_to);
            unset($item->related_id);
        }
        return $collection;
    }
}