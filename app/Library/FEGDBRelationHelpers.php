<?php
/**
 * Created by PhpStorm.
 * User: Hamid Raza
 * Date: 9/19/2018
 * Time: 11:16 AM
 */

namespace App\Library;

use App\Library\FEG\System\FEGSystemHelper;
use App\Models\location;
use App\Models\locationgroups;
use App\Models\Ordertyperestrictions;
use App\Models\product;
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

        $customRelation = CustemRelation::select('id', 'related_id', 'related_to', 'related_type', 'related_type_to', 'is_excluded', 'created_at', 'updated_at');

        $result = $customRelation
            ->where(function ($query) use ($relatedId){
                $query->whereIn('related_id', $relatedId)
                    ->orWhereIn('related_to', $relatedId);
            })
            ->where(function ($query) use ($relatedTypeTo, $relatedType){
                $query->where('related_type', $relatedTypeTo)
                    ->orWhere('related_type', $relatedType);
            })
            ->where(function ($query) use ($relatedTypeTo, $relatedType){
                $query->where('related_type_to', $relatedType)
                    ->orWhere('related_type_to', $relatedTypeTo);
            })
            ->where('is_excluded', $isExcluded)
            ->get();

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
                'related_id'      => $relatedId,
                'related_to'      => $relatedTo,
                'related_type'    => $relatedType,
                'related_type_to' => $relatedTypeTo,
                'is_excluded'     => $isExcluded,
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
            $relatedTypeToAttr = strtolower($relatedTypeTo[count($relatedTypeTo) - 1]) . "_id";
            $relatedTypeAttr = strtolower($relatedType[count($relatedType) - 1]) . "_id";
            $item->$relatedTypeToAttr = $item->related_to;
            $item->$relatedTypeAttr = $item->related_id;
            unset($item->related_to);
            unset($item->related_id);
        }
        return $collection;
    }

    /**
     * @param $locationId
     * @return mixed
     */
    public static function getExcludedProductTypeAndExcludedProductIds($locationId){

        $locationGroupIds   = self::getCustomRelationRecords($locationId, locationgroups::class, location::class, 0, true)->pluck('locationgroups_id')->toArray();
        $locationIds        = self::getCustomRelationRecords($locationGroupIds, locationgroups::class, location::class, 0, true)->pluck('location_id')->toArray();

        /*
         * Getting the Ids of Product Types that are related to
         * the current Location and related Location Groups
         * */
        $idsOfExcludedProductTypesFromLocationGroup = self::getCustomRelationRecords($locationGroupIds, Ordertyperestrictions::class, locationgroups::class, 1, true)->pluck('ordertyperestrictions_id')->toArray();
        $idsOfExcludedProductTypesFromLocation      = self::getCustomRelationRecords($locationIds, Ordertyperestrictions::class, location::class, 1, true)->pluck('ordertyperestrictions_id')->toArray();
        $excludedProductTypeIds = array_merge($idsOfExcludedProductTypesFromLocationGroup, $idsOfExcludedProductTypesFromLocation);


        /*
         * Getting the Ids of Products that are related to the
         * current Location and related Location Groups
         * */
        $idsOfExcludedProductsFromLocationGroup     = self::getCustomRelationRecords($locationGroupIds, product::class, locationgroups::class, 1, true)->pluck('product_id')->toArray();
        $idsOfExcludedProductsFromLocation          = self::getCustomRelationRecords($locationIds, product::class, location::class, 1, true)->pluck('product_id')->toArray();
        $excludedProductIds = array_merge($idsOfExcludedProductsFromLocationGroup, $idsOfExcludedProductsFromLocation);


        /*
         * getting the ids of products that are related
         * to the excluded product types.
         * */
        $idsOfExcludedProductsFromResultantProductTypes = product::whereIn('prod_type_id', $excludedProductTypeIds)->lists('id')->toArray();

        $finalArrayOfIdsOfExcludedProducts = array_merge($excludedProductIds, $idsOfExcludedProductsFromResultantProductTypes);

        return [
            'excluded_product_type_ids' =>  array_unique($excludedProductTypeIds),
            'excluded_product_ids'      =>  array_unique($finalArrayOfIdsOfExcludedProducts)
        ];
    }

}