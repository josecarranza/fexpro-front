<?php
/* Template Name: Custom Filter
 * @package WordPress
 
*/
get_header();
?>

<style>
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    a.single_add_to_cart_button {
        color: #fff;
        padding: 12px;
        text-decoration: none;
    }

    @keyframes spinner {
        to {
            transform: rotate(360deg);
        }
    }

    .spinner:before {
        content: '';
        box-sizing: border-box;
        position: absolute;
        top: 50%;
        left: 50%;
        width: 20px;
        height: 20px;
        margin-top: -10px;
        margin-left: -10px;
        border-radius: 50%;
        border: 2px solid #ccc;
        border-top-color: #333;
        animation: spinner .6s linear infinite;
    }

    a.ajax_add_to_cart.add_to_cart_button.single_add_to_cart_button.outOfStock {
        background: #e31d1a;
    }

    a.outOfStock {
        text-decoration: none;
        color: #ffffff;
    }

    .dataTables_wrapper .dataTables_processing {
        font-size: 5.2em !important;
        position: absolute;
        top: 0% !important;
        width: 100% !important;
        margin-left: -100px !important;
        margin-top: -26px !important;
        text-align: center !important;
        padding: 1em 0 !important;
        left: 0px !important;
        background: #0000001a !important;
        height: 100% !important;
        margin: 0 auto !important;
    }

    div.dataTables_wrapper div.dataTables_processing .overlay.custom-loader-background {
        top: 50% !important;
        position: relative !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        color: #333 !important;
        border: 1px solid #e31d1a !important;
        background-color: white;
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(100%, #dcdcdc)) !important;
        background: -webkit-linear-gradient(top, white 0%, #dcdcdc 100%) !important;
        background: -moz-linear-gradient(top, white 0%, #dcdcdc 100%) !important;
        background: -ms-linear-gradient(top, white 0%, #dcdcdc 100%);
        background: -o-linear-gradient(top, white 0%, #dcdcdc 100%) !important;
        background: linear-gradient(to bottom, #e31d1a 0%, #ea8281 100%) !important;
    }

    table#demo_table {
        margin-top: 25px !important;
    }

    div#demo_table_wrapper {
        padding-top: 20px;
        padding-bottom: 100px;
    }

    .addScrollYCustom,
    .customColorLists,
    .customTeamFilter {
        max-height: 400px !important;
        overflow: hidden;
        overflow-y: scroll;
    }

    .btnSubmitUrl,
    .btnCheckbox {
        margin-left: 15px;
        display: inline-block;
    }

    a.added_to_cart.wc-forward {
        padding: 13px 9px;
        background: #e36159;
        margin-top: 5px;
        color: #fff;
        text-decoration: none;
        display: inline-block;
        width: 103px;
    }

    .wcpt-controls-on-edges .wcpt-plus {
        background: #e36159;
        color: #fff;
    }

    .wcpt-controls-on-edges .wcpt-qty-controller {
        color: #fff;
        background: #e36159 !important;
    }

    a.anchorProdcutTitle {
        text-decoration: none;
    }

    .cart-sizes-attribute {
        padding-top: 5px;
    }

    table#demo_table thead th {
        font-size: 15px;
        text-transform: uppercase;
        color: #fff;
        background: #000000;
        text-align: center;
    }

    table#demo_table thead .sorting_asc {
        background-image: none !important;
    }

    table#demo_table thead .sorting_asc:after,
    table.dataTable thead .sorting:after {
        display: none !important
    }

    tbody#myTable tr td {
        vertical-align: middle;
        color: #000;
        font-size: 15px;
    }

    td.textCenter {
        text-align: center;
    }

    a.wcpt-clear-all-filters.wcpt-big-device-only {
        font-size: 18px;
    }

    .wcpt-dropdown-menu {
        width: 280px !important;
    }

    a.paginate_button {
        border: 1px solid #dadada !important;
    }

    .inner-size {
        display: block;
        width: 100%;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        text-align: center;
    }

    .cart-sizes-attribute .size-guide .inner-size {
        border: solid 1px #000;
        margin-top: 0px;
        border-right: none;
        border-left: none;
    }

    .inner-size span:first-child {
        font-weight: bold;
        background: #008188;
        color: #fff;
    }

    .inner-size span {
        display: block;
        width: 100%;
        border-bottom: solid 1px #000;
        border-right: 1px solid #000;
        color: #000;
        padding: 6px 10px;
    }

    .imageFilter img {
        transition: transform .2s;
    }

    .imageFilter .transition img {
        transform: scale(1.3);
    }

    .pa_brand_parent .wcpt-clear-filter,
    .pa_cat_parent .wcpt-clear-filter,
    .pa_color_parent .wcpt-clear-filter,
    .pa_team_parent .wcpt-clear-filter,
    .pa_season_parent .wcpt-clear-filter {
        background: #ffffff;
        box-shadow: 1px 1px 2px 0px #e31d1a;
    }

    .pa_brand_parent .wcpt-clear-filter:hover,
    .pa_cat_parent .wcpt-clear-filter:hover,
    .pa_color_parent .wcpt-clear-filter:hover,
    .pa_team_parent .wcpt-clear-filter:hover,
    .pa_season_parent .wcpt-clear-filter:hover {
        background: #e31d1a;
        color: #fff;
        box-shadow: 2px 2px 2px 0px #000000;
    }

    div.dataTables_wrapper div.dataTables_filter {
        text-align: right;
        width: 90% !important;
    }

    div.dataTables_wrapper div.dataTables_filter label,
    div.dataTables_wrapper div.dataTables_filter input {
        width: 100% !important;
    }

    span#exportexcel1 {
        float: right;
        background: #000;
        color: #fff;
        cursor: pointer;
        text-align: center;
        font-weight: bold;
        margin-bottom: 15px;
        padding: 9px 15px;
        margin-top: 2px;
        display: inline-block;
        transition: all 0.2s ease;
    }

    span#exportexcel1:hover {
        background: #b41520;
    }

    span#stop-refresh,
    span#stop-refresh1 {
        display: none;
        color: #f00;
        font-size: 18px;
        margin-left: 5px;
        margin-bottom: 15px;
        width: 100%;
    }


    #scroll {
        width: 250px;
        height: 50px;
        border: 2px solid black;
        background-color: lightyellow;
        top: 100px;
        left: 50px;
        position: absolute;
    }

    table#demo_table tbody td a.ajax_add_to_cart {
        font-size: 14px;
    }

    div#demo_table_info {
        display: none;
    }
</style>

<link href="https://cdn.datatables.net/1.10.13/css/dataTables.bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css">
<script type="text/javascript" src="<?php site_url() ?>/wp-content/themes/porto-child/jquery.dataTables.filter.min.js"></script>







<?php

//$term_id="";
// if(isset($_GET['custom'])){
// 	$term_idcustom = '178,150,119,1551,1560,1486,1554,1601,1682,1524,1841,1497,1490,1556,1552,1403,1472,225,206,2158,1827,1832,1836,1838,1842,1531,1847,1451,1683,1562,1555,2680,2812,3245,4169,4204,4275,1442,3258,1542,2343,3916,1605,1515,4584,4687,4691';

// }

// else
// {
//     $term_id = '178,150,119,1551,1560,1486,1554,1601,1682,1524,1841,1497,1490,1556,1552,1403,1472,225,206,2158,1827,1832,1836,1838,1842,1531,1847,1451,1683,1562,1555,2680,2812,3245,4169,4204,4275,1442,3258,1542,2343,3916,1605,1515,4584,4687,4691';

//     $term_idcustom = '178,150,119,1551,1560,1486,1554,1601,1682,1524,1841,1497,1490,1556,1552,1403,1472,225,206,2158,1827,1832,1836,1838,1842,1531,1847,1451,1683,1562,1555,2680,2812,3245,4169,4204,4275,1442,3258,1542,2343,3916,1605,1515,4584,4687,4691';
// }

$SLUG_MAIN_CAT="fall-winter-22";

$only_cats=array(4595,4104,1829);

$query1="SET @id_root_cate = (SELECT tax.term_taxonomy_id FROM wp_terms t INNER JOIN wp_term_taxonomy tax ON t.term_id=tax.term_id WHERE t.slug='".$SLUG_MAIN_CAT."' AND tax.taxonomy='product_cat')";
$query2="CREATE TEMPORARY TABLE if NOT exists tmp_categories AS (
    SELECT a.term_taxonomy_id,b.name, b.slug,a.parent, a.count, 1 level FROM wp_term_taxonomy a INNER JOIN wp_terms b ON a.term_id=b.term_id
    WHERE a.taxonomy='product_cat' AND a.count>0
    AND a.parent =@id_root_cate AND term_taxonomy_id IN (".implode(",",$only_cats).")
)";
$query3="CREATE TEMPORARY TABLE if NOT exists tmp_categories2 AS (
SELECT a.term_taxonomy_id,b.name, b.slug,a.parent,a.count, 2 level FROM wp_term_taxonomy a INNER JOIN wp_terms b ON a.term_id=b.term_id
    WHERE a.taxonomy='product_cat' AND a.count>0
    AND a.parent IN (SELECT term_taxonomy_id FROM tmp_categories tmp WHERE tmp.level=1)
)";
$query4="CREATE TEMPORARY TABLE if NOT exists tmp_categories3 AS (
SELECT a.term_taxonomy_id,b.name, b.slug,a.parent,a.count,3 level FROM wp_term_taxonomy a INNER JOIN wp_terms b ON a.term_id=b.term_id
    WHERE a.taxonomy='product_cat' AND a.count>0
    AND a.parent IN (SELECT term_taxonomy_id FROM tmp_categories2 tmp WHERE tmp.level=2)
)";


$query5 = " SELECT * FROM (
SELECT * FROM tmp_categories 
UNION ALL 
SELECT * FROM tmp_categories2
UNION ALL 
SELECT * FROM tmp_categories3
) tmp ORDER BY tmp.level ASC,tmp.name ASC";

$query6="DROP TEMPORARY TABLE  tmp_categories, tmp_categories2,tmp_categories3";

$wpdb->query($query1);
$wpdb->query($query2);
$wpdb->query($query3);
$wpdb->query($query4);
$_result = $wpdb->get_results($query5);


$taxonomies=$_result;

$_ids_cats=array_column($taxonomies,"term_taxonomy_id");

$wpdb->query($query6);
if (!empty($taxonomies)) :
$getCategoryArr = array();


    $slugs_ids=array_column($taxonomies,"slug","term_taxonomy_id");
    $parent2=array();
    foreach ($taxonomies as $key => $cat) {
        if($cat->level==1 && !isset($getCategoryArr[$cat->slug])){
            $getCategoryArr[$cat->slug]=array();
        }
        if($cat->level==2 && !isset($getCategoryArr[$slugs_ids[$cat->parent]][$cat->slug]) ){
            $getCategoryArr[$slugs_ids[$cat->parent]][$cat->slug]=array();
            $parent2[$cat->slug] = $slugs_ids[$cat->parent];
        }
        if($cat->level==3){
            $getCategoryArr[$parent2[$slugs_ids[$cat->parent]]][$slugs_ids[$cat->parent]][$cat->slug]=$cat->name;
        }
    }
    $__tmp = $getCategoryArr;
    $getCategoryArr=array($SLUG_MAIN_CAT=>$__tmp);
    unset($__tmp);
endif;


$wp_sql = $wpdb->prepare("SELECT * FROM `wp_fw_22` ");
$product_ids_arr =  $wpdb->get_results($wp_sql);

if ($_GET['attr_pa_brand']) {
    $term_id =  $_GET['attr_pa_brand'];
}

//inicio validar marca
//if (!empty($term_id)) { 
    $pa_color_arr_list = array();
    $pa_team_arr_list = array();
    $product_id_color_arr = array();
    $pa_season_arr_list = array();
    $pa_collection_arr_list = array();

    $sql_colores="
    SELECT DISTINCT a.term_taxonomy_id,b.name, b.slug 
    FROM wp_term_taxonomy a 
    INNER JOIN wp_terms b ON a.term_id=b.term_id
    INNER JOIN wp_term_relationships c ON a.term_taxonomy_id=c.term_taxonomy_id
    WHERE a.taxonomy='pa_color' 
    AND c.object_id IN (
    SELECT p.ID FROM wp_posts p 
    INNER JOIN wp_term_relationships p2 ON p.ID=p2.object_id
    WHERE p2.term_taxonomy_id IN(".implode(",",$_ids_cats).")
    ) order by b.name ASC";
    $colores = $wpdb->get_results($sql_colores);

    $final_result1 = array();
    foreach ($colores as $color_key1 => $color_value1) :
        $words = strtolower(preg_replace('/[0-9]+/', '', $color_value1->name));
        $final_result1[$words][] = strtoupper($color_value1->name);
    endforeach;

    $sql_collection="
    SELECT DISTINCT a.term_taxonomy_id,b.name, b.slug 
    FROM wp_term_taxonomy a 
    INNER JOIN wp_terms b ON a.term_id=b.term_id
    INNER JOIN wp_term_relationships c ON a.term_taxonomy_id=c.term_taxonomy_id
    WHERE a.taxonomy='pa_collection' 
    AND c.object_id IN (
    SELECT p.ID FROM wp_posts p 
    INNER JOIN wp_term_relationships p2 ON p.ID=p2.object_id
    WHERE p2.term_taxonomy_id IN(".implode(",",$_ids_cats).")
    ) order by b.name ASC";
    $pa_collection_arr_list =  $wpdb->get_results($sql_collection);



//fin validar marca
//}



$output = '<div class="wcpt-navigation wcpt-header wcpt-always-show" style="">';
$output .= '<div class="wcpt-filter-row wcpt-ratio-100-0 ">';
$output .= '<div class="wcpt-filter-column wcpt-left">';
$output .= '<div class="wcpt-item-row wcpt-1628166337621 ">';
$output .= '<div class="wcpt-clear-filters-wrapper  wcpt-1628166470420">';
$output .= '<a href="javascript:void(0)" class="wcpt-clear-all-filters wcpt-small-device-only">Clear filters</a>';


if (!empty($_GET['attr_pa_cat'])) {
    $explodeCatArr = explode(",", $_GET['attr_pa_cat']);
    $output .= '<div class="pa_cat_parent">';
    foreach ($explodeCatArr as $cat_key => $cat_value) {
        $term_name = get_term($cat_value)->name;
        $output .= '<div class="wcpt-clear-filter" data-wcpt-filter="category" data-wcpt-taxonomy="product_cat" data-wcpt-meta-key="" data-wcpt-value="' . $cat_value . '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
        $output .= '<span class="wcpt-filter-label">Category : ' . $term_name . '</span>';
        $output .= '</div>';
    }
    $output .= '</div>';
}

if (!empty($_GET['attr_pa_brand'])) {
    $explodeBrandArr = explode(",", $_GET['attr_pa_brand']);
    $output .= '<div class="pa_brand_parent">';
    foreach ($explodeBrandArr as $brand_key => $brand_value) {
        $term_name = get_term($brand_value)->name;
        $output .= '<div class="wcpt-clear-filter" data-wcpt-filter="attribute" data-wcpt-taxonomy="pa_brand" data-wcpt-meta-key="" data-wcpt-value="' . $brand_value . '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
        $output .= '<span class="wcpt-filter-label">Brand : ' . $term_name . '</span>';

        $output .= '</div>';
    }
    $output .= '</div>';
}

if (!empty($_GET['attr_pa_color'])) {
    $explodeColorArr = explode(",", $_GET['attr_pa_color']);
    $newArrrr = array();
    foreach ($explodeColorArr as $col_key => $col_value) {
        $words = preg_replace('/[0-9]+/', '', strtoupper($col_value));
        $newArrrr[$words][] = $col_value;
    }

    $output .= '<div class="pa_color_parent">';
    foreach ($newArrrr as $col_key => $col_value) {
        $imploadeString = implode(",", $col_value);
        $term_name = get_term_by('name', ucfirst($col_key), 'pa_color');

        $output .= '<div class="wcpt-clear-filter" data-wcpt-filter="attribute" data-wcpt-taxonomy="pa_color" data-wcpt-meta-key="" data-wcpt-value="' . $imploadeString . '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
        $output .= '<span class="wcpt-filter-label">Color : ' . $term_name->name . '</span>';
        $output .= '</div>';
    }
    $output .= '</div>';
}


if (!empty($_GET['attr_pa_collection'])) {
    $explodeTeamArr = explode(",", $_GET['attr_pa_collection']);
    $output .= '<div class="pa_collection_parent">';

    foreach ($explodeTeamArr as $collection_key => $collection_value) :

        $term_name = get_term($brand_value)->name;

        $output .= '<div class="wcpt-clear-filter" data-wcpt-filter="attribute" data-wcpt-taxonomy="pa_collection" data-wcpt-meta-key="" data-wcpt-value="' . $collection_value . '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
        $output .= '<span class="wcpt-filter-label">Collection : ' . $term_name . '</span>';
        $output .= '</div>';

    endforeach;

    $output .= '</div>';
}

/*
if (!empty($_GET['attr_pa_season'])) {
    $explodeTeamArr = explode(",", $_GET['attr_pa_season']);
    $output .= '<div class="pa_season_parent">';

    foreach ($explodeTeamArr as $season_key => $season_value) :


        $output .= '<div class="wcpt-clear-filter" data-wcpt-filter="attribute" data-wcpt-taxonomy="pa_season" data-wcpt-meta-key="" data-wcpt-value="' . $season_value . '"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
        $output .= '<span class="wcpt-filter-label">Season : ' . $season_value . '</span>';
        $output .= '</div>';

    endforeach;

    $output .= '</div>';
}
*/




$output .= '<a href="javascript:void(0)" class="wcpt-clear-all-filters wcpt-big-device-only">Clear filters</a>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$output .= '<div class="wcpt-filter-row wcpt-ratio-100-0 ">';
$output .= '<div class="wcpt-filter-column wcpt-left">';
$output .= '<div class="wcpt-item-row wcpt-1628166473573 ">';

if (!empty($getCategoryArr)) :
    // Category Lists
    $output .= '<div class="wcpt-dropdown wcpt-filter wcpt-filter--search-filter-options-enabled wcpt-1628166734492" data-wcpt-filter="category" data-wcpt-taxonomy="product_cat" ata-wcpt-heading_format__op_selected="heading_and_selected" data-wcpt-search-filter-options-placeholder="Search Category">';
    $output .= '<div class="wcpt-filter-heading"><span class="wcpt-dropdown-label"><div class="wcpt-item-row wcpt-1628166734504 "><span class="wcpt-text  wcpt-1628166734504">Category</span></div></span> <span class="wcpt-icon wcpt-icon-chevron-down  "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></span></div><div class="wcpt-hierarchy wcpt-dropdown-menu" style="max-height: none;"><input type="text" class="wcpt-search-filter-options" placeholder="Search Category">';

    $output .= '<div class="wcpt-search-filter-option-set customCategoryLists" style="max-height: none;">';
    foreach ($getCategoryArr as $key1 => $value1) {
        foreach ($value1 as $keySub => $valueSub) {


            $parentTerm = get_term_by('slug', $keySub, 'product_cat');
            $checked = "";
            $attr_pa_cat = explode(",", $_GET['attr_pa_cat']);
            if (in_array($parentTerm->term_id, $attr_pa_cat)) {
                $checked = "checked='checked'";
                $openClass = 'wcpt-ac-open';
            }

            $output .= '<div class="wcpt-dropdown-option wcpt-accordion " data-wcpt-value="' . $parentTerm->term_id . '" data-wcpt-open="0" data-wcpt-depth="0"  >';
            $output .=  '<label class="" data-wcpt-value="' . $parentTerm->term_id . '" data-wcpt-slug="' . $parentTerm->slug . '"><input class="wcpt-hr-parent-term1" type="checkbox" name="58016_product_cat[]" value="' . $parentTerm->term_id . '" ' . $checked . ' ><span><div class="wcpt-item-row wcpt-1628256285 "><span class="wcpt-text  wcpt-1628256286">' . $parentTerm->name . '</span></div></span><span class="wcpt-icon wcpt-icon-chevron-down wcpt-ac-icon "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></span></label>';

            if (!empty($valueSub)) {
                $output .= '<div class="wcpt-hr-child-terms-wrapper wcpt-dropdown-sub-menu wcpt-ac-content">     ';

                foreach ($valueSub as $keySubSub => $valueSubSub) {
                    $childTerm = get_term_by('slug', $keySubSub, 'product_cat');
                    $checked = "";
                    $attr_pa_cat = explode(",", $_GET['attr_pa_cat']);
                    if (in_array($childTerm->term_id, $attr_pa_cat)) {
                        $checked = "checked='checked'";
                    }
                    $output .= '<div class="wcpt-dropdown-option wcpt-accordion " data-wcpt-value="' . $childTerm->term_id . '" data-wcpt-open="0" data-wcpt-depth="1" >';
                    $output .=  '<label class="" data-wcpt-value="' . $childTerm->term_id . '" data-wcpt-slug="' . $childTerm->slug . '"><input class="wcpt-hr-parent-term1" type="checkbox" name="58016_product_cat[]" value="' . $childTerm->term_id . '" ' . $checked . ' ><span><div class="wcpt-item-row wcpt-1628256285 "><span class="wcpt-text  wcpt-1628256286">' . $childTerm->name . '</span></div></span><span class="wcpt-icon wcpt-icon-chevron-down wcpt-ac-icon "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></span></label>';


                    if (!empty($valueSubSub)) {
                        $output .= '<div class="wcpt-hr-child-terms-wrapper wcpt-dropdown-sub-menu wcpt-ac-content">     ';

                        foreach ($valueSubSub as $keySubSubSub => $valueSubSubSub) {
                            $childTSuberm = get_term_by('slug', $keySubSubSub, 'product_cat');
                            $checked = "";
                            $attr_pa_cat = explode(",", $_GET['attr_pa_cat']);
                            if (in_array($childTSuberm->term_id, $attr_pa_cat)) {
                                $checked = "checked='checked'";
                            }
                            $output .= '<div class="wcpt-dropdown-option" data-wcpt-value="' . $childTSuberm->term_id . '" data-wcpt-open="0" data-wcpt-depth="2" >';
                            $output .=  '<label class="" data-wcpt-value="' . $childTSuberm->term_id . '" data-wcpt-slug="' . $childTSuberm->slug . '"><input class="wcpt-hr-parent-term1" type="checkbox" name="58016_product_cat[]" value="' . $childTSuberm->term_id . '" ' . $checked . ' ><span><div class="wcpt-item-row wcpt-1628256285 "><span class="wcpt-text  wcpt-1628256286">' . $childTSuberm->name . '</span></div></span></label>';
                            $output .= '</div>';
                        }

                        $output .= '</div>';
                    }

                    $output .= '</div>';
                }

                $output .= '</div>';
            }

            $output .= '</div>';
        }
    }
    $output .= '</div>';
    $output .= '</div>';


    $output .= '</div>';

endif;

if (!empty($product_ids_arr)) :
    // Brand Lists
    $brandArrData  = array();
    $output .= '<div class="wcpt-dropdown wcpt-filter wcpt-filter--search-filter-options-enabled wcpt-1628570248170" data-wcpt-filter="attribute" data-wcpt-heading_format__op_selected="only_heading" data-wcpt-taxonomy="pa_brand" data-wcpt-search-filter-options-placeholder="Search Brand">';

    $output .= '<div class="wcpt-filter-heading"><span class="wcpt-dropdown-label"><div class="wcpt-item-row wcpt-1628570248186 "><span class="wcpt-text  wcpt-1628570248187">Brand </span></div></span><span class="wcpt-icon wcpt-icon-chevron-down  "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></span>	</div><div class="wcpt-dropdown-menu" style="max-height: none;"><input type="text" class="wcpt-search-filter-options" placeholder="Search Brand">';

    $output .= '<div class="wcpt-search-filter-option-set addScrollYCustom" style="max-height: none;">';
    foreach ($product_ids_arr as $brand_key => $brand_value) :

        if ($brand_value->term_id != 0) {
            $checked = "";
            $attr_pa_brand = explode(",", $_GET['attr_pa_brand']);
            if (!isset($_GET['custom'])) {
                if (in_array($brand_value->term_id, $attr_pa_brand)) {
                    $checked = "checked='checked'";
                }
            }

            if (!in_array('<label class="wcpt-dropdown-option " data-wcpt-slug="' . sanitize_title($brand_value->name) . '" data-wcpt-value="' . $brand_value->term_id . '"> <input type="checkbox" value="' . $brand_value->term_id . '" class="wcpt-filter-checkbox" name="58016_attr_pa_brand[]" data-wcpt-clear-filter-label="' . $brand_value->name . '"    ' . $checked . ' ><span><div class="wcpt-item-row wcpt-1628570249 "><span class="wcpt-text  wcpt-1628570250">' . $brand_value->name . '</span></div></span></label> ', $brandArrData)) {
                array_push($brandArrData, '<label class="wcpt-dropdown-option " data-wcpt-slug="' . sanitize_title($brand_value->name) . '" data-wcpt-value="' . $brand_value->term_id . '"> <input type="checkbox" value="' . $brand_value->term_id . '" class="wcpt-filter-checkbox" name="58016_attr_pa_brand[]" data-wcpt-clear-filter-label="' . $brand_value->name . '"    ' . $checked . ' ><span><div class="wcpt-item-row wcpt-1628570249 "><span class="wcpt-text  wcpt-1628570250">' . $brand_value->name . '</span></div></span></label> ');
                $output .= '<label class="wcpt-dropdown-option " data-wcpt-slug="' . sanitize_title($brand_value->name) . '" data-wcpt-value="' . $brand_value->term_id . '"> <input type="checkbox" value="' . $brand_value->term_id . '" class="wcpt-filter-checkbox" name="58016_attr_pa_brand[]" data-wcpt-clear-filter-label="' . $brand_value->name . '"    ' . $checked . ' ><span><div class="wcpt-item-row wcpt-1628570249 "><span class="wcpt-text  wcpt-1628570250">' . $brand_value->name . '</span></div></span></label> ';
            }
        }


    endforeach;
    $output .= '</div>';

    $output .= '</div>';
    $output .= '</div>';

endif;




if (!empty($final_result1)) :
    $output .= '<div class="wcpt-dropdown wcpt-filter wcpt-filter--search-filter-options-enabled wcpt-1628250561224" data-wcpt-filter="attribute" data-wcpt-heading_format__op_selected="only_heading" data-wcpt-taxonomy="pa_color" data-wcpt-search-filter-options-placeholder="Search Color">';

    $output .= '<div class="wcpt-filter-heading"><span class="wcpt-dropdown-label"><div class="wcpt-item-row wcpt-1628250561238 "><span class="wcpt-text  wcpt-1628250561239">Color </span></div></span>
                        <span class="wcpt-icon wcpt-icon-chevron-down  "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></span>	</div><div class="wcpt-dropdown-menu" style="max-height: none;"><input type="text" class="wcpt-search-filter-options" placeholder="Search Color">';

    $output .= '<div class="wcpt-search-filter-option-set customColorLists" style="max-height: none;">';


    foreach ($final_result1 as $color_key => $color_value) :
        $colorName = implode(",", $color_value);

        $checked = "";
        //$isUppper = ucwords($_GET['attr_pa_color'], ",");
        $attr_pa_color = explode(",", $_GET['attr_pa_color']);

        if (in_array(strtoupper($color_key), $attr_pa_color)) {
            $checked = "checked='checked'";
        }

        $output .= '<label class="wcpt-dropdown-option " data-wcpt-slug="' . strtoupper($color_key) . '" data-wcpt-value="' . $colorName . '"><input type="checkbox" value="' . $colorName . '" class="wcpt-filter-checkbox1" name="58016_attr_pa_color[]" data-wcpt-clear-filter-label="' . strtoupper($color_key) . '" ' . $checked . ' ><span><div class="wcpt-item-row wcpt-1628250440 "><span class="wcpt-text  wcpt-1628250441">' . strtoupper($color_key) . '</span></div></span></label> ';

    endforeach;

    $output .= '</div>';
    $output .= '</div>';

    $output .= '</div>';
endif;


if (!empty($pa_collection_arr_list)) :
    $output .= '<div class="wcpt-dropdown wcpt-filter wcpt-filter--search-filter-options-enabled wcpt-1628596766837 wcpt-filter-open wcpt-filter--active" data-wcpt-filter="attribute" data-wcpt-heading_format__op_selected="only_heading" data-wcpt-taxonomy="pa_team" data-wcpt-search-filter-options-placeholder="Search Collection">';

    $output .= '<div class="wcpt-filter-heading"><span class="wcpt-dropdown-label"><div class="wcpt-item-row wcpt-1628596766854 "><span class="wcpt-text  wcpt-1628596766855">Collection </span></div></span><span class="wcpt-icon wcpt-icon-chevron-down  "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></span>	</div><div class="wcpt-dropdown-menu" style="max-height: none;"><input type="text" class="wcpt-search-filter-options" placeholder="Search Collection">';

    $output .= '<div class="wcpt-search-filter-option-set customTeamFilter" style="max-height: none;">';

    $collection_selected = isset($_GET['attr_pa_collection'])?$_GET['attr_pa_collection']:"";
    $collection_selected= $collection_selected!=""?explode(",",$collection_selected):array();

    foreach ($pa_collection_arr_list as $collection_key => $collection_item) :



        $checked = "";
        $isUppper = ucwords($_GET['attr_pa_collection'], ",");
       
        if (in_array($collection_item->term_taxonomy_id, $collection_selected)) {
            $checked = "checked='checked'";
        }

        $output .= '<label class="wcpt-dropdown-option wcpt-active" data-wcpt-slug="' . $collection_item->slug . '"> <input type="checkbox" value="' . $collection_item->term_taxonomy_id . '" class="wcpt-filter-checkbox" name="58016_attr_pa_collection[]" data-wcpt-clear-filter-label="' . $collection_item->term_taxonomy_id . '" ' . $checked . ' ><span><div class="wcpt-item-row wcpt-1628596721 "><span class="wcpt-text  wcpt-1628596722">' . $collection_item->name . '</span></div></span> </label> ';

    endforeach;




    $output .= '</div>';
    $output .= '</div>';

    $output .= '</div>';

endif;


if (!empty($pa_season_arr_list)) :
    $output .= '<div class="wcpt-dropdown wcpt-filter wcpt-filter--search-filter-options-enabled wcpt-1628596766837" data-wcpt-filter="attribute" data-wcpt-heading_format__op_selected="only_heading" data-wcpt-taxonomy="pa_season" data-wcpt-search-filter-options-placeholder="Search Season">';
    $output .= '<div class="wcpt-filter-heading"><span class="wcpt-dropdown-label"><div class="wcpt-item-row wcpt-1628596766854 "><span class="wcpt-text  wcpt-1628596766855">Season </span></div></span><span class="wcpt-icon wcpt-icon-chevron-down  "><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></span> </div><div class="wcpt-dropdown-menu" style="max-height: none;"><input type="text" class="wcpt-search-filter-options" placeholder="Search Season">';

    $output .= '<div class="wcpt-search-filter-option-set customSeasonFilter" style="max-height: none;">';
    foreach ($pa_season_arr_list as $season_key => $season_value) :
        $string = str_replace(" ", "-", strtoupper($season_value));
        $slug = strtolower($season_value);

        $checked = "";
        $isUppper = ucwords($_GET['attr_pa_season'], ",");
        $attr_pa_season = explode(",", $_GET['attr_pa_season']);
        if (in_array(strtoupper($season_value), $attr_pa_season)) {
            $checked = "checked='checked'";
        }

        $output .= '<label class="wcpt-dropdown-option wcpt-active" data-wcpt-slug="' . $slug . '"> <input type="checkbox" value="' . strtoupper($season_value) . '" class="wcpt-filter-checkbox" name="58016_attr_pa_season[]" data-wcpt-clear-filter-label="' . strtoupper($season_value) . '" ' . $checked . ' ><span><div class="wcpt-item-row wcpt-1628596721 "><span class="wcpt-text  wcpt-1628596722">' . strtoupper($season_value) . '</span></div></span> </label> ';

    endforeach;




    $output .= '</div>';
    $output .= '</div>';

    $output .= '</div>';

endif;

$output .= '<div class="btnSubmitUrl">';
$output .= '<button class="btn btn-primary" data-brandId="' . $_GET['attr_pa_brand'] . '" data-newbrandId="' . $_GET['attr_pa_brand'] . '" data-catid="' . $_GET['attr_pa_cat'] . '" data-colorid="' . $_GET['attr_pa_color'] . '" data-teamid="' . $_GET['attr_pa_team'] . '" data-seasonid="' . $_GET['attr_pa_season'] . '" data-collection="' . $_GET['attr_pa_collection'] . '"   >Submit</button>';
$output .= '</div>';

$output .= '<div class="btnCheckbox">';
$output .= '<button class="btn btn-secondary">Select All</button>';
$output .= '</div>';

$output .= '<span id="exportexcel1" onclick="fnExcelReport1(); " data-exportdata="" data-currentPage="1" >Export All to XLSX</span>';
$output .= '<span id="stop-refresh">Exporting is inprogress. Please don\'t refresh the page.</span>';

$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
$output .= '</div>';
echo $output;

?>






<div id="content" role="main">
    <table class="table table-bordered" id="demo_table">
        <thead>
            <tr>
                <?php if ($_GET['attr_pa_brand']) { ?>
                    <th><input type="checkbox" id="selectAllCheckobx" autocomplete="off"></th>
                <?php } else { ?>
                    <th><input type="hidden" id="selectAllCheckobx" autocomplete="off">&nbsp;</th>
                <?php } ?>
                <th>Image</th>
                <th>Product Title</th>
                <th>Product SKU</th>
                <th>Gender</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Action</th>
            </tr>
        </thead>

    </table>

</div>

<?php if (!isset($_GET['attr_pa_brand']) || !empty($_GET['attr_pa_brand']) || !empty($_GET['custom'])) { ?>
    <script>
        jQuery(document).ready(function() {
            jQuery('.btnSubmitUrl button').attr('data-newbrandId', "<?php echo $term_idcustom; ?>");
            jQuery('.btnSubmitUrl button').attr('data-brandId', "<?php echo $term_id; ?>");
        });
    </script>
<?php } ?>
<script>
    var site_url = "<?=get_site_url()?>";
</script>
<script>
    jQuery(document).ready(function($) {

        var attr_pa_brand = '<?php echo $_GET['attr_pa_brand']; ?>';
        var pa_color = '<?php echo $_GET['attr_pa_color']; ?>';
        var pa_team = '<?php echo str_replace("-", " ", $_GET['attr_pa_team']); ?>';
        var pa_cat = '<?php echo str_replace("-", " ", $_GET['attr_pa_cat']); ?>';
        var pa_season = '<?php echo str_replace("-", " ", $_GET['attr_pa_season']); ?>';
        var pa_collection = '<?php echo str_replace("-", " ", $_GET['attr_pa_collection']); ?>';

        var demo_table = jQuery('#demo_table').dataTable({
            dom: "<'row'<'col-sm-1'l><'col-sm-5'f><'col-sm-6'p>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            order: [
                [1, 'asc']
            ],
            columns: [{
                    data: 'select_checkbox',
                    name: 'select_checkbox',
                    destroy: true,
                    targets: 1,
                    data: null,
                    className: 'text-center',
                    searchable: false,
                    orderable: false,
                    render: function(data, type, full, meta) {
                        var dataInputValue = data.select_checkbox;
                        var newSelectOptions = dataInputValue.split('##');
                        return '<input type="checkbox" class="_check customcheck" name="check" data-main_prodcut="' + newSelectOptions[1] + '" value="' + newSelectOptions[0] + '">';
                    },
                    width: "5%",
                },
                {
                    data: 'product_image',
                    name: 'product_image',
                    class: "textCenter imageFilter",
                    "width": "10%",
                    render: function(data, type, row) {
                        var filterData = data.split('##');
                        var dddd = [];
                        if (filterData[1] != " ") {
                            dddd = filterData[1].split(',');
                        }
                        var stringContent = '';
                        if (dddd != "") {
                            stringContent = '<div class="backImg" style="display:none"><img src="' + dddd[0] + '" /></div>';
                        }

                        return type === 'export' ? filterData[0] : '<img src="' + filterData[0] + '" class="mainImg" />' + stringContent;

                    }
                },
                {
                    data: 'product_title',
                    name: 'product_title',
                    render: function(data, type, row) {
                        var filterData = data.split('##');
                        var normalData = '<a href="' + filterData[0] + '" target="_blank" class="anchorProdcutTitle" >' + filterData[1] + '</a>' + filterData[2];
                        return type === 'export' ? filterData[1] : normalData;
                    }
                },
                {
                    data: 'product_sku',
                    name: 'product_sku',
                    class: "textCenter"
                },
                {
                    data: 'prod_gender',
                    name: 'prod_gender',
                    class: "textCenter"
                },
                {
                    data: 'prod_brand',
                    name: 'prod_brand',
                    class: "textCenter"
                },
                {
                    data: 'price',
                    name: 'price',
                    class: "textCenter "
                },
                {
                    data: 'qty',
                    name: 'qty',
                    type: "html",
                    class: "textCenter demoTableNumber"
                },
                {
                    data: 'edit_option',
                    class: "textCenter addToCartURL"
                },
            ],
            language: {
                search: "",
                searchPlaceholder: "Search...",
                processing: "<div class='overlay custom-loader-background'><i class='fa fa-cog fa-spin custom-loader-color'></i></div>"
            },
            destroy: true,
            processing: true,
            serverSide: true,
            info: true,
            searching: true,
            paging: true,
            lengthMenu: [
                [100, 300, 500, 1000, 3000, 5000],
                [100, 300, 500, 1000, 3000, 5000]
            ],
            pagingType: "full_numbers",

            ajax: {
                url: site_url+"/wp-admin/admin-ajax.php",
                data: {
                    action: "get_custom_filter_option_brand",
                    attr_pa_brand: attr_pa_brand,
                    attr_pa_color: pa_color,
                    attr_pa_team: pa_team,
                    attr_pa_cat: pa_cat,
                    attr_pa_season: pa_season,
                    attr_pa_collection:pa_collection
                },
                type: "POST",

            },
        });



        //demo_table.fnFilter(this.value);
        $('div.dataTables_filter input').unbind();
        $('div.dataTables_filter input').bind('keyup', function(e) {
            if (e.keyCode == 13) {
                $('.preloader').css('display', 'block');
                demo_table.fnFilter(this.value);
            }
        });



        $('#selectAllCheckobx').click(function() {

            if (jQuery(this).is(':checked')) {
                jQuery(this).attr('checked', true);
                var count = 0;
                jQuery("#myTable > tr").each(function(i) {
                    jQuery(this).children('td').find('._check').eq(0).attr('checked', true);
                    count++;
                });
                jQuery('.wcpt-cart-checkbox-trigger').removeClass('wcpt-hide').css('display', 'block').addClass('wcpt-show');
                jQuery('.wcpt-cart-checkbox-trigger span.wcpt-total-selected').text(count);
            } else {
                jQuery(this).attr('checked', false)
                jQuery("#myTable > tr").each(function() {
                    jQuery(this).children('td').find('._check').eq(0).attr('checked', false);
                });

                jQuery('.wcpt-cart-checkbox-trigger').removeClass('wcpt-show').css('display', 'none').addClass('wcpt-hide');
                jQuery('.wcpt-cart-checkbox-trigger span.wcpt-total-selected').text(1);
            }

        });




        $('a.wcpt-clear-all-filters.wcpt-big-device-only').click(function() {
            var currentVal = "";
            jQuery('.btnSubmitUrl button').attr('data-brandId', currentVal);
            jQuery('.btnSubmitUrl button').attr('data-catid', currentVal);
            jQuery('.btnSubmitUrl button').attr('data-colorid', currentVal);
            jQuery('.btnSubmitUrl button').attr('data-teamid', currentVal);
            jQuery('.btnSubmitUrl button').attr('data-seasonid', currentVal);
            jQuery('.btnSubmitUrl button').attr('data-collection', currentVal);
            jQuery('.btnSubmitUrl button').trigger('click');
        });



        /*   $('.wcpt-cart-checkbox-trigger').bind('click', function () {
               var customHtml = '';
               var counter=0;
               jQuery("#myTable tr .addToCartURL").each(function() {
                   if(jQuery(this).text() != ' Out Of Stock ' ){
                       customHtml += jQuery(this).html() ;
                       counter++;
                   }
               });

               $('#demo_table').after('<div class="checkboxSelected" style="display:none"></div>');    
               jQuery('.checkboxSelected').html(customHtml);
               jQuery( 'table#demo_table thead > tr > th:first-child' ).each(function() {
                   jQuery(this).find('input').trigger('click');	
               });

               var setTimer = parseInt(counter + '000');    
               $('.checkboxSelected a').each(function(lc) {
                   $('#demo_table_processing').css('display','block');
                   $(this).trigger('click');
                   setTimeout(function(){
                       $('#demo_table_processing').css('display','none');  $('.checkboxSelected').remove();
               
                   }, setTimer);  
               });
           
           }); 
           */

        $('.btnCheckbox').bind('click', function() {
            $('#selectAllCheckobx').trigger("click");
        });

        $('#myTable').on('mouseover', 'td.textCenter.imageFilter.sorting_1', function() {

            if (jQuery(this).find('.backImg').length > 0) {
                jQuery(this).find('img.mainImg').hide();
                jQuery(this).find('.backImg').show();
                jQuery(this).find('.backImg').addClass('transition');
            } else {
                jQuery(this).find('img.mainImg').show();
                jQuery(this).find('img.mainImg').addClass('transition');
            }
            jQuery(this).css("cursor", "pointer");

        }).on('mouseout', 'td.textCenter.imageFilter.sorting_1', function() {
            if (jQuery(this).find('.backImg').length > 0) {
                jQuery(this).find('img.mainImg').show();
                jQuery(this).find('.backImg').hide();
                jQuery(this).find('img.mainImg').removeClass('transition');
            } else {
                jQuery(this).find('img.mainImg').show();
                jQuery(this).find('img.mainImg').removeClass('transition');
            }
            jQuery(this).css("cursor", "pointer");
        });

        $(document).ajaxComplete(function() {
            $('#myTable tr .demoTableNumber .wcpt-qty-controller').bind('click', function() {
                var currentqty = jQuery(this).parent().children('input').val();
                jQuery(this).parent().parent().next().children(".single_add_to_cart_button").attr('data-quantity', currentqty);
            });

            $('#myTable tr .demoTableNumber input').bind('keyup change', function() {
                var currentqty = jQuery(this).parent().children('input').val();
                jQuery(this).parent().parent().next().children(".single_add_to_cart_button").attr('data-quantity', currentqty);
            });

            $('#myTable tr .addToCartURL .single_add_to_cart_button').bind('click', function(e) {
                e.preventDefault();

                //   jQuery(this).parent().children('a').trigger('click');
                //return true;
            });

            //      jQuery(".customcheck").on('click', function() {
            // 	alert(jQuery('.customcheck').filter(':checked').length);
            // });

        });




        jQuery(document).on("click", "._check.customcheck", function() {
            var a = jQuery('._check.customcheck').filter(':checked').length;
            if (a == 0) {
                jQuery(".wcpt-cart-checkbox-trigger").removeClass("wcpt-hide").addClass("1213132321321").hide();
            } else {
                jQuery(".wcpt-cart-checkbox-trigger").removeClass("wcpt-hide").addClass("1213132321321").show();
            }
        });

        //jQuery(".wcpt-cart-checkbox-trigger, function(){").on("click", function(){

        jQuery(document).on("click", ".wcpt-cart-checkbox-trigger", function() {
            var a = jQuery('._check.customcheck').filter(':checked').length;
            jQuery("._check.customcheck:checked").each(function() {
                // jQuery(document.body).trigger('wc_fragment_refresh');

                // var get_cartTotal = jQuery('span.cart-items1').text();     
                // var currentQty = jQuery(this).closest("tr").find(".textCenter.addToCartURL a").attr('data-quantity');

                // var TotalQty = parseInt(get_cartTotal) + parseInt(currentQty);
                // jQuery('span.cart-items1').text(TotalQty);

                jQuery(this).closest("tr").find(".textCenter.addToCartURL a").click();

            });
            jQuery(".wcpt-cart-checkbox-trigger").text("Done").show();
        });

        /*
    jQuery("._check.customcheck").click(function(){
       var checkedData = jQuery('#checkMeOut').prop('checked');
       var count = jQuery("._check.customcheck:checked").length;
       alert(count);
       
       if(checkedData){
           alert("0");
       }else{
           alert("1");
       }
    });
    
    jQuery("._check.customcheck").click(function(){
       if(jQuery(this).prop('checked')){
jQuery(".wcpt-cart-checkbox-trigger").removeClass("wcpt-hide").addClass("1213132321321").show();
}
       
    });
    */

        var brandArr = [];
        var cateArr = [];
        var colorArr = [];
        var teamArr = [];
        var seasonArr = [];
        $('.wcpt-clear-filter').click(function() {

            if (jQuery(this).attr('data-wcpt-taxonomy') == 'pa_brand') {
                var brandText = jQuery('.btnSubmitUrl button').attr('data-brandId');
                var brandArr = brandText.split(',');
                brandArr.splice(jQuery.inArray(jQuery(this).attr('data-wcpt-value'), brandArr), 1);
                var currentVal = brandArr.join(",");
                jQuery('.btnSubmitUrl button').attr('data-brandId', currentVal);
            }

            if (jQuery(this).attr('data-wcpt-taxonomy') == 'product_cat') {
                var catText = jQuery('.btnSubmitUrl button').attr('data-catid');
                var cateArr = catText.split(',');
                cateArr.splice(jQuery.inArray(jQuery(this).attr('data-wcpt-value'), cateArr), 1);
                var currentVal1 = cateArr.join(",");
                jQuery('.btnSubmitUrl button').attr('data-catid', currentVal1);
            }


            if (jQuery(this).attr('data-wcpt-taxonomy') == 'pa_color') {
                var colorText = jQuery('.btnSubmitUrl button').attr('data-colorid');
                var colorArr = colorText.split(',');

                var colorArr1 = jQuery(this).attr('data-wcpt-value').split(',');
                var i;
                for (i = 0; i < colorArr1.length; ++i) {
                    if (colorText.indexOf(colorArr1[i]) != -1) {
                        colorArr.splice(jQuery.inArray(colorArr1[i], colorArr), 1);
                    }
                }
                var currentVal2 = colorArr.join(",");
                jQuery('.btnSubmitUrl button').attr('data-colorid', currentVal2);
            }

            if (jQuery(this).attr('data-wcpt-taxonomy') == 'pa_team') {
                var teamText = jQuery('.btnSubmitUrl button').attr('data-teamid');
                var teamArr = teamText.split(',');
                teamArr.splice(jQuery.inArray(jQuery(this).attr('data-wcpt-value'), teamArr), 1);
                var currentVal3 = teamArr.join(",");
                jQuery('.btnSubmitUrl button').attr('data-teamid', currentVal3);
            }

            if (jQuery(this).attr('data-wcpt-taxonomy') == 'pa_season') {
                var seaText = jQuery('.btnSubmitUrl button').attr('data-seasonid');
                var seasonArr = seaText.split(',');
                seasonArr.splice(jQuery.inArray(jQuery(this).attr('data-wcpt-value'), seasonArr), 1);
                var currentVal4 = seasonArr.join(",");
                jQuery('.btnSubmitUrl button').attr('data-seasonid', currentVal4);
            }
            if (jQuery(this).attr('data-wcpt-taxonomy') == 'pa_collection') {
                var seaText = jQuery('.btnSubmitUrl button').attr('data-collection');
                var collectionArr = seaText.split(',');
                collectionArr.splice(jQuery.inArray(jQuery(this).attr('data-wcpt-value'), collectionArr), 1);
                var currentVal5 = collectionArr.join(",");
                jQuery('.btnSubmitUrl button').attr('data-collection', currentVal5);
            }

            jQuery('.btnSubmitUrl button').trigger('click');
        });


        var myArr = [];
        if (attr_pa_brand != '') {
            var myArr = attr_pa_brand.split(',');
        }
        jQuery('.addScrollYCustom input.wcpt-filter-checkbox').on('click', function() {

            if (jQuery('.btnSubmitUrl button').attr('data-newbrandid')) {
                myArr = [];
                jQuery('.btnSubmitUrl button').attr('data-brandId', '').attr('data-newbrandId', '');

            }

            if (jQuery(this).prop('checked') == true) {
                jQuery(this).attr('checked', true)
                myArr.push(this.value);
            } else {
                jQuery(this).attr('checked', false)
                myArr.splice(jQuery.inArray(this.value, myArr), 1);
            }
            var currentVal = myArr.join(",");
            jQuery('.btnSubmitUrl button').attr('data-brandId', currentVal);

        });


        var catArr = [];
        if (pa_cat != '') {
            var catArr = pa_cat.split(',');
        }

        $('.customCategoryLists input.wcpt-hr-parent-term1').on('click', function() {
            var currentVal = '';

            if (jQuery(this).is(':checked')) {
                $(this).attr('checked', true);
                if (catArr.indexOf(this.value) === -1) {
                    catArr.push(this.value);
                }
            } else {
                $(this).attr('checked', false)
                var removeItem = this.value;

                catArr = jQuery.grep(catArr, function(value) {
                    return value != removeItem;
                });
            }

            var currentVal = catArr.join(",");

            jQuery('.btnSubmitUrl button').attr('data-catId', currentVal);

        });

        var colorArr = [];
        if (pa_color != '') {
            var colorArr = pa_color.split(',');
        }
        jQuery('.customColorLists input.wcpt-filter-checkbox1').on('click', function() {
            var currentVal = '';
            if (jQuery(this).prop('checked') == true) {
                jQuery(this).attr('checked', true)
                if (colorArr.indexOf(this.value) === -1) {
                    colorArr.push(this.value);
                }
            } else {
                $(this).attr('checked', false)
                var removeItem = this.value;
                colorArr = jQuery.grep(colorArr, function(value) {
                    return value != removeItem;
                });

            }
            var currentVal = colorArr.join(",");



            jQuery('.btnSubmitUrl button').attr('data-colorId', currentVal);

        });



        var team1Arr = [];
        if (pa_team != '') {
            var team1Arr = pa_team.split(',');
        }
        jQuery('.customTeamFilter input.wcpt-filter-checkbox').on('click', function() {
            var currentVal = '';
            if (jQuery(this).prop('checked') == true) {
                jQuery(this).attr('checked', true)
                if (team1Arr.indexOf(this.value) === -1) {
                    team1Arr.push(this.value);
                }
            } else {
                $(this).attr('checked', false)
                var removeItem = this.value;
                team1Arr = jQuery.grep(team1Arr, function(value) {
                    return value != removeItem;
                });

            }
            var currentVal = team1Arr.join(",");

            jQuery('.btnSubmitUrl button').attr('data-teamid', currentVal);

        });


        var seaArr = [];
        if (pa_season != '') {
            var seaArr = pa_season.split(',');
        }
        jQuery('.customSeasonFilter input.wcpt-filter-checkbox').on('click', function() {
            var currentVal = '';
            if (jQuery(this).prop('checked') == true) {
                jQuery(this).attr('checked', true)
                if (seaArr.indexOf(this.value) === -1) {
                    seaArr.push(this.value);
                }
            } else {
                $(this).attr('checked', false)
                var removeItem = this.value;
                seaArr = jQuery.grep(seaArr, function(value) {
                    return value != removeItem;
                });

            }
            var currentVal = seaArr.join(",");

            jQuery('.btnSubmitUrl button').attr('data-seasonid', currentVal);

        });







        $('.btnSubmitUrl button').click(function() {
            var attr_pa_brand = jQuery('.btnSubmitUrl button').attr('data-brandId');
            var attr_pa_brand1 = jQuery('.btnSubmitUrl button').attr('data-newbrandId');
            var attr_pa_cat = jQuery('.btnSubmitUrl button').attr('data-catId');
            var attr_pa_color = jQuery('.btnSubmitUrl button').attr('data-colorid');
            var attr_pa_team = jQuery('.btnSubmitUrl button').attr('data-teamid');
            var attr_pa_season = jQuery('.btnSubmitUrl button').attr('data-seasonid');
            var attr_pa_collection = jQuery('.btnSubmitUrl button').attr('data-collection');
            /*
            if (attr_pa_brand1 != '') {
                if (attr_pa_brand == '') {
                    var currentUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?attr_pa_brand=' + attr_pa_brand + '&custom=abc';
                } else {
                    var currentUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?attr_pa_brand=' + attr_pa_brand + '&attr_pa_cat=' + attr_pa_cat + '&attr_pa_color=' + attr_pa_color + '&attr_pa_team=' + attr_pa_team + '&attr_pa_season=' + attr_pa_season + '&custom=abc';
                }
            } else {
                if (attr_pa_brand == '') {
                    var currentUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?attr_pa_brand=' + attr_pa_brand + '&custom=abc';
                } else {
                    var currentUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?attr_pa_brand=' + attr_pa_brand + '&attr_pa_cat=' + attr_pa_cat + '&attr_pa_color=' + attr_pa_color + '&attr_pa_team=' + attr_pa_team + '&attr_pa_season=' + attr_pa_season;
                }
            }*/
            var currentUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?attr_pa_brand=' + attr_pa_brand + '&attr_pa_cat=' + attr_pa_cat + '&attr_pa_color=' + attr_pa_color + '&attr_pa_collection=' + attr_pa_collection + '&attr_pa_season=' + attr_pa_season;
            window.location.href = currentUrl;
        });




    });


    function fnExcelReport1() {
        var pabrand = '<?php echo $_GET['attr_pa_brand'] ?>';
        var pacat = '<?php echo $_GET['attr_pa_cat'] ?>';
        var pacolor = '<?php echo $_GET['attr_pa_color'] ?>';
        var pateam = '<?php echo $_GET['attr_pa_team'] ?>';
        var paseason = '<?php echo $_GET['attr_pa_season'] ?>';

        var SITEURL = "<?php echo site_url(); ?>/wp-content/themes/porto-child/";
        var form_data = new FormData();
        form_data.append('action', 'customFilterExportLiveDataFilter');
        form_data.append('pabrand', pabrand);
        form_data.append('pacat', pacat);
        form_data.append('pacolor', pacolor);
        form_data.append('pateam', pateam);
        form_data.append('paseason', paseason);
        jQuery.ajax({
            type: "POST",
            url: site_url+"/wp-admin/admin-ajax.php",
            contentType: false,
            processData: false,
            data: form_data,
            beforeSend: function() {
                jQuery('#exportexcel1').text('Creating XLSX File');
                jQuery('#stop-refresh').show();
            },
            success: function(msg) {
                console.log(msg);
                jQuery('#exportexcel1').text('Data Exported');
                setTimeout(function() {
                    jQuery('#exportexcel1').text('Export All to XLSX');
                }, 500);
                jQuery('#stop-refresh').hide();
                var data = JSON.parse(msg);
                window.open(SITEURL + "orders/" + data.filename, '_blank');
            },
            error: function(errorThrown) {
                console.log(errorThrown);
                console.log('No update');
            }
        });
    }
</script>

<div class="wcpt-cart-checkbox-trigger" data-wcpt-redirect-url="" style="display: none;">
    <style media="screen">
        @media(min-width:1200px) {
            .wcpt-cart-checkbox-trigger {
                display: inline-block;
            }
        }

        @media(max-width:1100px) {
            .wcpt-cart-checkbox-trigger {
                display: inline-block;
            }
        }

        .wcpt-cart-checkbox-trigger {
            background-color: #e31d1a;
            border-color: rgba(0, 0, 0, .1);
            color: rgba(255, 255, 255);
        }
    </style>
    Add selected items to cart
</div>


<?php get_footer(); ?>