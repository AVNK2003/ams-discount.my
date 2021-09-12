<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @throws \Exception
     */
    public function temp(Request $request)
    {
        $perPage = 2;
        $currentPage = $request->currentPage ?? 0;
        $nextPage = $currentPage * $perPage;
        $companiesCount = Company::count();

        $companies = $this->getPage($nextPage, $perPage);

        if (!$companies->count() || $currentPage < 0) {
            $currentPage = 0;
            $companies = $this->getPage($currentPage, $perPage);
        }

        return view('temp', compact('companies', 'currentPage', 'companiesCount', 'perPage'));
    }

    private function getPage($skip, $perPage)
    {
        return Company::where('active', true)
            ->where('date_end', '>', now())
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->skip($skip)->take($perPage)->get();
    }
}
