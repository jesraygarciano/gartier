<?php

namespace App\Services;

use \App\Opening;
use Illuminate\Support\Collection;
use \App\Http\Resources\OpeningResource;

class ReportService
{
    protected $user;

    function __construct(){
        $this->user = \Auth::user();
    }

    function fetch_companies($request){
        return $this->getAuthCompanies();
    }

    function getAuthCompanies(){
        $owned_company = $this->user->ownedCompanies;
        $managed_company = $owned_company->merge($this->user->managedCompanies);

        return $managed_company;
    }

    function getApplicationCount($request){
        $count = 0;
        foreach($this->getAuthCompanies() as $company){
            $count = $count + $company->applications()->where('hiring_applications.status', $request->status)->count();
        }

        return $count;
    }

    function getAuthOpeningCount(){
        $count = 0;
        foreach($this->getAuthCompanies() as $company){
            $count += $company->openings->count();
        }

        return $count;
    }

    function getApplicationCountPerCompany($request){
        $companies = collect();
        foreach($this->getAuthCompanies() as $company){
            $count = $company->applications()->where('hiring_applications.status', $request->status)->count();
            $company->count = $count;
            $companies = $companies->concat([$company]);
        }

        return $companies;
    }
}
