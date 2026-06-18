<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function ndas()
    {
        return $this->hasMany(Nda::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function exitRequests()
    {
        return $this->hasMany(ExitRequest::class);
    }

    public function metrics()
    {
        return $this->hasMany(ProjectMetric::class);
    }

    public function consultants()
    {
        return $this->hasMany(ProjectConsultant::class);
    }

    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withPivot('role')
                    ->withTimestamps();
    }
}
