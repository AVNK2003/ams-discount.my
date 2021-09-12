<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Collection;

trait MainTrait
{
    public function mapData (Collection $companies)
    {
        $companiesMap = $companies->whereNotNull('coordinates');

        $map_data = array("type" => "FeatureCollection", "features" => array());


        foreach ($companiesMap as $company) {

            $coordinates = explode(",", $company->coordinates);

            $category = $company->categories->first();

            $arr = array("type" => "Feature",
                "id" => $company['id'],
                "geometry" => array("type" => "Point",
                    "coordinates" => [
                        (float)$coordinates[0], (float)$coordinates[1]
                    ]),
                "properties" => array("balloonContentHeader" => "<h2>${company['name']}</h2>",
                    "balloonContentBody" =>
                        "<img class='balloon' src='/img/uploads/thumbnail/${company['img']}' alt='logo'>
<p>Скидка по карте: ${company['discount']}</p>
<p>График: ${company['working_hours']}</p>
<p>Адрес: ${company['address']}</p>
<p>Сайт: ${company['site']}</p>
<p>Тел: ${company['tel']}</p>
<p>Краткое описание: ${company['description']}</p>",
                    "clusterCaption" => $category['name'],
                    "hintContent" => $company['name'],
                    "iconCaption" => $company['name']),
                "options" => array("preset" => "islands#blueDotIcon",//StretchyIcon circleDotIcon
                    "iconColor" => $category->color));


            array_push($map_data['features'], $arr);
        }

        return json_encode($map_data, JSON_UNESCAPED_UNICODE);
    }
}
