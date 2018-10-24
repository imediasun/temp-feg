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
use App\Models\Locationgroups;
use App\Models\Ordertyperestrictions;
use App\Models\product;
use \App\Models\Sximo;
use App\Models\Core\Groups;
use App\Models\CustemRelation;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;

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
                $query->where(\DB::raw('LOWER(related_type)'),'=',strtolower($relatedTypeTo))
                    ->orWhere(\DB::raw('LOWER(related_type)'),'=',strtolower($relatedType));
            })
            ->where(function ($query) use ($relatedTypeTo, $relatedType){
                $query->where(\DB::raw('LOWER(related_type_to)'),'=',strtolower($relatedType))
                    ->orWhere(\DB::raw('LOWER(related_type_to)'),'=',strtolower($relatedTypeTo));
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
     * @param $isGetExcludedProductTypes
     * @param $isGetExcludedProducts
     * @return mixed
     */
    public static function getExcludedProductTypeAndExcludedProductIds($locationId = null, $isGetExcludedProductTypes = false,  $isGetExcludedProducts = false){

        $locationId = !is_null($locationId) ? $locationId : \Session::get('selected_location');

        $locationGroupIds   = self::getCustomRelationRecords($locationId, Locationgroups::class, location::class, 0, true)->pluck('locationgroups_id')->toArray();
//        $locationIds        = self::getCustomRelationRecords($locationGroupIds, Locationgroups::class, location::class, 0, true)->pluck('location_id')->toArray();

        $locationIds = []; //new written line
//        if(empty($locationGroupIds))
            array_push($locationIds, $locationId);

        /*
         * Getting the Ids of Product Types that are related to
         * the current Location and related Location Groups
         * */
        $idsOfExcludedProductTypesFromLocationGroup = self::getCustomRelationRecords($locationGroupIds, Ordertyperestrictions::class, Locationgroups::class, 1, true)->pluck('ordertyperestrictions_id')->toArray();
        $idsOfExcludedProductTypesFromLocation      = self::getCustomRelationRecords($locationIds, Ordertyperestrictions::class, location::class, 1, true)->pluck('ordertyperestrictions_id')->toArray();
        $excludedProductTypeIds = array_merge($idsOfExcludedProductTypesFromLocationGroup, $idsOfExcludedProductTypesFromLocation);


        /*
         * Getting the Ids of Products that are related to the
         * current Location and related Location Groups
         * */
        $idsOfExcludedProductsFromLocationGroup     = self::getCustomRelationRecords($locationGroupIds, product::class, Locationgroups::class, 1, true)->pluck('product_id')->toArray();
        $idsOfExcludedProductsFromLocation          = self::getCustomRelationRecords($locationIds, product::class, location::class, 1, true)->pluck('product_id')->toArray();
        $excludedProductIds = array_merge($idsOfExcludedProductsFromLocationGroup, $idsOfExcludedProductsFromLocation);


        /*$variationIds = product::whereIn('id', $excludedProductIds)->lists('variation_id')->toArray();
        $excludedProductVariationIds = product::whereIn('variation_id', $variationIds)->lists('id')->toArray();*/

        /*
         * getting the ids of products that are related
         * to the excluded product types.
         * */
//        $idsOfExcludedProductsFromResultantProductTypes = product::whereIn('prod_type_id', $excludedProductTypeIds)->lists('id')->toArray();

        /*$finalArrayOfIdsOfExcludedProducts = array_merge(
            $excludedProductIds,
//            $idsOfExcludedProductsFromResultantProductTypes,
            $excludedProductVariationIds
        );*/

        $finalArrayOfIdsOfExcludedProducts = $excludedProductIds;

        if ($isGetExcludedProductTypes && !$isGetExcludedProducts) {
            $result = [
                'excluded_product_type_ids' =>  array_values(array_unique($excludedProductTypeIds)),
            ];
        } elseif (!$isGetExcludedProductTypes && $isGetExcludedProducts) {
            $result = [
                'excluded_product_ids' =>  array_values(array_unique($excludedProductIds)),
            ];
        } else {
            $result = [
                'excluded_product_type_ids' => array_values(array_unique($excludedProductTypeIds)),
                'excluded_product_ids'      => array_values(array_unique($finalArrayOfIdsOfExcludedProducts))
            ];
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function getAllExcludedDataDebugger(){
        $selectedLocation = \Session::get('selected_location');

        $locationGroups = FEGDBRelationHelpers::getCustomRelationRecords($selectedLocation,location::class,Locationgroups::class,0)->pluck('locationgroups_id')->toArray();
        $locationGroup = Locationgroups::select('name')->whereIn('id',$locationGroups)->orderBy('name','asc')->get()->pluck('name');

        $productData = FEGDBRelationHelpers::getExcludedProductTypeAndExcludedProductIds(null,true,true);

        $products = product::select('vendor_description')->whereIn('id',$productData['excluded_product_ids'])->get()->pluck('vendor_description')->toArray();
        $productTypes = Ordertyperestrictions::select('order_type as product_type')->whereIn('id',$productData['excluded_product_type_ids'])->get()->pluck('product_type')->toArray();
   $data =[
       'locationGroups' => $locationGroup,
       'productTypes' => $productTypes,
       'products' => $products
   ];
        return $data;
    }

    /**
     * @return array
     */

    public static function getExcludedProductTypesOnly(){
        $productTypeExcludedbyLocation = self::getCustomRelationRecords(Session::get('selected_location'),location::class,Ordertyperestrictions::class,1)->pluck('ordertyperestrictions_id')->toArray();
        $locationGroups = self::getCustomRelationRecords(Session::get('selected_location'),location::class,Locationgroups::class,0)->pluck('locationgroups_id')->toArray();
        if(count($locationGroups) > 0) {
            $groupExcludedTypes = self::getCustomRelationRecords($locationGroups, Locationgroups::class, Ordertyperestrictions::class, 1)->pluck('ordertyperestrictions_id')->toArray();
            if(count($groupExcludedTypes) > 0){
                $productTypeExcludedbyLocation = array_merge($productTypeExcludedbyLocation,$groupExcludedTypes);
            }
        }
        return $productTypeExcludedbyLocation;
    }

}