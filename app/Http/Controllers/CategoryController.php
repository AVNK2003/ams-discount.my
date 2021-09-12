<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Traits\MainTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use MainTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all()->sortBy('name');
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = new Category();
        $category->fill($request->all())->save();
        return redirect()->route('categories.index')->with('success', 'Категория успешно добавлена');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $companies = $category->companies
            ->sortByDesc('priority')
            ->sortByDesc('created_at');
        return view('index', [
            'title' => '- '.$category->name,
            'cabinet' => true,
            'companies' => $companies,
            'mapData' => $this->mapData($companies),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->fill($request->all())->save();
        cache()->forget('links-categories');
        return redirect()->route('categories.index')->with('success', 'Категория успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        cache()->forget('links-categories');
        return redirect()->route('categories.index')->with('success', 'Категория успешно удалена');
    }

    public function showCategory(Category $category)
    {
        $companies = $category->companies
            ->where('active', true)
            ->where('date_end','>',now())
            ->sortByDesc('priority')
            ->sortByDesc('created_at');

        $cities = collect();
        $companies->each(function ($item, $key) use ($cities) {
            $cities->push($item->cities->pluck('name', 'slug'));
        });

        $cities = call_user_func_array('array_merge', $cities->toArray());
        asort($cities);

        return view('index', [
            'title' => '- '.$category->name,
            'category' => $category,
            'cities' => $cities,
            'companies' => $companies,
            'mapData' => $this->mapData($companies),
        ]);
    }

    public function showCategoryCity(Category $category, City $city)
    {
//        $companies = $category->companies
//            ->where('active')
//            ->sortByDesc('priority')
//            ->sortByDesc('created_at');
//        dd($companies);

        //        Вариант запроса к БД (делает 3 запроса) для получения компании по городу и категории

        $companies = Company::with('cities')
            ->whereHas('cities', function (Builder $query) use ($city) {
                $query->where('id', $city->id);
            })
            ->with('categories')
            ->whereHas('categories', function (Builder $query) use ($category) {
                $query->where('id', $category->id);
            })
            ->where('active', true)
            ->where('date_end','>',now())
            ->orderByDesc('priority')
            ->orderByDesc('created_at')
            ->get();


//        Вариант запроса к БД (делает 3 запроса) для получения компании по городу и категории
//        $companies1 = collect();
//        foreach ($companies as $company) {
//            foreach ($company->cities as $company_city) {
//                if ($company_city->id == $city->id) {
//                    $companies1->push($company);
//                }
//            }
//        }


//        dd($companies1);

        return view('index', [
            'title' => '- '.$city->name.' - '.$category->name,
            'companies' => $companies,
            'mapData' => $this->mapData($companies),
        ]);
    }
}
