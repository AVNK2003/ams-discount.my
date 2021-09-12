<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Traits\MainTrait;
use Intervention\Image\Facades\Image;

class CompanyController extends Controller
{
    use MainTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->is_admin) {
            $companies = Company::with('partner')
                ->orderBy('active')
                ->orderByDesc('priority')
                ->orderByDesc('created_at')->get();
        } else {
            $companies = Company::where('user_id', auth()->user()->id)
                ->with('partner')
                ->orderBy('active')
                ->orderByDesc('priority')
                ->orderByDesc('created_at')->get();
        }
        return view('admin.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.companies.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CompanyRequest $request)
    {
        $company = new Company();
        $company->date_end = $request->date_end;
        $this->saveCompany($request, $company);
        cache()->forget('companies');
        return redirect()->route('companies.index')->with('success', 'Организация успешно отправлена на модерацию');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Company $company
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function show(Company $company)
    {
        $comments = \App\Models\Comment::where('company_id', $company->id)
            ->where('published', true)
            ->get();
        $company->views++;
        $company->save();

        return view(
            'admin.companies.show',
            [
                'cabinet' => true,
                'company' => $company,
                'comments' => $comments,
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Company $company
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Company $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CompanyRequest $request, Company $company)
    {
        $company->date_end = $request->date_end;
        $this->saveCompany($request, $company);
        cache()->forget('companies');
        return redirect()->route('companies.index')->with('success', 'Организация успешно обновлена');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Company $company
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Company $company)
    {
        $filename = $company->img;
        if ($filename) {
            unlink(public_path('img/uploads/thumbnail/' . $filename));
            unlink(public_path('img/uploads/' . $filename));
        }

        $company->delete();
        cache()->forget('companies');
        return redirect()->route('companies.index')->with('success', 'Организация успешно удалена');
    }

    private function saveCompany(CompanyRequest $request, Company $company)
    {
        $image = $request->file('img');
        if ($image) {
            $imgName = uniqid() . '.' . $image->extension();

            $destinationPath = public_path('img/uploads/thumbnail/');
            $img = Image::make($image->path());
            $img->resize(
                256,
                256,
                function ($constraint) {
                    $constraint->aspectRatio();
                }
            )->save($destinationPath . $imgName);

            $destinationPath = public_path('img/uploads');
            $image->move($destinationPath, $imgName);

            $company->img = $imgName;
        }

        if (auth()->user()->is_admin)
            $company->fill($request->except('categories', 'cities', 'img'))->save();
        else
            $company->fill($request->except('categories', 'cities', 'img', 'priority', 'active'))->save();

        $company->categories()->sync($request->categories);
        $company->cities()->sync($request->cities);
    }

    public function indexForAll()
    {
        $companies = cache()->remember('companies',60*60*24,function () {
            return Company::where('active', true)
                ->where('date_end','>',now())
                ->orderBy('priority', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
        });
        return view(
            'index',
            [
                'companies' => $companies,
                'mapData' => $this->mapData($companies),
            ]
        );
    }

    public function showCompanyForAll(Company $company)
    {
        $comments = \App\Models\Comment::where('company_id', $company->id)
            ->where('published', true)
            ->orderByDesc('created_at')
            ->get();
        $company->views++;
        $company->save();
        return view(
            'admin.companies.show',
            [
                'company' => $company,
                'comments' => $comments,
            ]
        );
    }

    public function toggleActive(Company $company)
    {
        $company->active = !$company->active;
        $company->save();
        $status = $company->active ? 'Организация размещена в каталоге' : 'Организация убрана из каталога';
        return redirect()->route('companies.index')->with('success', $status);
    }
}
