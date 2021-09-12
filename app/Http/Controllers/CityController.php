<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Traits\MainTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use MainTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.cities.index', [
            'cities' => City::all()->sortBy('name'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.cities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $city = new City();
        $city->fill($request->all())->save();
        return redirect()->route('cities.index')->withSuccess('Город успешно добавлен');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        $companies = $city->companies
            ->sortByDesc('priority')
            ->sortByDesc('created_at');
        return view('index', [
            'title' => '- '.$city->name,
            'cabinet' => true,
            'companies' => $companies,
            'mapData' => $this->mapData($companies),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(City $city)
    {
        return view('admin.cities.edit', compact('city'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function update(Request $request, City $city): \Illuminate\Http\RedirectResponse
    {
        /*        $cities->name = $request->name;
                $cities->eng = $request->eng;
                $cities->save();*/
        $city->fill($request->all())->save();
        cache()->forget('links-cities');
        return redirect()->route('cities.index')->with('success', 'Город был успешно обновлен');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(City $city)
    {
        $city->delete();
        cache()->forget('links-cities');
        return redirect()->route('cities.index')->with('success', 'Город был успешно удален');
    }

    public function showCity(City $city)
    {
        $companies = $city->companies
            ->where('active')
            ->where('date_end','>',now())
            ->sortByDesc('priority')
            ->sortByDesc('created_at');

        $categories = collect();
        $companies->each(function ($item, $key) use ($categories) {
            $categories->push($item->categories->pluck('name', 'slug'));
        });

        $categories = call_user_func_array('array_merge', $categories->toArray());
        asort($categories);
//        $categories = array_unique($categories);
//        dd($categories);

        return view('index', [
            'title' => '- '.$city->name,
            'city' => $city,
            'categories' => $categories,
            'companies' => $companies,
            'mapData' => $this->mapData($companies),
        ]);
    }

    public function showCityCategory(City $city, Category $category)
    {
//        $companies=$city->companies
//            ->where('active')
//            ->where('date_end','>',now())
//            ->sortByDesc('priority')
//            ->sortByDesc('created_at');

        //        Вариант запроса к БД (делает по сути 3 запроса) для получения компании по городу и категории

        $companies = Company::with('cities')
            ->whereHas('cities', function (Builder $query) use ($city) {
                $query->where('id', '=', $city->id);
            })
            ->with('categories')
            ->whereHas('categories', function (Builder $query) use ($category) {
                $query->where('id', '=', $category->id);
            })
            ->where('active', true)
            ->where('date_end','>',now())
            ->orderByDesc('priority')
            ->orderByDesc('created_at')
            ->get();

//        Вариант запроса к БД (делает по сути 3 запроса) для получения компании по городу и категории
//        $companies1 = collect([]);
//        foreach ($companies as $company) {
//            foreach ($company->categories as $company_category) {
//                if ($company_category->id === $category->id) {
//                    $companies1->push($company);
//                }
//            }
//        }

//        dd($companies1);

        $categories = collect();
        $companies->each(function ($item, $key) use ($categories) {
            $categories->push($item->categories->pluck('name', 'slug'));
        });

        $categories = call_user_func_array('array_merge', $categories->toArray());

        return view('index', [
            'title' => '- '.$city->name.' - '.$category->name,
            'companies' => $companies,
            'mapData' => $this->mapData($companies),
        ]);
    }
}
