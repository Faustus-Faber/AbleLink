<?php
// F10 - Rifat Jahan Roza
//F10 - Rifat Jahan Roza

namespace App\Models\Employment;

use App\Models\Auth\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

//F10 - Employer Job Posting & Dashboard - Company Profile
class EmployerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_description',
        'website',
        'phone',
        'address',
        'industry',
        'company_size',
        'wheelchair_accessible_office',
        'sign_language_available',
        'assistive_technology_support',
        'accessibility_accommodations',
        'inclusive_hiring_practices',
    ];

    protected $casts = [
        'wheelchair_accessible_office' => 'boolean',
        'sign_language_available' => 'boolean',
        'assistive_technology_support' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

